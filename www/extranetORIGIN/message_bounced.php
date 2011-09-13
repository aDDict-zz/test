<?
include "auth.php";
include "decode.php";
$weare=20;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/threadlist.lang";

$Wssortt = get_cookie("Wssortt");
$sortt = (isset($_GET["sortt"]) || empty($Wssortt)) ? get_http("sortt", 1) : $Wssortt;
setcookie("Wssortt",$sortt,time()+30*24*3600);

$pagenum=get_http("pagenum","");
$first=get_http("first","");

$maxPerPage=50;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;


$_MX_popup = 1;
include "menugen.php";


$group_id=intval($group_id);
$mres = mysql_query("select title,num_of_mess,membership from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' or membership='client' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else
    exit; 
$title=$rowg["title"];

$message_id=htmlspecialchars($message_id);

$res=mysql_query("select subject from messages where id='$message_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res)) {
    $subject=htmlspecialchars(mysql_result($res,0,0));
    $explain="&quot;$subject&quot; $word[bounced_mails]";
}
else
    exit;

logger("",$group_id,"","message_id:$message_id","bounced_back");	

$act_memb=$rowg["membership"];
$stattypes=array();
if ($act_memb=="client") {
    $rcl=mysql_query("select * from message_client where message_id='$message_id' and user_id='$active_userid'");
    if (!($rcl && mysql_num_rows($rcl))) {
        // this message is not assigned to this user.
        exit;
    }
    $r2=mysql_query("select st.id,st.parent_id from stattype st,stattype_user su where 
                     su.user_id='$active_userid'
                     and su.group_id='$group_id' and st.id=su.stattype_id");
    if ($r2 && mysql_num_rows($r2)) {
        while ($z=mysql_fetch_array($r2)) {
            $stattypes[]=$z["id"];
        }
    }
    if (!in_array(8,$stattypes) || !in_array(7,$stattypes) ) {
        // user has no right to see detailed stats for bounced pop-up [8] or bounced stats at all [7].
        exit;
    }
}

$res=mysql_query("select count(distinct email,error_code) from bounced_back where message_id='$message_id'
                  and email not in ('postmaster@maxima.hu','krisztina@egyperces.hu','')
                  and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $maxrecords=mysql_result($res,0,0);
else
    $maxrecords=0;
    
if($first+$maxPerPage<=$maxrecords) $mHere = $first+$maxPerPage;           
else $mHere = $maxrecords;                                                 
if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                         
PrintHead();

/*if (!$sortt)
    $sortt=4;

switch($sortt) {
    case 1: $order = "order by subject asc"; break;
    case 2: $order = "order by subject desc"; break;
    case 3: $order = "order by create_date asc"; break;
    case 4: $order = "order by create_date desc"; break;
    case 5: $order = "order by name asc"; break;
    case 6: $order = "order by name desc"; break;
}*/
$order = "order by email"; 
  
if ($spec==2) 
    $spectext="spec_ct_";
else
    $spectext="spec_message_";
          
$res=mysql_query("select distinct email,error_code from bounced_back where message_id='$message_id'
                  and email not in ('postmaster@maxima.hu','krisztina@egyperces.hu','')
                  and group_id='$group_id' $order limit $first,$maxPerPage");

if ($res && mysql_num_rows($res)) { 
    $tdclass="class='BACKCOLOR'";
    $pagenum=(int)($first / $maxPerPage) + 1;
    $maxpages = ceil($maxrecords / $maxPerPage);
    PrintNavigation($maxpages,$pagenum);
    print "
        <TR align=middle>
        <TD class=bgkiemelt2 width='75%'><span class='szovegvastag'>$word[t_email]</span></TD>
        <TD class=bgkiemelt2 width='25%'><span class='szovegvastag'>$word[t_errcode]</span></TD>
        </TR>\n";
    while($row=mysql_fetch_array($res)) {
        print "<TR>
               <TD align=\"left\" $tdclass vAlign=top width='75%'>".htmlspecialchars($row["email"])."
               <SPAN class=szoveg>$row[create_date]&nbsp;$testaddon</span></TD>
               <TD align=middle $tdclass vAlign=top width='25%'>$row[error_code]
               <SPAN class=szoveg></SPAN></TD>
               </TR>\n";
    }
    PrintNavigation($maxpages,$pagenum);
} 
else 
    print "<tr>    
           <td align='left' class=COLUMN1>
           <span class='szovegvastag'>-------</span></td>
           </tr>\n";
 
echo"
</TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
";  

//****[ PrintHead ]***********************************************************
function PrintHead() {

    global $_MX_var,$explain,$group_id,$message_id;
    echo "<span class='szovegvastag'>$explain<br><A href='message_bounced_csv.php?group_id=$group_id&message_id=$message_id' class='vastag' target='_blank'>CSV export</a></span><br><br>
          <TABLE border=0 cellPadding=0 cellSpacing=0 width='100%'>
          <tr><td class=bgkiemelt2><span class='szovegvastag'>2&nbsp;Connection refused</td>
              <td class=bgkiemelt2><span class='szovegvastag'>3&nbsp;Disk quota exceeded</td></tr>
          <tr><td class=bgkiemelt2><span class='szovegvastag'>5&nbsp;This message is looping</td>
              <td class=bgkiemelt2><span class='szovegvastag'>452&nbsp;Requested action not taken</td></tr>
          <tr><td class=bgkiemelt2><span class='szovegvastag'>550&nbsp;User unknown</td>
              <td class=bgkiemelt2><span class='szovegvastag'>551&nbsp;User not local</td></tr>
          <tr><td class=bgkiemelt2><span class='szovegvastag'>552&nbsp;Requested mail action aborted</td>
              <td class=bgkiemelt2><span class='szovegvastag'>553&nbsp;Illegal recipient adress</td></tr>
          <tr><td class=bgkiemelt2><span class='szovegvastag'>554&nbsp;Service unavailable</td>
              <td class=bgkiemelt2><span class='szovegvastag'>&nbsp;</td></tr>
          </table>
          <TABLE border=0 cellPadding=0 cellSpacing=0 width='100%'>
          <TBODY>
          <TR>
          <TD class=formmezo>
          <TABLE border=0 cellPadding=3 cellSpacing=1 width='100%'>
          <TBODY>";
}


//****[ PrintNavigation ]*****************************************************
function PrintNavigation($maxpages, $pagenum) {

  global $_MX_var,$word,$group_id,$sortt,$maxPerPage,$maxrecords,$first,$wizard,$spec,$message_id;

  $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
  $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
  $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;
  $sel_sort[$sortt] = "selected";
  $params="group_id=$group_id&message_id=$message_id";  

    echo "<tr><td colspan=2><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
        <tr>
        <td>
        <table border=0 cellspacing=0 cellpadding=0 width='100%'>
        <tr>
        <td class='formmezo' align='left' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
        <form name=inputs>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='spec' value='$spec'>
        <input type='hidden' name='message_id' value='$message_id'>
        <tr>\n";
    if ($first>0)
        echo "<td nowrap align='right'>
           <a href='$_MX_var->baseUrl/message_bounced.php?$params&first=0'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
           <a href='$_MX_var->baseUrl/message_bounced.php?$params&first=$OnePageLeft'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
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
            <a href='$_MX_var->baseUrl/message_bounced.php?$params&first=$OnePageRight'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
            <a href='$_MX_var->baseUrl/message_bounced.php?$params&first=$LastPage'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>
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
        <td class='formmezo' align='center' width='33%'>&nbsp;
        </td>
        <td class='formmezo' align='right' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
        <tr> <td>&nbsp;</td>
        </tr>
        </table>
        </td>
        </tr>
        </table></td></tr>\n";
}

?>
</body>
</html>
