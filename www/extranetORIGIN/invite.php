<?

header ("location: index.php");
exit;

/*
this script is not in use any more, it is left here to avoid eventual broken links.
the script is NOT COMPATIBLE with some newer features, for example matrices.
removed 2004-12-14
*/

include "auth.php";
$weare=29;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/invite.lang";
include "./lang/$language/members_demog_info.lang";

$mres = mysql_query("select title,invite_text from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }
$title=$rowg["title"];

$vipinfo=" vip_demog.group_id='$group_id' ";

$affiliate_id=$active_userid;

if (!$hist_back)
    $hist_back=1;

$error="";
$finished=0;
  
if ($enter=="yes") {
    $finished=1;
    $r5=mysql_query("select id from users_$title where ui_email='$demvars[email]' and robinson='no'");
    if ($r5 && mysql_num_rows($r5)) 
        $error.="<tr><td><span class='szovegvastag'>$demvars[email] $word[is_already_member].</span></td></tr>";
    VerifyDemog();
    if (!empty($error)) 
         $finished=0;
}
  
include "menugen.php";

PrintHead();
echo $error;
  
if (!$finished) {
    $hist_back++;
    echo "<tr>
        <td class=szoveg align=justify>
        <table border=0 cellspacing=0 cellpadding=5 width=100% bgcolor=#eeeeee>
        <form method=post name='mainform'>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='hist_back' value='$hist_back'>
        <input type='hidden' name='enter' value='yes'>\n";
    PrintDemog();
    echo "<tr>
        <td align=center colspan=2>
        <input type=button class='tovabbgomb' value='$word[submit]' onclick='CheckAll()'>
        </td>
        </tr>                
        </form>
        </table>\n";
}
else {
    $to="hidden-subscribe@$title.maxima.hu";
    $subject="aff:$active_userid";
    $body="";
    $res=mysql_query("select demog.*,vip_demog.mandatory from demog,vip_demog 
                      where demog.id=vip_demog.demog_id and $vipinfo and vip_demog.ask='yes'");
    if ($res && mysql_num_rows($res))
        while ($k=mysql_fetch_array($res)) {
            $variable_name=$k["variable_name"];
            if ($variable_name=="email")
                $username=$demvars["$variable_name"];
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
                $body.="# $variable_name:$value\n";           
            }
            elseif ($k["variable_type"]=="enum" && $k["multiselect"]=="yes") {
                $r9=mysql_query("select * from demog_enumvals where demog_id='$k[id]' and deleted='no'");
                    if ($r9 && mysql_num_rows($r9))
                        while ($k9=mysql_fetch_array($r9)) {
                            $multisel_varname="$variable_name-$k9[id]";        
                            if (!empty($demvars[$multisel_varname])) {
                                $value=$demvars[$multisel_varname];
                                $body.="# $variable_name:$value\n";
                            }
                        }
            }
            else {
                $value=$demvars["$variable_name"];
                $body.="# $variable_name:$value\n";
            }
        }        
    $body.="##end##\n";
    
    mail($to,$subject,$body,"From: $username");
    //echo nl2br(htmlspecialchars("$to,$subject,$body,\"From: $username"));
    //echo nl2br(htmlspecialchars("\n\n$body\n\n"));

    $hist_back=-$hist_back;
    echo "<tr><td>
        <table border=0 cellspacing=0 cellpadding=0 width=100% bgcolor=#eeeeee>
        <tr><td>
        <span class=szovegvastag><a href='invite.php?group_id=$group_id&title=$title'>
        $word[new_member]</a></span>
        </td></tr>
        </table>
        </td>
        </tr>\n";
}

PrintFoot();


#############################################################
function PrintHead()
{
  global $_MX_var,$special,$custom_header,$landing2,$word,$title,$landingpage2,$finished,$group_id;
  global $_MX_var,$active_userid;

   if (!$finished)
      MakeJava();

   echo "<center>
   <span class='szovegvastag'>$word[take_new_member]</span>
   <table border=0 cellspacing=0 cellpadding=1 bgcolor=$_MX_var->main_table_border_color width=600>
   <tr>
   <td align=center>
   <table border=0 cellspacing=0 cellpadding=5 width=100% bgcolor=#eeeeee>
";
}
#############################################################
function PrintFoot()
{
  global $_MX_var,$special,$custom_footer,$landingpage2,$finished;

   echo "
        </table>
        </td>
   </tr>
   </table>
   </center>
";
  include "footer.php";
  
}
#############################################################
function PrintDemog()
{
  global $_MX_var,$word,$demvars,$group_id,$multi,$vipinfo;
  
  $res=mysql_query("select demog.*,vip_demog.mandatory from demog,vip_demog 
                    where demog.id=vip_demog.demog_id and $vipinfo and vip_demog.ask='yes'");
  if ($res && mysql_num_rows($res))
     while ($k=mysql_fetch_array($res)) {
        $variable_name=$k["variable_name"];
        $value=$demvars["$variable_name"];
        echo "<tr>
              <td valign='top' width='50%'><span class='szoveg'>&nbsp;&nbsp;$k[question]&nbsp;</span></td>";
        if ($k["variable_type"]=="text" || $k["variable_type"]=="number" || $k["variable_type"]=="nick" || 
            $k["variable_type"]=="phone" || $k["variable_type"]=="email")
           echo "<td valign='top'>
              <input class=formframe type='text' name='demvars[$variable_name]' value='$value'>
              </td>";
        if ($k["variable_type"]=="date") {
           $yname=$variable_name."-year";
           $year=intval($demvars["$yname"]);
           $mname=$variable_name."-month";
           $month=intval($demvars["$mname"]);
           $dname=$variable_name."-day";
           $day=intval($demvars["$dname"]);
           echo "<td valign='top'>
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
           echo "<td valign='top'>
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
           echo "<td>&nbsp;</td></tr>";
           $r9=mysql_query("select * from demog_enumvals where demog_id='$k[id]' and deleted='no'");
           if ($r9 && mysql_num_rows($r9))
              while ($k9=mysql_fetch_array($r9)) {
                 $val=$k9["id"];
                 $val2=$k9["enum_option"];
                 $multisel_varname="$variable_name-$k9[id]";
                 if ($val==$demvars[$multisel_varname])
                    $sel="checked";
                 else
                    $sel="";
                 echo "<tr><td><span class='szoveg'>&nbsp;&nbsp;$val2</span></td><td>
                    <input type='checkbox' value='$val' name='demvars[$multisel_varname]' $sel></td></tr>";
              }
           echo "<tr><td colspan='2'><img src='$_MX_var->application_instance/gfx/shim.gif' height='2' width='2'></td>";
        }
        echo "</tr>";
     }

}
#############################################################
function VerifyDemog()
{
  global $_MX_var,$word,$demvars,$group_id,$finished,$error,$multi,$vipinfo;
  
  $res=mysql_query("select demog.*,vip_demog.mandatory from demog,vip_demog 
                      where demog.id=vip_demog.demog_id and $vipinfo and vip_demog.ask='yes'");
  if ($res && mysql_num_rows($res))
     while ($k=mysql_fetch_array($res)) {
        $variable_name=$k["variable_name"];
        $value=$demvars["$variable_name"];
        if (($k["variable_type"]=="text" || $k["variable_type"]=="number" || $k["variable_type"]=="nick" 
            || $k["variable_type"]=="phone" || $k["variable_type"]=="email") 
            && $k["mandatory"]=="yes" && trim($value)=="") 
           $error.= "<tr><td><span class='szovegvastag'>
              $word[demog_err_1a] &quot;$k[question]&quot; $word[demog_err_1b].</span></td></tr>"; 
        if ($k["variable_type"]=="number" && trim($value)!="" && !ereg("^[0-9]+$",$value)) 
           $error.= "<tr><td><span class='szovegvastag'>
              $word[az] &quot;$k[question]&quot; $word[demog_err_2].</span></td></tr>"; 
        if ($k["variable_type"]=="phone" && trim($value)!="" && !ereg("^\+?[0-9]+$",$value)) 
           $error.= "<tr><td><span class='szovegvastag'>
              $word[az] &quot;$k[question]&quot; $word[demog_err_3].</span></td></tr>"; 
        if ($k["variable_type"]=="email" && trim($value)!="" && 
           !eregi("^[\.\+_a-z0-9-]+@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+[a-z]{2}[mtgvu]?$", $value) ) 
           $error.= "<tr><td><span class='szovegvastag'>
              $word[demog_err_4]: $value.</span></td></tr>"; 
        if ($k["variable_type"]=="nick" && trim($value)!="" && !eregi("^[\.\+_a-z0-9-]+$", $value) ) 
           $error.= "<tr><td><span class='szovegvastag'>
              $word[az] &quot;$k[question]&quot; $word[demog_err_5].</span></td></tr>"; 
        if ($k["variable_type"]=="date") {
           $yname=$variable_name."-year";
           $year=intval($demvars["$yname"]);
           $mname=$variable_name."-month";
           $month=intval($demvars["$mname"]);
           $dname=$variable_name."-day";
           $day=intval($demvars["$dname"]);
           if ($k["mandatory"]=="yes" && $year==0 && $month==0 && $day==0)
              $error.= "<tr><td><span class='szovegvastag'>
                 $word[demog_err_1a] &quot;$k[question]&quot; $word[demog_err_1b].</span></td></tr>";
           if (!($year==0 && $month==0 && $day==0) && !checkdate ($month, $day, $year))
              $error.= "<tr><td><span class='szovegvastag'>
                 $word[demog_err_6]: $year-$month-$day</span></td></tr>";
        }
        if ($k["variable_type"]=="enum" && $k["mandatory"]=="yes" && $k["multiselect"]=="no" && $value==0) 
           $error.= "<tr><td><span class='szovegvastag'>
              $word[demog_err_7a] &quot;$k[question]&quot; $word[demog_err_7b].</span></td></tr>"; 
        }
}

#############################################################
function MakeJava()
{
  global $_MX_var,$word,$demvars,$group_id,$username,$multi,$demog_info,$special,$vipinfo;

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
  
  $elements=3;
  $res=mysql_query("select demog.*,vip_demog.mandatory from demog,vip_demog 
                    where demog.id=vip_demog.demog_id and $vipinfo and vip_demog.ask='yes'");
  if ($res && mysql_num_rows($res))
     while ($k=mysql_fetch_array($res)) {
        $variable_name=$k["variable_name"];
        $value=$demvars["$variable_name"];
        $question=addslashes($k[question]);
        if ($k["variable_type"]=="text" || $k["variable_type"]=="number" || $k["variable_type"]=="nick" 
           || $k["variable_type"]=="phone" || $k["variable_type"]=="email") {
           echo "
              val=document.mainform.elements[$elements].value;
              i=CheckEmpty(val);
           ";           
           if($k["mandatory"]=="yes")
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
           if ($k["mandatory"]=="yes")
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
        if ($k["variable_type"]=="enum" && $k["mandatory"]=="yes" && $k["multiselect"]=="no") 
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
              if ($k["mandatory"]=="yes") {
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
