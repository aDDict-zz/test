<?php
include "auth.php";
include "decode.php";
$weare=34;
include "cookie_auth.php";
include "common.php";

$form_id=get_http("form_id",0);
$form_endlink_id=get_http("form_endlink_id",0);

header("location: form_element_options.php?form_endlink_id=$form_endlink_id&group_id=$group_id&form_id=$form_id");
exit;

// This page is no longer maintained, form_element_options is used instead.

$language=select_lang();
include "./lang/$language/form.lang";
include "menugen.php";
include "_form.php";
$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;
$_MX_form = new MxForm($group_id,"",0,0);
$_MX_form->MakeMenu("elements",$formdata);

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

// http://192.168.250.2/maxima/form_element_options.php?form_element_id=265&subscribe_dep=1&group_id=251

$action=get_http("action","");
if ($form_endlink_id) {
    $res=mysql_query("select f.*,fe.dependency from form f,form_endlink fe 
                      where fe.id='$form_endlink_id' and f.group_id='$group_id' and fe.form_id=f.id");
    if ($res && mysql_num_rows($res)) {
        $formdata=mysql_fetch_array($res);
        $dep_object="form_endlink";
        $dep_id=$form_endlink_id;
        $qy="form_endlink_id=$dep_id";
        $form_id=$formdata["id"];
        if (strstr($formdata["dependency"],"&")==false) $dep_val="||"; else $dep_val="&&";
    }
    else {
        exit;
    }
}
$error=array();
$qys=str_replace(",", " and ",$qy);

if ($action=="enter") {
    $grpprt="";
        $dependent_id=slasher($_POST["dependent_id"]); // implied by the form element otherwise.
        $r=mysql_query("select * from form_element where form_id='$form_id' and id!='$form_element_id' and demog_id='$dependent_id'");
        if (!($r && mysql_num_rows($r))) {
            $dependent_id=0;
            $dependent_value="";
        }
    $dependent_value=slasher($_POST["dependent_value"]);
    reset ($_POST);
    $glue="";
    $anyopt=0;
    $alldata=array();
    $neg=slasher($_POST["neg"]);
    while (list($dvkey,$dvval)=each($_POST)) {
        if (ereg("dependent_value([0-9_]+)_?([0-9_]+)?",$dvkey,$regs)) {
            $dependent_value.="$glue$regs[1]";
            $glue=",";
            if ($dvval!="" || $neg[$regs[1]]) {
                $regs=explode("_",$regs[1]);
                if ($regs[2]!="") $regs[1].="_".$regs[2];
                if ($regs[1]=="0") $regs[1]="*";
                $alldata[$regs[0]].=($regs[1]!=null?$regs[1]:$dvval).",";
            }
            else {
                $query="delete from ".$dep_object."_dep where $qys and dependent_id=$regs[1]";
                mysql_query($query);
            }
        }
    }
    if (count($error)==0) {
        $dependency=array();
        $conn=slasher($_POST["conn"]);
        foreach ($alldata as $key=>$value) {
            $value=preg_replace("/,$/","",$value);
            $value=preg_replace("/^,/","",$value);
            $r=mysql_query("select * from ".$dep_object."_dep where $qys and dependent_id=$key");
            $fed=mysql_fetch_array($r);
            if ($neg[$key]=="on") $ng="!"; else $ng="";
            if ($r && mysql_num_rows($r)) {
                $query="update ".$dep_object."_dep set dependent_value='$value',neg='$ng' where $qys and dependent_id=$key";
                mysql_query($query);
                $dependency[]=$ng."D".$fed["id"];
            } else {
                $query="insert into ".$dep_object."_dep set dependent_id='$key',dependent_value='$value',$qy, neg='$ng'";
                mysql_query($query);
                $dependency[]=$ng."D".mysql_insert_id();
            }
        }
        $dep=implode($conn,$dependency);
        if ($group_id) $qys.=" and group_id=$group_id";
        $query="update $dep_object set dependency='$dep'$grpprt where id=$dep_id";
        if ($page_id) $query="update $dep_object set dependency='$dep'$grpprt where page_id=$page_id and group_id=$group_id and form_id=$form_id";
        mysql_query($query);
        print "<script>
        window.location='form_ch.php?form_id=$form_id&group_id=$group_id';
        </script>";
        exit; 
    }
}
echo "<a href='form_elements.php?group_id=$group_id&form_id=$form_id'>&lt;-Vissza</a>";
printhead();

echo "<tr>
<td colspan='3' bgcolor='white'><span class='szoveg'>". implode(",",$error) ."&nbsp;</span></td>
</tr>\n";
$dep=array();
$res=mysql_query("select * from ".$dep_object."_dep where $qys");
while ($w=mysql_fetch_array($res)) {
    $dep[$w["dependent_id"]]=$w;
    $di=$w["dependent_id"];
}
$widget="";
        $sorszam=1;
		$r2=mysql_query("select fe.id,fe.demog_id,fe.question,fe.widget,fe.dependency,d.variable_type 
			             from form_element fe left join demog d on fe.demog_id=d.id
						 where form_id='$form_id' order by page,box_id,sortorder");

        if ($r2 && mysql_num_rows($r2)) {
            while ($w=mysql_fetch_array($r2)) {
                if ($w["id"]!=$form_element_id && !in_array($w["widget"],array('comment','separator','cim'))) {                                 $widget=$w["widget"];
                    $dependent_id=$w["demog_id"];                    
                    $dependent_value=$dep[$w["demog_id"]]["dependent_value"];
                    if ($dep[$w["demog_id"]]["neg"]=="!") $neg_val="checked"; else $neg_val="";
                    $opt=htmlspecialchars($w["question"]);
                    if ($sorszam % 2 ==0) $bc=""; else $bc="grey";
                    if ($dependent_value!="" || $neg_val!="") $bc="vkek";
                    $dep_options.="<div class='$bc'><span onclick='chst($dependent_id);' class='$bc cp'>$sorszam. $opt</span></div>";
                    $sorszam++;
					$variable_type=$w["variable_type"];
                    $dep_options.= widget();
                }
            }
        }
function widget() {
    global $_MX_var,$dependent_value,$widget,$dependent_id,$word,$bc,$neg_val,$dep_val,$variable_type;
    $dependent_value=htmlspecialchars($dependent_value);
    $depwidget="";
    echo "<script>add_id($dependent_id);</script>";
    if ($widget=="") {
        $depwidget="- - -";
	}
	elseif ($variable_type=="enum") {
    //elseif ($widget=="multiselect" || $widget=="checkbox" || $widget=="radio" || $widget=="select") {
        $dependent_array=explode(",",$dependent_value);
        $depwidget="<input type='hidden' name='dependent_value".$dependent_id."' value=''>";
        if (in_array("*",$dependent_array)) {
            $sel="checked";
        }
        else {
            $sel="";
        }
        $depwidget.="<input type='checkbox' name='dependent_value".$dependent_id."_0' value='1' $sel> <b>$word[any_option]</b><br>";
        $r2=mysql_query("select * from demog_enumvals where demog_id='$dependent_id' and deleted='no'");
        if ($r2 && mysql_num_rows($r2)) {
            while ($w=mysql_fetch_array($r2)) {
                if (in_array($w["id"],$dependent_array)) {
                    $sel="checked";
                }
                else {
                    $sel="";
                }
                $ev=htmlspecialchars("$w[code] | $w[enum_option]");
                $depwidget.="<input type='checkbox' name='dependent_value".$dependent_id."_$w[id]' value='1' $sel> $ev<br>";
            }
        }
    }
    elseif ($widget=="checkbox_matrix" || $widget=="radio_matrix") {
        $dependent_array=explode(",",$dependent_value);
        $depwidget="<input type='hidden' name='dependent_value".$dependent_id."' value=''><table border=0 cellspacing=1 cellpadding=1 width='100%' style='border: 1px solid $_MX_var->main_table_border_color; background-color: $_MX_var->main_table_border_color;'><tr><td class='bgwhite'> </td>\n";
        $r3=mysql_query("select id,enum_option,vertical,code from demog_enumvals 
                         where demog_id='$dependent_id' and deleted='no'");
        if ($r3 && mysql_num_rows($r3)) {
            while ($k3=mysql_fetch_array($r3)) {
                if ($k3["vertical"]=="no") {
                    $rows[]=array($k3["enum_option"],$k3["id"],$k3["code"]);
                }
                else {
                    $cols[]=array($k3["enum_option"],$k3["id"],$k3["code"]);
                }
            }
        }
        for ($i=0;$i<count($rows);$i++) {
            $depwidget.="<td class='bgwhite'><span class='szoveg'>".htmlspecialchars($rows[$i][2])." | ".htmlspecialchars($rows[$i][0])."</span></td>\n";
        }
        for ($j=0;$j<count($cols);$j++) {
            $depwidget.="</tr><tr><td class='bgwhite'><span class='szoveg'>".htmlspecialchars($cols[$j][2]) . " | " . htmlspecialchars($cols[$j][0])."</span></td>\n";
            for ($i=0;$i<count($rows);$i++) {
                $value=$cols[$j][1]."_".$rows[$i][1];
                if (in_array($value,$dependent_array)) {
                    $sel="checked";
                }
                else {
                    $sel="";
                }
                $depwidget.="<td class='bgwhite'><input type='checkbox' name='dependent_value".$dependent_id."_$value' value='1' $sel></td>\n";
            }
        }
        $depwidget.="</tr></table>"; 
    }
    else {
        $depwidget="<input name='dependent_value$dependent_id' value=\"$dependent_value\">";
    }
    if ($dependent_value!=null || $neg_val!="") $vis="display: block;"; else $vis="display: none;";
    return "<div id='$dependent_id' class='$bc' style='padding: 0 0 5 30px; $vis'><input type='checkbox' $neg_val name='neg[$dependent_id]'> $word[neg]<br />$depwidget</div>";
}
    echo "<tr>
          <td bgcolor='white' align=left><span id='cv' class='szoveg cp' onclick='chast(\"ext\");'>+".$word['ext_view']."</span></td>          
          </tr>\n";
    echo "<tr>
          <td bgcolor='white' align=left><span class=szoveg>$dep_options</span></td>
          </tr>\n";

    echo "<tr>
        <td align=center colspan=3>$word[conn]: 
        <select name='conn'><option value='&&'";
        if ($dep_val=="&&") echo " selected";
        echo ">$word[and]</option><option value='||'";
        if ($dep_val=="||") echo "selected";
        echo ">$word[or]</option>";
    echo "</select>&nbsp;
        <input type='button' class='tovabbgomb' value='$word[submit3]' onclick='realsubmit();'> <input type='button' class='tovabbgomb' value='$word[close]' onclick='window.close();'>
        </td>
        </tr></form>";



printfoot();
include "footer.php";
// ------------ ------------- ------------- -------------

function printhead() {

    global $_MX_var,$stat_text,$userlist_yn,$filt_demog_options,$group_id,$pagenum,$word, $formdata, $form_endlink_id, $group_id, $page_id, $form_id,$word;

    echo "<html>
<head>
<title>$_MX_var->application_instance_name</title>
<link rel='stylesheet' type='text/css' href='$_MX_var->baseUrl/$_MX_var->application_instance_css'>
<script>
var extv='".$word['ext_view']."';
var normv='".$word['norm_view']."';

function resubmit() {
    var f=document.fops;
    f.action.value='seldep';
    f.submit();
}
function realsubmit() {
    var f=document.fops;
    f.action.value='enter';
    f.submit();
}
function chst(depi) {
    var f=document.getElementById(depi).style;
    if (f.display=='none') f.display='block'; else f.display='none';
}
var d_ids = new Array();
var vt ='ext';
function add_id(id) {
	d_ids.push(id); 
}

function chast() {
    var cv=document.getElementById('cv');
	for (i=0;i<d_ids.length;i++) {
        var f=document.getElementById(d_ids[i]).style;
        if (vt=='ext') f.display='block'; else f.display='none';
	}    
    if (vt=='ext') {
        vt='norm'; 
        cv.innerHTML='-'+normv;
    }
    else {
        vt='ext';
        cv.innerHTML='+'+extv;
    }
}
</script>
</head>
<body bgcolor='#FFFFFF' onload='focus()'>
<form method='post' action='form_endlink_options.php' name='fops'>
<input type='hidden' name='action' value='seldep'>
<input type='hidden' name='form_endlink_id' value='$form_endlink_id'>
<input type='hidden' name='group_id' value='$group_id'>
<input type='hidden' name='page_id' value='$page_id'>
<input type='hidden' name='form_id' value='$form_id'>
<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <tr>
		<td class=bgkiemelt2 align=left colspan=3><span class=szovegvastag>&quot;".htmlspecialchars($formdata["title"])."&quot $word[iform_title] &gt; ".htmlspecialchars($formdata["question"])." &gt; $word[fe_depend]</span></td>
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

