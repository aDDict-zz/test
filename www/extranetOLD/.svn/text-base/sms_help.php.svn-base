<?
include "auth.php";
include "decode.php";
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/sms.lang";

$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                   and groups.id='$group_id' and user_id='$active_userid'
                   and (membership='owner' or membership='moderator')");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: index.php"); exit; }


$_MX_popup = 1;
include "menugen.php";

echo "
<TABLE cellSpacing=1 cellPadding=0 width=\"100%\" border=0>
<tr>
<td align=left colspan=4><span class=szoveg>$word[sms_help_text]</span></td>
</tr>
</table>
</body>
</html>\n";

?>
