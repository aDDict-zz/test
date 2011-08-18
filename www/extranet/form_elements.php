<?  //error_reporting(E_ERROR);



include "auth.php";
include "decode.php";
$weare=34;
$subweare='elements';
include "cookie_auth.php";
include "common.php";
include "_form.php";

$mres = mysql_query("select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'"); /* echo "select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'"; die(); */
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    exit; }

$title=$rowg["title"];
$active_membership=$rowg["membership"];

$form_id=slasher(get_http("form_id",0));
$form_element_id=slasher(get_http("form_element_id",0));
$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;

//print_r($formdata); die();

$error=array();

if (isset($_GET["del_page"])) {
    $del_page=intval($_GET["del_page"]);
    if ($del_page>0) {
        $query="delete from form_element where form_id=$form_id and page=$del_page";
        mysql_query($query);
        $query="update form set pages=(pages-1) where id=$form_id and group_id=$group_id";
        mysql_query($query);
        $query="update form_element set page=(page-1) where form_id=$form_id and page>$del_page";
        mysql_query($query);
        $query="update form_page_dep set page_id=page_id-1 where page_id>$del_page and form_id=$form_id";
        mysql_query($query);
        $query="update form_page_box set page_id=page_id-1 where page_id>$del_page and form_id=$form_id";
        mysql_query($query);
        $query="update form_page set page_id=page_id-1 where page_id>$del_page and form_id=$form_id";
        mysql_query($query);
        header("Location: form_elements.php?group_id=$group_id&form_id=$form_id");
        exit;
    }
}

if (isset($_GET["ins_page"])) {
    $ins_page=intval($_GET["ins_page"]);
    if ($ins_page>0) {
        $query="update form set pages=(pages+1) where id=$form_id and group_id=$group_id";
        mysql_query($query);
        $query="update form_element set page=(page+1) where form_id=$form_id and page>$ins_page";
        mysql_query($query);
        $query="update form_page_dep set page_id=page_id+1 where page_id>$ins_page and form_id=$form_id";
        mysql_query($query);
        $query="update form_page_box set page_id=page_id+1 where page_id>$ins_page and form_id=$form_id";
        mysql_query($query);
        $query="update form_page set page_id=page_id+1 where page_id>$ins_page and form_id=$form_id";
        mysql_query($query);
        header("Location: form_elements.php?group_id=$group_id&form_id=$form_id");
        exit;
    }
}

include "menugen.php";
include "./lang/$language/form.lang";
    
$_MX_form = new MxForm($group_id,"",0,0);
$_MX_form->MakeMenu($subweare,$formdata);

print "
<style>
#phonetic2 * { font-size:12px; font-family: Arial,Helvetica,sans-serif; }
</style>
<script language=\"JavaScript\">
function dm_sort(form_element_id,dir,name) {
if (dir==0) {dtxt='$word[fe_up]';} else {dtxt='$word[fe_down]';}
if (moveby=prompt('$word[fe_move1] '+name+' $word[fe_move2] '+dtxt+' $word[fe_move3]:',1)) {
    location='form_in.php?group_id=$group_id&move=1&form_element_id='+form_element_id+'&dir='+dir+'&by='+moveby;
}}
function form_element_dep(link) {
    var f = document.feform;
    var len = f.elements.length;
    var additional = new Array();
    for (var i=0; i < len; i++) {
        if (f.elements[i].type == 'checkbox' && f.elements[i].name.substr(0,16) == 'copy_dependency_' && f.elements[i].checked) {
            additional.push(f.elements[i].name.substr(16));
        }
    }
    if (additional.length) {
        link += '&copy_dependency_ids=' + additional.join(',');
    }
    document.location = link;
}
function toggle_mandatory(ch) {
    var f = document.feform;
    var len = f.elements.length;
    for (var i=0; i < len; i++) {
        if (f.elements[i].type == 'checkbox' && f.elements[i].name.substr(0,9) == 'mandatory') {
            f.elements[i].checked = ch==1;
        }
    }
}
function fesubmit() {
    var f = document.feform;
    var len = f.elements.length;
    for (var i=0; i < len; i++) {
        if (f.elements[i].type == 'hidden' && f.elements[i].name.substr(0,10) == 'question[z') {
            var ce = document.getElementById('question_' + f.elements[i].name.substr(10).replace(']',''));
            if (ce) {
                f.elements[i].value = ce.innerHTML;
            }
        }
    }
    f.submit();
}
function edit_content(ctype,param) {
    var sel = window.getSelection();
    var ne = mx_geteditnode(sel.anchorNode);
    if (ne=='' || ne != mx_geteditnode(sel.focusNode)) {
        // check if the start and end of the selection are in the same editable element.
        // alert ('Different editables');
        return;
    }
    if (ctype == 'remove') {
        ne.innerHTML = ne.innerHTML.replace(/<[^<>]+>/g,'');
        return;
    }
    if (ctype == 'center') {
        ne.innerHTML = '<center>' + ne.innerHTML + '</center>';
        return;
    }
    if (sel.anchorNode != sel.focusNode) {
        // for start, works only if the selection starts and ends in the same node
        // alert ('Different node');
        return;
    }
    var format;
    if (ctype == 'fontsize') {
        fontsize = Math.max(1,Math.min(7,parseInt(prompt('Font méret (1-7):','4'))))*3;
        format = 'font-size: ' + fontsize + 'px';
    }
    else if (ctype == 'bold') {
        format = 'font-weight: bold;';
    }
    else if (ctype == 'italic') {
        format = 'font-style: italic';
    }
    else if (ctype == 'underline') {
        format = 'text-decoration: underline;';
    }
    else if (ctype == 'ForeColor') {
        format = 'color: ' + param;
    }
    if (ctype == 'link') {
        var stag = '<a href = \"' + prompt('Link url:','http://') + '\">';
        var etag = '</a>';
    }
    else {
        var stag = '<span style=\"' + format + '\">';
        var etag = '</span>';
    }
    ne.innerHTML = ne.innerHTML.replace(sel.toString(),stag + sel.toString() + etag);
}
function mx_geteditnode(n) {
    
    if (typeof(n.id)!='undefined' && n.id.substr(0,9) == 'question_') {
        return(n);
    }
    else if (typeof(n.parentNode)!='undefined' && n.parentNode) {
        return mx_geteditnode(n.parentNode);
    }    
    if (sel.anchorNode != sel.focusNode) {
        // for start, works only if the selection starts and ends in the same node
        alert ('Different node');
        return;
    }
    var format;
    if (ctype == 'fontsize') {
        fontsize = Math.max(1,Math.min(7,parseInt(prompt('Font méret (1-7):','4'))))*3;
        format = 'font-size: ' + fontsize + 'px';
    }
    else if (ctype == 'bold') {
        format = 'font-weight: bold;';
    }
    else if (ctype == 'italic') {
        format = 'font-style: italic';
    }
    else if (ctype == 'underline') {
        format = 'text-decoration: underline;';
    }
    else if (ctype == 'ForeColor') {
        format = 'color: ' + param;
    }
    if (ctype == 'link') {
        var stag = '<a href = \"' + prompt('Link url:','http://') + '\">';
        var etag = '</a>';
    }
    else {
        var stag = '<span style=\"' + format + '\">';
        var etag = '</span>';
    }
    ne.innerHTML = ne.innerHTML.replace(sel.toString(),stag + sel.toString() + etag);
}
function mx_geteditnode(n) {
    
    if (typeof(n.id)!='undefined' && n.id.substr(0,9) == 'question_') {
        return(n);
    }
    else if (typeof(n.parentNode)!='undefined' && n.parentNode) {
        return mx_geteditnode(n.parentNode);
    }    
    else {
        return false;
    }
}
</script>
<form method='post' action='form_in.php' style='border:0; padding:0;' name='feform'>
<input type='hidden' name='action' value='changeelem'>
<input type='hidden' name='form_id' value='$form_id'>
<input type='hidden' name='group_id' value='$group_id'>
<TABLE cellSpacing=1 cellPadding=0 width=\"100%\" class='bgcolor' border=0>\n";

$pgnum=intval($formdata["pages"]);
for ($x=1;$x<=$pgnum;++$x) {
    $r3=mysql_query("select page_id from form_page where form_id='$form_id' and page_id='$x'");
    if ($r3 && mysql_num_rows($r3)==0) {    
        mysql_query("INSERT INTO form_page (group_id, form_id, page_id, boxes)
                     VALUES ('$group_id', '$form_id', '$x', 1)");
    }        
}

if (isset($_GET["cimerror"])) {
    if ($_GET["cimerror"]==1) {
        $errorlist=$word["cimerror1"];
    }
    else if($_GET["cimerror"]==2) {
        $errorlist=$word["cimerror2"];
    }
    else if($_GET["cimerror"]==3) {
        $errorlist=$word["cimerror3"];
    }
    else if($_GET["cimerror"]==4) {
        $errorlist=$word["cimerror4"];
    }    
}

if (!empty($errorlist)) {
    print "<tr><td colspan='6' bgcolor='white'><span class='szovegvastag'>$errorlist</span></td></tr>\n";
}

if (isset($_POST['ssi'])) {
    if (isset($_POST['kep'])) {
        $i_id=array();
        foreach ($_POST['kep'] as $key=>$value) {
            $i_id[]=$key;
        }
        $ids=implode(",",$i_id);
        $query="update form_element set image='$ids' where id=$form_element_id";
    } else {
        $query="update form_element set image='0' where id=$form_element_id";
    }
    mysql_query($query);    
}

if (isset($_POST['rsi']) and isset($_POST['kep'])) {
    foreach ($_POST['kep'] as $key=>$value) {

    }
}

$prev_page=0;
$prev_box=-1;
$i=0;
$bgrnd="bgcolor=white";
echo "<tr><td class='bgkiemelt2' align=left width=13%><span class=szovegvastag>$word[t_varname]</span></td>
      <td class='bgkiemelt2' align=left width=30%><span class=szovegvastag>$word[t_question]/$word[t_hibauzenet]/$word[t_additionaltext]</span></td>
      <td class='bgkiemelt2' align=left width=18%><span class=szovegvastag>$word[t_widget]</span></td>
      <td class='bgkiemelt2' align=left width=6%><span class=szovegvastag>$word[t_mandatory]/<br />$word[t_question_top] - <br />$word[possible_values_bottom]</span></td>
      <td class='bgkiemelt2' align=left width=10%><span class=szovegvastag>$word[t_max_char]/<br />$word[image_position]</span></td>
      <td class='bgkiemelt2' align=left width=23%><span class=szovegvastag>$word[t_actions]</span></td>
      </tr>
      <tr>
      <td $bgrnd align=left><a href='form.php?group_id=$group_id'>&lt;-Vissza</td><td $bgrnd colspan=2>&nbsp;</td><td $bgrnd align=right colspan='3'><span class=szoveg>
      <a href='#' onClick='window.open(\"form_email_ch.php?form_id=$form_id&group_id=$group_id\", \"fefem\", \"width=650,height=500,scrollbars=yes,resizable=yes\"); return false;'>$word[fem_new]</a>
        &nbsp;
      <a href='#' onClick='window.open(\"form_element_verify.php?form_id=$form_id&group_id=$group_id\", \"fefr\", \"width=650,height=500,scrollbars=yes,resizable=yes\"); return false;'>$word[fe_verify]</a>
        &nbsp;
      <a href='#' onClick='windowOpen(\"form_select.php?form_id=$form_id&group_id=$group_id\", \"dfr\", \"width=760,height=500,left=0,top=0,scrollbars=yes,resizable=yes\"); return false;'>$word[fe_new]</a>
        <br>
      <a href='#' onClick='toggle_mandatory(1); return false;'>$word[fe_mandatory_all]</a>
        &nbsp;
      <a href='#' onClick='toggle_mandatory(0); return false;'>$word[fe_mandatory_none]</a>
      
      <a href='form_element_options.php?form_id=302&group_id={$group_id}&page_id=1&rule=global' style='color:#930;'>globális feltétel</a>
      
      </span></td>
      </tr>\n";
$r2=mysql_query("select * from form_email where form_id='$form_id' order by id");
if ($r2 && mysql_num_rows($r2)) {
    print "<tr>
           <td colspan='6' class='bgvilagos2'><span class='szovegvastag'>$word[fems]</span></td>
           </tr>";
    while ($k=mysql_fetch_array($r2)) {
        $fem_base="";
        $r3=mysql_query("select name from sender_base where id='$k[base_id]'");
        if ($r3 && mysql_num_rows($r3)) {
            $fem_base=htmlspecialchars(mysql_result($r3,0,0));
        }
        $fem_sender="";
        $r3=mysql_query("select concat(email,' <',name,'>') as name from user where id='$k[sender_id]'");
        if ($r3 && mysql_num_rows($r3)) {
            $fem_sender=htmlspecialchars(mysql_result($r3,0,0));
        }
        $dependent=mx_display_dep($k,1);
        if ($k["dependency"]) $style_em="style='padding:0 2px; font-weight:bold; background-color:#; color:white;'"; else $style_em="";
        print "<tr>
              <td $bgrnd align=left colspan='5' width='77%'><span class=szoveg>$word[st_base_id]: $fem_base&nbsp;&nbsp;&nbsp;&nbsp;$word[st_sender_id]: $fem_sender</span></td>
              <td $bgrnd align=left width=23%><span class=szoveg><a href='form_in.php?group_id=$group_id&form_email_id=$k[id]&delfem=1'>$word[form_specdel]</a>&nbsp;<a href='#' onClick='window.open(\"form_email_ch.php?form_id=$form_id&group_id=$group_id&form_email_id=$k[id]\", \"fefem$k[id]\", \"width=650,height=500,scrollbars=yes,resizable=yes\"); return false;'>$word[fem_changel]</a><br>
                <a $style_em href='form_element_options.php?form_email_id=$k[id]&group_id=$group_id&form_id=$form_id'>$word[fe_depend]</a></span></td>
              </tr>\n";
    }
}
echo "</table><ul style='padding:0px;' id='phonetic2'>";
$sorszam=0;
$lastpage=0;
$query="select fe.* from form_element fe where fe.form_id='$form_id' order by fe.page, fe.box_id, fe.sortorder";
$res=mysql_query($query);
logger($query,$group_id,"","form_id=$form_id","form_element");                
if ($res && $count=mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        if ($k["dependency"]) {
            $style="style='cursor:pointer; padding:0 2px; font-weight:bold; background-color:#369; color:white;'"; 
        }
        else {
            $style="style='cursor:pointer;'";
        }
        if ($prev_page!=$k["page"]) {
            for ($pg=$prev_page+1;$pg<=$k["page"];$pg++) {
                $inactive="";
                $hasdep="";
                $r3=mysql_query("select active,dependency from form_page where form_id='$form_id' and page_id='$pg'");
                if ($r3 && mysql_num_rows($r3)) {
                    $active=mysql_result($r3,0,0);
                    if ($active=="no") {
                        $inactive=" [$word[fe_inactive]]";
                    }
                    $hasdep_id=mysql_result($r3,0,1);
                    if ($hasdep_id) {
                        $hasdep=" style='color:#930;'";
                    }
                }                
                $modpage="<a name='form_page_$pg' href='form_page_ch.php?group_id=$group_id&form_id=$form_id&page_id=$pg'>$word[iform_page_change]</a>&nbsp;<a $hasdep href='form_element_options.php?form_id=$form_id&group_id=$group_id&page_id=$pg\'>$word[fe_depend]</a> <a onClick=\"window.open('form_generate.php?group_id=$group_id&form_id=$form_id&preview=1&show_page=$pg','preview');\" HREF='#'>$word[iform_preview]</a>";
                if ($pg>0) echo "</table></li>";
                echo "<li style='list-style-type: none;' id='".$pg."'><table cellSpacing=1 cellPadding=0 width=\"100%\" class='bgcolor addborder' border=0><tr>";
                echo "<td colspan='2' class='bgvilagos2'><span name='pagenum' class='szovegvastag'>$pg</span><span class='szovegvastag'>. $word[fe_page]</span><span class='szovegvastag'>&nbsp;&nbsp;<a href='form_elements.php?group_id=$group_id&form_id=$form_id&del_page=$pg'>$word[form_specdel]</a>&nbsp;&nbsp;<a href='form_elements.php?group_id=$group_id&form_id=$form_id&ins_page=$pg'>$word[new_page]</a> </span><br /><span class='szovegvastag'>Display all enum:   <input id='enumSetter' type='checkbox' value='On' name='enum' checked='yes' /></span></td>";
                echo preg_replace("/>\s+</","><","<td class='bgvilagos2' style='vertical-align:middle;'>
                            <a style='cursor:pointer;' onclick='edit_content(\"bold\",false);'><img src='" . $_MX_var->baseUrl . "/" . $_MX_var->application_instance . "/gfx/edit_bold.png' width='16' height='16' border='0' /></a>
                            <a style='cursor:pointer;' onclick='edit_content(\"italic\",false);'><img src='" . $_MX_var->baseUrl . "/" . $_MX_var->application_instance . "/gfx/edit_italic.png' width='16' height='16' border='0' /></a>
                            <a style='cursor:pointer;' onclick='edit_content(\"underline\",false);'><img src='" . $_MX_var->baseUrl . "/" . $_MX_var->application_instance . "/gfx/edit_underline.png' width='16' height='16' border='0' /></a>
                            <a style='cursor:pointer;' onclick='edit_content(\"fontsize\",false);'><img src='" . $_MX_var->baseUrl . "/" . $_MX_var->application_instance . "/gfx/edit_size.png' width='14' height='14' border='0' style='margin:1px;'/></a>
                            <a style='cursor:pointer;' onclick='edit_content(\"link\",false);'><img src='" . $_MX_var->baseUrl . "/" . $_MX_var->application_instance . "/gfx/edit_link.png' width='16' height='7' border='0' style='margin:4px 0;'/></a>
                            <a style='cursor:pointer;' onclick='edit_content(\"ForeColor\",\"#dd0000\");'><img src='" . $_MX_var->baseUrl . "/" . $_MX_var->application_instance . "/gfx/edit_red.png' width='12' height='12' border='0' /></a>
                            <a style='cursor:pointer;' onclick='edit_content(\"ForeColor\",\"#00dd00\");'><img src='" . $_MX_var->baseUrl . "/" . $_MX_var->application_instance . "/gfx/edit_green.png' width='12' height='12' border='0' /></a>
                            <a style='cursor:pointer;' onclick='edit_content(\"ForeColor\",\"#0000dd\");'><img src='" . $_MX_var->baseUrl . "/" . $_MX_var->application_instance . "/gfx/edit_blue.png' width='12' height='12' border='0' /></a>
                            <a style='cursor:pointer;' onclick='edit_content(\"ForeColor\",\"#000000\");'><img src='" . $_MX_var->baseUrl . "/" . $_MX_var->application_instance . "/gfx/edit_black.png' width='12' height='12' border='0' /></a>
                            <a style='cursor:pointer;' onclick='edit_content(\"center\");'><img src='" . $_MX_var->baseUrl . "/" . $_MX_var->application_instance . "/gfx/edit_center.png' width='16' height='16' border='0' /></a>
                            &nbsp;<a style='cursor:pointer;' onclick='edit_content(\"remove\");'><img src='" . $_MX_var->baseUrl . "/" . $_MX_var->application_instance . "/gfx/edit_remove.png' width='16' height='16' border='0' /></a>
                      </td>");
                echo "<td colspan='3' class='bgvilagos2' align='right'><span class='szovegvastag'>$modpage$inactive</span> </td>";
                echo "</tr>";
            }
        }
        if (($prev_box!=$k["box_id"] || $prev_page!=$k["page"]) && $k["box_id"] != '0') {
            $modpage="<a href='form_page_box_ch.php?group_id=$group_id&amp;form_id=$form_id&amp;page_id=$k[page]&amp;box_id=$k[box_id]'>$word[iform_page_box_change]</a>";
            $inactive="";
            $r3=mysql_query("select active from form_page_box where group_id='$group_id' and page_id='$k[page]' and box_id='$k[box_id]'");
            if ($r3 && mysql_num_rows($r3)) {
                $active=mysql_result($r3,0,0);
                if ($active=="no") {
                    $inactive=" [$word[fe_inactive]]";
                }
            }
            echo "<tr>";
            echo "<td colspan='3' class='bgvilagos2'><span class='szovegvastag'>&nbsp;&nbsp;$k[box_id]. $word[fe_box]</span></td>";
            echo "<td colspan='3' class='bgvilagos2' align='right'><span class='szovegvastag'>$modpage$inactive</span></td>";
            echo "</tr>";
        }
        $z=array();
        $r1=mysql_query("select * from demog where id='$k[demog_id]'");
        if ($r1 && mysql_num_rows($r1)) {
            $z=mysql_fetch_array($r1);
        }
        $bgrnd="bgcolor=white";
        $varname=htmlspecialchars($z["variable_name"]) . ($z["code"]?"<br>[" . htmlspecialchars($z["code"]) . "]":"");
        //$question=htmlspecialchars($k["question"],ENT_QUOTES);
        $question = $k["question"];
        $errmsg=htmlspecialchars($k["errmsg"]);
        $additionaltext=htmlspecialchars($k["additionaltext"]);
        $k["mandatory"]=="yes"?$checked="checked":$checked="";    
        $jname=ereg_replace("[\r\n'\"]","",$k["question"]);
		if (strlen($jname)>100) {
			$jname=substr($jname,0,100) . "...";
		}
        $i++;
        if ($i>1) {
            $up="<a href='javascript:dm_sort($k[id],0,\"$jname\")'>$word[fe_up]</a>";
        }    
        else {
            $up="&nbsp;&nbsp;&nbsp;&nbsp;";
        }    
        if ($i<$count || $k["page"]<$formdata["pages"]) {
            $down="<a href='javascript:dm_sort($k[id],1,\"$jname\")'>$word[fe_down]</a>";
        }    
        else {
            $down="";
        }
        if ($z["variable_type"]=="enum") {
            if ($z["multiselect"]=="yes") {
                //$widgeto=array("multiselect","checkbox"); 
                $widgeto=array("checkbox","multiselect");
            }
            else {
                //$widgeto=array("select","radio");
                $widgeto=array("radio","select");
            }
        }
        elseif ($z["variable_type"]=="matrix") {
            if ($z["multiselect"]=="yes") {
                $widgeto=array("checkbox_matrix");
            }
            else {
                $widgeto=array("radio_matrix");
            }
        }
        elseif ($z["variable_type"]=="enum_other") {
            if ($z["multiselect"]=="yes") {
                $widgeto=array("checkbox_other");
            }
            else {
                $widgeto=array("radio_other");
            }
        }
        elseif ($z["variable_type"]=="date") {
            $widgeto=array("datum");
        }
        else {
            $widgeto=array("input","textarea","password");
        }
        array_push($widgeto,'hidden');
        $widgets="";
        for ($wa=0;$wa<count($widgeto);$wa++) {
            $wdg=$widgeto[$wa];
            $wdg==$k["widget"]?$sel="selected":$sel="";
            
            if($z["variable_type"]=="enum"){
              if($wa==0)
                $widgets.="<option selected='selected'>$wdg</option>";
              else
                $widgets.="<option>$wdg</option>";
            } else {
              $widgets.="<option $sel>$wdg</option>"; 
            }
        }        
        $demog_id=$k["demog_id"];
        //$dependent=mx_display_dep($k);
        if ($z['variable_type'] == 'enum_other' || $z['variable_type'] == 'enum' || $z['variable_type'] == 'matrix') {
            $felements = "<a href='#' onClick='windowOpen(\"form_element_enum.php?group_id=$group_id&form_element_id=$k[id]\", \"deefr$k[id]\", \"width=650,height=550,scrollbars=yes,resizable=yes\"); return false;'>$word[fe_elements]</a>";
        } else {
            $felements = '';
        }
        $separator="<a href='form_in.php?group_id=$group_id&form_element_id=$k[id]&addspec=separator#form_element_$k[id]'>".substr($word["form_separator"],0,4).".</a>";
        $comment="<a href='form_in.php?group_id=$group_id&form_element_id=$k[id]&addspec=comment#form_element_$k[id]'>".substr($word["form_comment"],0,4).".</a>";
        $comment.=" <a href='form_in.php?group_id=$group_id&form_element_id=$k[id]&addspec=homepage#form_element_$k[id]'>hp</a>";
        $cim="<a href='form_in.php?group_id=$group_id&form_element_id=$k[id]&addspec=cim#form_element_$k[id]'>".$word["form_cim"]." </a>";
        $cim.=" <a href='form_in.php?group_id=$group_id&form_element_id=$k[id]&addspec=ceg_cim#form_element_$k[id]'>".$word["form_ceg_cim"]." </a>";
        $tel="<a href='form_in.php?group_id=$group_id&form_element_id=$k[id]&addspec=tel#form_element_$k[id]'>".$word["form_tel"]." </a>";
        $mobil="<a href='form_in.php?group_id=$group_id&form_element_id=$k[id]&addspec=mob#form_element_$k[id]'>".$word["form_mob"]." </a>";        
        $captcha="<a href='form_in.php?group_id=$group_id&form_element_id=$k[id]&addspec=captcha#form_element_$k[id]'>".$word["form_captcha"]." </a>";        

        if (in_array($k["widget"],array("separator","comment","cim","ceg_cim","homepage","tel","mob","captcha"))) {
            $selwidgettype="<input type='hidden' name='widget[z$k[id]]' value='$k[widget]'>";
            $varname="&lt; ".$word["form_$k[widget]"]." &gt;";
            if ($k["widget"]=="homepage") {
                $varname="&lt; homepage &gt;";
            }
        }
        else {
            $selwidgettype="<select name='widget[z$k[id]]'>$widgets</select>";
        }
        if ($k["widget"]=="separator" || $k["widget"]=="comment" || $k["widget"]=="homepage" || $k["widget"]=="captcha") {
            $selmandatory="&nbsp;&nbsp;&nbsp;-";
        }
        else {
            $selmandatory="<input type='checkbox' value=\"yes\" name='mandatory[z$k[id]]' $checked>";
        }
        $feqp=$k["question_position"]=="above"?" checked":"";
        $selmandatory.="<br /><input type='checkbox' value=\"yes\" name='question_pos[z$k[id]]' $feqp>";
        if ($k["widget"]=="radio_matrix" || $k["widget"]=="checkbox_matrix") {
            $fepvb=$k["possible_values"]=="bottom"?" checked":"";                
            $selmandatory.="&nbsp;-&nbsp;<input title='$word[possible_values_bottom_title]' type='checkbox' value=\"yes\" name='pvalues_bottom[z$k[id]]' $fepvb>";        
        }            

        $selquestion="<input type='hidden' name='question[z$k[id]]'><div contenteditable='true' id='question_$k[id]' style='border: 1px #ddd solid; padding:4px;'>$question</div>";
        if ($k["widget"]=="separator") {
            $selquestion="";
            $selerrmsg="";
            $depsub="";
        }
        elseif ($k["widget"]=="comment") {
            // $selquestion="<textarea onmousedown='this.focus()' cols='40' rows='3' name='question[z$k[id]]' wrap='virtual'>$question</textarea>";
            $selerrmsg="";
            $depsub="";
        }
        else {
            $q="select fe.* from form_element_subscribe fe where fe.form_id='$form_id' and form_element_id=$k[id]";
            $re=mysql_query($q);
            $kk=mysql_fetch_array($re);
            if ($kk["dependency"]) {
                $sty="style='padding:0 2px; font-weight:bold; background-color:#369; color:white;'"; 
            }
            else {
                $sty="";
            }
            // $selquestion="<input size='50' onmousedown='this.focus()' type='text' value=\"$question\" name='question[z$k[id]]'>";
            $selerrmsg="<br><input size='50' onmousedown='this.focus()' type='text' value=\"$errmsg\" name='errmsg[z$k[id]]'>";
            $depsub="<a name='form_element_subscribe_$k[id]' $sty href='form_element_options.php?form_element_id=$k[id]&subscribe_dep=1&group_id=$group_id&form_id=$form_id'>$word[fe_depend_subscribe]</a>";
        }
        if ($k["widget"]=="input" || $k["widget"]=="homepage") {
            $seladditionaltext="<br><input onmousedown='this.focus()' size='50' type='text' value=\"$additionaltext\" name='additionaltext[z$k[id]]'>";
        }
        else {
            $seladditionaltext="";
        }

        if (in_array($k["widget"],array("input","textarea","password"))) {
            $maxlength="<input size='4' onmousedown='this.focus()' type='text' value=\"$k[maxlength]\" name='maxlength[z$k[id]]'>";
        }
        else {
            $maxlength="-";
        }
        $sorszam++;
        if ($k["image"]!=0) {
            $style_im="style='padding:0 2px; font-weight:bold; background-color:#369; color:white;'"; 
        }
        else {
            $style_im="";
        }
        if (!empty($k["parent_dependency"])) {
            $style_po="style='padding:0 2px; font-weight:bold; background-color:#369; color:white;'"; 
        }
        else {
            $style_po="";
        }
        if ($style_im!="") {
            $ip=array ("above","before","below");
            $maxlength.="<br /><select name='image_pos[$k[id]]'>";
            foreach ($ip as $ipv) {
                $sel=$k["image_position"]==$ipv?"selected":"";
                $maxlength.="<option $sel value='$ipv'>$word[$ipv]</option>";
            }
            $maxlength.="</select>";
        }
        else
            $maxlength.="<br />-";
        echo "<tr>
              <td $bgrnd align=left width=13%><span class=szoveg>$sorszam. $varname</span></td>
              <td $bgrnd align=left width=30%><span class=szoveg>$selquestion$selerrmsg$seladditionaltext</span></td>
              <td $bgrnd align=left width=18%><span class=szoveg><a href='form_in.php?group_id=$group_id&form_element_id=$k[id]&delspec=1'>$word[form_specdel]</a> $selwidgettype $felements</span></td>
              <td $bgrnd align=left width=6%><span class=szoveg>$selmandatory</span></td>
              <td $bgrnd align=left width=10%><span class=szoveg>$maxlength</span></td>
              <td $bgrnd align=left width=23%><span class=szoveg>$separator $comment $cim $tel $mobil $captcha $up $down<br>$depsub [<a $style onclick=\"form_element_dep('form_element_options.php?form_element_id=$k[id]&group_id=$group_id&form_id=$form_id')\">$word[fe_depend]</a><input type='checkbox' name='copy_dependency_$k[id]'>]</span>
                  <a $style_im href='form_element_images.php?form_id=$form_id&group_id=$group_id&form_element_id=$k[id]'>$word[fe_choice_image]</a> 
              <a name='form_element_$k[id]' $style_po href='form_element_options.php?form_element_id=$k[id]&group_id=$group_id&form_id=$form_id'>$word[parent_object]</a></td>
              </tr>";
        $prev_page=$k["page"];
        $prev_box=$k["box_id"];
    }
    if ($prev_page!=$formdata["pages"]) {
        for ($pg=$prev_page+1;$pg<=$formdata["pages"];$pg++) {
                $inactive="";
                $hasdep="";
                $r3=mysql_query("select active,dependency from form_page where form_id='$form_id' and page_id='$pg'");
                if ($r3 && mysql_num_rows($r3)) {
                    $active=mysql_result($r3,0,0);
                    if ($active=="no") {
                        $inactive=" [$word[fe_inactive]]";
                    }
                    $hasdep_id=mysql_result($r3,0,1);
                    if ($hasdep_id) {
                        $hasdep=" style='color:#930;'";
                    }
                }
            $modpage="<a href='form_page_ch.php?group_id=$group_id&form_id=$form_id&page_id=$pg'>$word[iform_page_change]</a>&nbsp;<a $hasdep href='#' onClick='window.open(\"form_element_options.php?form_id=$form_id&group_id=$group_id&page_id=$pg\", \"deefr$k[id]\", \"width=500,height=450,scrollbars=yes,resizable=yes\"); return false;'>$word[fe_depend]</a> <a onClick=\"window.open('form_generate.php?group_id=$group_id&form_id=$form_id&preview=1&show_page=$pg','preview');\" HREF='#'>$word[iform_preview]</a>";
            if ($pg>0) echo "</table></li>";            
            echo "<li style='list-style-type: none;' id='$pg'><table cellSpacing=1 cellPadding=0 width=\"100%\" class='bgcolor' border=0><tr>";
            echo "<td colspan='3' class='bgvilagos2'><span name='pagenum' class='szovegvastag'>$pg</span><span class='szovegvastag'>. $word[fe_page]</span></td>";
            echo "<td colspan='3' class='bgvilagos2' align='right'><span class='szovegvastag'>$modpage</span></td>";
            echo "</tr>";
        }
    }
    echo "</ul><TABLE cellSpacing=1 cellPadding=0 width=\"100%\" class='bgcolor' border=0><tr>
        <td align=center colspan='6'>
        <input type=button class='tovabbgomb' value='$word[submit3]' onclick='fesubmit();'>
        </td>
        </tr></table>";
}
else {
    echo "<tr>
          <td bgcolor='white' align=right colspan='6'><span class=szoveg>
          <a href='#' onClick='window.open(\"form_select.php?form_id=$form_id&group_id=$group_id\", \"dfr\", \"width=650,height=500,scrollbars=yes,resizable=yes\"); return false;'>$word[fe_new]</a>&nbsp;</span></td>
          </tr>";
}
print "</table></form>";
include "footer.php";


function mx_display_dep(&$k,$from=0) {

    global $_MX_var,$word;

    if (empty($k["dependent_value"])) { 
        return $from?$word["fem_always"]:$word["fe_always"];
    }
    else {
        $dependent=$k["dependent_value"];
    }
    $dep_widget="";
    $rws=mysql_query("select * from demog where id='$k[dependent_id]'");
    if ($rws && mysql_num_rows($rws)) {
        $z=mysql_fetch_array($rws);
        if ($z["variable_type"]=="enum" || $z["variable_type"]=="matrix") {
            if ($k["dependent_value"]=="*") {
                $dependent="($word[any_option])";
            }
            else {
                $depp=array();
                $opts=implode(",",split("[,_]",$k["dependent_value"]));
                $oval=array();
                $rww=mysql_query("select id,enum_option from demog_enumvals where id in ($opts)");
                if ($rww && mysql_num_rows($rww)) {
                    while ($w=mysql_fetch_array($rww)) {
                        $oval["$w[id]"]=$w["enum_option"];
                    }
                }
                $dp=explode(",",$k["dependent_value"]);
                foreach ($dp as $dd) {
                    $ddd=array();
                    $dd2=explode("_",$dd);
                    foreach ($dd2 as $dd3) {
                        $ddd[]=$oval["$dd3"];
                    }
                    $depp[]="'". implode("=",$ddd) ."'";
                }
                $dependent=implode(" vagy ",$depp);
            }
        }
    }
    return htmlspecialchars("$z[variable_name] = $dependent");
}

?>
