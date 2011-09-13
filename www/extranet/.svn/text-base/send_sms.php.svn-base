<?
include "auth.php";
$weare=26;
include "cookie_auth.php";  

$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                   and groups.id='$group_id' and user_id='$active_userid' and groups.sms_send='yes'
                   and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: sms_list.php?group_id=$group_id"); exit; }
$title=$row["title"];

$sms_send="no";
$r2=mysql_query("select sms_send from user where id='$active_userid'");
if ($r2 && mysql_num_rows($r2)) {
    $sms_send=mysql_result($r2,0,0);
}
if ($sms_send!="yes") {
    header("Location: sms_list.php?group_id=$group_id"); exit; 
}

include "menugen.php";
include "./lang/$language/sms.lang";  

$err=get_http("err","");
  
if ($err) {
    $ur=htmlspecialchars(urldecode(stripslashes(get_http('ur',''))));
    $automat=htmlspecialchars(urldecode(stripslashes(get_http('ua',''))));
    $message=htmlspecialchars(urldecode(stripslashes(get_http('um',''))));
    $test_numbers=htmlspecialchars(stripslashes(get_http('ut','')));
    $senderid=htmlspecialchars(stripslashes(get_http('si','')));
    $err=get_http('err','');
    if ($err == 2)
        $errmsg=$word["sms_nomessage"];
    if ($err == 3)
        $errmsg=$word["sms_date_err"];
    if ($err == 4)
        $errmsg=$word["sms_testnum_err"];
    if ($err == 5)
        $errmsg=$word["sms_no_filter"];
    if ($err == 11)
        $errmsg="$word[sms_wrongparms]: $ur";
    if ($err == 12)
        $errmsg="$word[sms_wrongid]: $ur";
    if ($err == 13)
        $errmsg="$word[sms_wrongresp]: $ur";
    if ($err == 98) 
        $errmsg="$word[sms_validate_test]";
    if ($err == 99) 
        $errmsg="$word[sms_validate]";
    echo "<table border=0 cellspacing=0 cellpadding=0 width='100%'><tr><td align='left' class=COLUMN1><span class='szovegvastag'>$errmsg</span></td></tr></table>";
}

$res=mysql_query("select email from user where id='$active_userid'");
$email=mysql_result($res,0,0);
$foundf=0;
$filtsel="<select name='narrow' class='oinput'><option value='0'>$word[no_filter]</option>";
$vres=mysql_query("select * from filter where group_id='$group_id' order by name");
if ($vres && mysql_num_rows($vres)) {
    while ($vk=mysql_fetch_array($vres)) {
        if ($filter_id==$vk["id"]) {
            $sel="selected";
        }
        else {
            $sel="";
        }
        $filtsel.="<option $sel value='f$vk[id]'> $vk[name]</option>";
    }
    $foundf=1;
}
$vres=mysql_query("select * from user_group where group_id='$group_id' order by name");
if ($vres && mysql_num_rows($vres)) {
    if ($foundf) {
        $filtsel.="<option value='0'>-----------------</option>";
    }
    while ($vk=mysql_fetch_array($vres)) {
        if ($user_group_id==$vk["id"]) {
            $sel="selected";
        }
        else {
            $sel="";
        }
        $filtsel.="<option $sel value='g$vk[id]'>$word[member_group]: $vk[name]</option>";
    }
}
$filtsel.="</select>";

echo "<BR>
    <CENTER>
    <table border=0 cellspacing=1 cellpadding=0 bgcolor=$_MX_var->main_table_border_color width=100%>
    <tr>
    <td class='formmezo'>$word[sms_new]</td>
    </tr>
    <tr><td>
    <TABLE cellSpacing=0 cellPadding=0 border=0 bgcolor='white' width=100%>
    <TBODY>
    <form name=tform method='post' action='send_smsu2.php'>
    <input type='hidden' name='group_id' value='$group_id'>
    <tr>
    <td class=bgvilagos1><span class='szovegvastag'>$word[sms_message]*&nbsp;&nbsp;</span></td>
    <td class=bgvilagos1>1|<textarea class=oinput name='message' rows=4 cols=60 wrap=virtual>$message</textarea><br>
    <span class='szoveg'>*$word[messparts_explain]: </span>". htmlspecialchars($_MX_var->sms_delimiter) ."</td>
    </tr>
    <tr>
    <td class=bgvilagos1><span class='szovegvastag'>$word[sms_automat]</span>&nbsp;&nbsp;<br>
      <a href='#' onClick='window.open(\"$_MX_var->baseUrl/sms_help.php?group_id=$group_id\", \"smsh\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=500,height=500\"); return false;'>$word[sms_help]</a></td>
    <td class=bgvilagos1><textarea class=oinput name='automat' rows=10 cols=80 wrap=virtual>$automat</textarea></td>
    </tr>
    <tr>
    <td class=bgvilagos1><span class='szovegvastag'>&nbsp;$word[filterlist]&nbsp;</span></td>
    <td class=bgvilagos1 style='padding:6px 0;'>$filtsel&nbsp;&nbsp;</td>
    </tr>
    <tr>
    <td class=bgvilagos1><span class='szovegvastag'>&nbsp;Sender ID&nbsp;</span></td>
    <td class=bgvilagos1 style='padding-bottom:6px;'><input size='60' name='senderid' value=\"$senderid\"></td>
    </tr>
    <tr>
    <td class=bgvilagos1><span class='szovegvastag'>&nbsp;$word[test_numbers]&nbsp;</span></td>
    <td class=bgvilagos1><input size='60' name='test_numbers' value=\"$test_numbers\"><input type='submit' class='tovabbgomb' name='testsms' value='$word[test]'></td>
    </tr>
    <tr>
    <td colspan=2 align=center class=bgvilagos1>
    <input type='submit' class='tovabbgomb' name='newsms' value='$word[submit]'>
    </td>
    </tr>
    </FORM></TBODY></TABLE>
    </td></tr>
    </table>
    </CENTER>
    <BR>\n";

include "footer.php";

?>
