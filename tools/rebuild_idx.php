<?php
require_once ('../lib/common.php');
require_once('../lib/h2o.php');

if (is_file(__DIR__.'/../customer_class/conf.class.php'))
    require_once(__DIR__.'/../customer_class/conf.class.php');
else
    require_once(__DIR__.'/../class/conf.class.php');

if (is_file(__DIR__.'/../customer_class/db.class.php'))
    require_once(__DIR__.'/../customer_class/db.class.php');
else
    require_once(__DIR__.'/../class/db.class.php');

if (is_file(__DIR__.'/../customer_class/debug.class.php'))
    require_once(__DIR__.'/../customer_class/debug.class.php');
else
    require_once(__DIR__.'/../class/debug.class.php');

if (is_file(__DIR__.'/../customer_class/common.class.php'))
    require_once(__DIR__.'/../customer_class/common.class.php');
else
    require_once(__DIR__.'/../class/common.class.php');

if (is_file(__DIR__.'/../customer_class/common_object.class.php'))
    require_once(__DIR__.'/../customer_class/common_object.class.php');
else
    require_once(__DIR__.'/../class/common_object.class.php');

if (is_file(__DIR__.'/../customer_class/sphinx.class.php'))
    require_once(__DIR__.'/../customer_class/sphinx.class.php');
else
    require_once(__DIR__.'/../class/sphinx.class.php');

if (is_file(__DIR__.'/../customer_class/log.class.php'))
    require_once(__DIR__.'/../customer_class/log.class.php');
else
    require_once(__DIR__.'/../class/log.class.php');

if (is_file(__DIR__.'/../customer_class/user.class.php'))
    require_once(__DIR__.'/../customer_class/user.class.php');
else
    require_once(__DIR__.'/../class/user.class.php');

if (is_file(__DIR__.'/../customer_class/trigger.class.php'))
    require_once(__DIR__.'/../customer_class/trigger.class.php');
else
    require_once(__DIR__.'/../class/trigger.class.php');


$debug = new debug();
$conf = new conf();
$db=new Db($conf->main_db_type,
        $conf->main_db_host,
        $conf->main_db_user,
        $conf->main_db_pass,
        $conf->main_db_name,
        $conf->main_db_port);
$conf->load_from_db($db);
$log = new log();
$trigger = new trigger($db);
date_default_timezone_set($conf->timezone);

if (is_file(__DIR__.'/../customer_class/page.class.php'))
    require_once(__DIR__.'/../customer_class/page.class.php');
else
    require_once(__DIR__.'/../class/page.class.php');


(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('cli only');


ini_set('max_execution_time',3600);
chdir($conf->main_document_root);
$sp = new Sphinx($db);
$res=$sp->reset_all();
$lang="fr_FR";
print "Effacement de ".$res." enregistrements\n";

$requete = "SELECT *
              FROM user
             WHERE login = 'admin'";
$sql = $db->query($requete);
if (!$sql || ! $db->num_rows($sql) > 0)
{
    echo "Admin user not found";
    exit(1) ;
}
$res = $db->fetch_object($sql);

$current_user = new user($db);
$current_user->fetch($res->id);
$current_user->fetch_right();

$requete = "SELECT id
              FROM page
             WHERE active = 1
               AND exclude_search = 0";
$sql = $db->query($requete);
$date = time();
$iter=0;
$p = new page($db);
while ($res = $db->fetch_object($sql))
{
    $iter++;
    $p->fetch($res->id);
    $p->insert_sphinx();
}

$date2=time();

print $iter ." page(s) reindexer en ".  round($date2 - $date) ."s\n";

?>
