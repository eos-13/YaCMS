<?php
bindtextdomain("plugins", 'plugins/template/thumbnelia/lang');
textdomain("plugins");

$info = "<h1>";
$info .= dcgettext('plugins',"Thumbnelia gallery Plugin",LC_ALL);
$info .= "</h1>";
$info .= "<br/>";
$info .= dcgettext('plugins',"Thumbnelia gallery action",LC_ALL);

$shortDesc = dcgettext('plugins',"Thumbnelia gallery plugin",LC_ALL);

$version = "1.0";

$name = "thumbnelia";

textdomain("messages");