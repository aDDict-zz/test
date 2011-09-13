<?
include "auth.php";
include "decode.php";
$weare=25;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/sms.lang";

$group_id=intval($group_id);
$mres = mysql_query("select title,num_of_mess,membership from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);
else
    exit; 
$title=$rowg["title"];

$message_id=mysql_escape_string($message_id);

$res=mysql_query("select message from sms_send where id='$message_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    $subject=mysql_result($res,0,0);
    $subject=str_replace("\n","",$subject);
    $subject=str_replace("\r","",$subject);
    $explain="$subject - $word[t_not_delivered]";
}
else
    exit;

$act_memb=$rowg["membership"];

header("Content-Type: application/x-unknown");
header("Content-Disposition: filename=sms_bounced_$message_id.csv");

print "$explain\n\"$word[t_phone]\";\"$word[t_status]\"\n";
          
$res=mysql_query("select * from sms_status where code=7 and sms_id='$message_id'");

if ($res && mysql_num_rows($res)) { 
    while($row=mysql_fetch_array($res)) {
        print "\"$row[phone]\";\"$row[status]\"\n";
    }
}



