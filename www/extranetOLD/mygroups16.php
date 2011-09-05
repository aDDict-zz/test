<?

$group_id=intval($group_id);
header("location: mygroups10.php?automatic_type=web&group_id=$group_id");
exit;
// this page is replaced by the above mentioned.

include "auth.php";
$weare=22;
include "cookie_auth.php";  
  
$mres = mysql_query("select groups.* from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }
$title=$rowg["title"];

if (isset($_GET["test_header_footer"])) {
    print "$rowg[custom_head]\n\n\n\n$rowg[custom_foot]";
    exit;
}
if (isset($_GET["test_landingpage2"])) {
    print "$rowg[landingpage2]";
    exit;
}
if (isset($_GET["test_validator_page"])) {
    print "$rowg[validator_page]";
    exit;
}
if (isset($_GET["test_validator_page_unsub"])) {
    print "$rowg[validator_page_unsub]";
    exit;
}
if (isset($_GET["test_unsublink_ok"])) {
    print "$rowg[unsublink_ok]";
    exit;
}
if (isset($_GET["test_unsublink_notok"])) {
    print "$rowg[unsublink_notok]";
    exit;
}
  
include "menugen.php";
include "./lang/$language/settings.lang";

print "	<form action='mygroups16u.php' method='post'>
	<input type='hidden' name='group_id' value='$group_id'>	
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;1. $word[custom_landing2]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[custom_landing2_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name=landing2 rows=2 cols=55 wrap='virtual'>$rowg[landing2]</textarea>
    </td>
  </tr>
</table>
<br>		
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;2. $word[custom_header]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[custom_hf_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name=custom_header rows=15 cols=55 wrap='virtual'>$rowg[custom_head]</textarea><a href='mygroups16.php?group_id=$group_id&test_header_footer=1' target='_blank'>$word[test]</a>
    </td>
  </tr>
</table>
<br>		
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;3. $word[custom_footer]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[custom_hf_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name=custom_footer rows=15 cols=55 wrap='virtual'>$rowg[custom_foot]</textarea><a href='mygroups16.php?group_id=$group_id&test_header_footer=1' target='_blank'>$word[test]</a>
    </td>
  </tr>
</table>

<br>		
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;4. $word[custom_landingpage2]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[custom_landingpage2_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name=landingpage2 rows=15 cols=55 wrap='virtual'>$rowg[landingpage2]</textarea><a href='mygroups16.php?group_id=$group_id&test_landingpage2=1' target='_blank'>$word[test]</a>
    </td>
  </tr>
</table>

<br>		
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;5. $word[custom_validator_page]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[custom_validator_page_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name=validator_page rows=15 cols=55 wrap='virtual'>$rowg[validator_page]</textarea><a href='mygroups16.php?group_id=$group_id&test_validator_page=1' target='_blank'>$word[test]</a>
    </td>
  </tr>
</table>
<BR>
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;6. $word[custom_validator_page_desc_unsub]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[custom_validator_page_desc_unsub]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name=validator_page_unsub rows=15 cols=55 wrap='virtual'>$rowg[validator_page_unsub]</textarea><a href='mygroups16.php?group_id=$group_id&test_validator_page_unsub=1' target='_blank'>$word[test]</a>

    </td>
  </tr>
</table>
<BR>
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;7. $word[unsublink_ok]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[unsublink_ok]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name=unsublink_ok rows=15 cols=55 wrap='virtual'>$rowg[unsublink_ok]</textarea><a href='mygroups16.php?group_id=$group_id&test_unsublink_ok=1' target='_blank'>$word[test]</a>
    </td>
  </tr>
</table>
<BR>
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;8. $word[unsublink_notok]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[unsublink_notok]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name=unsublink_notok rows=15 cols=55 wrap='virtual'>$rowg[unsublink_notok]</textarea><a href='mygroups16.php?group_id=$group_id&test_unsublink_notok=1' target='_blank'>$word[test]</a>
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
