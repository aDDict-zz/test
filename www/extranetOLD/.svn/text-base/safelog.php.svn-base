<?
include "auth.php";

$xmlreq_id=intval(get_http("xmlreq_id",0));
$res=mysql_query("select logfile,group_id from xmlreq where id='$xmlreq_id'");
if ($res && mysql_num_rows($res)) {
    $slogfile=mysql_result($res,0,0);
    $group_id=mysql_result($res,0,1);
}
else {
    exit;
}

include "decode.php";
$weare=70;
include "cookie_auth.php";

$mres = mysql_query("select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }

$title=$rowg["title"];
$active_membership=$rowg["membership"];
header("Content-Type: application/octet-stream");
header("Content-Disposition: filename=importlog$xmlreq_id.txt");
readfile ("$slogfile.safelog");
 
?>
