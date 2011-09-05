<? //print_r($_POST); print_r($_GET); die();
include "auth.php";
include "decode.php";
$weare=34;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/filter.lang";
include "./lang/$language/form.lang";
include "menugen.php";
include "_form.php";

$form_id=get_http("form_id",0);
$copy_dependency_ids=get_http("copy_dependency_ids",0);
$copy_dependencies_to=array();
$copy_dependencies_to_names=array();
$form_element_id=get_http("form_element_id",0);
$group_id=get_http("group_id",0);
$subscribe_dep=get_http("subscribe_dep",0);
$form_email_id=get_http("form_email_id",0);
$form_endlink_id=get_http("form_endlink_id",0);
$form_element_subscribe_id=get_http("form_element_subscribe_id",0);
$page_id=get_http("page_id",0);
$action=get_http("action","");

$rule=get_http("rule","");

$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'"); //echo "select * from form where id='$form_id' and group_id='$group_id'"; die();
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
if ($subscribe_dep!=1) {
    $subscribe_dep=0;
}

$parent_row_or_col_org=$parent_identify_org=$parent_showsingle_org=$parent_page_inherit_org="";
$parent_always_org=array();
$form_element_id=slasher($form_element_id);
$form_email_id=slasher($form_email_id);

$handle_parents=0;

$dependency = "";
$parent_dependency = "";
if ($subscribe_dep) {   // subscribe to group depending on `form_element_id` value
    $query = mysql_query("select f.*,e.question as fqu,d.question as dqu,d.id as did
                          from form f,form_element e,demog d where f.group_id='$group_id'
                          and f.id=e.form_id and e.id='$form_element_id' and e.demog_id=d.id");
    if ($query && mysql_num_rows($query)) {
        $formdata = mysql_fetch_array($query);
        $form_id=$formdata["id"];
        $formdata["question"]=strlen($formdata["fqu"])?$formdata["fqu"]:$formdata["dqu"];
        $formdata["question"].=" > ". $word["fe_depend_subscribe"];
        $dependent_id=$formdata["did"];
        $dep_object="form_element_subscribe";
    }
    else {
        exit;
    }
    $query = mysql_query("select id,groups,dependency from form_element_subscribe where form_element_id='$form_element_id' and form_id='$form_id'");
    if ($query && mysql_num_rows($query)) {
        $fesk = mysql_fetch_array($query); 
        $form_element_subscribe_id=$fesk["id"];
        $fes_groups=$fesk["groups"];
        $dependency = $_MX_form->get_dependency($fesk,"element_subscribe","expression");
    }
    else {
        $fes_groups="";
        $r=mysql_query("insert into form_element_subscribe (form_id,form_element_id,dependent_id) values ('$form_id','$form_element_id','$formdata[did]')");
        if ($r) {
            $form_element_subscribe_id=mysql_insert_id();
            if (!$form_element_subscribe_id) {
                exit;
            }
        }
        else {
            exit;
        }
    }
    $qy="form_element_subscribe_id=$form_element_subscribe_id";
    $dep_id=$form_element_subscribe_id;
}
elseif ($form_endlink_id) {
    $res=mysql_query("select f.*,fe.title as endlinktitle,fe.dependency from form f,form_endlink fe where fe.id='$form_endlink_id' and f.group_id='$group_id' and fe.form_id=f.id");
    if ($res && mysql_num_rows($res)) {
        $formdata=mysql_fetch_array($res);
        $dep_object="form_endlink";
        $dep_id=$form_endlink_id;
        $qy="form_endlink_id=$dep_id";
        $form_id=$formdata["id"];
        $formdata["question"]=$formdata["endlinktitle"] . " endlink";
        $dependency = $_MX_form->get_dependency($formdata,"endlink","expression");
    }
    else {
        exit;
    }
}
elseif ($form_email_id) {
    $res=mysql_query("select f.*,fe.base_id,fe.dependency from form f,form_email fe where fe.id='$form_email_id' and f.group_id='$group_id' and fe.form_id=f.id");
    if ($res && mysql_num_rows($res)) {
        $formdata=mysql_fetch_array($res);
        $dep_object="form_email";
        $dep_id=$form_email_id;
        $qy="form_email_id=$dep_id";
        $form_id=$formdata["id"];
        $r3=mysql_query("select name from sender_base where id='$formdata[base_id]'");
        if ($r3 && mysql_num_rows($r3)) {
            $formdata["question"]=htmlspecialchars(mysql_result($r3,0,0));
        }
        $dependency = $_MX_form->get_dependency($formdata,"email","expression");
    }
    else {
        exit;
    }
}
elseif ($form_element_id) {
    $res=mysql_query("select f.*,fe.question,fe.dependency,fe.parent,fe.parent_dependency,fe.widget,fe.demog_id,fe.page from form f,form_element fe 
        where fe.id='$form_element_id' and f.group_id='$group_id' and fe.form_id=f.id");
    if ($res && mysql_num_rows($res)) {
        $formdata=mysql_fetch_array($res);
        $dep_object="form_element";
        $dep_id=$form_element_id;
        $demog_id=$formdata["demog_id"];
        $dres=mysql_query("select variable_name from demog where id='$demog_id'");
        if ($dres && mysql_num_rows($dres)) {
            $dep_object_variable_name=mysql_result($dres,0,0);
        }
        $qy="form_element_id=$dep_id";
        $form_id=$formdata["id"];
        if (preg_match("/^[0-9]+(,[0-9]+)*$/",$copy_dependency_ids)) {
            $rcd = mysql_query("select id,question from form_element where form_id='$form_id' and id!='$form_element_id' and id in ($copy_dependency_ids)");
            while ($kcd = mysql_fetch_array($rcd)) {
                $copy_dependencies_to[]= $kcd["id"];
                $copy_dependencies_to_names[]= $kcd["question"];
            }
        }
        if (isset($dep_object_variable_name) && in_array($formdata["widget"],$_MX_form->enum_widgets) && count($copy_dependencies_to)==0) {
            // handle parents only for enum widgets and only if we are not setting dependencies for multiple elements
            $handle_parents=1;
        }
        if ($handle_parents && !empty($formdata["parent"])) {
            $vname=explode("|:|",$formdata["parent"]);
            if (isset($vname[2])) $parent_row_or_col_org=$vname[2];
            if (isset($vname[4])) {
                $parent_always_org=explode(",",$vname[4]);
            }
            if (isset($vname[5])) $parent_identify_org=$vname[5];
            if (isset($vname[6])) $parent_showsingle_org=$vname[6];
        }
        $inhr=mysql_query("select parent_dependency from form_page where form_id=$form_id and page_id='$formdata[page]'");
        if ($inhr && mysql_num_rows($inhr)) {
            if (mysql_result($inhr,0,0)==$dep_object_variable_name) {
                $parent_page_inherit_org=1;
            }
        }
        $dependency = $_MX_form->get_dependency($formdata,"element","expression");
        $parent_dependency = $_MX_form->get_dependency($formdata,"element","expression",1);
    }
    else {
        exit;
    }
}
else {
    $form_id=slasher($form_id);
    $query = mysql_query("SELECT pages,title FROM form WHERE id = '$form_id' AND group_id = '$group_id'");
    if ($query && mysql_num_rows($query)) {
        $formdata = mysql_fetch_array($query);
        $pages = $formdata['pages'];
        $dep_object="form_page";
    }
    else {
        exit;
    }
    if (!empty($_GET["page_id"])) {
        $page_id = abs(intval($_GET["page_id"]));
    }
    else {
        $page_id = abs(intval($_POST["page_id"]));
    }
    $query = mysql_query("SELECT page_id,dependency FROM form_page WHERE group_id = '$group_id' AND form_id = '$form_id' AND page_id = '$page_id'");
    if (!($query && mysql_num_rows($query))) {
        if ($page_id > 0 && $page_id <= $pages) {
            mysql_query("INSERT INTO form_page (group_id, form_id, page_id) VALUES ('$group_id', '$form_id', '$page_id')");
        } 
        else {
            exit;
        }
    }
    $formdata = mysql_fetch_array($query);
    $dependency = $_MX_form->get_dependency($formdata,"page","expression");
    $dep_id=$page_id;
    $qy="page_id=$page_id, form_id=$form_id";
    $formdata["question"]="$page_id. oldal";
}

$error=array();
$qys=str_replace(",", " and ",$qy);
$subscribe_groups=array();
if ($action=="enter") {
    $grpprt="";
    if ($subscribe_dep) {
        $groups=slasher($_POST["groups"]);
        $overify=explode("\n",$groups);
        for ($i=0;$i<count($overify);$i++) {
            $vergr=mysql_escape_string(str_replace("\r","",$overify[$i]));
            if (!empty($vergr)) {
                $vres=mysql_query("select title from groups where title='$vergr'");
                if ($vres && mysql_num_rows($vres) && !in_array($vergr,$subscribe_groups)) {
                    $subscribe_groups[]=$vergr;
                }
                else {
                    $error[]="$word[fe_depend_subscribe_noex_group]: $vergr";
                }
            }
        }
        $grpprt=",groups='$groups'";
        $fes_groups=$groups;
    }
    if ($handle_parents) {
        reset ($_POST);
        $parent_elements = array();
        $parent_always_items = array();
        $parent_dependency_negation = array();
        $parent_dependency_ids = array();
        while (list($dvkey,$dvval)=each($_POST)) {
            if (ereg("^parent_([0-9]+)$",$dvkey,$regs)) {
                $parent_id=$regs[1];
                $parent_elements[]=$parent_id;
                $parent_columns = get_http("parent_columns_$parent_id","");
                $parent_neg = get_http("parent_neg_$parent_id","");
                $parent_neg = $parent_neg ? "!" : "";
                if ($parent_neg == "!") {
                    $parent_dependency_negation[]= $parent_id;
                }
                $preg = mysql_query("select id from form_element_parent_dep where form_element_id=$form_element_id and parent_id=$parent_id");
                if ($preg && mysql_num_rows($preg)) {
                    $form_element_parent_dep_id = mysql_result($preg,0,0);
                    mysql_query("update form_element_parent_dep set parent_columns='$parent_columns',neg='$parent_neg' where form_element_id=$form_element_id and parent_id=$parent_id");
                }
                else {
                    mysql_query("insert into form_element_parent_dep set form_element_id=$form_element_id,parent_id=$parent_id,parent_columns='$parent_columns',neg='$parent_neg'");
                    $form_element_parent_dep_id = mysql_insert_id();
                }
                $parent_dependency_ids["$parent_id"] = $form_element_parent_dep_id;
            }
            elseif (ereg("^parent_always_([0-9]+)$",$dvkey,$regs)) {
                $parent_always_items[]=$regs[1];
            }
        }
        $js_parent_dependency = $_MX_form->set_dependency($_POST["parent_dependency"],$parent_dependency_ids,$parent_dependency_negation,1);
        if (preg_match("/^error:(.+)$/i",$js_parent_dependency,$regs)) {
            $error[]= "Hiba: Logikai kapcsolat a szülő objektumok között: $regs[1]";
            $parent_dependency = $_POST["parent_dependency"];
        }
        else {
            $parent_dependency = $js_parent_dependency;
            $set_page_inherit = 0;
            if (count($parent_elements)) {
                $deletewhereadd=" and parent_id not in (" . implode(",",$parent_elements) . ")";
                $parent_always = implode(",",$parent_always_items);
                $parent_row_or_col = get_http("parent_row_or_col","");
                $parent_identify = isset($_POST["parent_identify"]) ? "name" : "seq";
                $parent_showsingle = isset($_POST["parent_showsingle"]) ? "0" : "1";
                if (isset($_POST["parent_page_inherit"])) {
                    $set_page_inherit = 1;
                }
                $parent="|:||:|$parent_row_or_col|:||:|$parent_always|:|$parent_identify|:|$parent_showsingle";
            }
            else {
                $deletewhereadd="";
                $parent="";
            }
            mysql_query("delete from form_element_parent_dep where form_element_id=$form_element_id$deletewhereadd");
            mysql_query("update form_element set parent='$parent',parent_dependency='$parent_dependency' where id=$form_element_id");
            // this will not work well if there is more than one element in the page that have parents. but we use this only when there is one such element in the page
            mysql_query("update form_page set parent_dependency='" . ($set_page_inherit ? "$dep_object_variable_name" : "") . "' where page_id=$formdata[page] and form_id=$form_id");
        }
    }
    $dependency_values=array();
    $dependency_negation=array();
    reset ($_POST);
    while (list($dvkey,$dvval)=each($_POST)) {
        if (preg_match("/dv([0-9]+)(_[0-9*]+)?(_[0-9]+)?/",$dvkey,$regs)) {
            if ($dvval != "" || !empty($_POST["dn$regs[1]"])) {
                if (!empty($regs[3])) {
                    $value = str_replace("_","",$regs[2]) . $regs[3];
                }
                elseif (!empty($regs[2])) {
                    switch ($regs[2]) {
                        case "_0": $value = "*"; break;
                        case "_*2": $value = "*2"; break;
                        default : $value = str_replace("_","",$regs[2]);
                    }
                }
                else {
                    $value = mysql_escape_string($dvval);
                }
                if (!isset($dependency_values["$regs[1]"])) {
                    $dependency_values["$regs[1]"]=array();
                }
                $dependency_values["$regs[1]"][]=$value;
                if (!empty($_POST["dn$regs[1]"])) {
                    $dependency_negation[] = $regs[1];
                }
            }
        }
    }
//print_r( $dependency_values ); die(); 
    if (count($error)==0) {
        $qys_list = array($qys);
        $dep_id_list = array($dep_id);
        foreach ($copy_dependencies_to as $copy_form_element_id) {
            $qys_list[]="form_element_id = $copy_form_element_id";
            $dep_id_list[]=$copy_form_element_id;
        }
        for ($qi=0; $qi<count($qys_list); $qi++) {
            $qys_item = $qys_list[$qi];
            $dep_id_item = $dep_id_list[$qi];
            $dependency_ids = array();
            foreach ($dependency_values as $key=>$values) {
                $r=mysql_query("select id from ".$dep_object."_dep where $qys_item and dependent_id=$key"); //echo "select id from ".$dep_object."_dep where $qys_item and dependent_id=$key<br />\n";
                $fed=mysql_fetch_array($r);
                $ng="";
                if (in_array($key,$dependency_negation)) {
                    $ng="!";
                }
                $value = implode(",",$values);
                if ($r && mysql_num_rows($r)) {
                    $query="update ".$dep_object."_dep set dependent_value='$value',neg='$ng' where $qys_item and dependent_id=$key";
                    mysql_query($query); echo "ez simple: {$query}<br />";
                    $dependency_ids["$key"]=mysql_result($r,0,0);
                }
                else {
                    $query="insert into ".$dep_object."_dep set dependent_id='$key',dependent_value='$value'," . str_replace(" and ",",",$qys_item) . ", neg='$ng'";
                    mysql_query($query); echo "az simple: {$query}<br />";
                    $dependency_ids["$key"]=mysql_insert_id();
                }
            }
            
            if(isset($rule) && $rule == "global"){
              for($i=1;$i<=$pages;$i++) {
                $query="delete from ".$dep_object."_dep where form_id = {$form_id} and page_id = {$i}";
                if (count(array_keys($dependency_values))) {
                    $query .= " and dependent_id not in (" . implode(",",array_keys($dependency_values)) . ")";
                } echo "delete stuff : $query<br />";
                mysql_query($query);
              }
            } else {
              for($i=1;$i<=$pages;$i++) {
                $query="delete from ".$dep_object."_dep where form_id = {$form_id} and page_id = {$i}";
                if (count(array_keys($dependency_values))) {
                    $query .= " and dependent_id not in (" . implode(",",array_keys($dependency_values)) . ")";
                } echo "delete simple : $query<br />";
                mysql_query($query);
              }
            }
            
            $js_dependency = $_MX_form->set_dependency($_POST["dependency"],$dependency_ids,$dependency_negation);
            if (preg_match("/^error:(.+)$/i",$js_dependency,$regs)) {
                $error[]= "Hiba: Logikai kapcsolat a feltételek között: $regs[1]";
                $dependency = $_POST["dependency"];
            }
            else {
                $query="update $dep_object set dependency='$js_dependency'$grpprt where id=$dep_id_item";
                if ($page_id) { 
                    $query="update $dep_object set dependency='$js_dependency' where page_id=$page_id and group_id=$group_id and form_id=$form_id";
                }
                mysql_query($query);
            }
        } //echo "dddddd:  {$form_id}"; die();
        if (count($error)==0) {
            print "<script>
            window.location='form_" . ($dep_object=="form_endlink"?"ch":"elements") . ".php?form_id=$form_id&group_id=$group_id#$dep_object" 
                    . "_" . ($dep_object=="form_element_subscribe"?$form_element_id:$dep_id) . "';
            </script>";
            exit;
        }
    }
}
echo "<a href='form_elements.php?group_id=$group_id&form_id=$form_id'>&lt;-Vissza</a>";
printhead($rule);

if (count($error)) {
    echo "<tr>
    <td bgcolor='white' style='border:1px #bbb solid;color:red;font-weight:bold;padding:12px;'>". implode(",",$error) ."&nbsp;</td>
    </tr>";
}
echo "<tr>
<td bgcolor='white' align=left><span id='cv' class='szoveg cp' onclick='chast(\"ext\");'>+".$word['ext_view']."</span></td>          
</tr>\n";

$dep=array();
$res=mysql_query("select * from ".$dep_object."_dep where $qys");
while ($w=mysql_fetch_array($res)) {
    $dep[$w["dependent_id"]]=$w;
}

if ($handle_parents) {
    $parent_dep=array();
    $res=mysql_query("select * from form_element_parent_dep where $qys");
    while ($w=mysql_fetch_array($res)) {
        $parent_dep[$w["parent_id"]]=$w;
    }
}

$widget_list="";
if ($form_element_subscribe_id) {
    $widget_list.="<input type='hidden' name='subscribe_dep' value='1'>
                  $word[fe_depend_subscribe_groups]:<br><textarea name='groups'>". htmlspecialchars($fes_groups) ."</textarea><br>$word[fe_depend_subscribe_condition]:";
}

$sorszam=1;
$bcalt="";
$js_data=array();
$widget_list .= "<table style='width:100%'>";
$r2=mysql_query("select fe.id,fe.demog_id,fe.question,fe.widget,fe.dependency,fe.parent,d.variable_name,d.variable_type,d.code,d.question as demog_question
                 from form_element fe left join demog d on fe.demog_id=d.id where form_id='$form_id' order by page,box_id,sortorder");
while ($w=mysql_fetch_array($r2)) {
	if ((!$subscribe_dep && $w["id"]!=$form_element_id && !in_array($w["id"],$copy_dependencies_to) 
                         && !in_array($w["widget"],array('comment','separator','cim','ceg_cim','captcha'))) 
			|| $subscribe_dep && $w["id"]==$form_element_id) {                                 
        if (isset($_MX_form->spec_widget_ids["$w[widget]"])) {
            $dependent_id=$_MX_form->spec_widget_ids["$w[widget]"];
        }
        else {
            $dependent_id=$w["demog_id"];                    
        }
        $depwidget=$dis_two=$dis_neg=$dis=$dis_any=$sel_any=$sel_two=$parent_widget=$parent_columns=$parent_neg=$is_parent=$dependent_value=$has_negation="";
        if (isset($dep[$dependent_id])) {
            $dependent_value = $dep[$dependent_id]["dependent_value"];
            $has_negation = $dep[$dependent_id]["neg"]=="!";
        }
        $has_dependency_setting = !empty($dependent_value) || $has_negation;
        $has_parent_setting = isset($parent_dep["$dependent_id"]);
        if ($has_parent_setting) {
            $is_parent="checked";
            $parent_columns = $parent_dep["$dependent_id"]["parent_columns"];
            $parent_neg = $parent_dep["$dependent_id"]["neg"]=="!" ? "checked" : "";
        }
        $js_data_parts = array();
        //if (in_array($w["widget"],array("multiselect","checkbox","radio","select"))) {
        if ($w["variable_type"]=="enum") {
            $dependent_array=explode(",",$dependent_value);
            if ($dependent_array[0]=="*") {
                $sel_any="checked"; 
                $dis="disabled=true"; 
                $dis_two="disabled=true";
            }
            elseif ($dependent_array[0]=="*2") {
                $sel_two="checked"; 
                $dis_neg="disabled=true"; 
                $dis="disabled=true"; 
                $dis_any="disabled=true";
            }
            $widget_id = "dv".$dependent_id."_0";
            $js_data_parts[]= "'$widget_id'";
            $depwidget.="<input type='checkbox' $dis_any onclick='control($dependent_id,this,0);deplog();' name='$widget_id' id='$widget_id' value='1' $sel_any> <b>$word[any_option]</b><br>";
            $widget_id = "dv".$dependent_id."_*2";
            $js_data_parts[]= "'$widget_id'";
            $depwidget.="<input type='checkbox' $dis_two onclick='control($dependent_id,this,1);deplog();' name='$widget_id' id='$widget_id' value='1' $sel_two> <b>$word[two_option]</b><br>"; 
            $r3=mysql_query("select * from demog_enumvals where demog_id='$dependent_id' and deleted='no'");
            if ($r3 && mysql_num_rows($r3)) {
                while ($k3=mysql_fetch_array($r3)) {
                    if (in_array($k3["id"],$dependent_array)) {
                        $sel="checked";
                    }
                    else {
                        $sel="";
                    }
                    $ev=htmlspecialchars("$k3[code] | $k3[enum_option]");
                    $widget_id = "dv".$dependent_id."_$k3[id]";
                    $js_data_parts[]= "'$widget_id'";
                    $depwidget.="<input type='checkbox' $dis name='$widget_id' id='$widget_id' value='1' $sel onclick='deplog(0);'> $ev<br>";
                }
            }
        }
        elseif (in_array($w["widget"],array("checkbox_matrix","radio_matrix"))) {
            $rows = array();
            $cols = array();
            $dependent_array=explode(",",$dependent_value);
            $depwidget="<table border=0 cellspacing=1 cellpadding=1 width='100%' style='border: 1px solid $_MX_var->main_table_border_color; background-color: $_MX_var->main_table_border_color;'>
                        <tr><td class='bgwhite'> </td>\n";
            $r3=mysql_query("select id,enum_option,vertical,code from demog_enumvals where demog_id='$dependent_id' and deleted='no'");
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
                    $widget_id = "dv".$dependent_id."_$value";
                    $js_data_parts[]= "'$widget_id'";
                    $depwidget.="<td class='bgwhite'><input type='checkbox' name='$widget_id' id='$widget_id' value='1' $sel onclick='deplog(0);'></td>\n";
                }
            }
            $depwidget.="</tr></table>"; 
            if ($handle_parents) {
                $parent_widget="<br>$word[which_column_depends] <input type='text' size='3' value='$parent_columns' name='parent_columns_$dependent_id'>";
            }
        }
        else {
            $widget_id="dv$dependent_id";
            $js_data_parts[]= "'$widget_id'";
            $depwidget="<input name='$widget_id' id='$widget_id' value=\"" . htmlspecialchars($dependent_value) . "\" onchange='deplog(0);'>";
        }
        if ($handle_parents) {
            $parent_widget="<hr>
                <input type='checkbox' id='parent_$dependent_id' $is_parent name='parent_$dependent_id' onclick='deplog(1);'> $word[parent_question] &nbsp;&nbsp;
                <input type='checkbox' $parent_neg name='parent_neg_$dependent_id'> ($word[neg])
                $parent_widget";
        }
        $widget_neg_id="dn$dependent_id";
        array_unshift($js_data_parts,"'$widget_neg_id'");
        array_unshift($js_data_parts,"'$w[variable_name]'");
        $js_data["$dependent_id"] = $js_data_parts;
        $question = htmlspecialchars($w["question"]);
        if (empty($question)) {
            $question = htmlspecialchars($w["demog_question"]);
        }
        if (!empty($w["code"])) {
            $question = htmlspecialchars("[$w[code]]") . $question;
        }
        $vis = $has_dependency_setting || $has_parent_setting ? "block" : "none";
        $bc = $has_dependency_setting || $has_parent_setting ? "vkek" : $bcalt;
        $widget_list.="<tr class='$bc'><td style='border-top:1px #aaa solid;'><span onclick='chst($dependent_id);' class='cp'>$sorszam.</td>
                           <td style='padding:0 8px; border-top:1px #aaa solid;'><span onclick='chst($dependent_id);' class='cp'>". htmlspecialchars($w["variable_name"]) . "</td>
                           <td style='border-top:1px #aaa solid;'><span onclick='chst($dependent_id);' class='cp'>$question</td>
                       </tr>
                       <tr class='$bc'><td colspan='3'><div id='$dependent_id' style='padding: 0 0 5 30px; display:$vis;'>
                           <input type='checkbox' $dis_neg value=1 id='$widget_neg_id' name='$widget_neg_id' onclick='deplog(0);'" . ($has_negation?" checked":"") . "> $word[neg]
                           <br>$depwidget<br>$parent_widget
                           </div>
                       </td></tr>";
        $bcalt = $bcalt == "" ? "grey" : "";
    }
    $sorszam++;
}
$widget_list .= "</table>\n<script>\nvar d_ids = new Array(" . implode(",",array_keys($js_data)) . ");\n";
foreach ($js_data as $js_depid=>$js_depwidgets) {
    $widget_list .= "d_data$js_depid = new Array(" . implode(",",$js_depwidgets) . ");\n";
}
$widget_list .= "</script>";

echo "<tr>
      <td bgcolor='white' align=left>$widget_list</td>
      </tr>\n";

// Parent data bound to the child object
if ($handle_parents) {
    $parent_always_dep="";
    if (in_array($formdata["widget"],array("checkbox","radio","select","multiselect"))) {
        $r3=mysql_query("select id,code,enum_option,vertical from demog_enumvals where demog_id='$demog_id' and deleted='no'");
        $parent_always_dep="<br>";
        if ($r3 && mysql_num_rows($r3)) {        
            while ($k3=mysql_fetch_array($r3)) {            
                if (in_array($k3["id"],$parent_always_org)) {
                    $ch="checked"; 
                }
                else {
                    $ch="";
                }
                $parent_always_dep.="<input type='checkbox' $ch name='parent_always_$k3[id]'> $k3[code] | $k3[enum_option]<br>";        
            }                
        }
        $parent_always_dep="<br>$word[question_always_displayed]$parent_always_dep";
    }                        

    $parent_row_or_col_dep="";
    if (in_array($formdata["widget"],array("checkbox_matrix","radio_matrix"))) {
        $chcol = $parent_row_or_col_org=="column" ? "checked" : "";
        $chrow = $parent_row_or_col_org=="column" ? "" : "checked";
        $parent_row_or_col_dep="<br>A mátrix <input type='radio' value='row' name='parent_row_or_col' $chrow>sorai&nbsp;&nbsp;&nbsp;<input type='radio' $chcol value='column' name='parent_row_or_col'>oszlopai függnek a szülő objektumoktól";
    }        
    $checked = $parent_identify_org=="name" ? "checked" : "";
    $parent_identify_dep="<br><input type='checkbox' name='parent_identify' $checked>&nbsp;Szülő objektumok elemeinek azonosítása a függő objektum elemeinek neve (és nem a sorrend) alapján";
    $checked = $parent_showsingle_org=="1" ? "" : "checked";
    $parent_showsingle_dep="<br><input type='checkbox' name='parent_showsingle' $checked>&nbsp;Ne jelenjen meg a függő objektum ha csak egy eleme/sora/oszlopa marad";
    $checked = $parent_page_inherit_org==1 ? "checked" : "";
    $parent_page_inherit_dep="<br><input type='checkbox' name='parent_page_inherit' $checked>&nbsp;Az oldal se jelenjen meg ha nem jelenik meg a függő objektum";
    echo "<tr>
          <td bgcolor='white' style='padding-top:8px;'><span class=szoveg>
          <b>Szülő objektumokhoz kapcsolódó általános beállítások</b>
          <br>Logikai kapcsolat a szülő objektumok között: ('and', 'or', 'not', '(' és ')' használható)
          <textarea id='parent_dependency' name='parent_dependency' wrap=virtual style='width:963px;height:60px;'>$parent_dependency</textarea>
          $parent_row_or_col_dep$parent_identify_dep$parent_showsingle_dep$parent_page_inherit_dep$parent_always_dep
          </span></td></tr>\n";
}
// Parent data bound to the child object END

// General data bound to dependencies
echo "<tr>
      <td bgcolor='white' style='padding-top:8px;'><span class=szoveg>
      <b>Feltételek általános beállítátásai</b>
      <br>Logikai kapcsolat a feltételek között: ('and', 'or', 'not', '(' és ')' használható)
      <textarea id='dependency' name='dependency' wrap=virtual style='width:963px;height:60px;'>$dependency</textarea>
      </span></td></tr>\n";
// General data bound to dependencies END

echo "<tr>
    <td align=center class=bgkiemelt2 style='padding:8px;'>
    <input type='button' class='tovabbgomb' value='$word[submit3]' onclick='realsubmit();'> <input type='button' class='tovabbgomb' value='$word[close]' onclick='window.close();'>
    </td>
    </tr></form>";

printfoot();
include "footer.php";
// ------------ ------------- ------------- -------------

function printhead($rule) {

    global $_MX_var,$stat_text,$userlist_yn,$filt_demog_options,$group_id,$pagenum,$word, $formdata, $form_element_id, $group_id, $page_id, $form_id,$form_endlink_id,$form_email_id,$word,$copy_dependency_ids,$copy_dependencies_to_names;

    echo "<script>
var extv='".$word['ext_view']."';
var normv='".$word['norm_view']."';
var pointers=new Object();

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
function chst(depid) {
    var f=document.getElementById(depid).style;
    if (f.display=='none') f.display='block'; else f.display='none';
}
var vt ='ext';
function control(id,ch,neg) {
    var cv=document.getElementById(id);
    var val=ch.checked;
    for (i=0;i<cv.childNodes.length;i++) {
        if (cv.childNodes[i].name=='neg['+id+']' && neg==1) {
            cv.childNodes[i].disabled=val;
            if (val==true) cv.childNodes[i].checked=false;
        }
        if (cv.childNodes[i].type=='checkbox' && cv.childNodes[i].value=='1' && cv.childNodes[i].name!=ch.name) {
            cv.childNodes[i].disabled=val;
            if (val==true) cv.childNodes[i].checked=false;
        }
    }
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
function deplog(isparent) {
    var wl = d_ids.length;
    var wset = new Object();
    for (var i=0; i<wl; i++) {
        var has_dep_setting=0;
        var di = d_ids[i];
        eval('var wall = d_data' + di);
        if (isparent) {
            var o = find_object('parent_' + di);
            if (o && o.checked) {
                has_dep_setting=1;
            }
        }
        else {
            for (var j=1; j<wall.length; j++) {
                var o = find_object(wall[j]);
                if (o.type=='checkbox' && o.checked || o.type=='text' && o.value) {
                    has_dep_setting=1;
                }
            }
        }
        if (has_dep_setting) {
            wset[wall[0]]=1;
        }
    }
    var dta = find_object(isparent?'parent_dependency':'dependency');
    var dtaparts = dta.value.split(' ');
    var conn = '';
    var newexp = '';
    var prevconn = '';
    var has_otherconn = 0;
    for (var i=0;i<dtaparts.length;i++) {
        var term = dtaparts[i];
        var isconn = term.replace('(','').replace(')','');
        if (isconn == 'or' || isconn == 'and') {
            conn = ' ' + isconn + ' ';
            prevconn += term + ' ';
        }
        else if (term==')' || term=='(' || term=='not') {
            prevconn += term + ' ';
            has_otherconn = 1;
        }
        else if (term!='') {
            var isterm = term.replace('(','').replace(')','');
            if (wset[isterm]) {
                newexp += (newexp == '' && has_otherconn==0 ? '' : prevconn) + term + ' ';
                wset[isterm] = 0;
            }
            prevconn='';
        }
    }
    newexp += prevconn;
    if (conn=='' && newexp!='') {
        conn = ' or ';
    }
    for (i in wset) {
        if (wset[i]==1) {
            newexp += conn + i;
            if (conn=='') {
                conn = ' or ';
            }
        }
    }
    dta.value=newexp;
}
function find_object(id) {
    if (typeof(pointers[id])=='undefined' || pointers[id]==false) {
        pointers[id]=false;
        if (document.getElementById) { if (!(document.getElementById(id))) { pointers[id]=false; } else pointers[id]=document.getElementById(id); } else pointers[id]=false; 
    }
    return pointers[id];
}

</script><form method='post' action='form_element_options.php' name='fops'>"
 . ($rule == "global" ? "<input type='hidden' name='rule' value='global'>" : "") . 
"<input type='hidden' name='action' value='seldep'>
<input type='hidden' name='form_element_id' value='$form_element_id'>
<input type='hidden' name='form_email_id' value='$form_email_id'>
<input type='hidden' name='form_endlink_id' value='$form_endlink_id'>
<input type='hidden' name='group_id' value='$group_id'>
<input type='hidden' name='page_id' value='$page_id'>
<input type='hidden' name='form_id' value='$form_id'>
<input type='hidden' name='copy_dependency_ids' value='$copy_dependency_ids'>
<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <tr>
        <td class=bgkiemelt2 align=left><span class=szovegvastag>&quot;".htmlspecialchars($formdata["title"])."&quot $word[iform_title] &gt; ".htmlspecialchars($formdata["question"])." &gt; $word[fe_depend]</span></td>
        </tr>\n";
        if (count($copy_dependencies_to_names)) {
            print "<tr>
            <td class=bgkiemelt2 align=left><span class=szovegvastag>Feltételek beállitása a következő kérdésekre is:<br>" . implode("<br>",$copy_dependencies_to_names) . "</span></td>
            </tr>\n";
        }
}

function printfoot() {

    echo "</table>
        </td>
        </tr>
        </table>\n";
}
