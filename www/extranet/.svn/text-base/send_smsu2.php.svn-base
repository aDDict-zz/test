<?
  include "auth.php";
  $weare=26;
  include "cookie_auth.php";
  include "common.php";

  set_time_limit(0);

  $mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                       and groups.id='$group_id' and user_id='$active_userid' and groups.sms_send='yes'
                       and (membership='owner' or membership='moderator' $admin_addq)");
  if ($mres && mysql_num_rows($mres))
      $row=mysql_fetch_array($mres);  
  else {
      header("Location: index.php"); exit; }
  $title=$row["title"];

$narrow=get_http("narrow","");
$senderid=get_http("senderid","");
$message=get_http("message","");
$automat=get_http("automat","");
$test_numbers=get_http("test_numbers","");

  if (ereg("f([0-9]+)",$narrow,$regs))
      $filter_id=$regs[1];
  else
      $filter_id=0;
  if (ereg("g([0-9]+)",$narrow,$regs))
      $user_group_id=$regs[1];
  else
      $user_group_id=0;

  $um=urlencode($message);
  $ua=urlencode($automat);
// these parameters are now coming as utf8, cp1250 is expected.
$message = @iconv("utf8","cp1250//IGNORE",$message);
$automat = @iconv("utf8","cp1250//IGNORE",$automat);

$message=ereg_replace("&#337;","õ",$message);
$message=ereg_replace("&#336;","Õ",$message);
$message=ereg_replace("&#369;","û",$message);
$message=ereg_replace("&#368;","Û",$message);
$message=ereg_replace("õ","ö",$message);
$message=ereg_replace("Õ","Ö",$message);
$message=ereg_replace("û","ü",$message);
$message=ereg_replace("Û","Ü",$message);

  // the sms message
  if (get_magic_quotes_gpc()) {
    $smessage=$message;
    $sautomat=$automat;
    $ssenderid=$senderid;
    $message=stripslashes($message);
    $automat=stripslashes($automat);
  }
  else {
    $smessage=addslashes($message);
    $sautomat=addslashes($automat);
    $ssenderid=addslashes($senderid);
  }
  $si=urlencode($senderid);
  $ut=urlencode($test_numbers);
  
  $params="&filter_id=$filter_id&user_group_id=$user_group_id&ur=$ur&um=$um&ua=$ua&ut=$ut&si=$si";
  
  if (empty($message)) {
     header("Location: send_sms.php?group_id=$group_id&err=2$params"); exit; }

  $test=0;
  if (isset($_POST["testsms"]) || isset($testsms) || isset($_POST["testsms"])) {
      if (!ereg("\+[0-9]{8,13}(,\+[0-9]{8,13})*",$test_numbers)) {
          header("Location: send_sms.php?group_id=$group_id&err=4$params"); exit; 
      }
      else {
          $test=1;
      }
  }

  // see if message can be parsed
  $automat=trim($automat);
  if (!empty($automat)) {  // if automat is empty it means that we send no messages if user responds.
      $row_ids=array("1");
      $rows=explode("\n",$automat);
      for ($i=0;$i<count($rows);$i++) {
        $ur=urlencode($rows[$i]);
        $cols=explode("|",trim($rows[$i]));
        $cols[0]=abs(intval($cols[0]));
        if ($cols[1]!="*")
            $cols[1]=abs(intval($cols[1]));
        if (count($cols)!=4) {
            header("Location: send_sms.php?group_id=$group_id&err=11$params"); exit; }
        if (!($cols[0]) || in_array($cols[0],$row_ids)) {
            header("Location: send_sms.php?group_id=$group_id&err=12&$params"); exit; }
        if (!($cols[1]) || (!in_array($cols[1],$row_ids) && $cols[1]!="*")) {
            header("Location: send_sms.php?group_id=$group_id&err=13&$params"); exit; }
        $row_ids[]=$cols[0];
      }
  }
  
  $set = "create_time=now(), message='$smessage', automat='$sautomat' ";

  // if filter_id is 0, everybody gets the message. 
  // if it is not, filter engine will tell us who will get the message.
  // the phone number is in 'mobil' demog. information, e.g. in the ui_mobil column 
  // of the corresponding users_$title table.
  // it is recommended for the group owner to add this demog information to his group if he 
  // wants to use sms feature... :)
  // now, (2001-07-30) user_group_id may be given (at most one of filter_id, user_group_id).

$filter_name="";
$q = mysql_query("select name from filter where id='$filter_id'");
if ($q && mysql_num_rows($q)) {
	$filter_name = mysql_result($q,0,0);
}

$q = mysql_query("select email from user where id='$active_userid'");
if ($q && mysql_num_rows($q)) {
	$user_email = mysql_result($q,0,0);
}

$q = mysql_query("select title from groups where id='$group_id'");
if ($q && mysql_num_rows($q)) {
	$group_name = mysql_result($q,0,0);
}

if ($test) {
    $statmsg=98;
    $testparm="$test_numbers";
    $filter_name="test-send";
}
else {
    $statmsg=99;
    $testparm="-";
    if (empty($filter_name)) {
          header("Location: send_sms.php?group_id=$group_id&err=5$params"); exit; 
    }
}
if ($automat) {
$automat=ereg_replace("&#337;","õ",$automat);
$automat=ereg_replace("&#336;","Õ",$automat);
$automat=ereg_replace("&#369;","û",$automat);
$automat=ereg_replace("&#368;","Û",$automat);
$automat=ereg_replace("õ","ö",$automat);
$automat=ereg_replace("Õ","Ö",$automat);
$automat=ereg_replace("û","ü",$automat);
$automat=ereg_replace("Û","Ü",$automat);

}
$call_script = $_MX_var->sms_engine  . " $group_name 0 dsms-$filter_name $testparm $senderid";

/*print $call_script; 
print "<br><br>";
print nl2br("From: $user_email

$message
-automat-
$automat
");
exit;*/

$fp = popen($call_script,'w');

fwrite($fp, "From: $user_email

$message
-automat-
$automat
");

fclose($fp);
	    
header("Location: send_sms.php?group_id=$group_id&err=$statmsg$params"); 

?>
