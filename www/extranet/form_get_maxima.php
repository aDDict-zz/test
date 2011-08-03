<?php 

/*
This file is included from:
- previewing forms, simply as include_once "form_get_maxima.php";
- from production forms, as include_once \"$_MX_var->baseDir/form_get_maxima.php\";
*/

function mx_form_get($maximaname,$preview) {

    if (!$preview) {
        $linkID = mysql_connect("localhost", "root", "bartok26");
        $succDB = mysql_select_db("maxima", $linkID);
        mysql_query("set names utf8");
    }
    $form_id=intval(mysql_escape_string(ereg_replace("mxform","",$maximaname)));
    $r=mysql_query("select f.active,f.form_inactive,g.title from form f,groups g where f.id=$form_id and f.group_id=g.id");
    if ($r && mysql_num_rows($r)) {
        if (mysql_result($r,0,0)=="no" && strlen(mysql_result($r,0,1)) && !$preview) {
            return "__form_inactive__|1";
        }
        $title = mysql_result($r,0,2);
        $cid="";
        $return_cid=false;
        if (isset($_REQUEST["cid"])) {
            $cid=mysql_escape_string($_GET["cid"]);
        }
        else {
            if (!empty($_REQUEST["banner"])) {
                $banner=mysql_escape_string($_REQUEST["banner"]);
                $r=mysql_query("select total_length from form_banner where form_id=$form_id and prefix='$banner'");
                if ($r && mysql_num_rows($r)) {
                    $total_length = mysql_result($r,0,0);
                    $return_cid = true;
                    mt_srand();
                    $cid = $banner . substr(md5(time() . "uuu" . mt_rand()),0,$total_length - strlen($banner));
                    $r=mysql_query("insert into {$title}_cid set cid='$cid'");
                }
            }
        }
        if (!$preview && !$return_cid) {
            $r=mysql_query("select * from {$title}_cid where cid='$cid' limit 1");
            if (!($r && mysql_num_rows($r))) {
                return "__invalid_cid__|1";
            }
        }
        $formdata="";
        if ($return_cid) {
            $formdata="cid|$cid";
        }
        elseif (!empty($cid)) {
            if (!$preview && !$return_cid) {
                $res=mysql_query("select * from users_$title where ui_cid='$cid' limit 1");
                if ($res && mysql_num_rows($res)) {
                    return "__finished__|1";
                }
            }
            $res=mysql_query("select formdata from form_save_temporary where cid='$cid' and maximaname='$maximaname'");
            if ($res && mysql_num_rows($res)) {
                $formdata = mysql_result($res,0,0);
            }
        }
        return $formdata;
    }
}
?>
