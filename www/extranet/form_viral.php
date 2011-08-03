<?
include "auth.php";
include "decode.php";
$weare=34;
$subweare='viral';
include "cookie_auth.php";
include "common.php";
include "_form.php";

$mres = mysql_query("select title,membership,unique_col
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
logger($query,$group_id,"","form_id=$form_id","form");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;

$error=array();

include "menugen.php";
include "./lang/$language/form.lang";
$_MX_form = new MxForm($group_id,"",0,0);
$_MX_form->MakeMenu($subweare,$formdata);
    
print "<form method='post' action='form_viral.php' style='margin:0; border:0;'>
       <input type='hidden' name='enter' value='1'>
       <input type='hidden' name='form_id' value='$form_id'>
       <input type='hidden' name='group_id' value='$group_id'>
       <table cellspacing='1' cellpadding='1' border='0' style='width:100%;' class='bgcolor'>\n";

if (isset($_GET["cimerror"])) {
    if ($_GET["cimerror"]==1) {
        $errorlist=$word["cimerror1"];
    }
    else {
        $errorlist=$word["cimerror2"];
    }
}

// variables needed for viral campaigns: max number of demogs, in the form or not, possible types of demogs for them
$_MX_form->viral_func=array("send_to"=>array("0","1","text","email"),
                            "counter"=>array("0","0","number"),
                            "sub_from"=>array("1","0","text"));

$types_inform=array();
$types_notinform=array();
$funcheads="";
$colspan=count($_MX_form->viral_func)+2;
$colwd=floor(55 / count($_MX_form->viral_func));
foreach ($_MX_form->viral_func as $vt=>$vf) {
    $funcheads.="<td class='bgkiemelt2' width='$colwd%'><span class=szovegvastag>". $word["viral_$vt"] ."</span></td>";
    foreach ($vf as $vff) {
        if (!ereg("[0-9]",$vff) && !in_array($vff,$types_inform) && $vf[1]) {
            $types_inform[]=$vff;
        }
        if (!ereg("[0-9]",$vff) && !in_array($vff,$types_notinform) && !$vf[1]) {
            $types_notinform[]=$vff;
        }
    }
}

$errors=array();
if (isset($_POST["enter"]) && $_POST["enter"]==1) {
    $posted=array();
    $setviral=0;
    if (isset($_POST["form_viral"]) && $_POST["form_viral"]==1) {
        $setviral=1;
    }
    $p_already=array();
    foreach ($_MX_form->viral_func as $vt=>$vf) {
        $posted["$vt"]=array();
    }
    foreach ($_POST as $pvar=>$pval) {
        if (ereg("^([^0-9]+)([0-9]+)$",$pvar,$prg)) {
            if (isset($posted["$prg[1]"]) && !in_array($prg[2],$p_already)) {
                $posted["$prg[1]"][]=$prg[2];
                $p_already[]=$prg[2];
            }
        }
    }
    foreach ($_MX_form->viral_func as $vt=>$vf) {
        if ($setviral && count($posted["$vt"])==0) {
            $errors[]="$word[viral_min1] ".$word["viral_$vt"]." $word[viral_min2]";
        }
        elseif ($setviral && $vf[0] && count($posted["$vt"])>$vf[0]) {
            $errors[]="$word[viral_max1] ".$word["viral_$vt"]." $word[viral_max2]";
        }
        elseif (count($posted["$vt"])) {
            $tr=mysql_query("select id,variable_type from demog where id in (". implode(",",$posted["$vt"]) .")");
            if (mysql_num_rows($tr) != count($posted["$vt"])) {
                $errors[]="$word[viral_invdemog]";
            }
            else {
                while ($tk=mysql_fetch_array($tr)) {
                    if (!in_array($tk["variable_type"],$vf)) {
                        $errors[]="$word[viral_invtype1] ".$word["viral_$vt"]." $word[viral_invtype2]";
                    }
                }
            }
        }
    }
    $base_id=intval($_POST["base_id"]);
    $rb=mysql_query("select id from sender_base where id='$base_id'");
    if (!($rb && mysql_num_rows($rb))) {
        $base_id=0;
        if ($setviral) {
            $errors[]=$word["viral_nobase"];
        }
    }
    $formdata["viral"]=$setviral?"yes":"no";
    if (count($errors)==0) {
        foreach ($_MX_form->viral_func as $vt=>$vf) {
            if ($vf[1]) {
                $vdl=implode(",",$posted["$vt"]);
                if (empty($vdl)) {
					$query="update form_element set viralfunc=replace(viralfunc,'$vt','')
                                 where form_id='$form_id'";                	
                    mysql_query($query);
					logger($query,$group_id,"","form_id=$form_id","form_element");                                 
                }
                else {
                	$query="update form_element set viralfunc=replace(viralfunc,'$vt','')
                                 where form_id='$form_id' and demog_id not in ($vdl)";
                    mysql_query($query);
					logger($query,$group_id,"","form_id=$form_id","form_element");
					$query="update form_element set viralfunc=concat(viralfunc,'$vt')
                                 where form_id='$form_id' and demog_id in ($vdl) and viralfunc not like '%$vt%'";
                    mysql_query($query);
					logger($query,$group_id,"","form_id=$form_id","form_element");
                }
            }
            else {
                $vdl=implode(",",$posted["$vt"]);
                if (empty($vdl)) {
                	$query="delete from form_viral where form_id='$form_id' and viralfunc='$vt'";
                    mysql_query($query);
					logger($query,$group_id,"","form_viral=$form_id","form_viral");                    
                }
                else {
                	$query="delete from form_viral where form_id='$form_id' and demog_id not in ($vdl) and viralfunc='$vt'";
                    mysql_query($query);
             		logger($query,$group_id,"","form_viral=$form_id","form_viral");                    
                    foreach ($posted["$vt"] as $isin) {
                        $rr=mysql_query("select * from form_viral where form_id='$form_id' 
                                         and demog_id='$isin' and viralfunc='$vt'");
                        if (!($rr && mysql_num_rows($rr))) {
                        	$query="insert into form_viral set form_id='$form_id',demog_id='$isin',
                                         viralfunc='$vt',group_id='$group_id'";
                            mysql_query($query);
							logger($query,$group_id,"","form_viral=$form_id","form_viral");        
                        }
                    }
                }
            }
        }
        $query="update form set viral='$formdata[viral]',viral_base_id='$base_id' where id='$form_id'";
        mysql_query($query);
		logger($query,$group_id,"","form=$form_id","form");                            
    }
    $formdata["viral_base_id"]=$_POST["base_id"];
}
$base_options="";
$rb=mysql_query("select id,name from sender_base where group_id='$group_id' order by name");
while ($kb=mysql_fetch_array($rb)) {
    $ksl=$kb["id"]==$formdata["viral_base_id"]?" selected":"";
    $base_options.="<option value='$kb[id]'$ksl>". htmlspecialchars($kb["name"]) ."</option>";
}
$form_viral_checked=$formdata["viral"]=="yes"?"checked":"";
$prev_page=-1;
$prev_box=-1;
$i=0;
$errorlist=implode("<br>",$errors);
if (!empty($errorlist)) {
    print "<tr><td colspan='$colspan' bgcolor='white'><span class='szovegvastag'>$errorlist&nbsp;</span></td></tr>\n";
}
print "<tr><td class='bgkiemelt2' width=15%><span class=szovegvastag>$word[t_varname]</span></td>
       <td class='bgkiemelt2' width=30%><span class=szovegvastag>$word[t_question]</span></td>
       $funcheads
       </tr><tr><td class='bgvilagos2' colspan='$colspan' style='padding:3px;'><span class=szovegvastag><input type='checkbox' name='form_viral' $form_viral_checked value='1'> $word[form_is_viral]</span></td></tr>
       </tr><tr><td class='bgvilagos2' colspan='$colspan' style='padding:3px;'><span class=szoveg>$word[viral_base]: <select name='base_id'><option value='0'> - - - </option>$base_options</select></span></td></tr>\n";
$dinform=array();
$res=mysql_query("select fe.viralfunc,fe.question,d.id,d.variable_name,d.variable_type 
                  from form_element fe,demog d where fe.form_id='$form_id' and fe.demog_id=d.id 
                  order by fe.page, fe.box_id, fe.sortorder");
if ($res && $count=mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        if (in_array($k["variable_type"],$types_inform) && $k["variable_name"]!=$rowg["unique_col"]) {
            mx_rows_viral($k,1);
        }
        $dinform[]=$k["id"];
    }
}
$dfilt="";
if (count($dinform)) {
    $dfilt="and d.id not in (". implode(",",$dinform) .")";
}
$res=mysql_query("select d.* from demog d,vip_demog vd where d.id=vd.demog_id and vd.group_id='$group_id' $dfilt
                  and variable_type in ('". implode("','",$types_notinform) ."') and variable_name!='$rowg[unique_col]'
                  order by variable_name");
if ($res && $count=mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        mx_rows_viral($k,0);
    }
}

print "<tr>
       <td align='center' colspan='$colspan'>
       <input type=submit class='tovabbgomb' value='$word[submit3]'>
       </td>
       </tr></table></form>\n";
include "footer.php";

function mx_rows_viral(&$k,$inform) {

    global $_MX_var,$_MX_form,$_POST,$form_id;
    static $index=0;

    $bgrnd="bgcolor=white";
    $varname=htmlspecialchars($k["variable_name"]);
    $question=htmlspecialchars($k["question"]);
    $rads="";
    if ($index%2)
       $bgrnd="class=bgvilagos2";
    else
       $bgrnd="bgcolor=white";
    $index++;
    foreach ($_MX_form->viral_func as $vt=>$vf) {
        $vwi="&nbsp;";  
        $vch="";
        if ($vf[1]==$inform && in_array($k["variable_type"],$vf)) {
            if (isset($_POST["enter"])) {
                if (isset($_POST["$vt$k[id]"])) {
                    $vch=" checked";
                }
            }
            elseif ($inform) {
                if ($vt==$k["viralfunc"]) {
                    $vch=" checked";
                }
            }
            else {
                $rrr=mysql_query("select * from form_viral where form_id='$form_id'
                                  and demog_id='$k[id]' and viralfunc='$vt'");
                if ($rrr && mysql_num_rows($rrr)) {
                    $vch=" checked";
                }
            }
            $vwi="<input type='checkbox'$vch name='$vt$k[id]' value='1'>";
        }
        $rads.="<td $bgrnd width='$colwd%'><span class=szoveg>$vwi</span></td>";
    }
    print "<tr>
          <td $bgrnd width=15%><span class=szoveg>$varname</span></td>
          <td $bgrnd width=30%><span class=szoveg>$question</span></td>
          $rads
          </tr>\n";
}
?>
