<?php

$from_offline_cgi=1;

$mpath = "../www";
$maint_filename = "../var/log/bounce_maint/" . date("Y-m-d-H-i-s") . ".notok";
mx_log("");

require "$mpath/auth.php";  
require "$mpath/decode.php";
//require "$mpath/auth.php";
require "email_message.php";

mx_log("Sending Jobinfo bounce report");

$email_message=new email_message_class;
$email_message->default_charset = "utf-8";
$email_message->SetMultipleEncodedEmailHeader("To",array("tbjanos@manufaktura.rs"=>"Jobinfo","akosi18934@hirekmedia.hu"=>"Biga"));
//$email_message->SetMultipleEncodedEmailHeader("To",array("akosi18934@hirekmedia.hu"=>"Biga"));
$email_message->SetEncodedEmailHeader("From","reports@maxima.hu","Jobinfo Bounce Report");
$email_message->SetEncodedEmailHeader("Reply-To","tbjanos@manufaktura.rs","Jobinfo Bounce Report");
$email_message->SetHeader("Sender","reports@maxima.hu");

$time = time() - 24*60*60;
$tablename = "message_" . date("o", $time) . "_" . preg_replace("/^0/","",date("W", $time));
$yesterday = date("Y-m-d", $time);
$outfile="jobore$yesterday" . rand() . ".csv";
$sql="select ' message_type',' message_date',' date' as date,' user_id',' error_code'
      union 
      select substring(message_id,7,2),concat('20',substring(message_id,1,2),'-',substring(message_id,3,2),'-',substring(message_id,5,2)),end_time,user_id,status 
      from $tablename where end_time like '$yesterday%' and message_type='j' and status not in ('new','sent') order by date
      into outfile '/var/maximas/eximlogs/jobinfo_report/$outfile' fields terminated by ',' optionally enclosed by '\"' lines terminated by '\\n'";
//print "$sql\n";

$_MX_var->sql($sql,"bounce");
mx_log(mysql_error());

$attachment=array(
    "FileName"=>"/usr/local/maximas2/eximlogs/jobinfo_report/$outfile",
    "Content-Type"=>"text/csv",
    "Disposition"=>"attachment"
);

$mail_bodytext = "Jobinfo bounce report for $yesterday genareted on " . date("Y-m-d H:i:s", time()) . "\n";
$email_message->AddPlainTextPart($email_message->WrapText($mail_bodytext));
$email_message->AddFilePart($attachment);
$subject = "Jobinfo bounce report"; 
$email_message->SetEncodedHeader("Subject",$subject);
$error=$email_message->Send();

mx_log($error);

mx_log("Removing single opt-in users whose welcome messages bounced, single groups");

$groupref = array();
$sql="select message_id as group_id,user_id from $tablename where end_time like '$yesterday%' and message_type='h' and status in ('bounce_baduser','bounce_unroutable')";
$res = $_MX_var->sql($sql,"bounce");
while ($k = mysql_fetch_array($res)) {
    if (!isset($groupref["$k[group_id]"])) {
        $r2 = $_MX_var->sql("select title from groups where id=$k[group_id]");
        if ($r2 && mysql_num_rows($r2)) {
            $groupref["$k[group_id]"] = mysql_result($r2,0,0);
        }
    }
    if (!isset($groupref["$k[group_id]"])) {
        continue;
    }
    $title = $groupref["$k[group_id]"];
    mx_log("$title: $k[user_id]");
    $q="update users_$title set bounced='yes' where id=$k[user_id] and to_days(now())-to_days(validated_date)<6";
    $_MX_var->sql($q);
}

mx_log("Removing single opt-in users whose welcome messages bounced, multigroup subscribes");

$sql="select distinct message_id as group_id,user_id as multioptin_id from $tablename where end_time like '$yesterday%' and message_type='i' and status in ('bounce_baduser','bounce_unroutable')";
$res = $_MX_var->sql($sql,"bounce");
while ($k = mysql_fetch_array($res)) {
    $r2 = $_MX_var->sql("select email,groups from multioptin where id=$k[multioptin_id]");
    if ($r2 && mysql_num_rows($r2)) {
        $email = mysql_escape_string(mysql_result($r2,0,0));
        $grouplist = mysql_result($r2,0,1);
        mx_log(str_replace("\n",",",$grouplist) . ": $email");
        $groups = explode("\n",$grouplist);
        foreach ($groups as $title) {
            if (preg_match("/^[a-z0-9_-]+$/",$title)) {
                $q="update users_$title set bounced='yes' where ui_email='$email' and to_days(now())-to_days(validated_date)<6";
                $_MX_var->sql($q);
            }
        }
    }
}

mx_log("","end");

function mx_log($log="",$type="log") {

    global $maint_filename;

    if (!empty($log)) {
        if ($fp = fopen($maint_filename,"a")) {
            fwrite($fp,"$log\n");
        }
    }
    if ($type == "end") {
        rename($maint_filename,preg_replace("/notok$/","ok",$maint_filename));
    }
}
?>
