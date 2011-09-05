<?
include "auth.php";
include "decode.php";
$weare=34;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/form.lang";

foreach ($_POST as $var=>$val) {
    $$var = $val;
}
foreach ($_GET as $var=>$val) {
    $$var = slasher($val);
}

$mres = mysql_query("select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    exit; 
}

$title=$rowg["title"];
$active_membership=$rowg["membership"];

$form_id=slasher($form_id);
$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;

$error=array();
    
printhead();

if (isset($_POST["verify_group"])) {
    $verify_group=mysql_escape_string($_POST["verify_group"]);
    $hasdata=1;
}
else {
    $verify_group="";
}

$errorlist="";

if ($hasdata) {
    $r=mysql_query("select id from groups where title='$verify_group'");
    if ($r && mysql_num_rows($r)) {
        $verify_group_id=mysql_result($r,0,0);
        $notin=array();
        $mq="select d.variable_name,d.question,d.id from form_element fe,demog d where fe.form_id='$form_id' and d.id=fe.demog_id";
		logger($mg,$group_id,"","form_id=$form_id","form_element");
        $rq=mysql_query($mq);
        while ($k=mysql_fetch_array($rq)) {
            $r2=mysql_query("select id from vip_demog where group_id='$verify_group_id' and demog_id='$k[id]'");
            if (!($r2 && mysql_num_rows($r2))) {
                $notin[]=htmlspecialchars("$k[variable_name] ($k[question])");
            }
        }
        $mq="select id from form_element where form_id='$form_id' and widget='cim' limit 1";
        $rq=mysql_query($mq);
        if ($rq && mysql_num_rows($rq)) {
            $cimvars=0;
            $cr=mysql_query("select count(vd.id) from vip_demog vd, demog d where vd.group_id='$verify_group_id'
                             and d.variable_name in ('hazszam','emelet','ajto','utca_nev','utca_tipus') and vd.demog_id=d.id");
            if ($cr && mysql_num_rows($cr)) {
                $cimvars=mysql_result($cr,0,0);
            }
            if ($cimvars<5) {
                $notin[]=htmlspecialchars("cim: 'hazszam','emelet','ajto','utca_nev','utca_tipus'");
            }
        }
        $mq="select id from form_element where form_id='$form_id' and widget='ceg_cim' limit 1";
        $rq=mysql_query($mq);
        if ($rq && mysql_num_rows($rq)) {
            $cimvars=0;
            $cr=mysql_query("select count(vd.id) from vip_demog vd, demog d where vd.group_id='$verify_group_id'
                             and d.variable_name in ('street_name_company','street_type_company','street_number_company','floor_company','door_company') and vd.demog_id=d.id");
            if ($cr && mysql_num_rows($cr)) {
                $cimvars=mysql_result($cr,0,0);
            }
            if ($cimvars<5) {
                $notin[]=htmlspecialchars("cegcim: 'street_name_company','street_type_company','street_number_company','floor_company','door_company'");
            }
        }
        $errorlist=$word["fe_nonexistent_vars"]."<br>". implode("<br>",$notin);
    }
    else {
        $errorlist=$word["fe_nonexistent_group"];        
    }
}

echo "
<tr>
<td colspan='3' bgcolor='white'><span class='szovegvastag'>$errorlist&nbsp;</span></td>
</tr>
<tr>
<td colspan='3' bgcolor='white'><span class='szoveg'>$word[group]:&nbsp;<input name='verify_group' value='".htmlspecialchars($verify_group)."'></span></td>
</tr>
<tr>
<td align=center colspan=3>
<input type=submit class='tovabbgomb' value='$word[gobutton]'> <input type='button' class='tovabbgomb' value='$word[close]' onclick='window.close();'>
</td>
</tr></form>";

printfoot();
include "footer.php";


function printhead() {

    global $_MX_var,$stat_text,$userlist_yn,$filt_demog_options,$group_id,$pagenum,$word, $formdata, $form_id, $group_id, $language;


$_MX_popup = 1;
include "menugen.php";

print "        
<form method='post' action='form_element_verify.php'>
<input type='hidden' name='action' value='addtoform'>
<input type='hidden' name='form_id' value='$form_id'>
<input type='hidden' name='group_id' value='$group_id'>
<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" class='bgcolor' border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" class='bgcolor' border=0>
        <tr>
		<td class=bgkiemelt2 align=left colspan=3><span class=szovegvastag>&quot;".htmlspecialchars($formdata["title"])."&quot $word[iform_title] &gt; $word[fe_verify]</span></td>
		</tr>\n";
}

function printfoot() {

    echo "</table>
        </td>
        </tr>
        </table>
</body>
    </html>\n";
}

