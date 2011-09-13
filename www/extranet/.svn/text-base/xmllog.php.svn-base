<?
include "auth.php";
$weare=70;
include "common.php";
include "cookie_auth.php";  
include "_sendlog.php";  

$group=get_http("group","");
$group=mysql_escape_string($group);

$mres = mysql_query("select title,num_of_mess,unique_col from groups,members where groups.id=members.group_id
                     and groups.title='$group' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' or membership='client' or membership='support' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    exit; 
}

$xmlreq_id=intval(get_http("xmlreq_id",0));
$message_id=intval(get_http("message_id",0));
$language="hu";
include "./lang/$language/threadlist.lang";

$sendlog = new MxSendLog($group,$message_id);
print $sendlog->GetMsgStatus();

?>
