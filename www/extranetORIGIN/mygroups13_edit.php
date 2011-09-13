<?
include "auth.php";
$weare=17;
if (get_http('group_id','')) $subweare=171;
else $subweare=172;
include "cookie_auth.php";  
include "common.php";
$language=select_lang();
include "./lang/$language/mygroups13.lang";    
include "_demog.php";
  
$_MX_demog = new MxDemog();

$id_ok=0;
$mres = mysql_query("select title from groups,members where groups.id=members.group_id and groups.id='$sgroup_id' 
                     and user_id='$active_userid' and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres)) {
    $id_ok=1;  
}
if (!$id_ok) {
    header("Location: $_MX_var->baseUrl/index.php"); 
    exit; 
}
$demog_id=intval(get_http("demog_id",0));
$copy_demog_id=intval(get_http("copy_demog_id",0));
$enter=intval(get_http("enter",0));
$res=mysql_query("select * from demog where id='$demog_id'");
if ($res && mysql_num_rows($res)) {
   $k=mysql_fetch_array($res);
   $what="$word[demog_edit]";
   $new=0;
}
else {
   $what="$word[demog_new]";
   $new=1;
   $demog_id=0;
}
// 2007-03-31 - $_MX_change_variables - az ilyen felhasznalo valtoztathat minden, a csoporthoz hozzaadhato demog infot
// a csoporthoz azok a demog infok adhatok hozza amelyek ehhez a csoporthoz vannak rendelve vagy nincsenek csoporthoz rendelve.
//if (!($new || $_MX_superadmin || $group_id==$k["groups"] || ($_MX_change_variables && $k["groups"]==""))) {
// 2007-04-23 az $_MX_change_variables megis mindent valtoztathat
if (!($new || $_MX_superadmin || $group_id==$k["groups"] || $_MX_change_variables)) {
    exit;
}
$enum_values=array();
$enum_values_vertical=array();
$error="";
if ($enter) {
    list($demog_id,$error)=$_MX_demog->add($group_id,$new);
    if (empty($error)) {
        $new=0;
    }
}
if (!$demog_id && $copy_demog_id) {
    $view_demog_id=$copy_demog_id;
    if (!$enter) {
        $r2=mysql_query("select * from demog where id='$copy_demog_id'");
        if ($r2 && mysql_num_rows($r2)) {
            $kc=mysql_fetch_array($r2);
            $ic=1;
            while ($ic<100) {
                $r2=mysql_query("select * from demog where variable_name='$kc[variable_name]'");
                if ($r2 && mysql_num_rows($r2)) {
                    $kc["variable_name"]="$kc[variable_name]$ic";
                    $ic++;
                }
                else {
                    $ic=100;
                }
            }
        }
    }
}
else {
    $view_demog_id=$demog_id;
}

$dvars=array("variable_name","variable_type","multiselect","multi_append","question","code");
foreach ($dvars as $dvr) {
    if (isset($_POST["$dvr"])) {
        $$dvr=htmlspecialchars($_POST["$dvr"]);
    }
    elseif (isset($k["$dvr"])) {
        $$dvr=htmlspecialchars($k["$dvr"]);
    }
    elseif (isset($kc["$dvr"])) {
        $$dvr=htmlspecialchars($kc["$dvr"]);
    }
    else {
        $$dvr="";
    }
}
if ($view_demog_id && ($variable_type=="enum" || $variable_type=='enum_other' || $variable_type=="matrix")) {
    $res=mysql_query("select * from demog_enumvals where vertical='no' and demog_id='$view_demog_id' and deleted='no'");
    if ($res && mysql_num_rows($res)) { 
        while ($kk=mysql_fetch_array($res)) {
            $enum_values[]=$kk["enum_option"];
        }
    } 
    if ($variable_type=="matrix") {
        $res=mysql_query("select * from demog_enumvals where vertical='yes' and demog_id='$view_demog_id' and deleted='no'");
        if ($res && mysql_num_rows($res)) { 
            while ($kk=mysql_fetch_array($res)) {
                $enum_values_vertical[]=$kk["enum_option"];
            }
        } 
    }
}
$henum_values=htmlspecialchars(implode(" | ",$enum_values))."&nbsp;";
$henum_values_vertical=htmlspecialchars(implode(" | ",$enum_values_vertical))."&nbsp;";
if ($new) {
    $sel=array();
    //if (!empty($variable_type)) {
    $variable_name_input="<input type='text' name='variable_name' value=\"$variable_name\" size='40'>";
    $variable_type_input="<select name='variable_type'>\n";
    foreach ($_MX_demog->dtypes as $dt) {
        $variable_type==$dt?$sel="selected":$sel="";
        $variable_type_input.="<option $sel value='$dt'>". $word["vdt_$dt"] ."</option>";
    }
    $variable_type_input.="</select>";
}
else {
    $variable_name_input=$variable_name;
    $variable_type_input=$word["vdt_$variable_type"];
}

$ms_yes="";
$ms_no="";
$multiselect=="yes"?$ms_yes="checked":$ms_no="checked";
$multi_append=="yes"?$ma_yes="checked":$ma_no="checked";
if (isset($_POST["grlist"])) {
    $grlist=$_POST["grlist"];
}
else {
    $grlist="";
    $groups=explode(",",$k["groups"]);
    if (is_array($groups)) 
        while (list(,$ggid)=each($groups)) {
            $resgr=mysql_query("select title from groups where id='$ggid'");
            if ($resgr && mysql_num_rows($resgr)) {
            if (!empty($grlist)) {
                $grlist.=",";
            }
            $grlist.=mysql_result($resgr,0,0);
        }
    }
}

include "menugen.php";
$allparm=$_MX_demog->GetParams();
$hallparm=$_MX_demog->GetParams(1);
print "<script>
       function enumwindow(demog_id,isvert) {
       window.open('mygroups13_enumvals.php?$allparm&demog_id='+demog_id+'&optvert='+isvert, 'm_d_i', 'width=600,height=500,left=150,top=200,scrollbars=yes,resizable=yes;'); return false; }
       </script>
       <form method='post' action='mygroups13_edit.php'>
       <div class='bordercolor'>
       <div align='right' style='padding:2px 0;'><span class='szovegvastag'><a href='mygroups13.php?$allparm'>$word[ed_back]</a></span></div>
       <input type='hidden' name='enter' value='1'><input type='hidden' name='demog_id' value='$demog_id'>$hallparm
       <table cellspacing=1 cellpadding=1 width='100%' border=0>
       <tr><td colspan='2' class='bgkiemelt2'><span class='szovegvastag'>$what<br>$error</span></td></tr>
       <tr><td><span class='szoveg'>$word[t_question]</span></td>
           <td><span class='szoveg'><input type='text' size='100' value=\"$question\" name='question'></span></td></tr>
       <tr><td><span class='szoveg'>$word[t_code]</span></td>
           <td><span class='szoveg'><input type='text' size='100' value=\"$code\" name='code'></span></td></tr>
       <tr><td><span class='szoveg'>$word[t_varname]</span></td><td><span class='szoveg'>$variable_name_input</span></td></tr>
       <tr><td><span class='szoveg'>$word[t_vartype]</span></td><td><span class='szoveg'>$variable_type_input</span></td></tr>
       <tr><td colspan='2'><span class='szoveg'>$word[var_explain]<br></span></td></tr>\n";

if ($new==0 && ($variable_type=="enum" || ($variable_type=="matrix"))) {
    (count($enum_values)<2)?$bst=" style='background-color:#fcc'":$bst="";
    echo "<tr>
    <td$bst><span class='szoveg'>$word[b_possval]&nbsp;<input type='button' onclick=\"javascript:enumwindow($demog_id,0)\" value='...'></span></td>
    <td$bst><span class='szoveg'>$henum_values</span></td>
    </tr>\n";
    if ($variable_type=="matrix") {
        (count($enum_values_vertical)<2)?$bst=" style='background-color:#fcc'":$bst="";
        echo "<tr>
        <td$bst><span class='szoveg'>$word[b_subq]&nbsp;<input type='button' onclick=\"javascript:enumwindow($demog_id,1)\" value='...'></span></td>
        <td$bst><span class='szoveg'>$henum_values_vertical</span></td>
        </tr>\n";
    }
    echo "<tr><td><span class='szoveg'>$word[vd_multi]</span></td>
    <td><span class='szoveg'>
    <input type='radio' name='multiselect' value='yes' $ms_yes>$word[yes]&nbsp;&nbsp;
    <input type='radio' name='multiselect' value='no' $ms_no>$word[no]</span></td>
    </tr>\n";
    echo "<tr><td><span class='szoveg'>$word[vd_multiappend]</span></td>
    <td><span class='szoveg'>
    <input type='radio' name='multi_append' value='yes' $ma_yes>$word[vd_multiappend_yes]&nbsp;&nbsp;
    <input type='radio' name='multi_append' value='no' $ma_no>$word[vd_multiappend_no]</span></td>
    </tr>\n";
}
elseif ($new==1 && $copy_demog_id && ($variable_type=="enum" || ($variable_type=="matrix"))) {
    echo "<tr>
    <td><input type='hidden' name='copy_demog_id' value='$copy_demog_id'><span class='szoveg'>$word[b_possval]&nbsp;</span></td>
    <td><span class='szoveg'>$henum_values</span></td>
    </tr>\n";
    if ($variable_type=="matrix") {
        echo "<tr>
        <td><span class='szoveg'>$word[b_subq]&nbsp;</span></td>
        <td><span class='szoveg'>$henum_values_vertical</span></td>
        </tr>\n";
    }
    echo "<tr><td><span class='szoveg'>$word[vd_multi]</span></td>
    <td><span class='szoveg'>
    <input type='radio' name='multiselect' value='yes' $ms_yes>$word[yes]&nbsp;&nbsp;
    <input type='radio' name='multiselect' value='no' $ms_no>$word[no]</span></td>
    </tr>\n";
    echo "<tr><td><span class='szoveg'>$word[vd_multiappend]</span></td>
    <td><span class='szoveg'>
    <input type='radio' name='multi_appned' value='yes' $ma_yes>$word[vd_multiappend_yes]&nbsp;&nbsp;
    <input type='radio' name='multi_append' value='no' $ma_no>$word[vd_multiappend_no]</span></td>
    </tr>\n";
}

if ($_MX_superadmin) {
    print "<tr><td colspan=2><span class='szoveg'>$word[ed_gexp]</span></td></tr>
           <tr><td><span class='szoveg'>$word[ed_groups]</span></td><td><span class='szoveg'>
                <input type='text' size='40' value='$grlist' name='grlist'></span></td></tr>";
}
print "<tr><td valign='top' align='center' colspan='2' class='bgkiemelt2'><span class='szoveg'>
            <input type='submit' name='submit' value='$word[submit3]'></span></td></tr>
</table>
</div>
</form>\n";

include "footer.php";
?>
