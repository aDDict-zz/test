<?
include "auth.php";
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/filter.lang";

$pagenum=get_http("pagenum","");
$first=get_http("first","");
$Wssortt=get_cookie('Wssortt');
$Wstperpage=get_cookie('Wstperpage');
$sortt = (isset($_GET['sortt']) || empty($Wssortt)) ? get_http('sortt',4) : $Wssortt;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Wstperpage)) ? get_http('maxPerPage',5) : $Wstperpage;
$letterfilt = get_http('letterfilt','');
$antiallgroups=$allgroups?0:1;
setcookie("Wssortt",$sortt,time()+30*24*3600);
setcookie("Wstperpage",$maxPerPage,time()+30*24*3600);

$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 20;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;

/**/

if (!ereg("^[a-z]$",$letterfilt))
    $letterfilt="";
if (strlen($letterfilt))
    $lqpart="and name like '$letterfilt%'";
else
    $lqpart="";


$_MX_popup = 1;
include "menugen.php";


echo      "<script language='JavaScript'>
      function addtoparent(spectext) {
        opener.addBody(spectext);
        window.close();
      }
      function addid(id,wizard) {
        opener.addid(id,wizard);
        window.close();
      }
        function f_go(letter) {
            location='filter_select.php?group_id=$group_id&letterfilt='+letter;
        }
      </script>";
$group_id=intval($group_id);
$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator')
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else
    exit; 
$title=$rowg["title"];

$res=mysql_query("select count(*) from filter where group_id='$group_id' $lqpart");
if ($res && mysql_num_rows($res))
    $maxrecords=mysql_result($res,0,0);
else
    $maxrecords=0;
    
if($first+$maxPerPage<=$maxrecords) $mHere = $first+$maxPerPage;           
else $mHere = $maxrecords;                                                 
if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                         
PrintHead();

if (!$sortt)
    $sortt=1;

switch($sortt) {
    case 1: $order = "order by name asc"; break;
    case 2: $order = "order by name desc"; break;
}
  
    /*$nest="<select name='q_enums$i' onchange=\"addBody('[spec_nest_'+this.form.q_enums$i.options[this.form.q_enums$i.selectedIndex].value+']')\"><option value=' '>$word[spec_nest]</option>";
    $res=mysql_query("select * from filter where group_id='$group_id' and id!='$id' order by name");
    if ($res && mysql_num_rows($res)) 
        while ($k=mysql_fetch_array($res)) 
            $nest.="<option value='$k[name]'>$k[name]</option>";
    $nest.="</select>";
    $v_vsel[1].="<br>$nest";*/


$spectext="spec_nest_";

$res=mysql_query("select * from filter where group_id='$group_id' $lqpart $order limit $first,$maxPerPage");

if ($res && mysql_num_rows($res)) { 
    $pagenum=(int)($first / $maxPerPage) + 1;
    $maxpages = ceil($maxrecords / $maxPerPage);
    PrintNavigation($maxpages,$pagenum);
    print "
        <TR align=middle>
        <TD class=bgkiemelt2 width='30%'><span class='szovegvastag'>$word[select_filter]</span></TD>
        <TD class=bgkiemelt2 width='70%'><span class='szovegvastag'>$word[ft_name]</span></TD>
        </TR>\n";
    $i=0;
    while($row=mysql_fetch_array($res)) {
        if (empty($row['message']))
            $message="[ $word[no_subject] ]"; 
        else  
            $message=$row['message'];
        $tdclass="class='BACKCOLOR bbordercolor ".($i%2==1?'bgvilagos2':'')."'";
        $jsfunc="addtoparent('[$spectext$row[name]]')";
        print "<TR>
               <TD align=middle $tdclass vAlign=top width='30%'>
               <SPAN class=szoveg><a href=\"javascript:$jsfunc\">$word[select_filter]</a>&nbsp;</span></TD>
               <TD $tdclass vAlign=top width='70%'><SPAN class=szoveg>"
               .htmlspecialchars($row["name"]);
        print "</SPAN></TD>
               </TR>\n";
        $i++;
    }
    PrintNavigation($maxpages,$pagenum);
} 
else 
    print "<tr>    
           <td align='left' class=COLUMN1>
           <span class='szovegvastag'>$word[no_messages]</span></td>
           </tr>\n";
 
echo"
</TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
";  

//****[ PrintHead ]***********************************************************
function PrintHead() {

    global $_MX_var,$letterfilt;

    echo "<TABLE border=0 cellPadding=0 cellSpacing=0 width='100%'>
          <TBODY>
          <TR>
          <TD class=formmezo>
          <TABLE border=0 cellPadding=3 cellSpacing=1 width='100%'>
          <TBODY>
          <tr><td colspan=2 class='BACKCOLOR'><span class='szovegvastag'>\n";
    for ($i=65;$i<91;$i++) {
        $char=chr($i);
        $fchar=strtolower($char);
        if ($fchar==$letterfilt)
            echo "&nbsp;&nbsp;$char&nbsp;&nbsp;&nbsp;";
        else
            echo "<a href=\"javascript:f_go('$fchar');\">$char</a> ";
    }
    echo "<a href=\"javascript:f_go('all');\">Mind</a></span></td></tr>\n";
}


//****[ PrintNavigation ]*****************************************************
function PrintNavigation($maxpages, $pagenum) {

    global $_MX_var,$word,$group_id,$sortt,$maxPerPage,$maxrecords,$first,$wizard,$spec,$letterfilt;

  $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
  $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
  $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;
  $sel_sort[$sortt] = "selected";
  $params="group_id=$group_id&spec=$spec&wizard=$wizard&letterfilt=$letterfilt";  

    echo "<tr><td colspan=4><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
        <tr>
        <td>
        <table border=0 cellspacing=0 cellpadding=0 width='100%'>
        <tr>
        <td class='formmezo' align='left' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
        <form name=inputs>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='spec' value='$spec'>
        <input type='hidden' name='wizard' value='$wizard'>
        <input type='hidden' name='letterfilt' value='$letterfilt'>
        <tr>\n";
    if ($first>0)
        echo "<td nowrap align='right'>
           <a href='$_MX_var->baseUrl/filter_select.php?$params&first=0'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
           <a href='$_MX_var->baseUrl/filter_select.php?$params&first=$OnePageLeft'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
           </td>\n";
    else
        echo "<td nowrap align='right'>&nbsp;&nbsp;
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1_dead.gif' width='20' height='14' border='0'>
              </td>\n";
    echo "<td nowrap align='right'> 
          <input type='text' name='pagenum' size='3' maxlength='3' value='$pagenum'>
          </td>
          <td nowrap class='formmezo'>&nbsp;/ $maxpages</td>\n";
    if ($first<$LastPage)
        echo "<td nowrap align='right'> &nbsp;
            <a href='$_MX_var->baseUrl/filter_select.php?$params&first=$OnePageRight'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
            <a href='$_MX_var->baseUrl/filter_select.php?$params&first=$LastPage'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>
            &nbsp; </td>\n";
    else
        echo "<td nowrap align='right'>&nbsp;&nbsp;
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2_dead.gif' width='20' height='14' border='0'>
              </td>\n";
    echo "</form>
        </tr>
        </table>
        </td>
        </tr>
        </table>
        </td>
        <td class='formmezo' align='center' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
        <form>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='spec' value='$spec'>
        <input type='hidden' name='wizard' value='$wizard'>
        <input type='hidden' name='letterfilt' value='$letterfilt'>
        <td nowrap class='formmezo' align='center'>$word[view]:</td>
        <td nowrap align='center'> 
        <input type='text' name='maxPerPage' value='$maxPerPage' size='3' maxlength='3'>
        </td>
        <td nowrap class='formmezo' align='center'> $word[mess_page]</td>
        </form>
        </tr>
        </table>
        </td>
        <td class='formmezo' align='right' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
        <form>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='spec' value='$spec'>
        <input type='hidden' name='wizard' value='$wizard'>
        <input type='hidden' name='letterfilt' value='$letterfilt'>
        <td nowrap class='formmezo'>$word[sort_order]: </td>
        <td nowrap><span class='szoveg'> 
        <select onChange='JavaScript: this.form.submit();' name=sortt>
        <option value=1 $sel_sort[1]>$word[name_asc]</option>
        <option value=2 $sel_sort[2]>$word[name_desc]</option>
        </select>
        </span>
        </td>
        </form>
        </tr>
        </table>
        </td>
        </tr>
        </table></td></tr>\n";
}

?>
</body>
</html>
