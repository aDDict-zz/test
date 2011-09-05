<?
$_MX_superadmin=0;
include "auth.php";
include "cookie_auth.php";  
if (!$_MX_superadmin) {
    exit;
}

$group_id=htmlspecialchars($group_id);
$sms_send=htmlspecialchars(get_http("sms_send","yes"));
$important=htmlspecialchars(get_http("important","yes"));

if ($sms_send) {
    $email="";
    $res=mysql_query("select sms_send from groups where id='$group_id'");
    if ($res && mysql_num_rows($res))
        $ss_old=mysql_result($res,0,0);
    else {
        header("Location: admin_statistic.php");
        exit;
    }
    if ($ss_old!=$sms_send) 
        mysql_query("update groups set sms_send='$sms_send' where id='$group_id'");
}
if ($important) {
    $email="";
    $res=mysql_query("select important from groups where id='$group_id'");
    if ($res && mysql_num_rows($res))
        $ss_old=mysql_result($res,0,0);
    else {
        header("Location: admin_statistic.php");
        exit;
    }
    if ($ss_old!=$important) 
        mysql_query("update groups set important='$important' where id='$group_id'");
}

header("Location: admin_statistic.php");
?>
