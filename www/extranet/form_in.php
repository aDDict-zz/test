<?
include "auth.php";
include "decode.php";
$weare=34;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/form.lang";

/*print_r($_POST);
print_r($_GET);

die();*/

$mres = mysql_query("select title,num_of_mess,membership,question_position 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    exit; 
}

if (isset($_GET["form_element_id"])) {
    $res=mysql_query("select form_id from form_element where id='". mysql_escape_string($_GET["form_element_id"]) ."'");
    if ($res && mysql_num_rows($res)) {
        $form_id=mysql_result($res,0,0);
    }
}
if (isset($_GET["form_email_id"])) {
    $res=mysql_query("select form_id from form_email where id='". mysql_escape_string($_GET["form_email_id"]) ."'");
    if ($res && mysql_num_rows($res)) {
        $form_id=mysql_result($res,0,0);
    }
}

if (isset($_GET["addspec"]) && isset($_GET["form_element_id"])) {
    $addspec=$_GET["addspec"];
    if (!in_array($addspec,array("comment","separator","cim","ceg_cim","homepage","tel","mob","captcha"))) { $addspec="separator"; }
    $form_element_id=mysql_escape_string($_GET["form_element_id"]);
    $res=mysql_query("select * from form_element where id='$form_element_id'");
    if ($res && mysql_num_rows($res)) {
        if ($z=mysql_fetch_array($res)) {
            if ($addspec=="cim") {
                $cimvars=0;
                $cr=mysql_query("select count(vd.id) from vip_demog vd, demog d where vd.group_id='$group_id'
                                 and d.variable_name in ('hazszam','emelet','ajto','utca_nev','utca_tipus') and vd.demog_id=d.id");
                if ($cr && mysql_num_rows($cr)) {
                    $cimvars=mysql_result($cr,0,0);
                }
                if ($cimvars<5) {
                    header("Location: form_elements.php?form_id=$z[form_id]&group_id=$group_id&cimerror=1");
                    exit;
                }
            }
            if ($addspec=="ceg_cim") {
                $cimvars=0;
                $cr=mysql_query("select count(vd.id) from vip_demog vd, demog d where vd.group_id='$group_id' and d.variable_name in 
                                 ('street_name_company','street_type_company','street_number_company','floor_company','door_company') 
                                 and vd.demog_id=d.id");
                if ($cr && mysql_num_rows($cr)) {
                    $cimvars=mysql_result($cr,0,0);
                }
                if ($cimvars<5) {
                    header("Location: form_elements.php?form_id=$z[form_id]&group_id=$group_id&cimerror=2");
                    exit;
                }
            }
            if ($addspec=="tel") {
                $cimvars=0;
                $cr=mysql_query("select count(vd.id) from vip_demog vd, demog d where vd.group_id='$group_id' and d.variable_name in 
                                 ('tel_korzet','tel_szam') 
                                 and vd.demog_id=d.id");
                if ($cr && mysql_num_rows($cr)) {
                    $cimvars=mysql_result($cr,0,0);
                }
                if ($cimvars<2) {
                    header("Location: form_elements.php?form_id=$z[form_id]&group_id=$group_id&cimerror=3");
                    exit;
                }
            }            
            if ($addspec=="mob") {
                $cimvars=0;
                $cr=mysql_query("select count(vd.id) from vip_demog vd, demog d where vd.group_id='$group_id' and d.variable_name in 
                                 ('mobil_korzet','mobil_szam') 
                                 and vd.demog_id=d.id");
                if ($cr && mysql_num_rows($cr)) {
                    $cimvars=mysql_result($cr,0,0);
                }
                if ($cimvars<2) {
                    header("Location: form_elements.php?form_id=$z[form_id]&group_id=$group_id&cimerror=4");
                    exit;
                }
            }                       
            // put the special element in front of the others in the box.
            $query="update form_element set sortorder=sortorder+1 where
                         page='$z[page]' and box_id='$z[box_id]' and form_id='$z[form_id]' and sortorder>='$z[sortorder]'";
            mysql_query($query);
			logger($query,$group_id,"","form_id=$form_id","form_element");                         
			$query="insert into form_element (form_id,page,box_id,sortorder,demog_id,widget) 
                         values ('$z[form_id]','$z[page]','$z[box_id]','$z[sortorder]',0,'$addspec')";			
            mysql_query($query);
			logger($query,$group_id,"","form_id=$form_id","form_element");
            header("Location: form_elements.php?form_id=$z[form_id]&group_id=$group_id&changed=1");
            exit;
        }
    }
    exit;
}

$res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;

//http://www.manufacture.co.yu/maxima/form_in.php?group_id=543&move=1&form_element_id=2&dir=0&by=4
if (isset($_GET["move"])) {
    $form_element_id=get_http("form_element_id",0,1);
    // Look up the ID of the form
    $query=mysql_query("select form_id from form_element where id='$form_element_id'");
    if (!$query || !mysql_num_rows($query)) {
        exit;
    }
    $row=mysql_fetch_array($query);
    $form_id=$row['form_id'];
    // Get all the elements of the form, sorted the same way as visible to the user
    $query="select id, page, box_id, sortorder from form_element
                         where form_id='$form_id' order by page, box_id, sortorder";
	logger($query,$group_id,"","form element id=$form_id","form_element");
    $query=mysql_query($query);
    if (!$query || !mysql_num_rows($query)) {
        exit;
    }
    $form_elements=array();
    while ($row=mysql_fetch_array($query)) {
        $form_elements[]=$row;
        if ($row['id']==$form_element_id) {
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
    $page=$form_elements[$position]['page'];
    $box=$form_elements[$position]['box_id'];
    $sortorder=$form_elements[$position]['sortorder']+$direction;
    if ($sortorder<1) {
        $sortorder=1;
    }
    // Check whether the anyone is using this sortorder
    $occupied=false;
    foreach ($form_elements as $element) {
        if ($element['page']==$page && $element['box_id']==$box
         && $element['sortorder']==$sortorder) {
            $occupied=true;
            break;
        }
    }
    // If the sortorder is used, make room
    if ($occupied) {
    	$query="update form_element set sortorder=sortorder$sqldirection
                      where form_id='$form_id' and page='$page'
                        and box_id='$box' and sortorder$sqlcomp'$sortorder'";
        mysql_query($query);
		logger($query,$group_id,"","form_id=$form_id","form_element");
    }
    // Now, update the element
    $query="update form_element
                    set page='$page', box_id='$box', sortorder='$sortorder'
                  where id='$form_element_id'";
    mysql_query($query);
    //echo $query."sfdg";
	logger($query,$group_id,"","form_id=$form_id","form_element");
    header("Location: form_elements.php?form_id=$form_id&group_id=$group_id&changed=1");
    exit;
}

if (isset($_GET["delspec"]) && isset($_GET["form_element_id"])) {
    $form_element_id=mysql_escape_string($_GET["form_element_id"]);
	$query="delete from form_element where id='$form_element_id'";
    mysql_query($query);
	logger($query,$group_id,"","form_element_id=$form_id","form_element");    
	$query="delete from form_element_enumvals where form_element_id='$form_element_id'";
    mysql_query($query);
    logger($query,$group_id,"","form_element_id=$form_id","form_element_enumvals");    
    header("Location: form_elements.php?form_id=$form_id&group_id=$group_id&changed=1");
}

if (isset($_GET["delfem"]) && isset($_GET["form_email_id"])) {
    $form_email_id=mysql_escape_string($_GET["form_email_id"]);
    $query="delete from form_email where id='$form_email_id'";
    mysql_query($query);
    logger($query,$group_id,"","form_email_id=$form_id","form_email");        
    header("Location: form_elements.php?form_id=$form_id&group_id=$group_id&changed=1");
}

$fsel=array();
$r2=mysql_query("select demog_id,page,box_id,max_num_answer,rotate from form_element where form_id='$form_id'");
if ($r2 && mysql_num_rows($r2)) {
    while ($z=mysql_fetch_array($r2)) {
        $fpage["$z[demog_id]"]=$z["page"];
        $fbox["$z[demog_id]"]=$z["box_id"];
        $fmna["$z[demog_id]"]=$z["max_num_answer"];        
        $frot["$z[demog_id]"]=$z["rotate"];
    }
}
$action=get_http("action","");
if ($action=="addtoform") {
    $changed=0;
    if (is_array($_POST["demog_id"])) {
        while (list($demog_id,$pagebox)=each($_POST["demog_id"])) {
            if ($_POST["demogr_id"][$demog_id]!=NULL) $pagebox=$_POST["demogr_id"][$demog_id];
            if ($_POST["max_num_answer"][$demog_id]!=NULL) $max_num_answer=$_POST["max_num_answer"][$demog_id]; else $max_num_answer=0;
            if ($_POST["rotate"][$demog_id]!=NULL) $rotate='yes'; else $rotate='no';            
            $demog_id = mysql_escape_string($demog_id);
            $page = mysql_escape_string(ereg_replace('[A-Z]', '', $pagebox));
            $box = mysql_escape_string(ereg_replace('[0-9]', '', $pagebox));
            if (empty($box)) {
                $box = '0';
            }
            if ($rotate != $frot[$demog_id]) {
                mysql_query("UPDATE form_element set rotate='$rotate' WHERE form_id = '$form_id' AND demog_id = '$demog_id'");
            }            
            if ($max_num_answer != $fmna[$demog_id]) {
                mysql_query("UPDATE form_element set max_num_answer=$max_num_answer WHERE form_id = '$form_id' AND demog_id = '$demog_id'");
            }            
            if ($page == 0 && isset($fpage[$demog_id])) {
                mysql_query("DELETE FROM form_element WHERE form_id = '$form_id' AND demog_id = '$demog_id'");
                $changed++;
            } elseif ($page != 0) {
                if (!isset($fpage[$demog_id])) {
                    add_element($demog_id, $page, $box, 'after_last', 'new_element');
                    $changed++;
                } else {
                    $pagesame = $fpage[$demog_id] == $page;
                    $boxsame = $fbox[$demog_id] == $box;
                    if (!$boxsame && !$pagesame) {
                        add_element($demog_id, $page, $box, 'try_same_position');
                        $changed++;
                    } elseif ($boxsame xor $pagesame) {
                        add_element($demog_id, $page, $box, 'try_same_position');
                        $changed++;
                    }
                }
            }
        }
    }
   	header("Location: form_select.php?form_id=$form_id&group_id=$group_id&changed=$changed&pagenum=$_POST[pagenum]&perpage=$_POST[perpage]&sword=$_POST[sword]");
    exit;
}


if ($action=="changeelem") {
    $r2=mysql_query("select id,widget from form_element where form_id='$form_id'");
    if ($r2 && mysql_num_rows($r2)) {
        while ($z=mysql_fetch_array($r2)) {
            $id=$z["id"];
            if (isset($_POST["question"]["z$id"])) {
                $question=slasher($_POST["question"]["z$id"]);
                if (in_array($z["widget"],array("input","textarea","password"))) {
                    $maxlength=",maxlength='" . slasher($_POST["maxlength"]["z$id"]) . "'";
		}
		else {
		    $maxlength="";
		}
                $errmsg=slasher($_POST["errmsg"]["z$id"]);
                $additionaltext=slasher($_POST["additionaltext"]["z$id"]);
                isset($_POST["mandatory"]["z$id"])?$mandatory="yes":$mandatory="no";
                isset($_POST["question_pos"]["z$id"])?$feqp="above":$feqp="normal";
                isset($_POST["pvalues_bottom"]["z$id"])?$fepvb="bottom":$fepvb="";                
                $widget=slasher($_POST["widget"]["z$id"]);
                if (isset($_POST["image_pos"][$id])) $ip=",image_position='".$_POST["image_pos"][$id]."'";else $ip="";
                $query="update form_element set widget='$widget',mandatory='$mandatory',question_position='$feqp',possible_values='$fepvb',question='$question'$maxlength, additionaltext='$additionaltext',errmsg='$errmsg' $ip where form_id='$form_id' and id='$id'";         
                mysql_query($query);
				logger($query,$group_id,"","form element id=$form_id","form_element");                             
            }
        }
    }
    header("Location: form_elements.php?form_id=$form_id&group_id=$group_id&changed=1");
    exit;
}

function add_element($demog_id, $page_id, $box_id, $where, $new = false)
{
    global $_MX_var,$group_id, $form_id,$rowg;

	if ($where == 'after_last') {
    	$sortorder = next_sortorder($page_id, $box_id);
    } else {
	    $query = mysql_query("SELECT sortorder FROM form_element
	                           WHERE form_id = '$form_id' AND demog_id = '$demog_id'");
	    if ($query && mysql_num_rows($query)) {
            $sortorder = mysql_result($query,0,0) + 1;
        } else {
            $sortorder = 1; // Actually, this should never be reached
        }
        $query = mysql_query("SELECT COUNT(*) FROM form_element
                               WHERE form_id = '$form_id' AND sortorder = '$sortorder'
                                 AND page = '$page_id' AND box_id = '$box_id'");
        if ($query && mysql_num_rows($query)) {
            $exists = mysql_result($query,0,0);
            if ($exists) {
                $sortorder = next_sortorder($page_id, $box_id);
            }
        }
    }
    if ($new) {
        $query = mysql_query("SELECT vd.mandatory, d.question, d.variable_type, d.multiselect
                                FROM demog d, vip_demog vd
                               WHERE d.id = vd.demog_id
                                 AND d.id = '$demog_id' AND vd.group_id = '$group_id'");
        if ($query && mysql_num_rows($query)) {
            $row = mysql_fetch_array($query);
            $question = mysql_escape_string($row["question"]);
            if ($row["variable_type"] == "enum") {
                $widget = $row["multiselect"] == "yes"? "multiselect": "select";
            } elseif ($row["variable_type"] == "enum_other") {
                $widget = $row["multiselect"] == "yes"? "checkbox_other": "radio_other";
            } elseif ($row["variable_type"] == "matrix") {
                $widget = $row["multiselect"] == "yes"? "checkbox_matrix": "radio_matrix";
            } elseif ($row["variable_type"] == "date") {
                $widget = "datum";
            } else {
                $widget = "input";
            }
            $query="INSERT INTO form_element
                                (form_id, page, box_id, sortorder,
                                 demog_id, question, widget, mandatory,question_position)
                         VALUES ('$form_id', '$page_id', '$box_id', '$sortorder',
                                 '$demog_id', '$question', '$widget', '$row[mandatory]','".trim($rowg["question_position"]
)."')";
            mysql_query($query);
			logger($query,$group_id,"","","form_element");                                 
        }
    } else {
    	$query="UPDATE form_element
                        SET page = '$page_id', box_id='$box_id', sortorder='$sortorder'
                      WHERE form_id = '$form_id' AND demog_id = '$demog_id'";
        mysql_query($query);
		logger($query,$group_id,"","form_element_id=$form_id","form_element");                                                       
    }   
}

function next_sortorder($page_id, $box_id)
{
    global $_MX_var,$form_id;
    $query = mysql_query("SELECT MAX(sortorder) FROM form_element
                           WHERE form_id = '$form_id' AND page = '$page_id' AND box_id = '$box_id'");
    if ($query && mysql_num_rows($query)) {
        $sortorder = mysql_result($query,0,0) + 1;
    } else {
        $sortorder = 1;
    }
    return($sortorder);
}
