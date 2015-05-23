<?php
load_alternative_class('class/debug.class.php');
require_once('conf/conf.php');
load_alternative_class('class/db.class.php');
load_alternative_class('class/common.class.php');
load_alternative_class('class/common_object.class.php');
require_once('lib/common.php');
require_once('lib/h2o.php');
load_alternative_class('class/conf.class.php');
$debug = new debug();
$conf = new conf();
$bdd=new Db($conf->main_db_type,
        $conf->main_db_host,
        $conf->main_db_user,
        $conf->main_db_pass,
        $conf->main_db_name,
        $conf->main_db_port);
$conf->load_from_db($bdd);
date_default_timezone_set($conf->timezone);

class view_error extends common
{
    public function run()
    {
        global $conf;
        $this->set_title('499  - client has closed connection ');
        $this->set_template_file(make_path('template',"httperror",'html',false));
        $this->set_main("<h1>client has closed connection </h1><br/>nginx : Le client a fermé la connexion avant de recevoir la réponse. Se produit quand le traitement est trop long côté serveur.<br/><br/><a href='".$conf->main_url_root."'>Retour à l'index</a>".(isset($_SERVER['HTTP_REFERER'])?"<br/><a href='".$_SERVER['HTTP_REFERER']."'>Retour à la page précédente</a>":"")."");
        echo $this->gen_page();
        exit;
    }
}

$v = new view_error($bdd,false);
$v->run();

?>