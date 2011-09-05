<?
include "auth.php";
include "cookie_auth.php";
include "decode.php";

$pagenum=get_http("pagenum","");
$first=get_http("first","");
$Wsortt=get_cookie('Wsortt');
$Wtperpage=get_cookie('Wtperpage');
$Amallgroups=get_cookie("Amallgroups");
$sortt = (isset($_GET['sortt']) || empty($Wsortt)) ? get_http('sortt',4) : $Wsortt;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Wtperpage)) ? get_http('maxPerPage',5) : $Wtperpage;
$allgroups = (isset($_GET['allgroups']) || empty($Amallgroups)) ? get_http('allgroups',0) : $Amallgroups;
$antiallgroups=$allgroups?0:1;
setcookie("Wsortt",$sortt,time()+30*24*3600);
setcookie("Wtperpage",$maxPerPage,time()+30*24*3600);
setcookie("Amallgroups",$allgroups,time()+30*24*3600);

$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 50;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;
  
$mres = mysql_query("select title,num_of_mess,membership from groups,members 
                     where groups.id=members.group_id and groups.id='$group_id' 
                     and (membership='owner' or membership='moderator' or membership='affiliate')
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }
$title=$rowg["title"];
$active_membership=$rowg["membership"];
  
$weare=20;
include "menugen.php";
include "./lang/$language/threadlist.lang";

if (!$sortt)
    $sortt=2;

switch($sortt) {
    case 1: $order = "order by track.date asc"; $order2 = "order by create_date asc"; break;
    case 2: $order = "order by track.date desc"; $order2 = "order by create_date desc"; break;
}

// select count(distinct userid),outid from tracko,users_permission where tracko.outid in (27542,27568) and users_permission.aff=80121 and tracko.userid=users_permission.id group by outid;
       
/*$messids=array();
$aq="select track.message_id,count(distinct tracko.userid)
     from track,tracko,users_$title where
     track.id=tracko.outid and tracko.userid=users_$title.id
     and track.group_id='$group_id' and users_$title.aff='$active_userid' 
     group by track.message_id $order";
//print $aq;
$res=mysql_query($aq);
if ($res && mysql_num_rows($res)) 
    while ($l=mysql_fetch_row($res)) {
        $messids[]=$l[0];
        $affcnts["$l[0]"]=$l[1];
    }

$maxrecords=count($messids);*/

$res=mysql_query("select count(*) from messages where group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    $maxrecords=mysql_result($res,0,0);
}

if($first+$maxPerPage<=$maxrecords) $mHere = $first+$maxPerPage;           
else $mHere = $maxrecords;                                                 
if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                         
PrintHead();

$idlist="0";
for ($i=$first;$i<$first+$maxPerPage && $i<$maxrecords;$i++)
    $idlist.=",$messids[$i]";

/*$res=mysql_query("select messages.*, user.name from messages,user 
                  where messages.user_id=user.id and messages.test='no' 
                  and group_id='$group_id' and messages.id in ($idlist) $order2");*/
$res=mysql_query("select messages.*, user.name from messages,user 
                  where messages.user_id=user.id and messages.test='no' 
                  and group_id='$group_id' $order2 limit $first,$maxPerPage");

if ($res && mysql_num_rows($res)) { 
    $pagenum=(int)($first / $maxPerPage) + 1;
    $maxpages = ceil($maxrecords / $maxPerPage);
    PrintNavigation($maxpages,$pagenum);
    print "
        <form method='post' name='delform'>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='del_messages' value='1'>
        <TR align=middle>
        <TD class=bgkiemelt2 width='25%'><span class='szovegvastag'>$word[t_date]</span></TD>
        <TD class=bgkiemelt2 width='25%'><span class='szovegvastag'>$word[t_sender]</span></TD>
        <TD class=bgkiemelt2 width='35%'><span class='szovegvastag'>$word[t_message]</span></TD>
        <TD class=bgkiemelt2 width='15%'><span class='szovegvastag'>$word[t_copies]</span></TD>
        </TR>\n";
    while($row=mysql_fetch_array($res)) {
        if (empty($row['subject']))
            $subject="[ $word[no_subject] ]"; 
        else  
            $subject=$row['subject'];
        $msq="select count(*) from users_$title where aff='$active_userid'
                              and messagelist like '%,$row[id],%'";
        #print "<br>$msq<br>";
        $tres2 = mysql_query($msq);
        if ($tres2 && mysql_num_rows($tres2)) 
            $mails=mysql_result($tres2,0,0);
        else
            $mails=0;
        //$mails=$affcnts["$row[id]"];
        if ($mails>0) {
            $ismime=0;
            $mime = mysql_query("select addon from mailarchive where id=$row[id]");
            if ($mime && mysql_num_rows($mime))
                $ismime = mysql_result($mime,0,0);        
            print "<TR>
                   <TD align=middle class='BACKCOLOR' vAlign=top width='18%'>
                   <SPAN class=szoveg>$row[create_date]&nbsp;$testaddon</span></TD>
                   <TD align=middle class='BACKCOLOR' vAlign=top width='16%'>
                   <SPAN class=szoveg>"
                   .nl2br(htmlspecialchars(quoted_printable_decode(decode_mime_string($row["name"])))).
                   "&nbsp;</SPAN></TD>
                   <TD class='BACKCOLOR' vAlign=top width='28%'><SPAN class=szoveg>"
                   .nl2br(htmlspecialchars(quoted_printable_decode(decode_mime_string($subject))));/*."<br>
                   <A href='#' class='vastag'
                   onClick='window.open(\"$_MX_var->baseUrl/message.php?id=$row[id]&group_id=$group_id\", 
                   \"message$row[id]\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=400,height=450\"); return false;'>[$word[plaintext]]</A>\n";
            if ($ismime) {
                print "<A href='#' class='vastag'
                       onClick='window.open(\"$_MX_var->baseUrl/mime.php?message_id=$row[id]&group_id=$group_id\", 
                       \"message$row[id]\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=600,height=550\"); return false;'>[$word[html]]</A>\n";
            }*/
            print "</SPAN></TD>
                   <TD align=middle class='BACKCOLOR' vAlign=top width='10%'>
                   <SPAN class=szoveg>$mails&nbsp;</span></TD>
                   </TR>\n";
        }
    }
    print "</form>\n";
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
  
include "footer.php";
  

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

    global $_MX_var,$word,$group_id,$sortt,$maxPerPage,$maxrecords,$first;

    $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
    $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
    $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;
    $sel_sort[$sortt] = "selecteD"; 
    $params="group_id=$group_id";

    echo "<tr><td colspan=4><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
        <tr>
        <td>
        <table border=0 cellspacing=0 cellpadding=0 width='100%'>
        <tr>
        <td class='formmezo' align='left' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
        <form name=inputs>
        <input type='hidden' name='group_id' value='$group_id'>
        <tr>\n";
    if ($first>0)
        echo "<td nowrap align='right'>
           <a href='$_MX_var->baseUrl/threadlist_aff.php?$params&first=0'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
           <a href='$_MX_var->baseUrl/threadlist_aff.php?$params&first=$OnePageLeft'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
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
            <a href='$_MX_var->baseUrl/threadlist_aff.php?$params&first=$OnePageRight'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
            <a href='$_MX_var->baseUrl/threadlist_aff.php?$params&first=$LastPage'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>
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
        <td nowrap class='formmezo'>$word[sort_order]: </td>
        <td nowrap><span class='szoveg'> 
        <select onChange='JavaScript: this.form.submit();' name=sortt>
        <!--<option value=1 $sel_sort[1]>$word[subject_asc]</option>
        <option value=2 $sel_sort[2]>$word[subject_desc]</option>-->
        <option value=1 $sel_sort[1]>$word[date_asc]</option>
        <option value=2 $sel_sort[2]>$word[date_desc]</option>
        <!--<option value=5 $sel_sort[5]>$word[sender_asc]</option>
        <option value=6 $sel_sort[6]>$word[sender_desc]</option>-->
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
