<?php
bindtextdomain("plugins", 'plugins/template/silver_track/lang');
textdomain("plugins");

$info = "<h1>";
$info .= dcgettext('plugins',"Silver track Plugin",LC_ALL);
$info .= "</h1>";
$info .= "<br/>";
$info .= dcgettext('plugins',"Silver track js in action",LC_ALL);

$shortDesc = dcgettext('plugins',"Silver track misc Plugin",LC_ALL);

$version = "1.0";

$name = "silver_track";

textdomain("messages");