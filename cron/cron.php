<?php

require_once(__DIR__.'/../conf/conf.php');
require_once(__DIR__.'/../lib/common.php');


if (is_file(__DIR__.'/../customer_class/log.class.php'))
    require_once(__DIR__.'/../customer_class/log.class.php');
else
    require_once(__DIR__.'/../class/log.class.php');

if (is_file(__DIR__.'/../customer_class/debug.class.php'))
    require_once(__DIR__.'/../customer_class/debug.class.php');
else
    require_once(__DIR__.'/../class/debug.class.php');

if (is_file(__DIR__.'/../customer_class/db.class.php'))
    require_once(__DIR__.'/../customer_class/db.class.php');
else
    require_once(__DIR__.'/../class/db.class.php');

if (is_file(__DIR__.'/../customer_class/common.class.php'))
    require_once(__DIR__.'/../customer_class/common.class.php');
else
    require_once(__DIR__.'/../class/common.class.php');

if (is_file(__DIR__.'/../customer_class/common_object.class.php'))
    require_once(__DIR__.'/../customer_class/common_object.class.php');
else
    require_once(__DIR__.'/../class/common_object.class.php');

if (is_file(__DIR__.'/../customer_class/conf.class.php'))
    require_once(__DIR__.'/../customer_class/conf.class.php');
else
    require_once(__DIR__.'/../class/conf.class.php');

$debug = new debug();
$conf = new conf();
$db=new Db($conf->main_db_type,
        $conf->main_db_host,
        $conf->main_db_user,
        $conf->main_db_pass,
        $conf->main_db_name,
        $conf->main_db_port);
$conf->load_from_db($db);
date_default_timezone_set($conf->timezone);


$dir = __DIR__."/scripts/";

if ($handle = opendir($dir))
{
    while (false !== ($file = readdir($handle))) {
        if (preg_match('/([\w]*)_cron.class.php$/',$file,$arr))
        {
            $requete = "SELECT *
                          FROM cron
                         WHERE object = '".$arr[1]."_cron'";
            $sql = $db->query($requete);
            $res = $db->fetch_object($sql);
            $num = $db->num_rows($sql);
            if ($res->active == 1)
            {
                require_once($dir.$arr[1]."_cron.class.php");
                $objStr = $arr[1]."_cron";
                $obj = new $objStr($db);
                $obj->do_action();
            }
        }
    }
}

?>