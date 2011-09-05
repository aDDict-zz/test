<?

$multiid=intval($multiid);
header("location: mygroups10.php?&multiid=$multiid");
exit;
// this page is replaced by the above mentioned.

include "auth.php";
include "cookie_auth.php";  

$mres = mysql_query("select * from multi where owner_id='$active_userid' and id='$multiid'");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: index.php"); exit; }

if (isset($_GET["test_header_footer"])) {
    print "$row[custom_head]\n\n\n\n$row[custom_foot]";
    exit;
}
if (isset($_GET["test_landingpage2"])) {
    print "$row[landingpage2]";
    exit;
}
if (isset($_GET["test_validator_page"])) {
    print "$row[validator_page]";
    exit;
}

$sgweare=2;
include "menugen.php";
include "./lang/$language/settings.lang";  

if ($row["demog_info"]=="yes")
   $yes="checked";
else
   $no="checked";
if ($row["welcome_yesno"]=="yes")
   $wyes="checked";
else
   $wno="checked";
   
print "	<form action='supergroup2u.php' method='post'>
	<input type='hidden' name='multiid' value='$multiid'>	
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
	<textarea class='oinput' name=landing2 rows=2 cols=55 wrap='virtual'>$row[landing2]</textarea>
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
	<textarea class='oinput' name=custom_header rows=15 cols=55 wrap='virtual'>$row[custom_head]</textarea><a href='supergroup2.php?multiid=$multiid&test_header_footer=1' target='_blank'>$word[test]</a>
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
	<textarea class='oinput' name=custom_footer rows=15 cols=55 wrap='virtual'>$row[custom_foot]</textarea><a href='supergroup2.php?multiid=$multiid&test_header_footer=1' target='_blank'>$word[test]</a>
    </td>
  </tr>
</table>
<br>		
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;4. $word[subscribe_subject]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[subscribe_subject_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<input class='oinput' name=subscribe_subject size='40' value=\"".htmlspecialchars($row["subscribe_subject"])."\">    
    </td>
  </tr>
</table>
<br>		
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;5. $word[subscribe_body]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[subscribe_body_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name=subscribe_body rows=15 cols=55 wrap='virtual'>$row[subscribe_body]</textarea>
    </td>
  </tr>
</table>
<br>		
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;6. $word[welcome_subject]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[welcome_subject_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<input class='oinput' name=welcome_subject size='40' value=\"".htmlspecialchars($row["welcome_subject"])."\">
    </td>
  </tr>
</table>
<br>
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;7. $word[custom_welcome_yesno]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[custom_welcome_yesno_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=left class=bgvilagos2>
<input type=radio name='welcome_yesno' value='yes' $wyes><span class=szovegvastag>&nbsp;$word[yes]&nbsp;</span>
<br>
<input type=radio name='welcome_yesno' value='no' $wno><span class=szovegvastag>&nbsp;$word[no]&nbsp;</span>
    </td>
  </tr>
</table>
<br>		
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;8. $word[welcome_message]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[welcome_message_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name=welcome_message rows=15 cols=55 wrap='virtual'>$row[welcome_message]</textarea>
    </td>
  </tr>
</table>
<br>		
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;9. $word[custom_landingpage2]</td>
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
	<textarea class='oinput' name=landingpage2 rows=15 cols=55 wrap='virtual'>$row[landingpage2]</textarea><a href='supergroup2.php?multiid=$multiid&test_landingpage2=1' target='_blank'>$word[test]</a>
    </td>
  </tr>
</table>
<br>		
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;10. $word[custom_validator_page]</td>
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
	<textarea class='oinput' name=validator_page rows=15 cols=55 wrap='virtual'>$row[validator_page]</textarea><a href='supergroup2.php?multiid=$multiid&test_validator_page=1' target='_blank'>$word[test]</a>
    </td>
  </tr>
</table>
<br>		
<table width=100% border=0 cellspacing=1 cellpadding=0>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;11. $word[multi_already]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td class=bgvilagos2>
      <table border=0 cellspacing=0 cellpadding=8>
        <tr>
          <td><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='1' height='1' hspace='90'><br>
            <span class=szoveg>$word[multi_already_desc]</span></td>
        </tr>
      </table>
    </td>
    <td width=100% align=center class=bgvilagos2>
	<textarea class='oinput' name='already_subs' rows=2 cols=55 wrap='virtual'>$row[already_subs]</textarea>
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
