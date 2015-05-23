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

$tmpLang = substr($lang,0,2);


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



?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>elFinder 2.x source version with PHP connector</title>
<script src="../main/admin/js//jquery.js" type="text/javascript" charset="utf-8"></script>
<script src="../main/admin/js/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="../main/admin/css/jquery-ui.css" type="text/css" media="screen" title="no title" charset="utf-8">
<link rel="stylesheet" href="../main/admin/css/elfinder/common.css"      type="text/css">
<link rel="stylesheet" href="../main/admin/css/elfinder/dialog.css"      type="text/css">
<link rel="stylesheet" href="../main/admin/css/elfinder/toolbar.css"     type="text/css">
<link rel="stylesheet" href="../main/admin/css/elfinder/navbar.css"      type="text/css">
<link rel="stylesheet" href="../main/admin/css/elfinder/statusbar.css"   type="text/css">
<link rel="stylesheet" href="../main/admin/css/elfinder/contextmenu.css" type="text/css">
<link rel="stylesheet" href="../main/admin/css/elfinder/cwd.css"         type="text/css">
<link rel="stylesheet" href="../main/admin/css/elfinder/quicklook.css"   type="text/css">
<link rel="stylesheet" href="../main/admin/css/elfinder/commands.css"    type="text/css">
<link rel="stylesheet" href="../main/admin/css/elfinder/fonts.css"       type="text/css">
<link rel="stylesheet" href="../main/admin/css/elfinder/theme.css"       type="text/css">
<!-- elfinder core -->
<script src="../main/admin/js/elfinder/elFinder.js"></script>
<script src="../main/admin/js/elfinder/elFinder.version.js"></script>
<script src="../main/admin/js/elfinder/jquery.elfinder.js"></script>
<script src="../main/admin/js/elfinder/elFinder.resources.js"></script>
<script src="../main/admin/js/elfinder/elFinder.options.js"></script>
<script src="../main/admin/js/elfinder/elFinder.history.js"></script>
<script src="../main/admin/js/elfinder/elFinder.command.js"></script>
<!-- elfinder ui -->
<script src="../main/admin/js/elfinder/ui/overlay.js"></script>
<script src="../main/admin/js/elfinder/ui/workzone.js"></script>
<script src="../main/admin/js/elfinder/ui/navbar.js"></script>
<script src="../main/admin/js/elfinder/ui/dialog.js"></script>
<script src="../main/admin/js/elfinder/ui/tree.js"></script>
<script src="../main/admin/js/elfinder/ui/cwd.js"></script>
<script src="../main/admin/js/elfinder/ui/toolbar.js"></script>
<script src="../main/admin/js/elfinder/ui/button.js"></script>
<script src="../main/admin/js/elfinder/ui/uploadButton.js"></script>
<script src="../main/admin/js/elfinder/ui/viewbutton.js"></script>
<script src="../main/admin/js/elfinder/ui/searchbutton.js"></script>
<script src="../main/admin/js/elfinder/ui/sortbutton.js"></script>
<script src="../main/admin/js/elfinder/ui/panel.js"></script>
<script src="../main/admin/js/elfinder/ui/contextmenu.js"></script>
<script src="../main/admin/js/elfinder/ui/path.js"></script>
<script src="../main/admin/js/elfinder/ui/stat.js"></script>
<script src="../main/admin/js/elfinder/ui/places.js"></script>

<!-- elfinder commands -->
<script src="../main/admin/js/elfinder/commands/reload.js"></script>
<script src="../main/admin/js/elfinder/commands/up.js"></script>
<script src="../main/admin/js/elfinder/commands/home.js"></script>
<script src="../main/admin/js/elfinder/commands/open.js"></script>
<script src="../main/admin/js/elfinder/commands/rm.js"></script>
<script src="../main/admin/js/elfinder/commands/info.js"></script>
<script src="../main/admin/js/elfinder/commands/rename.js"></script>
<script src="../main/admin/js/elfinder/commands/getfile.js"></script>
<script src="../main/admin/js/elfinder/commands/mkdir.js"></script>
<script src="../main/admin/js/elfinder/commands/upload.js"></script>
<script src="../main/admin/js/elfinder/commands/download.js"></script>
<script src="../main/admin/js/elfinder/commands/quicklook.js"></script>
<script src="../main/admin/js/elfinder/commands/quicklook.plugins.js"></script>
<script src="../main/admin/js/elfinder/commands/search.js"></script>
<script src="../main/admin/js/elfinder/commands/view.js"></script>
<script src="../main/admin/js/elfinder/commands/resize.js"></script>
<script src="../main/admin/js/elfinder/commands/sort.js"></script>

<!-- elfinder languages -->
<?php

    if (is_file("../main/admin/js/elfinder/i18n/elfinder.".$tmpLang.".js"))
    {
        echo '<script src="../main/admin/js/elfinder/i18n/elfinder.'.$tmpLang.'.js"></script>';
    } else {
        $tmpLang = substr($conf->default_lang,0,2);
        if (is_file("../main/admin/js/elfinder/i18n/elfinder.".$tmpLang.".js"))
        {
            echo '<script src="../main/admin/js/elfinder/i18n/elfinder.'.$tmpLang.'.js"></script>';
        } else {
            echo '<script src="../main/admin/js/elfinder/i18n/elfinder.en.js"></script>';
        }
    }
?>


<script>
var FileBrowserDialogue =
{
    init: function()
    {
          // Here goes your code for setting your custom things onLoad.
    },
    mySubmit: function (URL)
    {
        //pass selected file path to TinyMCE
        parent.tinymce.activeEditor.windowManager.getParams().setUrl(URL);
        // close popup window
        parent.tinymce.activeEditor.windowManager.close();
    }
}

jQuery(document).ready(function() {
    var elf = jQuery('#finder').elfinder({
        url: 'elfinder/php/connector.php',  // connector URL
        lang : 'fr',
        debug:false,
        getFileCallback: function(file)
        { // editor callback
            path = "<?php echo $main_url_root."/"; ?>/"+file.path;
            FileBrowserDialogue.mySubmit(path); // pass selected file path to TinyMCE
        }
    }).elfinder('instance');
});
</script>
</head>
<body>
<div id="finder">finder <span>here</span></div>
</body>
</html>