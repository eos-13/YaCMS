<?php
require_once('conf/conf.php');
chdir ($main_document_root);
require_once('lib/common.php');

load_alternative_class('class/log.class.php');
load_alternative_class('class/session.class.php');
$session = new Session();
load_alternative_class('class/debug.class.php');
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
$req = $conf->main_base_path ? substr(request_uri(), strlen($conf->main_base_path)) : request_uri();



load_alternative_class('class/imap_client.class.php');
$imap = new imap_client($bdd);
$imap->connect();