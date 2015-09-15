<?php
require_once('lib/common.php');

load_alternative_class('class/log.class.php');
load_alternative_class('class/session.class.php');
$session = new Session();
load_alternative_class('class/debug.class.php');
require_once('conf/conf.php');
load_alternative_class('class/db.class.php');
load_alternative_class('class/common.class.php');
load_alternative_class('class/common_object.class.php');
load_alternative_class('class/trigger.class.php');
require_once('lib/h2o.php');
load_alternative_class('class/conf.class.php');
load_alternative_class('class/user.class.php');
$debug = new debug();
$conf = new conf();
$bdd=new Db($conf->main_db_type,
        $conf->main_db_host,
        $conf->main_db_user,
        $conf->main_db_pass,
        $conf->main_db_name,
        $conf->main_db_port);
$conf->load_from_db($bdd);
$session->init();
$lang = $session->get('lang');
if (!$lang) $lang = $conf->default_lang;
if (isset($_GET['lang'])){
    if (in_array($_GET['lang'],preg_split("/,/",$conf->available_lang)))
        $lang = $_GET['lang'];
}
$session->set('lang', $lang);

$editlang = $session->get('editlang');
if (!$editlang) $editlang = $conf->default_lang;
if (isset($_GET['editlang'])){
    if (in_array($_GET['editlang'],preg_split("/,/",$conf->available_lang)))
        $editlang = $_GET['editlang'];
}
$session->set('editlang', $editlang);


$domain = 'messages';
putenv('LC_ALL=' . $lang);
setlocale(LC_ALL, $lang);
bindtextdomain($domain, './lang');
textdomain($domain);

date_default_timezone_set($conf->timezone);
$log = new log();
$current_user = new user($bdd);
$trigger = new trigger($bdd);


if ($session->get('user_logged_in'))
{
    $model_user_file = make_path('model', 'login', 'php');
    $current_user->fetch_by_md5($session->get('user_md5'));
    $current_user->fetch_right();

} elseif (isset($_COOKIE['remember_me']))
{
    $model_user_file = make_path('model', 'login', 'php');
    require_once($model_user_file);
    $m = new model_login($bdd,$path);
    $ret = $m->login_with_cookie($_COOKIE['remember_me']);
    if ($ret) { $current_user->fetch_by_md5($session->get('user_md5')); }
    $current_user->fetch_right();
}


$id = $_REQUEST['id'];

if (!$id) {
    include_once('errors/404.php');
}
load_alternative_class('class/form.class.php');
$f = new form($bdd);
$res=$f->fetch($id);
if (!$res)
{
    include_once('errors/404.php');
}
//1 parse result
$result = array();
$form_base = json_decode($f->get_jsonData());
$possible_post_key = array();
foreach($form_base as $key=>$val)
{
    if ((isset($val->fields->id)?$val->fields->id->value:false))
        $possible_post_key[] = $val->fields->id->value;
}
foreach($_REQUEST as $key=>$val)
{
    //1bis valid
    if (in_array($key, $possible_post_key)
        || in_array(preg_replace('/_add-on$/',"",$key), $possible_post_key)
        || in_array(preg_replace('/_dropdown$/',"",$key), $possible_post_key))
    {
        $result[$key]=$val;
    } else {
        //var_dump('filtered');
    }
    //
}
$data = $result;
$result = json_encode($result);
//2 store or send mail

if ($f->get_type_connector() != "mail")
{
    //Store
    $requete = "INSERT INTO forms_result
                            (forms_refid, user_refid,result)
                     VALUES (".$_REQUEST['id'].",
                              ".(null !== $current_user->get_id() && $current_user->get_id()>0?$current_user->get_id():"null") .",
                              '". addslashes($result) ."')";
    $sql = $bdd->query($requete);
} else if ($f->get_type_connector() == "internal_msg") {
    load_alternative_class("class/internal_msg.class.php");
    $int_msg = new internal_msg($bdd);
    global $current_user;
    if (isset($_REQUEST['msg_type'])) $msg_type =$_REQUEST['msg_type'];
    else {
        $requete = "SELECT *
                      FROM internal_msg_type
                     WHERE `defaut`=  1";
        $sql = $bdd->query($requete);
        $res = $bdd->fetch_object($sql);
        $msg_type = $res->id;
        if (!$msg_type) $msg_type == 1;
    }
    $title = $f->get_title();
    $internal_msg = "";
    foreach($result as $key => $val)
    {
        if ($key == 'msg_type') continue;
        if ($key == 'id') continue;
        if ($key == 'lang') continue;
        if ($key == 'editlang') continue;
        $internal_msg.= $key.":".$val."<br/>\n";
    }
    $int_msg->insert($current_user->get_id(), $internal_msg_type_refid, $internal_msg, $title);
} else {
    //send email
    $from = false;
    $to = false;
    $options_mail = json_decode($f->get_connector_option());
    load_alternative_class('class/mail_model.class.php');
    $mm = new mail_model($bdd);
    $mm->fetch($options_mail->model);
    if ($options_mail->to == "moi")
    {
        $options_mail->to = $current_user->get_id();
        $to = $current_user->get_email();
    } elseif ($options_mail->to == "field"){
        $options_mail->to = $data[$options_mail->field_to] ;
        $to = $options_mail->to;
    } elseif (is_numeric($options_mail->to))
    {
        $u = new user($bdd);
        $u->fetch($options_mail->to);
        $options_mail->to = $u->get_id();
        $to = $u->get_email();
    } else {
        $to = $options_mail->to;
    }
    if ($options_mail->from == "moi")
    {
        $options_mail->from = $current_user->get_id();
        $from = $current_user->get_email();
    } elseif ($options_mail->from == "field"){
        $options_mail->from = $data[$options_mail->field_from] ;
        $from = $options_mail->from;
    } elseif (is_numeric($options_mail->from))
    {
        $u = new user($bdd);
        $u->fetch($options_mail->from);
        $options_mail->from = $u->get_id();
        $from = $u->get_email();
    } else {
        $from = $options_mail->from;
    }
    $mail = $mm->prepare_external_2($options_mail->to, $options_mail->from,$data);
    load_alternative_class('class/mail.class.php');
    $m = new mail();
    $cc = false;
    $reply_to = false;
    if (isset($options_mail->cc) && $options_mail->cc."x" != "x")
    {
        $cc = $options_mail->cc;
    }
    if (isset($options_mail->reply_to)  && $options_mail->reply_to."x" != "x" )
    {
        $reply_to = $options_mail->reply_to;
    }
    $m->send_email($mail['subject'], $mail['content'], $to, $from, $reply_to,$cc);
}
//3 Trigger
global $trigger;
$trigger->run_trigger("RESULT_FORM",$f,$current_user,$data);

class view_result extends common
{
    public function run()
    {

         dispatch($this->db,'result',false);
         exit;
//         $this->set_main(_("Result page"));

//         load_alternative_class('class/page.class.php');
//         $this->set_extra_render('breadcrumbs', $this->get_breadcrumbs());
//         echo $this->gen_page();
    }
}


$v = new view_result($bdd,"form_result");
$v->run();