<?
include "auth.php";
$weare=32;
include "cookie_auth.php";  
  
$mres = mysql_query("select title,invite_text from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: index.php"); exit; }
$title=$rowg["title"];
  
include "menugen.php";
include "./lang/$language/invite.lang";

print "	<form action='mygroups17u.php' method='post'>
	<input type='hidden' name='lang' value='$lang'>
	<input type='hidden' name='group_id' value='$group_id'>	

<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;1. $word[invite_text]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[invite_text]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name=invite_text wrap='virtual' cols=50 rows=6>$rowg[invite_text]</textarea>
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
