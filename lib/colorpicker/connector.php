<?php
chdir("../..");

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
global $lang,$conf;
require_once('class/session.class.php');
$session = new session();
$session->init();
$lang = $session->get('lang');
if (!$lang) $lang = $conf->default_lang;
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



?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<?php
    echo "<title>"._('ColorPicker')."</title>";
    $conf->admin_mode=true;
    echo "<script type='text/javascript' src=".make_path('js','jquery','js',true,true)."></script>";
    echo "<script type='text/javascript' src=".make_path('js','jquery-ui.min','js',true,true)."></script>";
    echo "<script type='text/javascript' src=".make_path('js','farbtastic','js',true,true)."></script>";
    echo '<link type="text/css" rel="stylesheet" href="'.make_path('css','farbtastic','css',true,true).'" />';
    echo '<link type="text/css" rel="stylesheet" href="'.make_path('css','jquery-ui.min','css',true,true).'" />';
?>
<script>
jQuery(document).ready(function()
{
    jQuery('button').button();
    startcolor = parent.tinymce.activeEditor.windowManager.getParams().get_color();
    if (typeof startcolor !== 'undefined')
    {
        startcolor = rgb2hex(startcolor);
        jQuery('#picker').val(startcolor);
    }
    jQuery('#colorpicker').farbtastic('#picker');
    jQuery('#picker').css('display','none');
    jQuery('#save').click(function(e)
    {
        color=jQuery('#picker').val();
        //pass selected file path to TinyMCE
        parent.tinymce.activeEditor.windowManager.getParams().set_color(color);
        // close popup window
        parent.tinymce.activeEditor.windowManager.close();

    });
});
function rgb2hex(rgb)
{
     rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
     return (rgb && rgb.length === 4) ? "#" +
      ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
      ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
    }
</script>
</head>
<body>
<input type="text" id="picker" type="hidden" name="picker" value="#123456" /></form>
<div id="colorpicker"></div>
<div class="save">
<button id="save"><?php echo _('Selectionner'); ?></button>
</div>
</body>
</html>