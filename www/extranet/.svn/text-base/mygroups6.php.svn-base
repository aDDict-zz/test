<?
include "auth.php";
$weare=9;
include "cookie_auth.php";  
include "common.php";
$language=select_lang();
include "./lang/$language/invite.lang";
  
$mres = mysql_query("select title,invite_text from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }
$title=$rowg["title"];
$mail_invited=$rowg["invite_text"];
if (empty($mail_invited))
    $mail_invited=$word["mail_invited"];

srand ((double) microtime() * 1000000);

include "menugen.php";

if ($enter == 'yes') {
    $invite_error="";
    $user_id=0;
    $emails=slasher($emails,0);
    $liste = split("[\n ,;]",$emails);
    for ($i=0; $i<count($liste); $i++) {
        $email=$liste[$i];
        if (eregi("@([a-zA-Z0-9_]+)\.maxima\.hu$",$email)) 
            $invite_error.="$word[self_email_addr] $email .&nbsp;";
        else {
            if (eregi("^[\+\._a-z0-9-]+@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+[a-z]{2}[mtgvu]?$",$email)) {
                $query = mysql_query("select validated,robinson,id,bounced from users_$title where ui_email='$email'");
                if ($query && mysql_num_rows($query)) {
                    $validated=mysql_result($query,0,0);
                    $robinson=mysql_result($query,0,1);
                    $user_id=mysql_result($query,0,2);
                    $bounced=mysql_result($query,0,3);
                    if ($validated=="yes" && $robinson=="no" && $bounced=="no")
                        $invite_error.="$email $word[is_already_member].&nbsp;";/* i think this is stupid.
                    elseif ($validated=="no")
                        $invite_error.="$email $word[is_already_invited].&nbsp;";*/      
                }
                if (empty($invite_error)) {
                    $query = mysql_query("select * from groups where id='$group_id'");
                    $r=mysql_fetch_array($query);
                    $message="\n$mail_invited\n\n$r[intro]\n\n$word[mail_ensure]\n\nwww.maxima.hu\n";
                    $unique_id = substr(md5(time().$REMOTE_ADDR.$email."x"),0,10);
                    if (!$user_id) {
                        mysql_query("insert into users_$title (ui_email,validated,tstamp,date) 
                                     values ('$email','no',now(),now())");
                        $user_id=mysql_insert_id();
                    }
                    $from="validate-sub-$unique_id@$r[title].maxima.hu";
                    mysql_query("insert into validation 
                                 (user_id,group_id,action,unique_id,date,tstamp) values 
                                 ('$user_id','$group_id','sub','$unique_id',now(),now())");
                    mail($email,"$word[mail_subscr_pref] $title $word[mail_subscr_suff]",$message,"From: $from"); 
                    //echo nl2br(htmlspecialchars("$email,\"$word[mail_subscr_pref] $title $word[mail_subscr_suff]\",$message,\"From: $from")); 
                    $invite_sent=$word["invite_success"];
                }
            } 
            else 
                $invite_error.="$word[wrong_email_addr]: $email.&nbsp;";
        }
    }
}

print  "<TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
        <TBODY>
        <TR>
        <TD valign=top colSpan=3 align=left>
        <span class='szovegvastag'>$invite_error<br>$invite_sent</span>
        </TD></TR></TBODY></TABLE>
        <table border='0' cellpadding='0' cellspacing='1' width='100%'>
        <form method='post'>
        <input type='hidden' name='enter' value='yes'>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='lang' value='$lang'>
        <tr>
        <td colspan=3 valign='top' class=formmezo width='100%'>
        <table border='0' cellpadding='2' cellspacing='0'>
        <tr>
        <td colspan=3 class=formmezo>$word[prefix_invite] $title $word[sufix_invite]</td>
        </tr>
        </table>
        </td>
        </tr>
        <tr>
        <td class=bgvilagos2 width=30%>&nbsp;<span class=szoveg>$word[invite_description]</span></td>
        <td class=bgvilagos2 valign='top'><textarea rows='12' cols='54' name='emails'></textarea></td>
        </tr>
        <tr>
        <td align='center' class=bgvilagos2 colspan='2'><input class='tovabbgomb' type='submit' name='saveall' value='$word[submit]'></td>
        </tr>
        </table>
        </form>\n";
  
include "footer.php";

?>
