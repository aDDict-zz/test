<?
  include "auth.php";
  $weare=6;
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
  
  $mail_demog_info=slasher($mail_demog_info);
  $use_tracko=slasher($use_tracko);

  mysql_query("update groups set mail_demog_info='$mail_demog_info',use_tracko='$use_tracko',tstamp=now() where id='$group_id'");

  header("Location: mygroups2.php?group_id=$group_id"); 
?>
