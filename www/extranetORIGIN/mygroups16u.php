<?


$group_id=intval($group_id);
header("location: mygroups10.php?automatic_type=web&group_id=$group_id");
exit;
// this page is replaced by the above mentioned.




  include "auth.php";
$weare=22;
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
  
  $custom_footer=slasher($custom_footer);
  $custom_header=slasher($custom_header);  
  $landing2=slasher($landing2);
  $landingpage2=slasher($landingpage2);
  $validator_page=slasher($validator_page);
  $validator_page_unsub=slasher($validator_page_unsub);
  $unsublink_ok=slasher($unsublink_ok);
  $unsublink_notok=slasher($unsublink_notok);

  mysql_query("update groups set custom_head='$custom_header', custom_foot='$custom_footer',landing2='$landing2',landingpage2='$landingpage2',validator_page='$validator_page',validator_page_unsub='$validator_page_unsub',unsublink_ok='$unsublink_ok', unsublink_notok='$unsublink_notok', tstamp=now() where id='$group_id'");

  header("Location: mygroups16.php?group_id=$group_id");  
?>
