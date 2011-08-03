<?
include "auth.php";
include "common.php";

// http://www.maxima.hu/maint.php?a=251de36bc8a134b9828b5593f5edf2ac44cc
$action="";
$hashid="";
$group_id=0;
if (isset($_GET["a"])) {
    if (ereg("^([0-9]+)(d|s)([0-9a-z]{32})",$_GET["a"],$regs)) {
        $group_id=$regs[1];
        $action=$regs[2];
        $hashid=$regs[3];
    }
}
$succ="notok";
$res=mysql_query("select header,footer,name,maint_notify_delete_ok,maint_notify_delete_notok from groups where id='$group_id'");
if ($res && mysql_num_rows($res)) {
    $k=mysql_fetch_array($res);
    $r2=mysql_query("select id from maint_notify_delete where group_id='$group_id' and hashid='$hashid' and status='sent'");
    if ($r2 && mysql_num_rows($r2)) {
        $succ="ok";
        mysql_query("update maint_notify_delete set status='clicked' where id=". mysql_result($r2,0,0));
    }
    $message=$k["header"].$k["maint_notify_delete_$succ"].$k["footer"];
    $message = preg_replace("/\{email\}/i",$email,$message);
    $message = preg_replace("/\{group\}/i",$k["name"],$message);
    print $message;
}       
?>
