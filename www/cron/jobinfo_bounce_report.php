<?php

$from_offline_cgi=1;

$mpath = "/var/www/maxima.hu";
//$mpath = "/var/www/html/maxima";

require "$mpath/decode.php";
require "$mpath/auth.php";
require "email_message.php";

$email_message=new email_message_class;
$email_message->default_charset = "utf-8";
//$email_message->SetMultipleEncodedEmailHeader("To",array("tbjanos@manufaktura.rs"=>"Jobinfo","biga@web10.hirekmedia.hu"=>"Biga"));
$email_message->SetMultipleEncodedEmailHeader("To",array("akosi18934@hirekmedia.hu"=>"Biga"));
$email_message->SetEncodedEmailHeader("From","reports@maxima.hu","Jobinfo Bounce Report");
$email_message->SetEncodedEmailHeader("Reply-To","tbjanos@manufaktura.rs","Jobinfo Bounce Report");
$email_message->SetHeader("Sender","reports@maxima.hu");

$yesterday = date("Y-m-d", time() - 24*60*60);
$outfile="/tmp/jobore$yesterday" . rand() . ".csv";
$sql="select ' message_type',' message_date',' date' as date,' user_id',' error_code'
      union 
      select message_type,message_date,date,user_id,error_code from bounced_jobinfo where date like '$yesterday%' order by date 
      into outfile '$outfile' fields terminated by ',' optionally enclosed by '\"' lines terminated by '\\n'";
mysql_query($sql);

$attachment=array(
    "FileName"=>$outfile,
    "Content-Type"=>"text/csv",
    "Disposition"=>"attachment"
);

$mail_bodytext = "Jobinfo bounce report for $yesterday genareted on " . date("Y-m-d H:i:s", time()) . "\n";
$email_message->AddPlainTextPart($email_message->WrapText($mail_bodytext));
$email_message->AddFilePart($attachment);
$subject = "Jobinfo bounce report"; 
$email_message->SetEncodedHeader("Subject",$subject);
$error=$email_message->Send();

print $error;

?>
