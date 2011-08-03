<?



$group_id=intval($group_id);
header("location: mygroups10.php?group_id=$group_id");
exit;
// this page is replaced by the above mentioned.


  include "auth.php";
    $weare=13;
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
  
  $unsub_mail_subject=slasher($unsub_mail_subject);
  $unsub_mail=slasher($unsub_mail);
  $mail_subject=slasher($mail_subject);
  $subscribe_subject=slasher($subscribe_subject);
  $subscribe_body=slasher($subscribe_body);  
  $welcome_yesno=slasher($welcome_yesno);     
  $intro=slasher($intro);
  $already_subs=slasher($already_subs);

  mysql_query("update groups set unsub_mail_subject='$unsub_mail_subject',unsub_mail='$unsub_mail',mail_subject='$mail_subject',subscribe_subject='$subscribe_subject',subscribe_body='$subscribe_body',welcome_yesno='$welcome_yesno',already_subs='$already_subs',intro='$intro',tstamp=now() where id='$group_id'");

  header("Location: mygroups10.php?group_id=$group_id");  
?>
