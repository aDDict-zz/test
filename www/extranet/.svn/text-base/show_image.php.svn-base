<?php

include "auth.php";

$filename=$_GET["filename"];
if (!eregi("(gif|jpg|png)$",$filename)) {
    exit;
}
$sfilename = mysql_escape_string($filename);
$res=mysql_query("select * from form_images where filename='$filename'");
if ($res && mysql_num_rows($res)) {
    $fpath=$_MX_var->form_imagepath . $filename;
    header('Content-Type: image/jpeg');
    header('Content-Length: '.filesize($fpath));
    $fp = fopen($fpath, 'rb');
    fpassthru($fp);
    fclose($fp);
}
?>
