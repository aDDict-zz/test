<?
  include "auth.php";
$weare=12;
  include "cookie_auth.php"; 
  include "common.php";

  $newgroup=get_http('newgroup','');
  $delgroup=get_http('delgroup','');  
  $addtogroup=get_http('addtogroup','');
  $showgroup=get_http('showgroup','');
  $user_id=get_http('user_id','');

  $group_id=intval($group_id);
  $newgroup=slasher($newgroup,0);
  $delgroup=intval($delgroup);  
  $addtogroup=intval($addtogroup);
  $showgroup=intval($showgroup);

  set_time_limit(0);

$mres = mysql_query("select groups.* from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: index.php"); exit; }
$title=$rowg["title"];
  
  if($newgroup && !eregi("^[a-z]+[0-9a-z_]*$", $newgroup)) {
     header("Location: mygroups9.php?group_id=$group_id&err=1");
     exit;
  }

  if ($delgroup) {
  	$q="delete from user_group where group_id='$group_id' and id='$delgroup'";
     mysql_query($q);
     logger($q,$group_id,"","user_group_id=$delgroup","user_group");
     if (mysql_affected_rows()) {
    	$q="delete from user_group_members where user_group_id='$delgroup'";
       mysql_query($q);     
       logger($q,$group_id,"","user_group_id=$delgroup","user_group_members");              
       $q="update users_$title set uglist=replace(uglist,',$delgroup,','')";
       mysql_query($q);
       logger($q,$group_id,"","","users_");       
     }
  }
  if ($newgroup) 
	  $q="insert into user_group (group_id,name,tstamp) values ('$group_id','$newgroup',now())";	  
      mysql_query($q);
      logger($q,$group_id,"","user_group_id=$delgroup","users_");       
  if ($delusers && is_array($user_id)) {
    if ($showgroup != -1) {
      $r5=mysql_query("select id from user_group where group_id='$group_id' and id='$showgroup'");
      if ($r5 && mysql_num_rows($r5)) {
        while (list($key, $val) = each($user_id)) {
          $key=intval($key);
          $q="delete from user_group_members where user_id='$key' and user_group_id='$showgroup'";
          mysql_query($q);
       	  logger($q,$group_id,"","user_id=$key","users_group_members");
       	  $q="update users_$title set uglist=replace(uglist,',$showgroup,','') where id='$key'";
          mysql_query($q);                     	  
		  logger($q,$group_id,"","user_id=$key","users_");         
        }
      }
    }
  }

  if ($addtogroup && is_array($user_id)) {
    $r5=mysql_query("select id from user_group where group_id='$group_id' and id='$addtogroup'");
    if ($r5 && mysql_num_rows($r5)) {      
      while (list($key, $val) = each($user_id)) {
        $key=intval($key);
        //print "select id from users_$title where user_id='$key'<br>";
        $r6=mysql_query("select id from users_$title where id='$key'");
        if ($r6 && mysql_num_rows($r6)) {      
          $r7=mysql_query("select id from user_group_members where user_group_id='$addtogroup' and user_id='$key'");
          if ($r7 && !mysql_num_rows($r7)) {      
          $q="insert into user_group_members (user_id,user_group_id,tstamp) 
                       values ('$key','$addtogroup',now())";	
          mysql_query($q);
		  logger($q,$group_id,"","","user_group_members");
		  $q="update users_$title set uglist=concat(uglist,',$addtogroup,') where id='$key'";                       
          mysql_query($q);                 		  
		  logger($q,$group_id,"","user_id=$key","users_");      
        }
       }
      }
    }
  }
  //exit;
  header("Location: mygroups9.php?group_id=$group_id");  
?>
