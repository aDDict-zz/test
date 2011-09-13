<?
// this script is obsolete, and will be deleted. Do not use.
header("Location: index.php");
exit;

  include "auth.php";
$weare=14;
$sgweare=14;
  include "cookie_auth.php"; 
  include "common.php";
  
if ($group_id) {
    $group_id=slasher($group_id);
    $mres = mysql_query("select groups.* from groups,members where groups.id=members.group_id
                         and groups.id='$group_id' and user_id='$active_userid' 
                         and (membership='owner' or membership='moderator' $admin_addq)");
    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else {
        header("Location: index.php"); exit; }
    $title=$rowg["title"];
    $multiid="";
    $maddon=",multi='no'";
}
else {
    $multiid=intval($multiid);        
    $mres = mysql_query("select m.id from multi m inner join multi_members mm where m.id='$multiid' and m.id=mm.group_id and mm.user_id='$active_userid' and mm.membership='moderator'");
    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else {
        header("Location: index.php"); exit; }
    $title=$rowg["title"];
    $group_id="";
    $maddon=",multi='yes'";
}

$errs="";
$alert=slasher($alert);
$alert_yes_days=slasher($alert_yes_days);
$alert_now_days=slasher($alert_now_days);
$alert_text=slasher($alert_text);
$alert_subject=slasher($alert_subject);

if (($alert=="yes" && !empty($alert_yes_days)) || $alert=="now") {
    $condarr=explode(",",${"alert_".$alert."_days"});
    $founderr=0;
    if (is_array($condarr)) {
        while (list(,$cond)=each($condarr)) {
            if (ereg("^([0-9]+)-([0-9]+)$",$cond,$regs)) {
                $r1=intval($regs[1]);
                $r2=intval($regs[2]);
                if ($r1==0 && $alert=="yes")
                    $errs=2;
                if ($r1>=$r2)
                    $founderr=1;
            }
            elseif (ereg("^[0-9]+$",$cond)) {
                $cond=intval($cond);
                if ($cond==0 && $alert=="yes")
                    $errs=2;
            }
            else
                $founderr=1;
        }
    }
    else
        $errs=1;
    if ($founderr && !$errs)
        $errs=1;
}
if (!$errs) {
    if ($alert=="yes") {
        $galert="alert='yes',";
        if (!empty($alert_yes_days)) {
            $res=mysql_query("select id from resend where group_name='$title' and engine='cron'");
            if ($res && mysql_num_rows($res)) {
                $job_id=mysql_result($res,0,0);
                mysql_query("update resend set days='$alert_yes_days' where id='$job_id'");
            }
            else
                mysql_query("insert into resend set days='$alert_yes_days',group_name='$title',engine='cron',date=now()$maddon");
        }
        else
            mysql_query("delete from resend where group_name='$title' and engine='cron'");
    }
    elseif ($alert=="now") {
        $galert="";
        mysql_query("insert into resend set days='$alert_now_days',group_name='$title',engine='daemon',date=now()$maddon");
        $errs=3;
    }
    else
        $galert="alert='no',";
    if ($group_id)
        mysql_query("update groups set $galert"."alert_text='$alert_text',alert_subject='$alert_subject',tstamp=now() where id='$group_id'");
    else
        mysql_query("update multi set $galert"."alert_text='$alert_text',alert_subject='$alert_subject',tstamp=now() where id='$multiid'");
}
if ($errs)
    $errs="&err=$alert$errs&alert_days=".${"alert_".$alert."_days"};
    
header("Location: resend.php?group_id=$group_id&multiid=$multiid$errs");

?>
