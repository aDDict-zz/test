<?
include "auth.php";
$weare=70;
include "common.php";
include "cookie_auth.php";  

$mres = mysql_query("select title,num_of_mess,unique_col from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    exit; 
}

$group_id=get_http("group_id",0);
$xmlreq_id=get_http("xmlreq_id",0);
$group_id=intval($group_id);
$xmlreq_id=intval($xmlreq_id);

$percent=0;
$status="aborted";
$messages="";
$logfile="";
$res=mysql_query("select id,status,progress,progress_max,job_errors,job_output,logfile,job_type,downloaded,
                  ext_dl,ext_dl_auth,ext_dl_username,ext_dl_password
                  from xmlreq where group_id='$group_id' and id='$xmlreq_id'");
if ($res && mysql_num_rows($res)) {
    $k=mysql_fetch_array($res);
    $status=$k["status"];
    $ismess=array();
    if (strlen($k["job_errors"])) {
        $ismess[]=nl2br(htmlspecialchars($k["job_errors"]));
    }
    if (strlen($k["job_output"]) && $k["job_type"]!="export_member") {
        $ismess[]=nl2br(htmlspecialchars($k["job_output"]));
    }
    if (strlen($k["job_output"]) && $k["job_type"]=="export_member") {
        if ($k["ext_dl_auth"]=="in-url" && $k["ext_dl"]=="yes") {
            $ext_dl_password=htmlspecialchars($k["ext_dl_password"]);
            $ext_dl_username=htmlspecialchars($k["ext_dl_username"]);
            $url="$_MX_var->baseUrl/xrd.php?xrd=$k[id]&csvpass=$ext_dl_password&csvuser=$ext_dl_username";
        }
        else {
            $url="$_MX_var->baseUrl/xrd.php?xrd=$k[id]";
        }
        if ($k["ext_dl"]=="yes") {
            $status="ready";
        }
        if ($k["ext_dl"]=="yes" || $k["downloaded"]=="no") {
            $ismess[]="<a href='$url'>Letöltés</a><br>$url";
        }
    }
    if (count($ismess)) {
        $messages=implode("<br>",$ismess);
    }
    if ($k["progress_max"]>0) {
        $percent=floor($k["progress"]/$k["progress_max"]*100);
        $percent=min($percent,99);
    }
    if (strlen($k["logfile"]) && $k["job_type"]!="export_member") {
        $logfile=$k["id"];
    }
}

print "*|$status|$percent|$messages|$logfile";

?>
