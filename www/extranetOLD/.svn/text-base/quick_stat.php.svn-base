<?
include "auth.php";
$weare=18;
$language="hu";
include "cookie_auth.php"; 
include "common.php";
include "_quick_stat.php";
include "./lang/$language/statistics.lang";

$message_id=get_http("message_id",0);
$group_id=intval(get_http("group_id",0));
$csv=intval(get_http("csv",1));
$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid'
                     and (membership='owner' or membership='moderator' $admin_addq)");
logger($q,$group_id,"","demographic_statisztika","groups,members");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }
$title=$row["title"];
  
$stat = new MaxQucikStat($group_id,$message_id,$title,$csv);

?>
