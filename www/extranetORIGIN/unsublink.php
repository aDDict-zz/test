<?

include "auth.php";
include "common.php";

$p = split('/',getenv(PATH_INFO));

// $p=split(',',$pp);
// print md5("$p[1]:$p[2]:mojnedapitas:$p[3]");
// unsublink.php?pp=x,251,9999,110533
// unsublink.php?pp=x,251,0,teszt110536@example.com
// unsublink.php?pp=x,251,9999,100658,ffbaa285a85fd4fefa27426c36c2a8b8
// unsublink.php?pp=x,251,0,teszt110536@example.com,6aeef2349ba507dadfc86bac27126cdb

$res = 'notok';

$group_id = slasher($p[1]);
$message_id = slasher($p[2]);
$user_id = slasher($p[3]);
$email = '';
$p[4]=str_replace(".","",$p[4]);

$from_html_version='no';
if (ereg("^(.+)z$",$p[4],$urz)) {
    $p[4]=$urz[1];
    $from_html_version='yes';
}

if ($p[4] == md5("$p[1]:$p[2]:mojnedapitas:$p[3]")) {
    $q = mysql_query("select title, owner_id from groups where id='$group_id'");
    if ($q and mysql_num_rows($q)) {
       $r = mysql_fetch_row($q);
       $gtitle = $r[0];
       $owner_id = $r[1];
       if (ereg("^[0-9]+$",$user_id)) {
            $qpart="u1.id='$user_id'";
       }
       else {
            $qpart="u1.ui_email='$user_id'";
       }
       $q = mysql_query("select u1.ui_email, u2.id, u1.id user_id from users_$gtitle u1 left join user u2 on (u1.ui_email=u2.email) where $qpart and u1.robinson='no'");
       if ($q and mysql_num_rows($q)) {
            $r = mysql_fetch_row($q);
            $email = $r[0];
            $uid = $r[1];
            $user_id = $r[2];
            if ($uid != $owner_id) {  // from LX?
               // everything is fine, user is subscribed, exists, and he is not owner.
               $res = 'ok';
               $succ=mx_ppos_unsub($email,$gtitle,"unsublink");
               mysql_query("insert into validation(user_id,group_id,action,date,validated,tstamp,sender,message_id) values ('$user_id','$group_id','unsub',now(),'yes',now(),'$email','$message_id')");
            }
       }
    }       
}

$q = mysql_query("select unsublink_$res,header,footer,name from groups where id='$group_id'");

if ($q && mysql_num_rows($q)) {
   $r = mysql_fetch_row($q);
   print mysql_error();
   $r[0] = preg_replace("/\{email\}/i",$email,$r[0]);
   $r[0] = preg_replace("/\{group\}/i",$r[3],$r[0]);
   print "$r[1]$r[0]$r[2]";
}

?>


