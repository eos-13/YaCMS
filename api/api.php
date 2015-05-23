<?php
chdir ("../");
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
$log = new log();
$current_user = new user($bdd);
$trigger = new trigger($bdd);
if ($_REQUEST['type'] != "auth")
    load_alternative_class('class/'.$_REQUEST['type'].'.class.php');
load_alternative_api_class('class/api_'.$_REQUEST['type'].'.class.php');

session_start();

ini_set("soap.wsdl_cache", "0");
ini_set("soap.wsdl_cache_ttl", "0");
ini_set("soap.wsdl_cache_enabled", "0");

require 'lib/wsdl-creator/vendor/autoload.php';
use WSDL\WSDLCreator;

if (isset($_GET['wsdl']))
{
    $wsdl = new WSDL\WSDLCreator (
            "api_".$_REQUEST['type'],
            $conf->main_url_root . '/api/api.php?type='.$_REQUEST['type']
    );
    $wsdl->setNamespace ( "http://www.cikord.com/" );
    $wsdl->renderWSDL();
    exit;
}

$authOK = false;
if (isset($_COOKIE) && isset($_COOKIE['api']))
{
    $requete = "SELECT *
                  FROM api
                 WHERE token = '".addslashes($_COOKIE['api'])."'";
    $sql = $bdd->query($requete);
    if ($sql && $bdd->num_rows($sql) > 0)
    {
        $authOK = true;
        $res = $bdd->fetch_object($sql);
        $current_user = new user($bdd);
        $current_user->fetch_by_username($res->login);
        $current_user->fetch_right();
        if ( ! $current_user->has_right('admin')) $authOK = false;
    }
}
if ($authOK || $_REQUEST['type'] == "auth")
{
    try{
        ini_set('default_socket_timeout', 5);
        $server = new SoapServer(
                $conf->main_url_root."/api/api.php?wsdl&type=".$_REQUEST['type'],
                array(
                    'uri' => $conf->main_url_root.'/api/api.php?type='.$_REQUEST['type'],
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    "exceptions" => true,
                )
        );
        $c = 'api_'.$_REQUEST['type'];
        $server->setClass("api_".$_REQUEST['type'],$bdd,$_REQUEST['type']);
        $server->handle();
    } catch (SoapFault $e) {
        $server = new SoapServer(null, array('uri' => $conf->main_url_root.'/api/api.php?type='.$_REQUEST['type']));
        $server->fault($e->getCode(), $e->getMessage());
        $server->handle();
    } catch (Exception $e) {
        $server = new SoapServer(null, array('uri' => $conf->main_url_root.'/api/api.php?type='.$_REQUEST['type']));
        $server->fault($e->getCode(), $e->getMessage());
        $server->handle();
    }
} else {
    $server = new SoapServer(null, array('uri' => $conf->main_url_root.'/api/api.php?type='.$_REQUEST['type']));
    $server->fault('Server', "Not authorized");
    $server->handle();
}
?>