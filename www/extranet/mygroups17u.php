<?
include "auth.php";
$weare=32;
include "cookie_auth.php"; 
include "common.php";
  
$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: index.php"); exit; }
$title=$rowg["title"];
  
$invite_text=slasher($invite_text);

mysql_query("update groups set invite_text='$invite_text',tstamp=now() where id='$group_id'");

header("Location: mygroups17.php?group_id=$group_id");  
?>
