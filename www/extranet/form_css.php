<?
include "auth.php";
include "decode.php";
$weare=34;
$subweare="css";
include "cookie_auth.php";
include "common.php";

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
    exit; }

$title=$rowg["title"];
$active_membership=$rowg["membership"];

$form_id=slasher($form_id);
$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;

$error=array();

include "menugen.php";
include "./lang/$language/form.lang";
include "_form.php";
    
$_MX_form = new MxForm($group_id,"",0,0);
$_MX_form->MakeMenu($subweare,$formdata);

$errorlist="";
// copy css
if ($active_membership == "owner" || $active_membership=="moderator") {
    if (isset($form_id) && isset($new_form_id) && !empty($form_id) && !empty($new_form_id)) {
        $new_form_id = slasher($new_form_id);
        $res = mysql_query("select m.membership 
                             from form f,groups g,members m where f.group_id=g.id and g.id=m.group_id
                             and (m.membership='owner' or m.membership='moderator' or m.membership='admin')
                             and m.user_id='$active_userid' and f.id='$new_form_id'");
        if ($res && mysql_num_rows($res)) {
            copy_css($form_id, $new_form_id);
        }
        else {
            $errorlist.=$word["copy_css_no_permission"];
        }
    }
    $mres = mysql_query("select f.id, f.title as ftitle, g.title as gtitle 
                         from form f,groups g,members m where f.group_id=g.id and g.id=m.group_id
                         and (m.membership='owner' or m.membership='moderator' or m.membership='admin')
                         and m.user_id='$active_userid' and f.id!='$form_id'
                         order by g.title, f.title");
    $form_list = array("<option value='0'> --- </option>");
    while ($mr = mysql_fetch_array($mres)) {
        $title = (mb_strlen($mr["ftitle"]) > 60) ? mb_substr($mr["ftitle"],0,60,'UTF-8') . "..." : $mr["ftitle"];
        $form_list[] = "<option value='$mr[id]'>$mr[gtitle] - $title ($mr[id])</option>";
    }
    if (count($form_list)>1) {
        $form_list = implode("",$form_list);
        echo "<div class='fl'>
              <form method='post' action='' name='copy_css'>
              <input type='hidden' name='form_id' value='$form_id'>
              <input type='hidden' name='group_id' value='$group_id'>
              $word[copy_css_from]: <select name='new_form_id'>$form_list</select> 
              <input type='button' name='copy_css_submit' value='OK' onClick='checkOption();'></form>
              </div><div class='clear'></div>";
    }
}

    print "<script language=\"JavaScript\">
function checkOption() {
    if ($('select[name=new_form_id] option:selected').val()==0) {alert('$word[select_new_form]');}
    else {document.copy_css.submit();}
}
function dm_sort(form_element_id,dir,name) {
    if (dir==0) {dtxt='$word[fe_up]';} else {dtxt='$word[fe_down]';}
    if (moveby=prompt('$word[fe_move1] '+name+' $word[fe_move2] '+dtxt+' $word[fe_move3]:',1)) {
        location='form_in.php?group_id=$group_id&move=1&form_element_id='+form_element_id+'&dir='+dir+'&by='+moveby;
    }}
function picker(variable) {
    eval('var color2 = document.cssform.'+variable+'.value;');
    opener=window.open('picker.php?variable='+variable+'&color='+color2,'','toolbar=no,scrollbars=no,width=360,height=280'); }
function ch_trans(obj) {
    var obj=document.getElementById(obj);
    if (obj) {
        if (obj.style.visibility!='visible' && obj.style.visibility!='') {        
            obj.style.visibility='visible';
        } else {
            obj.style.visibility='hidden';            
            obj.style.backgroundColor='#fff';
            obj.value='';            
        }            
    }        
}    
</script>
<script src='js/jscolor/jscolor.js' type='text/javascript'></script>
<form method='post' name='cssform' action='form_css.php' style='border:0;margin:0;'>
<input type='hidden' name='action' value='change'>
<input type='hidden' name='form_id' value='$form_id'>
<input type='hidden' name='group_id' value='$group_id'>
<TABLE cellSpacing=1 cellPadding=3 width=\"100%\" class='bgcolor addpadding' border=0>
\n";

$dbvals=array();
$res=mysql_query("select * from form_css where form_id='$form_id' order by object_name");
logger($query,$group_id,"","form_id=$form_id","form_css");
if ($res && $count=mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $dbvals["$k[object_name]"]=$k["value"];
    }
}


//exceptions

if($_POST["left_td_width"] == 0 || $_POST["left_td_width"] == ""){
  $_POST["left_td_width"] = 120;
}


$updates=array();
if ($_POST["action"]=="change") {
    while (list($object,$properties)=each($_MX_form->css_objects)) {
        for ($i=1;$i<count($properties);$i++) {
            $property=$properties[$i];
            if ($property=="border") {
                $value=$_POST["${object}_${property}_0"]." ".$_POST["${object}_${property}_1"];
            }
            else {
                $value=$_POST["${object}_$property"];
            }

            $value=slasher($value,0);
            if ($property=="border") {
                $vals=explode(" ",$value);
                if ($vals[1]=="") $vals[1]="FFFFFF";
                $value=implode(" ",$vals);
                if (!ereg("^1?[0-9]$",$vals[0])) {
                    $errorlist.="$object - $property erteke 0-19 lehet.<br>";
                }
                if (!eregi("^([0-9a-f]{3})?[0-9a-f]{3}$",$vals[1])) {
                    $errorlist.="$object - $property 3 vagy 6 jegyu hexadecimalis szam lehet.<br>";
                }
            }
            elseif ($property=="color" || $property=="bgcolor") {
                if (!eregi("^([0-9a-f]{3})?[0-9a-f]{3}$",$value)) {
                    if (!($property=="bgcolor" && $value=="")) {
                        $errorlist.="$object - $property 3 vagy 6 jegyu hexadecimalis szam lehet.<br>";
                    }
                }
            }
            elseif ($property=="padding" || $property=="width" || $property=="height" || $property=="fontsize" || $property=="margin-left" || $property=="margin-right") {
                if (!ereg("^[0-9]+$",$value)) { 
                    $errorlist.="$object - $property erteke pozitiv egesz lehet.<br>";
                }
            }
            $updates["$object $property"]=mysql_escape_string($value);
            if ($property=="fontsize") {
                for ($ii=1;$ii<=3;$ii++) {
                    $specset=0;
                    if (isset($_POST["${object}_${property}_$ii"])) {
                        $specset=1;
                    }
                    $updates["$object $property"].=" $specset";
                }
            }
        }
    }
    if (empty($errorlist)) {
        reset($updates);
        while (list($key,$val)=each($updates)) {
            $res=mysql_query("select id from form_css where form_id='$form_id' and object_name='$key'");
            if ($res && mysql_num_rows($res)) {
                $css_id=mysql_result($res,0,0);
                $query="update form_css set value='$val' where id='$css_id'";
                mysql_query($query);
                logger($query,$group_id,"","form_css_id=$css_id","form_css");
            }
            else {
            	$query="insert into form_css set value='$val',object_name='$key',form_id='$form_id'";
                mysql_query($query);
                logger($query,$group_id,"","form_id=$css_id","form_css");                
            }
        }
        $errorlist="A valtoztatasok sikeresek voltak.";
    }
}

if (!empty($errorlist)) {
    print "<tr><td colspan='3' bgcolor='white'><span class='error'>$errorlist</span></td></tr>\n";
}
print "<tr><td class='bgkiemelt2' align=left width=20%><span class=szovegvastag>$word[t_object]</span></td>
      <td class='bgkiemelt2' align=left width=20%><span class=szovegvastag>$word[t_property]</span></td>
      <td class='bgkiemelt2' align=left width=60%><span class=szovegvastag>$word[t_value]</span></td>
      </tr>\n";
reset($_MX_form->css_objects);
$j=0;
while (list($object,$properties)=each($_MX_form->css_objects)) {
    for ($i=1;$i<count($properties);$i++) {
        $property=$properties[$i];
        $j++;
        if ($j%2)
           $bgrnd="class=bgvilagos2";
        else
           $bgrnd="bgcolor=white";
        if (isset($_POST["${object}_${property}_0"]) || isset($_POST["${object}_${property}"])) {
            if ($property=="border") {
                $value=$_POST["${object}_${property}_0"]." ".$_POST["${object}_${property}_1"];
            }
            else {
                $value=$_POST["${object}_$property"];
                if ($property=="fontsize") {
                    for ($ii=1;$ii<=3;$ii++) {
                        $specset=0;
                        if (isset($_POST["${object}_${property}_$ii"])) {
                            $specset=1;
                        }
                        $value.=" $specset";
                    }
                }
            }
        }
        elseif (isset($dbvals["$object $property"])) {
            $value=$dbvals["$object $property"];
        }
        else {
            $value=$_MX_form->CssDefaults($object,$property);
        }
        $bgcolor="";
        $bgtext="<span class='szovegvastag' style='cursor: pointer' onclick='ch_trans(\"${object}_${property}\")'>transzparens</a>";
        $bgstyle=" style='visibility: hidden;' ";
        if ($property=="border") {
            $vals=explode(" ",$value);
            $bgtext="";
            $bgstyle="";
            $bgcolor=" bgcolor='#$vals[1]'";            
            $widget="<table cellpadding=0 cellspacing=0 border=0><tr valign=top><td><input type='text' size='2' value='$vals[0]' name='${object}_${property}_0'>px #<input $bgstyle id='${object}_${property}' class='color' type='text' size='8' value='$vals[1]' name='${object}_${property}_1'>&nbsp;</td><td>$bgtext</td></tr></table>";
        }
        elseif ($property=="color" || $property=="bgcolor") {
            if ($value) {$bgcolor=" bgcolor='#$value'"; $bgstyle=""; $bgtext="";}
            $widget="<table cellpadding=0 cellspacing=0 border=0><tr valign=top><td>#<input $bgstyle id='${object}_${property}' class='color' type='text' size='8' value='$value' name='${object}_${property}'>&nbsp;</td><td>$bgtext</td></tr></table>";
        }
        elseif ($property=="fontsize") {
            $valuearr=explode(" ",$value);
            $widget="<input type='text' size='5' value='$valuearr[0]' name='${object}_$property'>px";
            for ($ii=1;$ii<=3;$ii++) {
                $checked=$valuearr[$ii]?"checked":"";
                $widget.="&nbsp;&nbsp;<input type='checkbox' $checked name='${object}_${property}_$ii'>".$word["fontspec$ii"];
            }
        }
        elseif ($property=="padding" || $property=="width" || $property=="height" || $property=="margin-left" || $property=="margin-right") {
            $widget="<input type='text' size='5' value='$value' name='${object}_$property'>px";
        }
        else {
            $widget="<input type='text' size='40' value='$value' name='${object}_$property'>";
        }

        $propname=htmlspecialchars($word["fp_$property"]);
        $objname=htmlspecialchars($word["fo_$object"]);
        echo "<tr valign='center'>
              <td $bgrnd align=left width=20%><span class=szoveg>&nbsp;$objname</span></td>
              <td $bgrnd align=left width=20%><span class=szoveg>&nbsp;$propname</span></td>
              <td $bgrnd align=left width=60%><span class=szoveg>$widget</span></td>
              </tr>\n";
    }
}
echo "
	<tr>
    <td align=center colspan='3' bgcolor='white' style='height:140px;'>&nbsp;</td>
	</tr>
	<tr>
    <td align=center colspan='3'>
    <input type=submit class='tovabbgomb' value='$word[submit3]'>
    </td>
	</tr>
	</table>\n";
include "footer.php";

function copy_css($form_id, $new_form_id) {
    // $form_id = copy css to this form
    // $new_form_id = copy css from this form
    global $errorlist, $word;

    $delete="delete from form_css where form_id='$form_id'";
    if (mysql_query($delete)) {
        $insert="insert into form_css select 0,$form_id,object_name,value from form_css where form_id='$new_form_id'";
        if (mysql_query($insert)) {
            $errorlist=$word["data_changed"];
        }
        else {
            $errorlist.=$word["error_save"]." (insert)";
        }
    }
    else {
        $errorlist.=$word["error_save"]." (delete)";
    }
}
?>
