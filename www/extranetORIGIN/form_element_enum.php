<?
include "auth.php";
include "decode.php";
$weare=34;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/form.lang";

// id of the 'from' enum variable, default affiliates can be assigned to its options.
$_MX_from_demog_id=22;

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
$form_element_id = get_http("form_element_id","");

if (isset($_GET["form_element_enumvals_id"])) {
    $fee_id=mysql_escape_string($_GET["form_element_enumvals_id"]);
    $res=mysql_query("select form_element_id from form_element_enumvals where id='$fee_id'");
    if ($res && mysql_num_rows($res)) {
        if ($k=mysql_fetch_array($res)) {
            $form_element_id=$k["form_element_id"];
        }
    }
}
$enter = get_http("enter","");

$form_id=slasher($form_id);
$res=mysql_query("select fe.*,f.* from form_element fe,form f where fe.id='$form_element_id' and fe.form_id=f.id and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;

$res=mysql_query("select * from demog where id='$formdata[demog_id]'");
if ($res && mysql_num_rows($res))
    $demogdata=mysql_fetch_array($res);
else
    exit;

if (isset($_GET["form_element_enumvals_id"])) {
    $query = mysql_query("select fee.id,fee.sortorder
                        from demog_enumvals de,form_element_enumvals fee where de.demog_id='$formdata[demog_id]' 
                        and de.deleted='no' and fee.form_element_id='$form_element_id' and de.id=fee.demog_enumvals_id 
                        order by fee.sortorder,de.enum_option");
    if (!$query || !mysql_num_rows($query)) {
        exit;
    }
    $form_elements=array();
    while ($row=mysql_fetch_array($query)) {
        $form_elements[]=$row;
        if ($row['id']==$fee_id) {
            $oldposition=$position=count($form_elements)-1;
        }
    }
    // Determine which way to move the element
    if (isset($_GET["dir"]) && $_GET["dir"]==0) {
        $sqldirection=$direction=-1;
        $sqlcomp="<=";
    } else {
        $direction=1;
        $sqldirection="+1";
        $sqlcomp=">=";
    }
    if (isset($_GET["by"])) {
        $moveby=abs(intval($_GET["by"]));
    }
    if (empty($moveby)) {
        $moveby=1;
    }
    // Calculate the new position
    $position+=$direction*$moveby;
    if ($position>=count($form_elements)) {
        $position=count($form_elements)-1;
    }
    if ($position<0) {
        $position=0;
    }
    // The page and the box are taken from the element whose place will be
    // taken
    $sortorder=$form_elements[$position]['sortorder']+$direction;
    // Check whether the anyone is using this sortorder
    $occupied=false;
    foreach ($form_elements as $element) {
        if ($element['sortorder']==$sortorder) {
            $occupied=true;
            break;
        }
    }
    // If the sortorder is used, make room
    if ($occupied) {
        mysql_query("update form_element_enumvals set sortorder=sortorder$sqldirection 
                     where form_element_id='$form_element_id' and sortorder$sqlcomp'$sortorder'");
    }
    // Now, update the element
    mysql_query("update form_element_enumvals set sortorder='$sortorder' where id='$fee_id'");
}

$error=array();

if ($enter == 'yes') {
    if (is_array($_POST['de'])) {
        $res = mysql_query("select * from demog_enumvals where demog_id='$formdata[demog_id]' and deleted='no'");
        //print("select * from demog_enumvals where demog_id='$formdata[demog_id]' and deleted='no'");
        if (isset($_POST["default_value"]) && ereg("^[0-9]+$",$_POST["default_value"])) {
            $default_value=$_POST["default_value"];
        }
        else {
            $default_value="";
        }
        if ($res && $count=mysql_num_rows($res)) {
            while ($k=mysql_fetch_array($res)) {
                if (isset($_POST["de"]["$k[id]"]) && $_POST["de"]["$k[id]"]==1) {
                     $etitle=mysql_escape_string(stripslashes($_POST["ttl$k[id]"]));
                     $default_aff=mysql_escape_string($_POST["daff$k[id]"]);
                     $rz=mysql_query("select * from form_element_enumvals where form_element_id='$form_element_id' and demog_enumvals_id='$k[id]'");
                     isset($_POST["break_after"]["$k[id]"])?$break_after="yes":$break_after="no";
                     isset($_POST["excludes_others"]["$k[id]"])?$excludes_others="yes":$excludes_others="no";
                     if ($rz && mysql_num_rows($rz)) {
                         mysql_query("update form_element_enumvals set title='$etitle',break_after='$break_after',
                                      excludes_others='$excludes_others',
                                      default_aff='$default_aff' where 
                                      form_element_id='$form_element_id' and demog_enumvals_id='$k[id]'");
                     }
                     else {
                         // sortorder=>$k[id] - new enumvals will thus be added in the same order as they were added to the variable
                         mysql_query("insert into form_element_enumvals 
                                      (form_element_id,demog_enumvals_id,title,excludes_others,break_after,sortorder,default_aff) 
                                      values ('$form_element_id','$k[id]','$etitle','$excludes_others','$break_after','$k[id]','$default_aff')");
                     }
                }
                else {
                    mysql_query("delete from form_element_enumvals where form_element_id='$form_element_id' and demog_enumvals_id='$k[id]'");
                }
            }
        }
        $_POST["hide_option"]=="yes"?$hide_option="yes":$hide_option="no";
        $multi_append="no";
        if ($_POST["multi_append"]==$demogdata["multi_append"]) {
            $multi_append="default";
        }
        elseif ($_POST["multi_append"]=="yes") {
            $multi_append="yes";
        }
        $_POST["direction"]=="horizontal"?$direction="horizontal":$direction="vertical";
        // For matrices, we use maxlength to set the width of the first column:
        // 0 - not set
        // 100 - maximum length, 100%
        // <0 - -maxlength pixels
        if (isset($_POST["maxlength"])) {
            $maxlength="100";
        }
        elseif (isset($_POST["maxlengthpx"])) {
            $maxlength= - abs(intval($_POST["maxlengthpx"]));
        }
        else {
            $maxlength="0";
        }
        mysql_query("update form_element set maxlength='$maxlength',hide_option='$hide_option',multi_append='$multi_append',
                     direction='$direction',default_value='$default_value' where id='$form_element_id'");
        $formdata["hide_option"]=$hide_option;
        $formdata["multi_append"]=$multi_append;
        $formdata["direction"]=$direction;
        $formdata["default_value"]=$default_value;
        $formdata["maxlength"]=$maxlength;
    }
}

if ($formdata["demog_id"]==$_MX_from_demog_id) {
    $colspan="6";
    $selaffhdr="<td class='bgkiemelt2' align=left width=10%><span class=szovegvastag>Affiliate id</span></td>";
}
else {
    $colspan="5";
    $selaffhdr="";
}
if ($formdata["widget"]=="checkbox" || $formdata["widget"]=="checkbox_matrix") {
    $colspan++;
}

printhead();

if (isset($changed))
    $errorlist="$changed $word[fe_changed]";
else
    $errorlist="";

echo "<tr>
<td colspan='$colspan' bgcolor='white'><span class='szoveg'>$errorlist&nbsp;</span></td>
</tr>";
$formdata["hide_option"]=="yes"?$hocheck="checked":$hocheck="";
$formdata["direction"]=="horizontal"?$dicheck="checked":$dicheck="";
$mlcheck="";
$maxlengthpx="";
if ($formdata["maxlength"]=="100") {
    $mlcheck="checked";
}
if ($formdata["maxlength"]<0) {
    $maxlengthpx= - $formdata["maxlength"];
}

if ($formdata["widget"]!="hidden") {
    if (in_array($formdata["widget"],array("radio_matrix","checkbox_matrix"))) {
        print "<tr><td colspan='$colspan' bgcolor='white'><span class='szoveg'>
               <input type='checkbox' name='direction' value='horizontal' $dicheck> $word[fe_m_direction]
                    </span></td></tr><tr><td colspan='$colspan' bgcolor='white'><span class='szoveg'>
               $word[fe_m_firstlength] <input size='4' name='maxlengthpx' value='$maxlengthpx'>px <input type='checkbox' name='maxlength' value='100' $mlcheck> $word[fe_m_maxlength]</span></td></tr>";
    }
    else {
        print "<tr><td colspan='$colspan' bgcolor='white'><span class='szoveg'>
                <input type='checkbox' name='hide_option' value='yes' $hocheck> $word[fe_hide_option]
                    </span></td></tr><tr><td colspan='$colspan' bgcolor='white'><span class='szoveg'>
                <input type='checkbox' name='direction' value='horizontal' $dicheck> $word[fe_direction]</span></td></tr>";
    }
    if ($formdata["widget"]=="radio_matrix") {
        print "<tr><td colspan='$colspan' bgcolor='white'><span class='szoveg'><input type='checkbox' name='hide_option' value='yes' $hocheck> $word[fe_m_hide_option]</span></td></tr>";
    }
    if ($formdata["widget"]=="checkbox_matrix") {
        print "<tr><td colspan='$colspan' bgcolor='white'><span class='szoveg'><input type='checkbox' name='hide_option' value='yes' $hocheck> $word[fe_fill_each_row]</span></td></tr>";
    }
}
if ($demogdata["multiselect"]=="yes") {
    if ($formdata["multi_append"]!="yes" && $formdata["multi_append"]!="no") {
        $mappend=$demogdata["multi_append"];
    }
    else {
        $mappend=$formdata["multi_append"];
    }
    $mappend=="yes"?$ma_yes="checked":$ma_no="checked";
    $demogdata["multi_append"]=="yes"?$ma_yesdef="&nbsp;($word[vd_multiappend_default])":$ma_nodef="&nbsp;($word[vd_multiappend_default])";
    print "<tr><td colspan='$colspan' bgcolor='white'><span class='szoveg'>$word[vd_multiappend]<br><input type='radio' name='multi_append' value='yes' $ma_yes>$word[vd_multiappend_yes]$ma_yesdef&nbsp;&nbsp;<input type='radio' name='multi_append' value='no' $ma_no>$word[vd_multiappend_no]$ma_nodef</span></td>
    </tr>\n";
}

$fsel=array();
$r2=mysql_query("select demog_enumvals_id,title,default_aff from form_element_enumvals where form_element_id='$form_element_id'");
if ($r2 && mysql_num_rows($r2)) {
    while ($z=mysql_fetch_array($r2)) {
        $fsel[$z['demog_enumvals_id']] = 1;
        $ftitle[$z['demog_enumvals_id']] = $z["title"];
        $daffs[$z['demog_enumvals_id']] = $z["default_aff"];
    }
}

$index=0;
$notinlist="0";
echo "<tr><td class='bgkiemelt2' align=left width=32%><span class=szovegvastag>$word[t_var]</span></td>
      <td class='bgkiemelt2' align=left width=26%><span class=szovegvastag>$word[fe_in] <a href=\"javascript:select_all()\"><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/selectall.gif' border='0' alt='$word[select_all]'></a> <a href=\"javascript:deselect_all()\"><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/selectnone.gif' border='0' alt='$word[select_none]'></a></span></td>$selaffhdr
      <td class='bgkiemelt2' align=left width=12%><span class=szovegvastag>Default<a href=\"javascript:deselect_all_defaults()\"><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/selectnone.gif' border='0' alt='$word[select_none]'></a></span></td>
      <td class='bgkiemelt2' align=left width=7%><span class=szovegvastag>$word[fe_break]</span></td>\n";
if ($formdata["widget"]=="checkbox" || $formdata["widget"]=="checkbox_matrix") {
    echo "<td class='bgkiemelt2' align=left width=7%><span class=szovegvastag>$word[fe_excludes_others]</span></td>\n";
}
echo "<td class='bgkiemelt2' align=left width=23%><span class=szovegvastag>$word[fe_title]</span></td>
      </tr>\n";
$res = mysql_query("select de.*,fee.excludes_others,fee.break_after,fee.id as feeid
                    from demog_enumvals de,form_element_enumvals fee where de.demog_id='$formdata[demog_id]' 
                    and de.deleted='no' and fee.form_element_id='$form_element_id' and de.id=fee.demog_enumvals_id 
                    order by fee.sortorder,de.enum_option");
if ($res && $count=mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        mxmakerow($k,1);
        $notinlist.=",$k[id]";
    }
}
echo "<tr><td align=center colspan='$colspan' style='height:1px;'></td></tr>";

$res = mysql_query("select * from demog_enumvals where demog_id='$formdata[demog_id]' and deleted='no' 
                    and id not in ($notinlist) order by enum_option");
if ($res && $count=mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        mxmakerow($k);
    }
}
echo "<tr>
    <td align=center colspan='$colspan'>
    <input type=submit class='tovabbgomb' value='$word[submit3]'> <input type='button' class='tovabbgomb' value='$word[close]' onclick='window.close();'>
    </td>
    </tr></form>";

printfoot();
include "footer.php";

function mxmakerow(&$k,$isselected=0) {

    global $_MX_var,$index,$fsel,$ftitle,$word,$formdata,$_MX_from_demog_id,$daffs;

    if ($index%2)
       $bgrnd="class=bgvilagos2";
    else
       $bgrnd="bgcolor=white";
    $varname=htmlspecialchars("$k[code] | $k[enum_option]");
    if ($k["vertical"]=="yes") {
        $varname.=" (részkérdés)";
    }
    if ($fsel[$k['id']]) {
        $sel1="checked";
        $sel0="";
    } else {
        $sel1="";
        $sel0="checked";
    }
    $pagelist="$word[fe_no] <input type='radio' value='0' $sel0 name='de[$k[id]]'> $word[fe_yes]<input type='radio' value='1' $sel1 name='de[$k[id]]'>";
    $etitle="<input type='text' name='ttl$k[id]' value=\"". htmlspecialchars($ftitle["$k[id]"]) ."\"'>";
    $k["excludes_others"]=="yes"?$checked="checked":$checked="";
    $exclo="<input type='checkbox' name='excludes_others[$k[id]]' value='yes' $checked>";
    $k["break_after"]=="yes"?$checked="checked":$checked="";
    $ebreak="<input type='checkbox' name='break_after[$k[id]]' value='yes' $checked>";
    if ($isselected) {
        $jname=ereg_replace("[\r\n'\"]","",$k["enum_option"]);
        if ($index>0) {
            $up="<a href='javascript:dm_sort($k[feeid],0,\"$jname\")'>$word[fe_up]</a>";
        }    
        else {
            $up="&nbsp;&nbsp;&nbsp;&nbsp;";
        }    
        $down="<a href='javascript:dm_sort($k[feeid],1,\"$jname\")'>$word[fe_down]</a>";
        $pagelist.="$up $down";
    }
    if ($k["vertical"]=="yes") {
        $edefault="";
    }
    else {
        if ($k["id"]==$formdata["default_value"]) {
            $sel="checked";
        }
        else {
            $sel="";
        }
        $edefault="<input type='radio' name='default_value' $sel value='$k[id]'>";
    }
    if ($formdata["demog_id"]==$_MX_from_demog_id) {
        $selaffrow="<td $bgrnd align=left width=10%><span class=szoveg><input type='text' size='6' name='daff$k[id]' value=\"".$daffs["$k[id]"]."\"'></span></td>";
    }
    else {
        $selaffrow="";
    }
    echo "<tr>
          <td $bgrnd align=left width=32%><span class=szoveg>&nbsp;&nbsp;$varname</span></td>
          <td $bgrnd align=left width=26%><span class=szoveg>&nbsp;&nbsp;$pagelist</span></td>$selaffrow
          <td $bgrnd align=left width=12%><span class=szoveg>&nbsp;&nbsp;$edefault</span></td>
          <td $bgrnd align=left width=7%><span class=szoveg>&nbsp;&nbsp;$ebreak</span></td>\n";
    if ($formdata["widget"]=="checkbox" || $formdata["widget"]=="checkbox_matrix") { 
        echo "<td $bgrnd align=left width=7%><span class=szoveg>&nbsp;&nbsp;$exclo</span></td>\n";
    }
    echo "<td $bgrnd align=left width=23%><span class=szoveg>&nbsp;&nbsp;$etitle</span></td>
          </tr>\n";
    $index++;

}


function printhead() {

    global $_MX_var,$stat_text,$userlist_yn,$filt_demog_options,$group_id,$pagenum,$word, $formdata, $form_element_id, $group_id,$colspan, $language;



$_MX_popup = 1;
include "menugen.php";


echo "
<script language=\"JavaScript\">
function dm_sort(fee_id,dir,name) {
    if (dir==0) {dtxt='$word[fe_up]';} else {dtxt='$word[fe_down]';}
    if (moveby=prompt('$word[fe_move1] '+name+' $word[fe_move2] '+dtxt+' $word[fe_move3]:',1)) {
        location='form_element_enum.php?group_id=$group_id&move=1&form_element_enumvals_id='+fee_id+'&dir='+dir+'&by='+moveby;
    }
}
</script>
<form method='post' name='myinputs' action='form_element_enum.php'>
<input type='hidden' name='enter' value='yes'>
<input type='hidden' name='form_element_id' value='$form_element_id'>
<input type='hidden' name='group_id' value='$group_id'>
<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <tr>
		<td class=bgkiemelt2 align=left colspan='$colspan'><span class=szovegvastag>&quot;".htmlspecialchars($formdata["title"])."&quot $word[iform_title] &gt; $formdata[question] &gt; $word[fe_new]</span></td>
		</tr>\n";
}

function printfoot() {

    echo "
<script language=\"JavaScript\">
function select_all()
{
  len = document.myinputs.elements.length;
  var i=0;
  for(i=0; i < len; i++) {
    if (document.myinputs.elements[i].type == 'radio' && document.myinputs.elements[i].value==1)
    { document.myinputs.elements[i].checked = true }
  }

}
function deselect_all()
{
  len = document.myinputs.elements.length;
  var i=0;
  for(i=0; i < len; i++) {
    if (document.myinputs.elements[i].type == 'radio' && document.myinputs.elements[i].value==0)    
    { document.myinputs.elements[i].checked = true }
  }

}

function deselect_all_defaults()
{
  len = document.myinputs.elements.length;
  var i=0;
  for(i=0; i < len; i++) {
    if (document.myinputs.elements[i].type == 'radio' && document.myinputs.elements[i].name=='default_value')    
    { document.myinputs.elements[i].checked = false }
  }

}

</script>

</table>
        </td>
        </tr>
        </table>
</body>
    </html>\n";
}

