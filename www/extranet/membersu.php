<?
include "auth.php";
$weare=4;
include "cookie_auth.php";  
include "common.php";  

$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' or membership='support' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: index.php"); exit; }
$title=$rowg["title"];

$deluser = get_http('deluser','');
$activateuser = get_http('activateuser','');
$unsubuser = get_http('unsubuser','');
$deluser_id = get_http('deluser_id','');
if (($deluser || $activateuser || $unsubuser) && is_array($deluser_id)) {
    if ($unsubuser) {
        $log_query="update";
        $log_info="unsub";
    }
    elseif ($deluser) {
        $log_query="delete";
        $log_info="delete";
    }
    else {
        $log_query="update";
        $log_info="resub";
    }
    while (list($key, $val) = each($deluser_id)) {
        $key=intval($key);
        $unsub_email="";
        $r9=mysql_query("select ui_email from users_$title where id='$key'");
        if ($r9 && mysql_num_rows($r9)) {
            $unsub_email=mysql_result($r9,0,0);
        }
        $log_info .= " $unsub_email";
        if ($unsubuser) {
            $log_info="modify unsub ";
            $unique_col="";
            $r9=mysql_query("select unique_col from groups where id='$group_id'");
            if ($r9 && mysql_num_rows($r9)) {
                $unique_col=mysql_result($r9,0,0);
            }
            if ($unique_col=="email" && !empty($unsub_email)) {
                $succ=mx_ppos_unsub($unsub_email,$group_id,"php-members");
                logger("update ",$group_id,"","unsub_email=$unsub_email","users_");                
            }
            else {
            	$query="update users_$title set robinson='yes' where id='$key'";
                $r5=mysql_query($query);
                logger($query,$group_id,"","user_id=$key","users_");
            }
        }
        elseif ($deluser) {
        	$query="delete from users_$title where id='$key'";
            $r5=mysql_query($query);
            logger($query,$group_id,"","user_id=$key","users_");            
        }
        else {
        	$query="update users_$title set robinson='no' where id='$key'";	
            $r5=mysql_query($query);
            logger($query,$group_id,"","user_id=$key","users_");            
        }
    }
}
header("Location: members.php?group_id=$group_id&filt_demog=$filt_demog");
?>
