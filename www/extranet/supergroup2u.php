<?


$multiid=intval($multiid);
header("location: mygroups10.php?&multiid=$multiid");
exit;
// this page is replaced by the above mentioned.



  include "auth.php";
  include "cookie_auth.php"; 
  include "common.php";
  
  $multiid=intval($multiid);
  $mres = mysql_query("select * from multi where owner_id='$active_userid' and id='$multiid'");
  if ($mres && mysql_num_rows($mres))
     $row=mysql_fetch_array($mres);  
  else {
     header("Location: index.php"); exit; }
  
  $custom_footer=slasher($custom_footer);
  $custom_header=slasher($custom_header);  
  $subscribe_subject=slasher($subscribe_subject);
  $subscribe_body=slasher($subscribe_body);  
  $welcome_message=slasher($welcome_message);  
  $welcome_subject=slasher($welcome_subject);    
  $landing2=slasher($landing2);
  $landingpage2=slasher($landingpage2);
  $welcome_yesno=slasher($welcome_yesno);    
  $validator_page=slasher($validator_page);  
  $already_subs=slasher($already_subs);  

  mysql_query("update multi set custom_head='$custom_header', custom_foot='$custom_footer',landing2='$landing2',welcome_message='$welcome_message',subscribe_subject='$subscribe_subject',subscribe_body='$subscribe_body',welcome_subject='$welcome_subject',landingpage2='$landingpage2',welcome_yesno='$welcome_yesno',validator_page='$validator_page',already_subs='$already_subs',tstamp=now() where id='$multiid'");

  header("Location: supergroup2.php?multiid=$multiid");  
?>
