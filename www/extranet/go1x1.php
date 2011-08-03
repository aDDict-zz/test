<?
include "auth.php";

$pars=split("/",getenv(PATH_INFO));
if (count($pars)>1) {
  $m=$pars[1];
  $u=$pars[2];
  $ra=$pars[3];
}

$message_id=addslashes($m);
$user_id=addslashes($u);

#verify if message_id exists.
$q="select group_id from messages where id='$message_id'";
//print $q;

header("Content-type: image/gif");

$r=mysql_query($q);
if ($r && $bnum=mysql_num_rows($r)) {
    $group_id=mysql_result($r,0,0);
    if (!get_magic_quotes_gpc()) 
        $HTTP_USER_AGENT=addslashes($HTTP_USER_AGENT);
    mysql_query("insert into track1x1 (group_id,message_id,user_id,date,remote_addr,http_user_agent)
                 values ('$group_id','$message_id','$user_id',now(),'$ra','$HTTP_USER_AGENT')");

    #an empty 1x1 gif
    print base64_decode("R0lGODlhAQABAIAAAMDAwAAAACwAAAAAAQABAAACAkQBADs=");
}
?>
