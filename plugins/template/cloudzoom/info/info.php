<?php
bindtextdomain("plugins", 'plugins/template/cloudzoom/lang');
textdomain("plugins");

$info = "<h1>";
$info .= dcgettext('plugins',"Cloudzoom template Plugin",LC_ALL);
$info .= "</h1>";
$info .= "<br/>";
$info .= dcgettext('plugins',"Plugin pour faire des zoom sur une image",LC_ALL);

$shortDesc = dcgettext('plugins',"Cloudzoom template Plugin",LC_ALL);

$version = "1.0";

$name = "circularSlider";

textdomain("messages");