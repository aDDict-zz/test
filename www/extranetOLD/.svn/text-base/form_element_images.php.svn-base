<?
include "auth.php";
include "decode.php";
$weare=34;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/form.lang";
include "menugen.php";
include "_form.php";

$form_id=get_http("form_id",0);
$form_element_id=get_http("form_element_id",0);
$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;
$_MX_form = new MxForm($group_id,"",0,0);
$_MX_form->MakeMenu("elements",$formdata);

if (isset($_POST["upimage"])) {
    $filename="";
    if (isset($_FILES["imagepath"]["tmp_name"])) {
        $filename=$_FILES["imagepath"]["tmp_name"];
    }
    if (!empty($filename) && $filename!="none" && is_uploaded_file($filename)) {
        $size=getimagesize("$filename");
        if (!eregi("\.(gif|jpg|png)$",$_FILES["imagepath"]["name"],$regs)) {
            $error.="$word[gifjpgonly]";
        }
        else {
            $ext=$regs[1];
        }
        if (empty($error)) {
            $rnd=substr(md5(time().mt_rand(0,10000)),3,4);
            $imagename="fe-$rnd.$ext";
            $dest=$_MX_var->form_imagepath . $imagename;            
            move_uploaded_file($filename,$dest);
            mysql_query("insert into form_images set name='".$_FILES["imagepath"]["name"]."',filename='$imagename',type='$ext',form_id='$form_id'");
        }
    }
    else
        $error.=$word["choiceimage"];
}
print $error;
?>
<script language="JavaScript">
function qprev(filename,id) {
    var c=document.getElementById(id);
    var p=document.getElementById('imgprev');
    p.style.top=c.offsetTop;
    p.innerHTML='<img src=\'show_image.php?filename='+filename+'\'/>';
}
function select_all() {
    len = document.slimg.elements.length;
    var i=0;
    for(i=0; i < len; i++) {
        if (document.slimg.elements[i].type == 'checkbox')
            { document.slimg.elements[i].checked = true }
    }
}
function deselect_all() {
    len = document.slimg.elements.length;
    var i=0;
    for(i=0; i < len; i++) {
        if (document.slimg.elements[i].type == 'checkbox')
            { document.slimg.elements[i].checked = false }
        }
    }

</script>

<?php
print '<form method="post" name="ni" enctype="multipart/form-data">
        '.$word["fe_choice_image"].' <input type="file" name="imagepath"/>
        <input type="submit" value="'.$word["fe_upload"].'" name="upimage"/>
        </form>';
echo "<a href='form_elements.php?group_id=$group_id&form_id=$form_id'>&lt;-Vissza</a>";
print "<div style='float:left; width:65%;'><form name='slimg' method='post' action='form_elements.php?form_id=$form_id&group_id=$group_id&form_element_id=$form_element_id#form_element_$form_element_id'><a href='javascript:select_all()' style='margin-left: 45px;'><img border='0' alt='Mindent kojelöl' src='$_MX_var->application_instance/gfx/selectall.gif'/></a><a href='javascript:deselect_all()'><img border='0' alt='Mindent megszüntet' src='$_MX_var->application_instance/gfx/selectnone.gif'/></a><ul style='margin-top: 0px;'>"; 

$r=mysql_query("Select * from form_element where id=$form_element_id");
$fe=mysql_fetch_array($r);
$ids=explode(",",$fe["image"]);

$r=mysql_query("Select * from form_images where form_id='$form_id' order by name");
while ($k=mysql_fetch_array($r)) {
    if (in_array($k["id"],$ids)) {$style="color:#993300; font-weight:bold;"; $ch="checked";} else {$style="";$ch="";}
    echo "<li id='$k[id]' onmouseover=\"qprev('".$k["filename"]."',$k[id])\" style='$style list-style-type: decimal; cursor: pointer;'><input type='checkbox' $ch name='kep[$k[id]]'>".$k["name"]."&nbsp;&nbsp;</li>";
}
print "<li style='list-style-type: none;'><input type='submit' name='ssi' value='$word[save_selected_images]'>&nbsp;</li></ul></form></div><div id='imgprev' style='text-align: right; float:left; width: 35%'></div>";
?>
