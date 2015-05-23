<?php
if (is_file(".install"))
{
    header('location:install.php');
}
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

$conf->main_url_root = preg_replace('/\/$/','',$conf->main_url_root);
$conf->main_base_path = preg_replace('/\/$/','',$conf->main_base_path);
$conf->main_document_root = preg_replace('/\/$/','',$conf->main_document_root);

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
setlocale( LC_MESSAGES, $lang);
$session->set('lang', $lang);

$editlang = $session->get('editlang');
if (!$editlang) $editlang = $conf->default_lang;
if (isset($_GET['editlang']))
{
    if (in_array($_GET['editlang'],preg_split("/,/",$conf->available_lang)))
        $editlang = $_GET['editlang'];
}
$session->set('editlang', $editlang);

$advanced_mode = false;
$advanced_mode = $session->get('advanced_mode');
if (isset($_GET['advanced_mode']) && $_GET['advanced_mode'] == 1)
{
    $advanced_mode = true;
} else if (isset($_GET['advanced_mode']))  {
    $advanced_mode = false;
}
$session->set('advanced_mode', $advanced_mode);

$domain = 'messages';
putenv('LC_ALL=' . $lang);
setlocale(LC_ALL, $lang);
bindtextdomain($domain, './lang');
textdomain($domain);

date_default_timezone_set($conf->timezone);
$log = new log();
$current_user = new user($bdd);
$trigger = new trigger($bdd);
$req = $conf->main_base_path ? substr(request_uri(), strlen($conf->main_base_path)) : request_uri();


$url = @parse_url($req);
$path = isset($url['path']) ? trim(urldecode($url['path']), '/') : false;
$query = isset($url['query']) ? $url['query'] : false;

if ($path."x" == "x" || $path=="index.php"){ $path = 'home'; }

if ($path == 'bdd' ) {
    include_once 'errors/500.php';
    exit;
}

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
    if ($ret) { $current_user->fetch_right(); }
}

$conf->admin_mode = false;
if (preg_match('/^'.$conf->admin_keyword.'/',$path))
{
    $conf->admin_mode=true;
    $path=preg_replace('/^[\w]*\//','',$path);
    load_alternative_class('class/admin_common.class.php');
    if ($path==$conf->admin_keyword){ header('location:'.$conf->admin_keyword."/index");exit; }
    if ($path=="index.php"){header('location:'."index"); exit; }
    if ($path=="index.html"){header('location:'."index"); exit;}
    if ($path=="index.htm"){header('location:'."index"); exit;}
}
$conf->required_path = $path;
dispatch($bdd,$path,false);
