<?
include "auth.php";
include "decode.php";
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/threadlist.lang";

$Wssortt=get_cookie('Wssortt');
$sortt = (isset($_GET['sortt']) || empty($Wssortt)) ? get_http('sortt',4) : $Wssortt;
setcookie("Wssortt",$sortt,time()+30*24*3600);

$first=get_http('first',0);
$pagenum=get_http('pagenum',0);
$maxPerPage=25;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;


$_MX_popup = 1;
include "menugen.php";


$rec_userid = get_http('rec_userid','');
$group_id=intval($group_id);
$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator')
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    exit; 
}

$title=$rowg["title"];
$rec_userid=mysql_escape_string($rec_userid);

mysql_query("create temporary table mr_tmp (message_id int default '0' not null,unique key message_id(message_id))");
     
$messagenum=0;
$mrecv=array();
$res=mysql_query("select messagelist,ui_email from users_$title where id='$rec_userid'");
logger($query,$group_id,"","user_id=$rec_userid","users_$title");
if ($res && mysql_num_rows($res)) {
    $messagelist=mysql_result($res,0,0);
    $email=mysql_escape_string(mysql_result($res,0,1));
    $explain=htmlspecialchars("email=$email $word[got_mails]");
    $mrr=explode(",",$messagelist);
    if (is_array($mrr)) {
        while (list(,$part)=each($mrr)) {
            $part=ereg_replace("[^0-9]","",$part);
            if ($part) {   // might be duplicated
                mysql_query("insert into mr_tmp(message_id) values ($part)");
                $messagenum++;
            }
        }
    }
}
else
    exit;

$maxrecords=$messagenum;
// how many bounced back, which errors.
$errs=array();
$res=mysql_query("select count(distinct b.message_id) as cnt,error_code from bounced_back b,mr_tmp m where b.email='$email' and
                  b.message_id=m.message_id and b.group_id='$group_id' and b.project='maxima' group by error_code");
print mysql_error();
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $errs["$k[error_code]"]=$k["cnt"];
    }
}

$opened=0;
$res=mysql_query("select count(distinct t.message_id) as cnt from track1x1 t,mr_tmp m where t.user_id='$rec_userid' and
                  t.message_id=m.message_id and t.group_id='$group_id'");
print mysql_error();
if ($res && mysql_num_rows($res)) {
    $opened=mysql_result($res,0,0);
}

$clicked=0;
$res=mysql_query("select count(distinct f.message_id) as cnt from mr_tmp m,feedback f,trackf t where 
                  t.user_id='$rec_userid' and m.message_id=f.message_id and f.id=t.feed_id and
                  f.group_id='$group_id'");
print mysql_error();
if ($res && mysql_num_rows($res)) {
    $clicked=mysql_result($res,0,0);
}

$unsubbed_id=0;
$unsubbed_subject="";
$res=mysql_query("select m.id,m.subject from messages m,validation v where v.user_id='$rec_userid' and
                  v.message_id=m.id and v.group_id='$group_id' and v.action='unsub' and v.validated='yes'");
print mysql_error();
if ($res && mysql_num_rows($res)) {
    $unsubbed_id=mysql_result($res,0,0);
    $unsubbed_subject=htmlspecialchars(mysql_result($res,0,1));
}
    
if($first+$maxPerPage<=$maxrecords) $mHere = $first+$maxPerPage;           
else $mHere = $maxrecords;                                                 
if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                         
PrintHead();
print "<tr><td colspan='3' class='bgkiemelt2'><span class='szoveg'>\n";
if (is_array($errs))
    while (list($errcode,$cnt)=each($errs))
       print "$cnt $word[mr_bounce1] $errcode $word[mr_bounce2]<br> \n";
print "$word[mr_click1] $clicked $word[mr_click2]<br> 
       $word[mr_t1x11] $opened $word[mr_t1x12]<br>\n";
if ($unsubbed_id) 
    print "$word[mr_unsub1] $unsubbed_subject $word[mr_unsub2]<br>";
print "       </span></td></tr>\n";

if (!$sortt)
    $sortt=4;

switch($sortt) {
    case 1: $order = "order by subject asc"; break;
    case 2: $order = "order by subject desc"; break;
    case 3: $order = "order by create_date asc"; break;
    case 4: $order = "order by create_date desc"; break;
}
  
if ($maxrecords) {
    $res=mysql_query("select m.* from messages m,mr_tmp t where m.id=t.message_id and m.group_id='$group_id' $order limit $first,$maxPerPage");

    $messlist="0";
    $messids=array();
    $subjects=array();
    $dates=array();
    if ($res && mysql_num_rows($res)) { 
        while ($z=mysql_fetch_array($res)) {
            $messlist.=",$z[id]";
            $messids[]=$z["id"];
            $subjects["$z[id]"]=$z["subject"];
            $dates["$z[id]"]=$z["create_date"];
        }
    }
    $m_stat=array();

    $glue=array();
    $res=mysql_query("select distinct error_code,m.id from bounced_back b, messages m 
                      where m.id in ($messlist) and b.group_id='$group_id' and b.project='maxima'
                      and m.id=b.message_id and b.email='$email'");
    if ($res && mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            $m_stat["$k[id]"].=$glue["$k[id]"]."$word[bounced]:$k[error_code]";
            $glue["$k[id]"]="<br>";
        }
    }

    $tres2=mysql_query("select distinct message_id from track1x1 where message_id in ($messlist) 
                        and user_id='$rec_userid' and group_id='$group_id'");
    if ($tres2 && mysql_num_rows($tres2)) {
        while ($l=mysql_fetch_array($tres2)) {
            $m_stat["$l[message_id]"].=$glue["$l[message_id]"]."$word[mr_opened]";
            $glue["$l[message_id]"]="<br>";
        }
    }

    $tres2 = mysql_query("select distinct f.url,f.message_id from feedback f,trackf t where 
                          f.message_id in ($messlist) and f.group_id='$group_id' and t.user_id='$rec_userid'
                          and f.id=t.feed_id group by f.message_id");
    if ($tres2 && mysql_num_rows($tres2)) {
        while ($l=mysql_fetch_array($tres2)) {
            $url=htmlspecialchars($l["url"]);
            $m_stat["$l[message_id]"].=$glue["$l[message_id]"]."CT:<a href='$l[url]' target='_blank'>$url</a>";
            $glue["$l[message_id]"]="<br>";
        }
    }

    if ($unsubbed_id) {
        $m_stat["$unsubbed_id"].=$glue["$l[unsubbed_id]"]."$word[mr_unsub_reason]";
        $glue["$unsubbed_id"]="<br>";
    }

    $mimes=array();
    $tres2 = mysql_query("select id from mailarchive where id in ($messlist)");
    if ($tres2 && mysql_num_rows($tres2)) {
        while ($l=mysql_fetch_row($tres2)) {
            $mimes["$l[0]"]=1;
        }
    }

    $pagenum=(int)($first / $maxPerPage) + 1;
    $maxpages = ceil($maxrecords / $maxPerPage);
    PrintNavigation($maxpages,$pagenum);
    print "<TD class=bgkiemelt2 width='20%'><span class='szovegvastag'>$word[t_date]</span></TD>
        <TD class=bgkiemelt2 width='40%'><span class='szovegvastag'>$word[t_message]</span></TD>
        <TD class=bgkiemelt2 width='40%'><span class='szovegvastag'>&nbsp;</span></TD></TR>\n";
    while (list($message_id,$subject)=each($subjects)) {
        if (empty($subject))
            $subject="[ $word[no_subject] ]"; 
        $tdclass="class='BACKCOLOR'";
        print "<TR>
               <TD align=middle $tdclass vAlign=top width='18%'>
               <SPAN class=szoveg>$dates[$message_id]</SPAN></TD>
               <TD $tdclass vAlign=top width='28%'><SPAN class=szoveg>".nl2br(htmlspecialchars(quoted_printable_decode(decode_mime_string($subject))))."<br>
               <A href='#' class='vastag' onClick='window.open(\"$_MX_var->baseUrl/message.php?id=$message_id&group_id=$group_id\", \"message$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=400,height=450\"); return false;'>[$word[plaintext]]</A>\n";
        if ($mimes["$message_id"]) 
            print "<A href='#' class='vastag' onClick='window.open(\"$_MX_var->baseUrl/message_source.php?message_id=$message_id&group_id=$group_id\", \"message_source$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=600,height=550\"); return false;'>[$word[source]]</A>
                   <A href='#' class='vastag' onClick='window.open(\"$_MX_var->baseUrl/mime.php?message_id=$message_id&group_id=$group_id\", \"message$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=600,height=550\"); return false;'>[$word[mime_rendered]]</A>";
        print "</SPAN></TD>"; // column 2 end

        $substat="$m_bounce[$message_id]$m_ct[$message_id]$m_open[$message_id]$m_unsub[$message_id]";
        if (empty($m_stat["$message_id"])) {
            $m_stat["$message_id"]="&nbsp;-";
        }

        print "<TD align=middle $tdclass vAlign=top width='28%'><SPAN class=szoveg>$m_stat[$message_id]</TD></TR>\n";
    }
    print "</form>\n";
    PrintNavigation($maxpages,$pagenum);
} 
else 
    print "<tr>    
           <td align='left' class=COLUMN1>
           <span class='szovegvastag'>$word[no_user_messages]</span></td>
           </tr>\n";
 
echo"
</TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
";  

mysql_query("drop table mr_tmp");

//****[ PrintHead ]***********************************************************
function PrintHead() {

    global $_MX_var,$explain, $maxrecords, $word;
    echo "<span class='szovegvastag'>$explain - $maxrecords $word[t_message]</span><br><br>
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

  global $_MX_var,$word,$group_id,$sortt,$maxPerPage,$maxrecords,$first,$wizard,$spec,$rec_userid;

  $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
  $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
  $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;
  $sel_sort[$sortt] = "selected";
  $params="group_id=$group_id&rec_userid=$rec_userid";  

    echo "<tr><td colspan=3><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
        <tr>
        <td>
        <table border=0 cellspacing=0 cellpadding=0 width='100%'>
        <tr>
        <td class='formmezo' align='left' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
        <form name=inputs>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='spec' value='$spec'>
        <input type='hidden' name='rec_userid' value='$rec_userid'>
        <tr>\n";
    if ($first>0)
        echo "<td nowrap align='right'>
           <a href='$_MX_var->baseUrl/message_received.php?$params&first=0'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
           <a href='$_MX_var->baseUrl/message_received.php?$params&first=$OnePageLeft'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
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
            <a href='$_MX_var->baseUrl/message_received.php?$params&first=$OnePageRight'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
            <a href='$_MX_var->baseUrl/message_received.php?$params&first=$LastPage'><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>
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
        <tr> 
        <form>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='spec' value='$spec'>
        <input type='hidden' name='rec_userid' value='$rec_userid'>
        <td nowrap class='formmezo'>$word[sort_order]: </td>
        <td nowrap><span class='szoveg'> 
        <select onChange='JavaScript: this.form.submit();' name=sortt>
        <option value=1 $sel_sort[1]>$word[subject_asc]</option>
        <option value=2 $sel_sort[2]>$word[subject_desc]</option>
        <option value=3 $sel_sort[3]>$word[date_asc]</option>
        <option value=4 $sel_sort[4]>$word[date_desc]</option>
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
