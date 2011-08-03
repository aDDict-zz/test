<?
$_MX_superadmin=0;
include "auth.php";
include "cookie_auth.php";  
if (!$_MX_superadmin) {
    exit;
}
$delete=get_http("delete","");

$delhash=($group_id*907333)%4111;
if ($delhash!=$delete) {
    header("Location: admin_statistic.php");
    exit;
}

$title="";
$res=mysql_query("select title from groups where id='$group_id'");
if ($res) 
    if (mysql_num_rows($res))
        $title=mysql_result($res,0,0);

if (empty($title)) {
    header("Location: admin_statistic.php");
    exit;
}

$res=mysql_query("select id from messages where group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $message_id=$k["id"];
        mysql_query("delete from bodies where id='$message_id'");
        mysql_query("delete from mailarchive where id='$message_id'");
    }
}
mysql_query("delete from messages where group_id='$group_id'");

$res=mysql_query("select id from validatemes where group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $message_id=$k["id"];
        mysql_query("delete from vbodies where id='$message_id'");
        mysql_query("delete from vmailarchive where id='$message_id'");
    }
}
mysql_query("delete from vlidatemes where group_id='$group_id'");

$res=mysql_query("select id from messages_scheduled where group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $message_id=$k["id"];
        mysql_query("delete from bodies_scheduled where id='$message_id'");
        mysql_query("delete from mailarchive_scheduled where id='$message_id'");
    }
}
mysql_query("delete from messages_scheduled where group_id='$group_id'");

mysql_query("delete from message_client where group_id='$group_id'");
mysql_query("delete from message_client_scheduled where group_id='$group_id'");

$res=mysql_query("select id from feedback where group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $feed_id=$k["id"];
        mysql_query("delete from trackf where feed_id='$feed_id'");
    }
}
mysql_query("delete from feedback where group_id='$group_id'");

$res=mysql_query("select id from filter where group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $filter_id=$k["id"];
        mysql_query("delete from filter_data where filter_id='$filter_id'");
    }
}
mysql_query("delete from filter where group_id='$group_id'");

$res=mysql_query("select id from track where group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $outid=$k["id"];
        mysql_query("delete from tracko where outid='$outid'");
    }
}
mysql_query("delete from track where group_id='$group_id'");

$res=mysql_query("select id from user_group where group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $user_group_id=$k["id"];
        mysql_query("delete from user_group_members where user_group_id='$user_group_id'");
    }
}
mysql_query("delete from user_group where group_id='$group_id'");

mysql_query("delete from sms_tracko where group_id='$group_id'");
mysql_query("delete from sms_in where group_id='$group_id'");
mysql_query("delete from sms_send where group_id='$group_id'");
mysql_query("delete from multigroup where groupid='$group_id'");
mysql_query("delete from multivalidation where group_id='$group_id'");
mysql_query("delete from validation where group_id='$group_id'");
mysql_query("delete from data_cemetary where group_id='$group_id'");
mysql_query("delete from bounced_back where group_id='$group_id' and project='maxima'");
mysql_query("delete from members where group_id='$group_id'");

mysql_query("delete from groups where id='$group_id'");
mysql_query("drop table users_$title");

header("Location: admin_statistic.php");
?>
