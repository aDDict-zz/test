<?
include "auth.php";
include "decode.php";
$weare=145;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/alapadatok.lang";

if (empty($sortt) && isset($Wssortt))
    $sortt=$Wssortt; 
if (empty($maxPerPage) && isset($Wstperpage))
    $maxPerPage=$Wstperpage;
setcookie("Wssortt",$sortt,time()+30*24*3600);
setcookie("Wstperpage",$maxPerPage,time()+30*24*3600);

$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 20;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;


$_MX_popup = 1;
include "menugen.php";


$group_id=intval($group_id);
$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else
    exit; 
$title=$rowg["title"];

$maxrecords=1;
if ($logtype!="maxima0") {
    $logtype="sender_engine";
}
$filename="$_MX_var->error_log_dir/$title.$logtype";
    
if($first+$maxPerPage<=$maxrecords) $mHere = $first+$maxPerPage;           
else $mHere = $maxrecords;                                                 
if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                         
PrintHead();

$pagenum=(int)($first / $maxPerPage) + 1;
$maxpages = ceil($maxrecords / $maxPerPage);
PrintNavigation($maxpages,$pagenum);
print "
    <TR align=middle>
    <TD class=bgkiemelt2 width='24%'><span class='szovegvastag'>$word[t_datum]</span></TD>
    <TD class=bgkiemelt2 width='74%'><span class='szovegvastag'>$word[t_error]</span></TD>
    </TR>\n";

if ($fp=@fopen($filename,"r")) {
    while($row=fgets($fp)) {
        $row=ereg_replace("[\r\n]","",$row);
        $tdclass="class='BACKCOLOR'";
        $parts=explode("\t",$row);
        $data=htmlspecialchars($parts[0]);
        $message=htmlspecialchars($parts[1]);
        print "<TR>
               <TD $tdclass vAlign=top width='24%'><SPAN class=szoveg>$data</span></TD>
               <TD $tdclass vAlign=top width='74%'><SPAN class=szoveg>$message</span></TD>
               </TR>\n";
    }
    fclose($fp);
}
PrintNavigation($maxpages,$pagenum);
echo"
</TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
";  

//****[ PrintHead ]***********************************************************
function PrintHead() {

    echo "<TABLE border=0 cellPadding=0 cellSpacing=0 width='100%'>
          <TBODY>
          <TR>
          <TD class=formmezo>
          <TABLE border=0 cellPadding=3 cellSpacing=1 width='100%'>
          <TBODY>";
}


//****[ PrintNavigation ]*****************************************************
function PrintNavigation($maxpages, $pagenum) {

    global $_MX_var,$word,$group_id,$sortt,$maxPerPage,$maxrecords,$first,$logtype;

  $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
  $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
  $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;
  $sel_sort[$sortt] = "selected";
  $params="group_id=$group_id&logtype=$logtype";  

    echo "<tr><td colspan=2><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
        <tr>
        <td>
        <table border=0 cellspacing=0 cellpadding=0 width='100%'>
        <tr>
        <td class='formmezo' align='left' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
        <form name=inputs>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='logtype' value='$logtype'>
        <tr>\n";
    if ($first>0)
        echo "<td nowrap align='right'>
           <a href='$_MX_var->baseUrl/alapadatok_logs.php?$params&first=0'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
           <a href='$_MX_var->baseUrl/alapadatok_logs.php?$params&first=$OnePageLeft'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
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
            <a href='$_MX_var->baseUrl/alapadatok_logs.php?$params&first=$OnePageRight'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
            <a href='$_MX_var->baseUrl/alapadatok_logs.php?$params&first=$LastPage'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>
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
        <input type='hidden' name='logtype' value='$logtype'>
        <td nowrap class='formmezo' align='center'>$word[view]:</t>
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
        <input type='hidden' name='logtype' value='$logtype'>
        <td nowrap class='formmezo'>$word[sort_order]: </td>
        <td nowrap><span class='szoveg'> 
        <select onChange='JavaScript: this.form.submit();' name=sortt>
        <option value=1 $sel_sort[1]>$word[by_email_asc]</option>
        <option value=2 $sel_sort[2]>$word[by_email_desc]</option>
        <option value=3 $sel_sort[3]>$word[by_name_asc]</option>
        <option value=4 $sel_sort[4]>$word[by_name_desc]</option>
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
