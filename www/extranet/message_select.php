<?
include "auth.php";
include "decode.php";
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/threadlist.lang";
include "./lang/$language/form.lang";

$pagenum=get_http("pagenum","");
$first=get_http("first","");
$Wssortt=get_cookie('Wssortt');
$Wstperpage=get_cookie('Wstperpage');
$sortt = (isset($_GET['sortt']) || empty($Wssortt)) ? get_http('sortt',4) : $Wssortt;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Wstperpage)) ? get_http('maxPerPage',5) : $Wstperpage;
$antiallgroups=$allgroups?0:1;
setcookie("Wssortt",$sortt,time()+30*24*3600);
setcookie("Wstperpage",$maxPerPage,time()+30*24*3600);

$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 20;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;

$spec=get_http("spec",0);

/**/


$_MX_popup = 1;
include "menugen.php";


echo      "<script language='JavaScript'>
function addmoretoparent(connect) {
        var chs=document.getElementsByName('chboxes');
        var spectext=new Array();
        for(var c=0; c<chs.length; ++c) {
            if (chs[c].checked==true) {
                spectext.push(chs[c].value);
                }
        }
        if (spectext.length) {
            spectext='('+spectext.join(' '+connect+' ')+')';
            opener.addBody(spectext);
        }            
        window.close();
      }
      function addtoparent(spectext) {
        opener.addBody(spectext);
        window.close();
      }
      function addid(id,wizard) {
        opener.addid(id,wizard);
        window.close();
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

$res=mysql_query("select count(*) from messages where group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $maxrecords=mysql_result($res,0,0);
else
    $maxrecords=0;
    
if($first+$maxPerPage<=$maxrecords) $mHere = $first+$maxPerPage;           
else $mHere = $maxrecords;                                                 
if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                         
PrintHead();

if (!$sortt)
    $sortt=4;

switch($sortt) {
    case 1: $order = "order by subject asc"; break;
    case 2: $order = "order by subject desc"; break;
    case 3: $order = "order by create_date asc"; break;
    case 4: $order = "order by create_date desc"; break;
    case 5: $order = "order by name asc"; break;
    case 6: $order = "order by name desc"; break;
}
  
if ($spec==2) 
    $spectext="spec_ct_";
else
    $spectext="spec_message_";
          
$res=mysql_query("select messages.*, user.name from messages,user where messages.user_id=user.id 
              and group_id='$group_id' $order limit $first,$maxPerPage");

if ($res && mysql_num_rows($res)) { 
    $pagenum=(int)($first / $maxPerPage) + 1;
    $maxpages = ceil($maxrecords / $maxPerPage);
    PrintNavigation($maxpages,$pagenum);
    print "
        <TR align=middle>
        <TD class=bgkiemelt2 width='15%'><span class='szovegvastag'>$word[t_id]</span></TD>
        <TD class=bgkiemelt2 width='15%'><span class='szovegvastag'>$word[t_date]</span></TD>
        <TD class=bgkiemelt2 width='15%'><span class='szovegvastag'>$word[t_sender]</span></TD>
        <TD class=bgkiemelt2 width='40%'><span class='szovegvastag'>$word[t_message]</span></TD>
        <TD class=bgkiemelt2 width='15%'><span class='szovegvastag'>$word[t_filter]</span></TD>
        </TR>\n";
    $i=0;
    while($row=mysql_fetch_array($res)) {
        if (empty($row['subject']))
            $subject="[ $word[no_subject] ]"; 
        else  
            $subject=$row['subject'];
        if ($row["test"]=="yes") {
            $testaddon="<br><b>[$word[test_mail]]</b>";
            $tdclass="bgcolor='ddeeee'";
        }
        else {
            $testaddon="";
            $tdclass="class='BACKCOLOR bbordercolor ".($i%2==1?"bgvilagos2":"")."'";
        }
        $ismime=0;
        $mime = mysql_query("select addon from mailarchive where id=$row[id]");
        if ($wizard>0)
            $jsfunc="addid($row[id],$wizard)";
        else
            $jsfunc="addtoparent('[$spectext$row[id]]')";
        if ($mime && mysql_num_rows($mime))
            $ismime = mysql_result($mime,0,0);        
        $filter_name="";
        $rf=mysql_query("select name from filter where id='$row[filter_id]'");
        if ($rf && mysql_num_rows($rf)) {
            $filter_name=mysql_result($rf,0,0);
        }
        print "<TR>
            <TD align=middle $tdclass vAlign=top width='15%'>
               <input type='checkbox' name='chboxes' value='[$spectext$row[id]]'>&nbsp;
               <SPAN class=szoveg>$row[id]&nbsp;<a href=\"javascript:$jsfunc\">$word[select_mess]</a>&nbsp;</span></TD>
               <TD align=middle $tdclass vAlign=top width='15%'>
               <SPAN class=szoveg>$row[create_date]&nbsp;$testaddon</span></TD>
               <TD align=middle $tdclass vAlign=top width='15%'>
               <SPAN class=szoveg>"
               .nl2br(htmlspecialchars(quoted_printable_decode(decode_mime_string($row["name"])))).
               "&nbsp;
               </SPAN></TD>
               <TD $tdclass vAlign=top width='40%'><SPAN class=szoveg>"
               .nl2br(htmlspecialchars(quoted_printable_decode(decode_mime_string($subject))))."<br>
               <A href='#' class='vastag'
               onClick='window.open(\"$_MX_var->baseUrl/message.php?id=$row[id]&group_id=$group_id\", 
               \"message$row[id]\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=400,height=450\"); return false;'>[$word[plaintext]]</A>\n";
        if ($ismime) {
            print "<A href='#' class='vastag'
                   onClick='window.open(\"$_MX_var->baseUrl/message_source.php?message_id=$row[id]&group_id=$group_id\", 
                   \"message_source$row[id]\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=600,height=550\"); return false;'>[$word[source]]</A>
                   <A href='#' class='vastag'
                   onClick='window.open(\"$_MX_var->baseUrl/mime.php?message_id=$row[id]&group_id=$group_id\", 
                   \"message$row[id]\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=600,height=550\"); return false;'>[$word[mime_rendered]]</A>\n";
        }
        print "</SPAN></TD>
               <TD align=middle $tdclass vAlign=top width='15%'>
               <SPAN class=szoveg>$filter_name</span></TD>
               </TR>\n";
        $i++;
    }
    print " <tr><td colspan='5' $tdclass>
                <input type='button' onclick='addmoretoparent(\"and\");' value='$word[and]'>
                <input type='button' onclick='addmoretoparent(\"or\");' value='$word[or]'>
            </td></tr>";
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

    echo "<TABLE border=0 cellPadding=0 cellSpacing=0 width='100%'>
          <TBODY>
          <TR>
          <TD class=formmezo>
          <TABLE border=0 cellPadding=3 cellSpacing=1 width='100%'>
          <TBODY>";
}


//****[ PrintNavigation ]*****************************************************
function PrintNavigation($maxpages, $pagenum) {

    global $_MX_var,$word,$group_id,$sortt,$maxPerPage,$maxrecords,$first,$wizard,$spec;

  $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
  $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
  $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;
  $sel_sort[$sortt] = "selected";
  $params="group_id=$group_id&spec=$spec&wizard=$wizard";  

    echo "<tr><td colspan='5'><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
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
        <tr>\n";
    if ($first>0)
        echo "<td nowrap align='right'>
           <a href='$_MX_var->baseUrl/message_select.php?$params&first=0'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
           <a href='$_MX_var->baseUrl/message_select.php?$params&first=$OnePageLeft'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
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
            <a href='$_MX_var->baseUrl/message_select.php?$params&first=$OnePageRight'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
            <a href='$_MX_var->baseUrl/message_select.php?$params&first=$LastPage'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>
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
        <td nowrap class='formmezo'>$word[sort_order]: </td>
        <td nowrap><span class='szoveg'> 
        <select onChange='JavaScript: this.form.submit();' name=sortt>
        <option value=1 $sel_sort[1]>$word[subject_asc]</option>
        <option value=2 $sel_sort[2]>$word[subject_desc]</option>
        <option value=3 $sel_sort[3]>$word[date_asc]</option>
        <option value=4 $sel_sort[4]>$word[date_desc]</option>
        <option value=5 $sel_sort[5]>$word[sender_asc]</option>
        <option value=6 $sel_sort[6]>$word[sender_desc]</option>
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
