<?php

bindtextdomain("plugins", 'plugins/template/booklet/lang');
textdomain("plugins");

$info = "<h1>";
$info .= dcgettext('plugins',"Booklet Plugin",LC_ALL);
$info .= "</h1>";
$info .= "<br/>";
$info .= dcgettext('plugins',"Présentation sous forme de livre",LC_ALL);

$shortDesc = dcgettext('plugins',"Booklet Plugin",LC_ALL);

$version = "1.0";

$name = "booklet";

textdomain("messages");
