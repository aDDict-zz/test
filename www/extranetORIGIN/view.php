<?php
include "auth.php";
$weare=20;
include "mime_str.php";

#print "&lt;DEBUG&gt;group_id=$group_id message_id=$message_id title=$title<br><br>";

$group_id=get_http("group_id",0);
$message_id=get_http("message_id",0);
$index=get_http("index",0);
$mime_part_id=get_http("mime_part_id",0);

$is_html_view=0;
$for_egyperc=0;
include "_htmlview_auth.php";
if ($is_html_view==0 && $for_egyperc==0) {
    include "cookie_auth.php";
    $mres = mysql_query("select title,num_of_mess,membership from groups,members 
                         where groups.id=members.group_id and groups.id='$group_id' 
                         and (membership='owner' or membership='moderator' or membership='client' or membership='support' or membership='affiliate' $admin_addq)
                         and user_id='$active_userid'");
    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else 
        exit; 

    $message_id=intval($message_id);
    $res=mysql_query("select * from messages where group_id='$group_id' and id='$message_id'");
    if ($res && mysql_num_rows($res))
        $row=mysql_fetch_array($res);
    else
        exit;
}

if ($mime_part_id) {
    $query=mysql_query("select header,body from mailarchive where id='$message_id'");
    if ($query && mysql_num_rows($query)) {
        $l=mysql_fetch_row($query);
        $string=$l[0]."\n\n".$l[1];
        $string=ereg_replace("\r\n","\n",$string);
        $string=ereg_replace("\n","\n",$string);
        str_fetchstructure($string,0,strlen($string),$mime_part_id); 
    }
}
?>
