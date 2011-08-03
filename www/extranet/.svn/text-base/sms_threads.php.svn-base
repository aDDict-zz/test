<?
include "auth.php";
include "cookie_auth.php";  

$sortm=get_http("sortm",0);
if (empty($sortm) && isset($_COOKIE["smssort"]))
    $sortm=$_COOKIE["smssort"]; 
$maxPerPage=get_http("maxPerPage",0);
if (empty($maxPerPage) && isset($_COOKIE["smsperpage"]))
    $maxPerPage=$_COOKIE["smsperpage"];
$sortm=abs(intval($sortm));
if (!$sortm)
    $sortm=2;
setcookie("smssort",$sortm,time()+30*24*3600);
setcookie("smsperpage",$maxPerPage,time()+30*24*3600);

$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 25;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;

$group_id=intval($group_id);
$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' and groups.sms_send='yes'
                     and (membership='owner' or membership='moderator')");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: index.php"); exit; }
$title=$row["title"];

$message_id=get_http("message_id",0);
$first=get_http("first",0);
$res=mysql_query("select * from sms_send where id='$message_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $sms=mysql_fetch_array($res);
else {
    header ("Location: sms_list.php?group_id=$group_id");
    exit;
}

$weare=27;
$subweare=271;
include "menugen.php";
include "./lang/$language/sms.lang";  

$hmessage=htmlspecialchars($sms["message"]);
echo "<table border=0 cellspacing=0 cellpadding=0 width='100%'>
      <tr>    
      <td align='left' class=COLUMN1><span class='cim'>&nbsp;$word[st_threads] - $word[sms_message]: <br>
      &nbsp;$hmessage ($sms[create_time])</td>
      </tr>
      </table><br>\n";

$answers=array();
$answers["0"]="($word[st_no_automat_response])";
$answers["1"]=$hmessage;
$automat=trim($sms["automat"]);
if (!empty($automat)) {  // if automat is empty it means that we send no messages if user responds.
    $rows=explode("\n",$automat);
    for ($i=0;$i<count($rows);$i++) {
        $cols=explode("|",trim($rows[$i]));
        $cols[0]=abs(intval($cols[0]));
        if ($cols[0]) {
            if (empty($cols[3]))
                $answers["$cols[0]"]="($word[st_no_automat_response])";
            else
                $answers["$cols[0]"]=htmlspecialchars($cols[3]);
        }
    }
}

$maxrecords=0;
$res = mysql_query("select count(distinct user_id) from sms_tracko where sms_send_id='$message_id'");
if ($res && mysql_num_rows($res)) {
    $k = mysql_fetch_row($res);
    $maxrecords = $k[0];
}

if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                         
switch($sortm) {
    case 1: $order="order by messnum asc"; break;
    case 2: $order="order by messnum desc"; break;
    case 3: $order="order by lastmess asc"; break;
    case 4: $order="order by lastmess desc"; break;
}
  
$pagenum = (int)($first / $maxPerPage) + 1;
$maxpages = ceil($maxrecords / $maxPerPage);
  
if (!$maxrecords)
    print "<table width='100%' border=0 cellspacing=0 cellpadding=1 bgcolor=$_MX_var->main_table_border_color>
          <tr>
          <td colspan=4> 
          <TABLE cellSpacing=0 cellPadding=0 width='100%' align=center border=0>
          <tr> 
          <td align='left' class=bgvilagos2> 
          <span class='szovegvastag'>$word[st_nousers].</span>
          </td></tr></table>
          </td></tr></table>";
else {
    print "<TABLE border=0 cellPadding=0 cellSpacing=0 width='100%'>
           <TBODY>
           <TR>
           <TD class=formmezo>
           <TABLE border=0 cellPadding=3 cellSpacing=1 width='100%'>
           <TBODY>\n";    
    $sql = "select sms_tracko.*, users_$title.ui_email as uie, users_$title.ui_mobil as uim,
            count(*) as messnum, max(sms_tracko.date) as lastmess
            from sms_tracko,users_$title where sms_tracko.user_id=users_$title.id
            and sms_send_id='$message_id'
            group by sms_tracko.user_id $order limit $first, $maxPerPage";
    //print $sql;
    $res = mysql_query($sql);

    if ($res && mysql_num_rows($res)) {
        PrintNavigation($maxpages, $pagenum);
        print "<TR align=left>
               <TD class=bgkiemelt2 width='20%'><span class='szovegvastag'>$word[email]</span></TD>
               <TD class=bgkiemelt2 width='20%'><span class='szovegvastag'>$word[mobil]</span></TD>
               <TD class=bgkiemelt2 width='60%'><span class='szovegvastag'>$word[conversation]</span></TD>
               </TR>\n";
        while ($k=mysql_fetch_array($res)) {
            $conversation="";
            $r2=mysql_query("select * from sms_tracko where user_id=$k[user_id]
                             and response_id<>-1 and sms_send_id='$message_id' order by date");
            if ($r2 && mysql_num_rows($r2)) {
                while ($l=mysql_fetch_array($r2)) {
                    $user_response=htmlspecialchars($l["user_response"]);
                    $user_response_id=$answers["$l[user_response_id]"];
                    $response_id=$answers["$l[response_id]"];

                    $user_response = preg_replace("/_a`/","á",$user_response);
                    $user_response = preg_replace("/\}/","á",$user_response);
                    $user_response = preg_replace("/\{/","á",$user_response);
                    $user_response = preg_replace("/_A`/","Á",$user_response);
                    $user_response = preg_replace("/\]/","Á",$user_response);
                    $user_response = preg_replace("/\[/","Á",$user_response);
                    $user_response = preg_replace("/_o`/","ó",$user_response);
                    $user_response = preg_replace("/_O`/","Ó",$user_response);
                    $user_response = preg_replace("/_e'/","é",$user_response);
                    $user_response = preg_replace("/_e`/","é",$user_response);
                    $user_response = preg_replace("/_E'/","É",$user_response);
                    $user_response = preg_replace("/\^/","Ü",$user_response);
                    $user_response = preg_replace("/\~/","ü",$user_response);
                    $user_response = preg_replace("/\|/","ö",$user_response);
                    $user_response = preg_replace("/_i`/","i",$user_response);
                    $user_response = preg_replace("/_I`/","I",$user_response);
                    $user_response = preg_replace("/_u`/","ú",$user_response);
                    $user_response = preg_replace("/_U`/","Ú",$user_response);

                    if (!empty($conversation))
                        $conversation.="<br>";
                    $conversation.="$l[date], <b>$user_response</b> [$response_id]";
                }
            }
            if (empty($conversation))
                $conversation="($word[st_noresponse].)";
            print "<TR align=left>
                   <TD align=left class='BACKCOLOR' vAlign=top width='20%'><SPAN class=szoveg>$k[uie]&nbsp;</span></TD>
                   <TD align=left class='BACKCOLOR' vAlign=top width='20%'><SPAN class=szoveg>$k[uim]&nbsp;</span></TD>
                   <TD align=left class='BACKCOLOR' vAlign=top width='60%'><SPAN class=szoveg>$conversation&nbsp;</span></TD>
                   </TR>\n";            
        }
        PrintNavigation($maxpages, $pagenum);
    } 
    print "</TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>\n";  
}

include "footer.php";


//****[ PrintNavigation ]*****************************************************
function PrintNavigation($maxpages, $pagenum) {

    global $_MX_var,$word,$group_id,$sortm,$maxPerPage,$maxrecords,$first,$message_id;

    $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
    $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
    $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;
    $sel_sort[$sortm] = "selected"; 
    $params="group_id=$group_id&message_id=$message_id";

    echo "<tr><td colspan=3><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
        <tr>
        <td>
        <table border=0 cellspacing=0 cellpadding=0 width='100%'>
        <tr>
        <td class='formmezo' align='left' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
        <form name=inputs>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='message_id' value='$message_id'>
        <tr>\n";
    if ($first>0)
        echo "<td nowrap align='right'>
           <a href='$_MX_var->baseUrl/sms_threads.php?$params&first=0'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
           <a href='$_MX_var->baseUrl/sms_threads.php?$params&first=$OnePageLeft'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
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
            <a href='$_MX_var->baseUrl/sms_threads.php?$params&first=$OnePageRight'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
            <a href='$_MX_var->baseUrl/sms_threads.php?$params&first=$LastPage'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>
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
        <input type='hidden' name='message_id' value='$message_id'>
        <td nowrap class='formmezo' align='center'>$word[view]:</td>
        <td nowrap align='center'> 
        <input type='text' name='maxPerPage' value='$maxPerPage' size='3' maxlength='3'>
        </td>
        <td nowrap class='formmezo' align='center'> $word[user_page]</td>
        </form>
        </tr>
        </table>
        </td>
        <td class='formmezo' align='right' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
        <tr> 
        <form>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='message_id' value='$message_id'>
        <td nowrap class='formmezo'>$word[sort_order]: </td>
        <td nowrap><span class='szoveg'> 
        <select onChange='JavaScript: this.form.submit();' name=sortm>
        <option value=1 $sel_sort[1]>$word[messnum_asc]</option>
        <option value=2 $sel_sort[2]>$word[messnum_desc]</option>
        <option value=3 $sel_sort[3]>$word[lastmess_asc]</option>
        <option value=4 $sel_sort[4]>$word[lastmess_desc]</option>
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
