<?
include "auth.php";
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/filter.lang";  


$_MX_popup = 1;
include "menugen.php";


print "
<form name='eform'>
<table border=0 cellspacing=1 cellpadding=1 width='100%' style='border: 1px solid #369;'>
";

$group_id=intval(get_http("group_id",0));
$demog_id=intval(get_http("demog_id",0));
$mres = mysql_query("select title from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator')
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }

$demog_id=intval($demog_id);
$r2=mysql_query("select variable_name,variable_type,question,demog_id from vip_demog,demog where 
                 vip_demog.demog_id=demog.id and group_id='$group_id' and demog_id='$demog_id'");
$mm=mysql_fetch_array($r2);
$cols=array();
$rows=array();
$cols_js=array();
$rows_js=array();
$r3=mysql_query("select id,enum_option,vertical from demog_enumvals where demog_id='$mm[demog_id]'");
if ($r3 && mysql_num_rows($r3)) {
    while ($k3=mysql_fetch_array($r3)) {
        if ($k3["vertical"]=="no") {
            $rows[]=$k3["enum_option"];
            $rows_js[]="'".js_escape($k3["enum_option"])."'";
        }
        elseif ($mm["variable_type"]=="matrix") {
            $cols[]="$k3[enum_option]($k3[id])";
            $cols_js[]="'".$k3["id"]."'";
        }
    }
}
if (count($cols)==0 && $mm["variable_type"]=="enum") {
    $cols[]=$word["ft_select"];
}
$colspan=count($cols)+1;
$title=htmlspecialchars($mm["question"]);

print "
<tr><td class='bglightblue' valign='center' align='left' colspan='$colspan'><span class='szovegvastag'>&quot;$title&quot;&nbsp;$word[ft_demog_sel]</span></td></tr>
<tr><td class='bgwhite bbordercolor'><span class='szoveg'>$word[ft_options]</span></td>\n";
for ($i=0;$i<count($cols);$i++) {
    print "<td class='bgwhite bbordercolor'><span class='szoveg'>".htmlspecialchars($cols[$i])."</span></td>\n";
}
for ($j=0;$j<count($rows);$j++) {
    print "</tr><tr><td class='bgwhite bbordercolor'><span class='szoveg'>".htmlspecialchars($rows[$j])."</span></td>\n";
    for ($i=0;$i<count($cols);$i++) {
        if ($mm["variable_type"]=="matrix") {
            $value="$i-$j";
        }
        else {
            $value="$j";
        }
        print "<td class='bgwhite bbordercolor'><input type='checkbox' value='$value'></td>\n";
    }
}
$rows_js_arr=implode(",",$rows_js);
$cols_js_arr=implode(",",$cols_js);
print "</tr><tr><td class='bgwhite' colspan='$colspan' align='center'><span class='szoveg'><input type='button' name='orb' value='$word[ft_orb]' onclick='set_parent(\"or\")'>&nbsp;<input type='button' name='andb' value='$word[ft_andb]' onclick='set_parent(\"and\")'>&nbsp;<input type='button' name='ccb' value='$word[ft_close]' onclick='window.close();'></span></td></tr>
</table>
</form>
<script>
var rows=new Array($rows_js_arr);
var cols=new Array($cols_js_arr);
var fe=document.eform.elements;
var vtype='$mm[variable_type]';
function set_parent(oper) {
    var pstr=''; found=0;
    for (fi=0;fi<fe.length;fi++) { if (fe[fi].type=='checkbox' && fe[fi].checked) {
        mtind=''; mt=''; ind='';
        if (vtype=='enum') { ind=fe[fi].value; }
        else { cval=fe[fi].value; sw=0; for (ff=0;ff<cval.length;ff++) { swc=cval.charAt(ff); if (swc=='-') { sw=1; } else if (sw==0) { mt+=swc; } else { ind+=swc; } } mtind='/'+cols[mt]; }
        if (found) { pstr+=' '+oper; }
        pstr+=' $mm[variable_name]'+mtind+'=\\''+rows[ind].replace(/'/g,\"\\\'\")+'\\'';
        found++;
    } }
    if (found) { if (found>1 && oper=='or') { pstr=' ('+pstr+') '; } opener.parent.addBody(pstr); window.close(); } else { alert('$word[ft_nosel]'); }
}
</script>
</body>
</html>
";

function js_escape($string) {
    $string=str_replace("'","\\'",$string);
    $string=ereg_replace("\r?\n","\\n",$string);
    return $string;
}

?>
