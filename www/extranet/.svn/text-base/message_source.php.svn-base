<?
include "auth.php";
$weare=20;

include "cookie_auth.php";
include "mime_str.php";

#print "&lt;DEBUG&gt;group_id=$group_id message_id=$message_id title=$title<br><br>";

$mres = mysql_query("select title,num_of_mess,membership from groups,members 
                     where groups.id=members.group_id and groups.id='$group_id' 
                     and (membership='owner' or membership='moderator' or membership='client' or membership='support' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
     exit; }

$message_id=intval($message_id);

!empty($admin_addq)?$act_memb="moderator":$act_memb=$rowg["membership"];

if ($act_memb=="client") {
    $rcl=mysql_query("select * from message_client where message_id='$message_id' and user_id='$active_userid'");
    if (!($rcl && mysql_num_rows($rcl)))
        exit;
}

$res=mysql_query("select * from messages where group_id='$group_id' and id='$message_id'");
if ($res && mysql_num_rows($res))
    $row=mysql_fetch_array($res);
else
    exit;

mysql_query("set names latin1");
$query=mysql_query("select header,body from mailarchive where id='$message_id'");
if ($query && mysql_num_rows($query)) {
  $l=mysql_fetch_row($query);
  $string=$l[0]."\n\n".$l[1];
  $deblen0=strlen($l[0]);
  $deblen1=strlen($l[1]);
  $string=ereg_replace("\r\n","\n",$string);
  $string=ereg_replace("\n","\n",$string);
#  print "&lt;DEBUG&gt; header length:$deblen0 body length:$deblen1<br><br>";
#  $string=ereg_replace('=3D','=',$string);
#  print $string;
#  exit;
}


print "<html>
<head>
<title>Source</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
</head>
<body><font face=arial size=1>";
print nl2br(htmlspecialchars($string));
print "</font></body></html>";

?>
