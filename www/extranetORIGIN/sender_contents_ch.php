<?
include "auth.php";
$weare=80;
if (get_http('contents_id','')) $subweare=801;
else $subweare=802;
include "cookie_auth.php";

$Amfsort=get_cookie("Amfsort");
$Amfppage=get_cookie("Amfppage");

$fsort = (isset($_GET['fsort']) || empty($Amfsort)) ? get_http('fsort',4) : $Amfsort;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Amfppage)) ? get_http('maxPerPage',25) : $Amfppage;

setcookie("Amfsort",$fsort,time()+30*24*3600);
setcookie("Amfppage",$maxPerPage,time()+30*24*3600);

$mres = mysql_query("select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }

include "menugen.php";
include "./lang/$language/sender.lang";

$supported_codepages=array();
$rec=mysql_query("select codepage,description from codepages order by sortorder");
while ($kec=mysql_fetch_array($rec)) {
    $supported_codesets["$kec[codepage]"]=$kec["description"];
}

$fields=array(
    "name"=>array("input","mandatory",100),
    "plain_address"=>array("input","",400),
    "html_address"=>array("input","mandatory",400),
    "codeset"=>array("select","",0),
    "dependent_name"=>array("select","",0),
    "dependent_value"=>array("input","",0)
);

$contents_id=intval(get_http("contents_id",0));
$r2=mysql_query("select * from sender_contents where group_id='$group_id' and id='$contents_id'");
if ($r2 && mysql_num_rows($r2)) {
    $brow=mysql_fetch_array($r2);
}
else {
    $brow=array();
    $contents_id=0;
}

$glue="";
$ismsg=0;
if (isset($_POST["enter"]) && $_POST["enter"]==1) {
    reset ($fields);
    $errors=array();
    $sets=array();
    while (list($field,$meta)=each($fields)) {
        if (isset($_POST["$field"])) {
            $ovalue=slasher(trim($_POST["$field"]),0);
            $value=slasher(trim($_POST["$field"]));
        }
        else {
            $value="";
        }
        if ($field=="dependent_value") {
            while (list($dvkey,$dvval)=each($_POST)) {
                if (ereg("dependent_value([0-9_]+)",$dvkey,$regs)) {
                    $value.="$glue$regs[1]";
                    $glue=",";
                }
            }
        }
        if ($meta[1]=="mandatory" && empty($value)) {
            $errors[]=$word["s_mand1"].$word["sb_$field"].$word["s_mand2"];
        }
        if ($meta[0]=="checkbox" || $meta[0]=="radio") {
            if ($value!="yes") {
                $value="no";
            }
        }
        if ($field=="name") {
            if (!eregi("^[0-9a-z_]+$",$value)) {
                $errors[]=$word["cont_name_format"];
            }
        }
        if ($field=="codeset") {
            if (!isset($supported_codesets["$value"])) {
                list($value,$x)=each($supported_codesets);
            }
        }
        $sets[]="$field='$value'";
    }
    if (count($errors)==0) {
        $sqldata=implode(",",$sets);
        $msg=$word["data_changed"];
        if ($contents_id) {
        	$query="update sender_contents set $sqldata where id='$contents_id' and group_id='$group_id'";
            mysql_query($query);
            logger($query,$group_id,"","sender_contents id=$contents_id","sender_contents");
        }
        else {
        	$query="insert into sender_contents set $sqldata,group_id='$group_id'";
            mysql_query($query);
            logger($query,$group_id,"","","sender_contents");            
            print mysql_error();
        }
    }
    if (count($errors)) {
        $ismsg=1;
        $msg=implode("<br>",$errors);
    }
    else {
        $ismsg=2;
    }
}

$hiddens=array(array("contents_id",$contents_id),array("group_id",$group_id),array("enter","1"));
$hidden="";
$getvars="";
foreach ($hiddens as $hide) {
    $hidden.="<input type='hidden' name='$hide[0]' value=\"$hide[1]\"/>";
    if ($hide[0]!="enter") {
        $getvars.="&$hide[0]=$hide[1]";
    }
}

$formw=650;

print "<form action='sender_contents_ch.php' method='post' name='funky'>$hidden
            <table width=100% border=0 cellspacing=1 cellpadding=0 class='bordercolor'>
                <!--<tr>
                    <td>
                        <span class='szovegvastag'><a href='sender_contents.php?group_id=$group_id'>$word[contents]</a></span>
                    </td>
                    <td align='right'>
                        <span class='szovegvastag'><a href='sender_contents.php?group_id=$group_id&contents_id=$contents_id&preview=1' target='_blank'>$word[contents_preview]</a> <a href='sender_contents.php?group_id=$group_id'>$word[back]</a></span>
                    </td>
                </tr>-->";

if ($ismsg) {
    print "<tr><td colspan='2' width='100%'><table width=100% border=0 cellspacing=0 cellpadding=2><tr>
               <td class='formmezo' width='100%'>$msg</td>
           </tr></table></td></tr>";
}

$widget="";
reset ($fields);
while (list($field,$meta)=each($fields)) {
    $value="";
    $owidget="";
    if (isset($_POST) && isset($_POST["$field"])) {
        $value=htmlspecialchars(slasher($_POST["$field"],0));
    }
    elseif (isset($brow) && isset($brow["$field"])) {
        $value=htmlspecialchars($brow["$field"]);
    }
    else {
        $value="";
    }
    $varname=$word["sb_$field"].":";
    if ($meta[0]=="output") {
        $owidget="$value";
    }
    elseif ($meta[0]=="radio") {
        $opts=array("yes","no");
        foreach ($opts as $opt) {
            $value==$opt?$checked="checked":$checked="";
            $owidget.="<input type='radio' name='$field' value=\"$opt\"$checked/> ".$word["sb_$opt"]."&nbsp;&nbsp;&nbsp;&nbsp;";
        }
    }
    elseif ($field=="codeset") {
        $dependent_name=$value;
        $owidget="<select name='codeset'>";
        foreach ($supported_codesets as $cs => $desc) {
            $sel=($cs==$value?" selected":"");
            $owidget.="<option value='$cs'$sel>$desc</option>";
        }
    }
    elseif ($field=="dependent_name") {
        $dependent_name=$value;
        $owidget="<select name='dependent_name' onchange=\"document.funky.enter.value='2';document.funky.submit();\" style='width:$formw"."px;' class='oinput'><option value=''> ($word[sb_always]) </option>";
        $r2=mysql_query("select d.id,d.variable_name,d.variable_type as widget from demog d,vip_demog vd where 
                         vd.group_id='$group_id' and d.id=vd.demog_id order by d.variable_name");
        if ($r2 && mysql_num_rows($r2)) {
            while ($w=mysql_fetch_array($r2)) {
                if ($w["variable_name"]==$dependent_name) {
                    $widget=$w["widget"];
                    $sel="selected";
                    $dependent_id=$w["id"];
                }
                else {
                    $sel="";
                }
                $opt=htmlspecialchars($w["variable_name"]);
                $owidget.="<option value='$w[variable_name]' $sel>$opt</option>";
            }
        }
    }
    elseif ($field=="dependent_value") {
        reset ($_POST);
        while (list($dvkey,$dvval)=each($_POST)) {
            if (ereg("dependent_value([0-9_]+)",$dvkey,$regs)) {
                $value.="$glue$regs[1]";
                $glue=",";
            }
        }
        $dependent_value=$value;
        if ($widget=="") {
            $owidget="- - -";
        }
        elseif ($widget=="enum") {
            $dependent_array=explode(",",$dependent_value);
            $owidget="<input type='hidden' name='dependent_value' value=''>";
            $r2=mysql_query("select * from demog_enumvals where demog_id='$dependent_id' and deleted='no'");
            if ($r2 && mysql_num_rows($r2)) {
                while ($w=mysql_fetch_array($r2)) {
                    if (in_array($w["id"],$dependent_array)) {
                        $sel="checked";
                    }
                    else {
                        $sel="";
                    }
                    $ev=htmlspecialchars($w["enum_option"]);
                    $owidget.="<input type='checkbox' name='dependent_value$w[id]' value='1' $sel> $ev<br>";
                }
            }
        }
        elseif ($widget=="matrix") {
            $dependent_array=explode(",",$dependent_value);
            $owidget="<input type='hidden' name='dependent_value' value=''><table border=0 cellspacing=1 cellpadding=1 width='100%' style='border: 1px solid $_MX_var->main_table_border_color; background-color: $_MX_var->main_table_border_color;'><tr><td class='bgwhite'> </td>\n";
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
                $owidget.="<td class='bgwhite'><span class='szoveg'>".htmlspecialchars($rows[$i][2])." | ".htmlspecialchars($rows[$i][0])."</span></td>\n";
            }
            for ($j=0;$j<count($cols);$j++) {
                $owidget.="</tr><tr><td class='bgwhite'><span class='szoveg'>".htmlspecialchars($cols[$j][2]) . " | " . htmlspecialchars($cols[$j][0])."</span></td>\n";
                for ($i=0;$i<count($rows);$i++) {
                    $value=$cols[$j][1]."_".$rows[$i][1];
                    if (in_array($value,$dependent_array)) {
                        $sel="checked";
                    }
                    else {
                        $sel="";
                    }
                    $owidget.="<td class='bgwhite'><input type='checkbox' name='dependent_value".$dependent_id."_$value' value='1' $sel></td>\n";
                }
            }
            $owidget.="</tr></table>"; 
        }
        else {
            $owidget="<input name='dependent_value' value=\"$dependent_value\" style='width:$formw"."px;'>";
        }
    }
    elseif ($meta[0]=="textarea") {
        $owidget="<textarea name='$field' style='width:$formw"."px; height:150px;' class='oinput'>$value</textarea>";
    }
    else {
        $owidget="<input name='$field' value=\"$value\" style='width:$formw"."px;' maxlength='$meta[2]' class='oinput'/>";
    }
    print "<tr><td width='100' class='bgvilagos2'><span class=szoveg>$varname</span></td>
               <td width='670' class='bgvilagos2'><span class=szoveg>$owidget</span></td></tr>\n";
}
print "<TR><TD align='center' colspan='2'><INPUT class='tovabbgomb' type=submit name='saveall' value=\"$word[submit3]\"></TD></TR>
       </TABLE>
       </form>\n";	  
  
include "footer.php";
?>
