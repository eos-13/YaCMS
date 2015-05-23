<?php
bindtextdomain("plugins", 'plugins/template/tooltips/lang');
textdomain("plugins");

$info = "<h1>";
$info .= dcgettext('plugins',"Tooltips plugin",LC_ALL);
$info .= "</h1>";
$info .= "<br/>";
$info .= dcgettext('plugins',"Tooltips",LC_ALL);

$shortDesc = dcgettext('plugins',"Tooltip Plugin",LC_ALL);

$version = "1.0";

$name = "tooltips";

textdomain("messages");