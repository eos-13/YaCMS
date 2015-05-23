<?php
//
// jQuery File Tree PHP Connector
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// Output a list of files for jQuery File Tree
//
require_once '../../conf/conf.php';
chdir ("../../");
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
$session->set('lang', $lang);

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
if (!$current_user->get_id() > 0 )
{
    include_once('errors/401.html');
    exit;
}


$root = $main_document_root."/files/";

$_POST['dir'] = urldecode($_POST['dir']);
$_POST['dir'] = preg_replace('/^[\.\/]*/','',$_POST['dir']);
if( file_exists($root . $_POST['dir']) ) {
    $files = scandir($root . $_POST['dir']);
    natcasesort($files);
    if( count($files) > 2 ) { /* The 2 accounts for . and .. */
        //var_dump($file);

        echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
        // All dirs
        foreach( $files as $file ) {
            if (preg_match('/^\./',$file)) continue;
            if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file) ) {
                echo "<li class=\"directory collapsed\"><a dir_is=\"/".$_POST['dir']. $file."/\" href=\"#\" rel=\"" . htmlentities(addslashes($_POST['dir'] . $file)) . "/\">" . htmlentities($file) . "</a></li>";
            }
        }
        // All files
        foreach( $files as $file ) {
            if (preg_match('/^\./',$file)) continue;
            if( file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file) ) {
                $ext = preg_replace('/^.*\./', '', $file);
                $ext = strtolower($ext);
                echo "<li class=\"file ext_$ext\"><a href=\"#\" dir_is=\"/".$_POST['dir']."\" rel=\"" . htmlentities(addslashes($_POST['dir'] . $file)) . "\">" . htmlentities($file) . "</a></li>";
            }
        }
        echo "</ul>";
    }
}

?>