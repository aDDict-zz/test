<?
include "auth.php";
include "decode.php";
$weare=20;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/threadlist.lang";

$pagenum=get_http("pagenum","");
$first=get_http("first","");
$Wssortt=get_cookie('Wssortt');
$sortt = (isset($_GET['sortt']) || empty($Wssortt)) ? get_http('sortt',4) : $Wssortt;

$group_id=intval($group_id);
$mres = mysql_query("select title,num_of_mess,membership from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' or membership='client' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else
    exit; 
$title=$rowg["title"];

$message_id=htmlspecialchars($message_id);

$res=mysql_query("select subject from messages where id='$message_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    $subject=htmlspecialchars(mysql_result($res,0,0));
    $explain="\"$subject\" - $word[unsubbed_users]";
}
else
    exit;

$act_memb=$rowg["membership"];
$stattypes=array();
if ($act_memb=="client") {
    $rcl=mysql_query("select * from message_client where message_id='$message_id' and user_id='$active_userid'");
    if (!($rcl && mysql_num_rows($rcl))) {
        // this message is not assigned to this user.
        exit;
    }
    $r2=mysql_query("select st.id,st.parent_id from stattype st,stattype_user su where 
                     su.user_id='$active_userid'
                     and su.group_id='$group_id' and st.id=su.stattype_id");
    if ($r2 && mysql_num_rows($r2)) {
        while ($z=mysql_fetch_array($r2)) {
            $stattypes[]=$z["id"];
        }
    }
    if (!in_array(9,$stattypes) || !in_array(10,$stattypes) ) {
        // user has no right to see detailed stats for unsubs pop-up [10] or unsubs stats at all [9].
        exit;
    }
}


$order = "order by ui_email"; 
  
$res=mysql_query("select distinct u.ui_email from validation v,users_$title u where 
                  v.message_id='$message_id' and v.group_id='$group_id' and v.action='unsub'  
                  and v.validated='yes' and v.user_id=u.id $order");


header("Content-Type: application/x-unknown");
header("Content-Disposition: filename=userlist_{$title}_unsub.csv");

print "$explain\n$word[t_email]\n";

if ($res && mysql_num_rows($res)) { 
    while($row=mysql_fetch_array($res)) {
        print "$row[ui_email]\n";
    }
}

?>
