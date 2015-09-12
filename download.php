<?php

require_once('conf/conf.php');

$file = urldecode($_REQUEST['file']);
$file = $main_document_root."/files/".$file;

$info = new SplFileInfo($file);
$taille = 0;
$basename=false;
if ($info->isFile())
{
    $taille = $info->getSize();
    $basename = $info->getFilename();

}

//telechargement

header("Content-Type: application/force-download; name=\"$basename\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: $taille");
header('Content-Disposition: inline; filename="'.$basename.'"');
//header("Content-Disposition: attachment; filename=\"$basename\"");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

if (is_file($info->getPathname()) && is_readable($info->getPathname()))
{
    echo file_get_contents($info->getPathname());
}

exit();