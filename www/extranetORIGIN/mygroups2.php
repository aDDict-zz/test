<?
include "auth.php";
$weare=6;
include "cookie_auth.php"; 

$mres = mysql_query("select groups.* from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }
$title=$rowg["title"];

include "menugen.php";
include "./lang/$language/settings.lang";

if ($rowg["sms_send"]=="owner")
   $sms_send_owner="checked";
else
   $sms_send_moderators="checked";
if ($rowg["mail_demog_info"]=="yes")
   $mail_demog_info_yes="checked";
else
   $mail_demog_info_no="checked";
if ($rowg["use_tracko"]=="yes")
   $use_tracko_yes="checked";
else
   $use_tracko_no="checked";

print "	<form action='mygroups2u.php' method='post'>
	<input type='hidden' name='group_id' value='$group_id'>	

<br>
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;1. $word[mail_demog_info]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[mail_demog_info_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=left class=bgvilagos2>
<input type=radio name='mail_demog_info' value='yes' $mail_demog_info_yes><span class=szovegvastag>&nbsp;$word[mail_demog_info_yes]&nbsp;</span>
<br>
<input type=radio name='mail_demog_info' value='no' $mail_demog_info_no><span class=szovegvastag>&nbsp;$word[mail_demog_info_no]&nbsp;</span>
    </td>
  </tr>
</table>

<br>
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;2. $word[use_tracko]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[use_tracko_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=left class=bgvilagos2>
<input type=radio name='use_tracko' value='yes' $use_tracko_yes><span class=szovegvastag>&nbsp;$word[yes]&nbsp;</span>
<br>
<input type=radio name='use_tracko' value='no' $use_tracko_no><span class=szovegvastag>&nbsp;$word[no]&nbsp;</span>
    </td>
  </tr>
</table>

<br>		
<TABLE cellSpacing=8 cellPadding=0 width='100%' border=0>
  <TBODY>
  <TR>
    <TD align=center><INPUT class='tovabbgomb' type=submit name='saveall' value=$word[submit3]> 
    </TD></TR></TBODY></TABLE>
</form>
";
   
include "footer.php";

?>
