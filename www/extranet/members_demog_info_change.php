<?
include "auth.php";
include "decode.php";
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/members_demog_info.lang";  
include "./lang/$language/members.lang";
include "_filter.php";

$_MX_popup = 1;
include "menugen.php";

$demog_id = get_http('demog_id','');
$group_id = get_http('group_id','');
$user_id = get_http('user_id','');
$filt_demog = get_http('filt_demog','');
$enter = get_http('enter','');
$a = get_http('a','');
if (isset($_POST['demvars'])) {
    $demvars = $_POST['demvars'];
}
else {
    $demvars = array();
}
$error = "";
$warn_empty = "no";

$user_id=intval($user_id);
$filt_demog=intval($filt_demog);
$group_id=intval($group_id);
$demog_id=intval($demog_id);
$mres = mysql_query("select groups.id,title,num_of_mess,unique_col from groups,members where groups.id=members.group_id
                   and groups.id='$group_id' and (membership='owner' or membership='moderator' or membership='support')
                   and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres)) {
    $rowg=mysql_fetch_array($mres);  
}
else {
    exit;
} 
$title=$rowg["title"];
$unique_col=$rowg["unique_col"];

$ru=mysql_query("select * from users_$title where id='$user_id'");
$rf=mysql_query("select * from filter where id='$filt_demog' and group_id='$group_id'");
if ($ru && mysql_num_rows($ru)) {
    $user_data=mysql_fetch_array($ru);
    $scope="user";
}
elseif ($rf && mysql_num_rows($rf)) {
    $filter_data=mysql_fetch_array($rf);
    $scope="filter";
}
else {
    print "$word[no_such_user]";
    exit;
}

if ($scope == "user") {
    echo "<span class='szovegvastag'>$word[scope_user]:</span><span class='szoveg'> " . $user_data["ui_$rowg[unique_col]"] . "<br><br></span>";
}
if ($scope == "filter") {
    $_MX_filter = new MxFilter($rowg,1);
    $_MX_filter->GetParams();
    $_MX_filter->params["show_user_list"] = "no";
    $_MX_filter->GetSql();
    if (empty($_MX_filter->update_query)) {
        exit;
    }
    echo "<span class='szovegvastag'>$word[scope_filter]:</span><span class='szoveg'>$filter_data[name]<br><br>$_MX_filter->stat_text<br><br><a target='_blank' href='mygroups14.php?group_id=$group_id&filt_demog=$filt_demog&demog_id=$demog_id'>$word[md_statistics]</a><br><br></span>";
}

$finished=0;
$error="";
if ($enter=="yes") {
    $finished=1;
    VerifyDemog();
    if (!empty($error)) {
        $finished=0;
    }
}
if ($finished) {
    $res=mysql_query("select * from demog where id='$demog_id'");
    if ($k=mysql_fetch_array($res)) {
        $variable_name=$k["variable_name"];
        if ($k["variable_type"]=="date" || $k["variable_type"]=="enum" || $k["variable_type"]=="number")
           $vt=$k["variable_type"];
        else
           $vt="other";
        $multienum=0;
        if ($k["variable_type"]=="date") {
           $yname=$variable_name."-year";
           $year=intval($demvars["$yname"]);
           $mname=$variable_name."-month";
           $month=intval($demvars["$mname"]);
           $dname=$variable_name."-day";
           $day=intval($demvars["$dname"]);
           $month0=$month<10?"0$month":"$month";
           $day0=$day<10?"0$day":"$day";
           $value="$year-$month0-$day0";
        }
        elseif ($k["variable_type"]=="enum" && $k["multiselect"]=="yes") {
           $value=",";
           $r9=mysql_query("select * from demog_enumvals where demog_id='$demog_id' and deleted='no'");
           if ($r9 && mysql_num_rows($r9))
              while ($k9=mysql_fetch_array($r9)) {
                 $multisel_varname="$variable_name-$k9[id]";        
                 if (!empty($demvars[$multisel_varname])) {
                    $value.="$demvars[$multisel_varname],";
                 }
              }
        }
        elseif ($k["variable_type"]=="enum" && $k["multiselect"]=="no") 
           $value=",$demvars[$variable_name],";
        else 
           $value=$demvars["$variable_name"];
        $value=slasher($value);
        $query = "update users_$title set ui_$variable_name='$value',tstamp=now(),data_changed=now() where ";
        if ($scope == "filter" && !empty($_MX_filter->update_query)) {
            $query .= $_MX_filter->update_query;
            $logger_info = "by $scope, filter_id=$filt_demog, total=$_MX_filter->maxrecords";
        }
        else {
            $query .= "id='$user_id'";
            $logger_info = "by $scope $user_id";
        }
        //print "$query<br>";
        logger($query,$group_id,"update",$logger_info,"users_$rowg[title]");
        mysql_query($query);
        $error = $word["update_successful"];
     }        
}
  
MakeJava();

PrintHead();
  
  echo "
<tr>
<td valign='center' align='left'>
</td>
</tr>
<tr>
<td>
<table width='100%' border=0 cellspacing=0 cellpadding=1 bgcolor='white'>
<form name='mainform' method='post'>
<input type='hidden' name='enter' value='yes'>  
<tr>
<td colspan=2><span class='szovegvastag' style='color:red;'>$error</span></td>
</tr>
";

   PrintDemog();

   echo "
<tr>
<td class=INPUTNAME valign='top' colspan=2 align='center'>
<input class='tovabbgomb' type='button' value='$word[submit]' name='a' onclick='CheckAll()'><br>&nbsp;
</td>
</tr>
<tr>
<td class=INPUTNAME valign='top' colspan=2 align='center'>
<a href='$_MX_var->baseUrl/members_demog_info.php?group_id=$group_id&user_id=$user_id&filt_demog=$filt_demog'>$word[back]</a>&nbsp;
</td>
</tr>
</form>
";

PrintFoot();


#############################################################
function PrintHead()
{
  global $_MX_var,$error,$word;

  echo "<br>
<table width='100%' border='0' cellspacing='0' cellpadding='0'>
<tr>
<td valign='top'  width='100%' align='left'>
<table border=0 cellspacing=0 cellpadding=1 width='100%'>
";

}
#############################################################
function PrintFoot()
{
   echo "
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
";

include "footer.php"; 

}
#############################################################
function PrintDemog()
{
  global $_MX_var,$word,$demvars,$enter,$active_userid,$multi_id,$user_id,$group_id,$demog_id,$title,$scope;
  
  $res=mysql_query("select * from demog where id='$demog_id'");
  if ($res && mysql_num_rows($res))
     while ($k=mysql_fetch_array($res)) {
        $variable_name=$k["variable_name"];
        if ($enter=="yes" && !empty($demvars["$variable_name"])) {
            $value=htmlspecialchars($demvars["$variable_name"]);
        }
        elseif ($scope == "filter") {
            $value="";
        }
        else {
          $r5=mysql_query("select ui_$variable_name from users_$title where id='$user_id'");
          if ($r5 && mysql_num_rows($r5)) {
             $value=htmlspecialchars(mysql_result($r5,0,0));
          }
        }
                   
        echo "<tr>
           <td class=INPUTNAME valign='top'><span class='szoveg'>&nbsp;&nbsp;$k[question]&nbsp;</span></td>";
        if ($k["variable_type"]=="text" || $k["variable_type"]=="number" || $k["variable_type"]=="nick" || 
            $k["variable_type"]=="phone" || $k["variable_type"]=="email") {
			$widget=$k["variable_type"]=="text"?"<textarea style='width:600px;height:300px;' class=formframe name='demvars[$variable_name]'>$value</textarea>":"<input sytle='width:600px;' class=formframe type='text' name='demvars[$variable_name]' value=\"$value\">";
           echo "<td class=INPUTFIELD valign='top'>
              $widget
              </td>";
        }
        if ($k["variable_type"]=="date") {
           $yname=$variable_name."-year";
           $mname=$variable_name."-month";
           $dname=$variable_name."-day";
           if ($enter!="yes") {
              $year=intval(substr($value,0,4));
              $month=intval(substr($value,5,2));
              $day=intval(substr($value,8,2));
           }
           else {
              $year=intval($demvars["$yname"]);
              $month=intval($demvars["$mname"]);
              $day=intval($demvars["$dname"]);
           }
           echo "<td class=INPUTFIELD valign='top'>
              <span class='szoveg'>$word[year]</span>
              <input class=formframe type='text' name='demvars[$yname]' value='$year' size='4'>
              <select name='demvars[$mname]'>
              <option value='0'>$word[not_specified]</option>";
           for ($i=1;$i<=12;$i++) {
              if ($i==$month)
                 $sel="selected";
              else
                 $sel="";
              $month0=$i<10?"m0$i":"m$i";                 
              echo "<option value=$i $sel>$word[$month0]</option>";
           }
           echo "</select>
              <select name='demvars[$dname]'>
              <option value='0'>$word[not_specified]</option>";
           for ($i=1;$i<=31;$i++) {
              if ($i==$day)
                 $sel="selected";
              else
                 $sel="";
              echo "<option value=$i $sel>$i</option>";
           }
           echo "</select>
              </td>";
        }
        if ($k["variable_type"]=="enum" && $k["multiselect"]=="no") {
           if ($enter!="yes") {
              $cmm=explode(",",$value);
              if (is_array($cmm)) {
                while (list(,$enval)=each($cmm)) {
                    $enval=intval($enval);
                    if ($enval)
                      $demvars[$variable_name]=$enval;
                }
              }
           }
           echo "<td class=INPUTFIELD valign='top'>
              <select name='demvars[$variable_name]'>
              <option value='0'>$word[not_specified]</option>";
           $r9=mysql_query("select * from demog_enumvals where demog_id='$k[id]' and deleted='no'");
           if ($r9 && mysql_num_rows($r9))
              while ($k9=mysql_fetch_array($r9)) {
                 $val=$k9["id"];
                 $val2=$k9["enum_option"];
                 if ($val==$demvars[$variable_name])
                    $sel="selected";
                 else
                    $sel="";
                 echo "<option value='$val' $sel>$val2</option>";
              }
           echo "</select>              
              </td>";
        }
        if ($k["variable_type"]=="enum" && $k["multiselect"]=="yes") {
           echo "<td class=INPUTFIELD>&nbsp;</td></tr>";
           $r9=mysql_query("select * from demog_enumvals where demog_id='$k[id]' and deleted='no'");
           if ($r9 && mysql_num_rows($r9))
              while ($k9=mysql_fetch_array($r9)) {
                 $val=$k9["id"];
                 $val2=$k9["enum_option"];
                 $multisel_varname="$variable_name-$k9[id]";
                 if ($enter!="yes") {
                    if (ereg(",$val,",$value))
                       $demvars[$multisel_varname]=$val;
                    else
                       $demvars[$multisel_varname]=0;
                 }
                 if (isset($demvars[$multisel_varname]) && $val==$demvars[$multisel_varname])
                    $sel="checked";
                 else
                    $sel="";
                 echo "<tr><td class=INPUTNAME valign='top' colspan=2>
                       &nbsp;&nbsp;<input type='checkbox' value='$val' name='demvars[$multisel_varname]' $sel>
                       <span class=szoveg>$val2</span></td></tr>";
              }
           echo "<tr><td colspan='2'><img src='$_MX_var->application_instance/gfx/spacer.gif' height='2' width='2'></td>";
        }
        echo "</tr>";
     }

}
#############################################################
function VerifyDemog()
{
    global $_MX_var,$word,$demvars,$group_id,$finished,$error,$multi,$multi_id,$demog_id,$unique_col,$title,$warn_empty;
  
    $res=mysql_query("select * from demog where id='$demog_id'");
  
    while ($k=mysql_fetch_array($res)) {
        $variable_name=$k["variable_name"];
        $value = "";
        if (isset($demvars["$variable_name"])) {
            $value=slasher($demvars["$variable_name"],0);
        }
        if (($k["variable_type"]=="text" || $k["variable_type"]=="number" || $k["variable_type"]=="nick"
            || $k["variable_type"]=="phone" || $k["variable_type"]=="email")
            && $warn_empty=="yes" && trim($value)=="")
           $error.= "$word[demog_err_1a] &quot;$k[question]&quot; $word[demog_err_1b].<br>";
        if ($k["variable_type"]=="number" && trim($value)!="" && !ereg("^[0-9]+$",$value)) 
           $error.= "$word[az] &quot;$k[question]&quot; $word[demog_err_2].<br>"; 
        if ($k["variable_type"]=="phone" && trim($value)!="" && !ereg("^\+?[0-9]+$",$value)) 
           $error.= "$word[az] &quot;$k[question]&quot; $word[demog_err_3].<br>"; 
        if ($k["variable_type"]=="email" && trim($value)!="" && 
           !eregi("^[\.\+_a-z0-9-]+@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+[a-z]{2}[mtgvu]?$", $value) ) 
           $error.= "$word[demog_err_4]: $value.<br>"; 
        if ($k["variable_type"]=="nick" && trim($value)!="" && !eregi("^[\.\+_a-z0-9-]+$", $value) ) 
           $error.= "$word[az] &quot;$k[question]&quot; $word[demog_err_5].<br>"; 
        if ($k["variable_type"]=="date") {
           $yname=$variable_name."-year";
           $year=intval($demvars["$yname"]);
           $mname=$variable_name."-month";
           $month=intval($demvars["$mname"]);
           $dname=$variable_name."-day";
           $day=intval($demvars["$dname"]);
           if ($warn_empty=="yes" && $year==0 && $month==0 && $day==0)
              $error.= "$word[demog_err_1a] &quot;$k[question]&quot; $word[demog_err_1b].<br>";
           if (!($year==0 && $month==0 && $day==0) && !checkdate ($month, $day, $year))
              $error.= "$word[demog_err_6]: $year-$month-$day<br>";
        }
        if ($k["variable_type"]=="enum" && $warn_empty=="yes" && $k["multiselect"]=="no" && $value==0) {
           $error.= "$word[demog_err_7a] &quot;$k[question]&quot; $word[demog_err_7b].<br>"; 
        }
    }
    if ($variable_name==$unique_col) {
        if ($scope!="user" && !isset($_GET["mass_demog"])) {
            $error.="Can not update unique demog for mutiple users.<br>";
        }
        else {
            $svalue=addslashes($value);
            $hvalue=htmlspecialchars($value);
            $res=mysql_query("select id from users_$title where ui_$unique_col='$svalue'");
            if ($res && mysql_num_rows($res)) {
                 $error.= "$word[demog_err_8a] &quot;$hvalue&quot; $word[demog_err_8b].<br>"; 
            }
        }
    }        
}

#############################################################
function MakeJava()
{
  global $_MX_var,$word,$demvars,$changenick,$multi_id,$group_id,$demog_id,$warn_empty,$scope;

echo "<script>
   function CheckEmpty(val) {
      len=val.length;
      i=0;
      for(position=0;position<len;position++) {
         if(val.charAt(position)!=' ' && val.charAt(position)!='\\t' 
            && val.charAt(position)!='\\n') { i++; } }   
      return i;
   }
   function CheckAll() {
";
    if ($scope != "user") {
        print "
            if (!confirm('$word[multiupdate_warn]')) {
                return false;
            }
        ";
    }

  $res=mysql_query("select * from demog where id='$demog_id'");
  $elements=1;
  if ($res && mysql_num_rows($res))
     while ($k=mysql_fetch_array($res)) {
        $variable_name=$k["variable_name"];
        $value="";
        if (!empty($demvars["$variable_name"])) {
            $value=$demvars["$variable_name"];
        }
        $question=addslashes($k["question"]);
        if ($k["variable_type"]=="text" || $k["variable_type"]=="number" || $k["variable_type"]=="nick" 
           || $k["variable_type"]=="phone" || $k["variable_type"]=="email") {
           echo "
              val=document.mainform.elements[$elements].value;
              i=CheckEmpty(val);
           ";           
          if($warn_empty=="yes")
              echo "
                 if(!i) {
                    alert(\"$word[demog_err_1a] '$question' $word[demog_err_1b].\");
                    return;
                 }
              ";
        }
        if ($k["variable_type"]=="number") 
           echo "
              if(i && val.search(new RegExp(\"^[0-9]+$\",\"gi\"))<0) {
                 alert(\"$word[az] '$question' $word[demog_err_2].\");
                 return;
              }
           "; 
        if ($k["variable_type"]=="phone") 
           echo "
              if(i && val.search(new RegExp(\"^[\+]?[0-9]+$\",\"gi\"))<0) {
                 alert(\"$word[az] '$question' $word[demog_err_3].\");
                 return;
              }
           "; 
        if ($k["variable_type"]=="email") 
           echo "
              if(i && val.search(new RegExp(\"^[\.\+_a-z0-9-]+@([0-9a-z][0-9a-z-]*[0-9a-z][\.])+[a-z][a-z][mtgvu]?$\",\"gi\"))<0) {
                 alert(\"$word[demog_err_4]: \"+val);
                 return;
              }
           "; 
        if ($k["variable_type"]=="nick") 
           echo "
              if(i && val.search(new RegExp(\"^[\.\+_a-z0-9-]+$\",\"gi\"))<0) {
                 alert(\"$word[az] '$question' $word[demog_err_5].\");
                 return;
              }
           "; 
        if ($k["variable_type"]=="date") {
           $elem1=$elements++;
           $elem2=$elements++;
           $elem3=$elements;
           echo "
              year=document.mainform.elements[$elem1].value;
              mi=document.mainform.elements[$elem2].selectedIndex;
              month=document.mainform.elements[$elem2].options[mi].value-1;
              di=document.mainform.elements[$elem3].selectedIndex;
              day=document.mainform.elements[$elem3].options[di].value*1;              
           "; 
           if ($warn_empty=="yes")
              echo "
                 if(year<1 && month<1 && day<1) {
                    alert(\"$word[demog_err_1a] '$question' $word[demog_err_1b].\");
                    return;
                 }
              ";
              echo "
                 if(!(year<1 && month<1 && day<1)) {
                    d=new Date(year,month,day);
                    month1=d.getMonth()+1;
                    month++;
                    day1=d.getDate();
                    if(month1!=month || day1!=day) {
                       alert(\"$word[demog_err_6]: \"+year+\"-\"+month+\"-\"+day+\".\"); 
                       return;
                    }
                 }
              ";           
        }
        if ($k["variable_type"]=="enum" && $warn_empty=="yes" && $k["multiselect"]=="no") 
              echo "
                 if(document.mainform.elements[$elements].selectedIndex==0) {
                    alert(\"$word[demog_err_7a] '$question' $word[demog_err_7b].\");
                    return;
                 }
              ";
        if ($k["variable_type"]=="enum" && $k["multiselect"]=="yes") {
           $r9=mysql_query("select count(*) from demog_enumvals where demog_id='$k[id]' and deleted='no'");
           if ($r9 && mysql_num_rows($r9)) {
              $boxnum = mysql_result($r9,0,0);
              $boxend = $elements+$boxnum;
              if ($warn_empty=="yes") {
                 echo "
                   var i=0;
                   var k=0;
                   for(i=$elements; i < $boxend; i++) {
                   if (document.mainform.elements[i].type == 'checkbox')
                   {  
                      if (document.mainform.elements[i].checked == true)
                      { k++; }
                   }
                   }              
                   if (!k) {
                        alert(\"$word[demog_err_7a] '$question' $word[demog_err_7b].\");
                        return;
                   }                   
                 ";
              }
              $elements=$elements+$boxnum-1;
            }
        }
     $elements++;
}
  echo "
    document.mainform.submit();
  }
</script>
";

}
?>
