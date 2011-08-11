<?
include "auth.php";
include "decode.php";
$_MX_superadmin=0;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/mygroups13.lang";      
include "_demog.php";
$_MX_popup = 1;
include "menugen.php";
?>
<style>
td { font-size: 11pt; color: #000000; font-family: Arial, Helvetica, sans-serif; background-color: #fff; }
input { font-size: 11pt; color: #000000; font-family: Arial, Helvetica, sans-serif; width: 400px; font-weight:normal; height:20px; padding:0;}
input.cbs { width: 15px; height:15px;}
input.btn { width: 100px;}
</style>
<form action='mygroups13_enumvals.php' method='post' name='vform' id='vform'>
<TABLE cellSpacing=1 cellPadding=0 width="100%" bgColor="$_MX_var->main_table_border_color" border=0>
<tbody id="gtbl">
<?
$_MX_demog = new MxDemog();
$id_ok=0;
$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                     and groups.id='$sgroup_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres)) {
    $id_ok=1;  
    $title=mysql_result($mres,0,1);
}
if (!$id_ok) {
    exit; 
}
$demog_id=intval(get_http("demog_id",0));
$mres = mysql_query("select * from demog where id='$demog_id'");
logger($q,$group_id,"","demog_id=$demog_id","demog");
if ($mres && mysql_num_rows($mres)) {
    $k=mysql_fetch_array($mres);
}
else {
    exit;
}
if ($k["variable_type"]!="matrix" && $k["variable_type"]!="enum") {
    exit;
}
//!!!!!!!!!!!!!!!!! TODO
$readonly=0;
// 2007-03-31 - $_MX_change_variables - az ilyen felhasznalo valtoztathat minden, a csoporthoz hozzaadhato demog infot
// a csoporthoz azok a demog infok adhatok hozza amelyek ehhez a csoporthoz vannak rendelve vagy nincsenek csoporthoz rendelve.
//if ($_MX_superadmin || $k["groups"]==$group_id || ($_MX_change_variables && $k["groups"]=="")) {
if ($_MX_superadmin || $k["groups"]==$group_id || $_MX_change_variables) {
    $readonly=0;
}

$allparm=$_MX_demog->GetParams();
$hallparm=$_MX_demog->GetParams(1);
$optvert=intval(get_http("optvert",1));
$from=intval($from);
if (!($k["variable_type"]=="matrix" && $optvert==1)) {
    $optvert=0;
    $hvert=$readonly?$word["ev_options"]:$word["ev_oedit"];
    $hhvert=$word["ev_option"];
    $qpart="vertical='no'";
}
else {
    $optvert=1;
    $hvert=$readonly?$word["ev_verticals"]:$word["ev_vedit"];
    $hhvert=$word["ev_vertical"];
    $qpart="vertical='yes'";
}

if (isset($_POST["enter"]) && !$readonly) {
    foreach ($_POST as $pvar=>$pval) {
        if ($pvar=="oldopt" && is_array($pval)) {
            foreach ($pval as $oid=>$oval) {
                if (isset($_POST["delopt"]["$oid"])) {
                	$q="update demog_enumvals set deleted='yes' where id='$oid' and demog_id='$demog_id'";
                    mysql_query($q);
                    logger($q,$group_id,"","demog_id=$demog_id","demog_enumvals");
                }
                elseif (strlen($oval)) {
                    if (strlen($_POST["code"]["$oid"])) $codeup=", code='".$_POST["code"]["$oid"]."'"; else $codeup="";
                    $oval=slasher($oval);
                    $q="update demog_enumvals set enum_option='$oval' $codeup where id='$oid' and demog_id='$demog_id'";
                    mysql_query($q);
                    logger($q,$group_id,"","demog_id=$demog_id","demog_enumvals");
                }
            }
        }
        elseif (ereg("^eopt([0-9]+)$",$pvar,$regs) && !empty($pval)) {
            $pval=slasher($pval);
            $ecode1="";
            if (isset($_POST["ecode$regs[1]"])) {
                $ecode1=$_POST["ecode$regs[1]"];
            }
            $ecode1=slasher($ecode1);
            $q="insert into demog_enumvals set enum_option='$pval',code='$ecode1',demog_id='$demog_id',$qpart,tstamp=now()";
            mysql_query($q);
            logger($q,$group_id,"","demog_id=$demog_id","demog_enumvals");
        }
    }
}

print "<tr><td class='bgkiemelt2' align=left colspan='3'><span class=szovegvastag>$k[variable_name] - $hvert</span>$hallparm<input type='hidden' name='demog_id' value='$demog_id'><input type='hidden' name='optvert' value='$optvert'><input type='hidden' name='enter' value='1'><input type='hidden' name='from' value='$from'></td></tr>
<tr><td class='bgkiemelt2'><span class=szovegvastag>$hhvert</span></td><td class='bgkiemelt2'>$word[ev_code]</td>";
if (!$readonly) {
    print "<td class='bgkiemelt2'><span class=szovegvastag>$word[ev_delete]</span></td>";
}
print "</tr>\n";

$res=mysql_query("select id,enum_option,code from demog_enumvals where demog_id='$demog_id' and $qpart and deleted='no' order by enum_option");
if ($res && mysql_num_rows($res)) {
    while ($z=mysql_fetch_array($res)) {
        $oldopt=htmlspecialchars($z["enum_option"]);
        if ($readonly) {
            print "<tr><td>$oldopt [$z[id]]</td><td>$z[code]</td></tr>\n";
        }
        else {
            print "<tr><td><input name='oldopt[$z[id]]' value=\"$oldopt\">[$z[id]]</td><td><input style='width: 60px;' name='code[$z[id]]' value=\"$z[code]\"></td><td><input type='checkbox' name='delopt[$z[id]]' value='1' class='cbs'></td></tr>\n";
        }
    }
}
$from?$refr="":$refr="opener.location=\"mygroups13_edit.php?demog_id=$demog_id&$allparm&rnd=". mt_rand(0,1000000). "\";";
if (!$readonly) {
    print "<tr><td><input name='eopt1' id='eopt1' onKeyPress=\"nflde(event);\"></td><td><input style='width: 60px;' id='ecode1' name='ecode1' value=\"\" onKeyPress=\"nflde(event);\"></td><td>&nbsp;</td></tr>";
}
print "</tbody>
</table>
<div width='100%' align='center'>";
if (!$readonly) {
    print "<input name='enterx' type='button' value='$word[ev_save]' class='btn' onclick='v.submit();'>";
}

print "<input name='entery' type='button' value='$word[ev_close]' class='btn' onclick='$refr window.close();'></div>
</form>
<script language=\"javascript\">
var nri; var v;
function nflde(e) {
    var tn=this.name;tnn=''; var ne=(e?e:event); for (i=0;i<tn.length;i++){cc=tn.charAt(i);if(parseInt(cc)>0){tnn+=''+cc;}}; 
    var kc=ne.keyCode; if (!kc) { kc=ne.which; } 
    if (kc==13) { tnn?next=parseInt(tnn)+1:next=2;si=document.getElementById('eopt'+next);if (si){si.focus();}else{var s2=document.getElementById('ecode'+(tnn?tnn:1)); var spss=0; if (s2) {spss=parseInt(s2.value)+1;} addrow(next,spss);}}
}
function addrow(nri,spss) {
    if (document.getElementById) {t=document.getElementById('gtbl'); if (t) {
        var nr=t.appendChild(document.createElement('TR')); var c=new Array();
        for (var i=0;i<3;i++) { c[i]=nr.appendChild(document.createElement('TD')); }
        var ip=c[0].appendChild(document.createElement('INPUT'));ip.onkeypress=nflde;ip.setAttribute('name','eopt'+nri); ip.setAttribute('id','eopt'+nri);ip.focus();
        var ip=c[1].appendChild(document.createElement('INPUT'));ip.onkeypress=nflde;ip.setAttribute('name','ecode'+nri); ip.setAttribute('id','ecode'+nri);ip.style.width='60px';ip.value=spss;
        c[2].innerHTML='&nbsp;';
    }}
}
function aeinit() {
    v=document.vform;v.eopt1.focus();
}
aeinit();
</script>

<br /><br />"
. (!$readonly ? getAnotherRelevantGroups($demog_id) : null) .
"

</body>
</html>\n";

?>
