<?
  include "auth.php";
  $weare=25;
  include "cookie_auth.php";  

$Wsortts=get_cookie("Wsortts");
$Wtperpage=get_cookie("Wtperpage");

$sortm = (isset($_GET['sortm']) || empty($Wsortts)) ? get_http('sortm',1) : $Wsortts;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Wtperpage)) ? get_http('maxPerPage',15) : $Wtperpage;

  setcookie("Wsortts",$sortm,time()+30*24*3600);
  setcookie("Wtperpage",$maxPerPage,time()+30*24*3600);

  $first=get_http("first",0);
  $pagenum=get_http("pagenum",1);

  $maxPerPage=intval($maxPerPage);
  if($maxPerPage<1) $maxPerPage = 15;
  $pagenum=intval($pagenum);
  if($pagenum<1) $pagenum=1;
  if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
  if(!$first) $first = 0;

  $group_id=intval($group_id);
  $mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                       and groups.id='$group_id' and user_id='$active_userid' and groups.sms_send='yes'
                       and (membership='owner' or membership='moderator' $admin_addq)");
  if ($mres && mysql_num_rows($mres))
      $row=mysql_fetch_array($mres);  
  else {
      header("Location: index.php?no_group=1"); exit; }
  $title=$row["title"];
  
  include "menugen.php";
  include "./lang/$language/sms.lang";  
  
  $sql = "from sms_send where group_id='$group_id'";
  
  $res = mysql_query("select count(*) $sql");
  if ($res && mysql_num_rows($res)) {
    $k = mysql_fetch_row($res);
    $maxrows = $k[0];
  }
  
  if($first>$maxrows) $first = (ceil($maxrows / $maxPerPage)-1) * $maxPerPage;                                         
  switch($sortm) {
    case 0:
    case 1: 
      $sql .= " ORDER BY create_time DESC "; break;
    case 2: 
      $sql .= " ORDER BY create_time ASC "; break;
  }
  
  $res = mysql_query("select * $sql LIMIT $first, $maxPerPage");

  $pagenum = (int)($first / $maxPerPage) + 1;
  $maxpages = ceil($maxrows / $maxPerPage);
  
  PrintHead();
  
  if (!$maxrows)
      echo "
  <table width='100%' border=0 cellspacing=0 cellpadding=1 bgcolor=$_MX_var->main_table_border_color>
  <tr>
  <td colspan=4> 
  <TABLE cellSpacing=0 cellPadding=0 width='100%' align=center border=0>
       <tr> 
        <td align='left' class=bgvilagos2> 
         <span class='szovegvastag'>$word[sms_not_yet]</span>
        </td></tr></table>
     </td></tr></table>";

  if($res && mysql_num_rows($res)) {
    PrintNavigation($maxpages, $pagenum, $maxrows);
    $putbreak=0;
    while($row=mysql_fetch_array($res)) {
      $r2=mysql_query("select email,name from user where id ='$row[sender_id]'");
      if ($r2 && mysql_num_rows($r2)) {
         $k2=mysql_fetch_array($r2);
         $sender="$k2[email] ($k2[name])";
      }
      $messcount=0;
      $r2=mysql_query("select count(*) from sms_tracko where sms_send_id='$row[id]'
                       and response_id=-1");
      if ($r2 && mysql_num_rows($r2))
          $messcount=mysql_result($r2,0,0);
      $usercount=0;
      $r2=mysql_query("select count(distinct user_id) from sms_tracko where sms_send_id='$row[id]'
                       and response_id<>-1");
      if ($r2 && mysql_num_rows($r2))
          $usercount=mysql_result($r2,0,0);
      $totalcount=0;
      $r2=mysql_query("select count(*) from sms_tracko where sms_send_id='$row[id]'
                       and response_id<>-1");
      if ($r2 && mysql_num_rows($r2))
          $totalcount=mysql_result($r2,0,0);
      if ($messcount) {
        $percent=number_format($usercount*100/$messcount,2);
        $usercount.=" ($percent%)";
      }
      $not_delivered=0;
      $r3 = mysql_query("select count(*) from sms_status where code=7 and sms_id='$row[id]'");
      if ($r3 && mysql_num_rows($r3)) {
      	   $not_delivered = mysql_result($r3,0,0);
      }
      if ($row["test"]=="yes") {
        $databg="bgcolor='ddeeee'";
        $messcount="Teszt: $row[test_numbers]";
      }
      else {
        $databg="class='bgvilagos2'";
        $messcount="$messcount$word[sms_s2]$totalcount$word[sms_s3]";
      }
                       
      $sms_data="$word[sms_message]: $row[message]<br>
                 $messcount<br>
                 $usercount$word[sms_s5]<br>
                 <a href='sms_threads.php?group_id=$group_id&message_id=$row[id]'>$word[st_threads]</a><br>
		         <A href='#' onClick='window.open(\"$_MX_var->baseUrl/sms_bounced.php?message_id=$row[id]&group_id=$group_id\", \"mbou$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=420,height=500\"); return false;'>$word[not_delivered]: $not_delivered</a>";
      if (!empty($row["automat"]))
         $sms_data.="$word[sms_automat]:<br>".nl2br(htmlspecialchars($row["automat"]))."<br>";
      $t_filter="";
      if ($row["filter_id"]) {
            $r4=mysql_query("select name from filter where id='$row[filter_id]'");
            if ($r4 && mysql_num_rows($r4)) 
                $t_filter="$word[lfilter]: ".nl2br(htmlspecialchars(mysql_result($r4,0,0)));
      }
      elseif ($row["user_group_id"]) {
            $r4=mysql_query("select name from user_group where id='$row[user_group_id]'");
            if ($r4 && mysql_num_rows($r4)) 
                $t_filter="$word[lmemgr]: ".nl2br(htmlspecialchars(mysql_result($r4,0,0)));
      }
      $sms_data.="$t_filter<br>";        
      if ($row["delivery_time"])
         $sms_data.="$word[sms_delivery_time] $row[delivery_time]<br>";
      if ($row["obsolete"]) 
         $sms_data.="$word[sms_obsolete] $row[obsolete] $word[sms_hours]<br>";
      if ($row["warn"] == "yes")
         $sms_data.="$word[sms_warn]";
      if(!$putbreak) 
        $putbreak=1;
      else
        echo "<br>";
      echo "<TABLE cellSpacing=0 cellPadding=0 width='100%' align=center border=0>
<TBODY>
<TR>
<TD class=bgkiemelt2 noWrap>$sender</td>
<td align='right' class='bgkiemelt2'><span class='datum'>$row[create_time]</span></td>
</tr>
<tr> 
<td valign='top' colspan='2' $databg><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/1x1.gif' width='10' height='1'></td>
</tr>
<tr>
<td valign='top' colspan='2' $databg>$sms_data</td>
</tr>
</tbody>
</table>
";
    }
  PrintNavigation($maxpages, $pagenum, $maxrows);
  } 
  
  include "footer.php";


//****[ PrintHead ]***********************************************************
function PrintHead() {
global $_MX_var,$word, $err, $group_id;


/*echo "
<table border=0 cellspacing=0 cellpadding=0 width='100%'>
<tr>    
<td align='left' class=COLUMN1><span class='cim'><a href='#bottom'>$word[SMS_NEW]</a><BR>
<a href='$_MX_var->baseUrl/send_sms_stat.php?group_id=$group_id'>$word[SMS_STAT]</a><BR>
<a href='$_MX_var->baseUrl/sms_in.php?group_id=$group_id'>$word[SMS_IN]</a></span></td>
</tr>
</table>
 ";*/
  
}
//----------------------------------------------------------------------------


//----------------------------------------------------------------------------
//****[ PrintNavigation ]*****************************************************
function PrintNavigation($maxpages, $pagenum, $maxrows) {
global $_MX_var,$word;                     // path to root of this site
global $_MX_var,$group_id;                 // selected group id
global $_MX_var,$sortm;                    // sort method
global $_MX_var,$maxPerPage;               // things for leafing
global $_MX_var,$path;                     // path to the selected group
global $_MX_var,$threadselect;
global $_MX_var,$_POST, $_GET;
global $_MX_var,$first;
global $_MX_var,$params, $threshold, $active_membership;
  
  // set navigation start numbers
  $LastPage = (ceil($maxrows / $maxPerPage)-1) * $maxPerPage;
  $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
  $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;

  $sel_sort[$sortm]='selected';
  // print navigation segment
  echo "
  <table width='100%' border=0 cellspacing=0 cellpadding=1 bgcolor=$_MX_var->main_table_border_color>
  <tr>
    <td>
    <table border=0 cellspacing=0 cellpadding=0 width='100%'>
        <tr>
            <td class='formmezo' align='left'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='group_id' value='$group_id'>
          <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'>              <a href='$_MX_var->baseUrl/sms_list.php?group_id=$group_id&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/sms_list.php?group_id=$group_id&first=$OnePageLeft'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
            </td>";
 else
 echo " 
            <td nowrap align='right'>&nbsp;&nbsp;
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down1_dead.gif' width='20' height='14' border='0'>
            </td>";
 echo "
            <td nowrap align='right'> 
              <input type='text' name='pagenum' size='3' maxlength='3' value='$pagenum'>
            </td>
            <td nowrap class='formmezo'>&nbsp;/ $maxpages</td>";
if ($first<$LastPage)
echo "
            <td nowrap> &nbsp;
              <a href='$_MX_var->baseUrl/sms_list.php?group_id=$group_id&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/sms_list.php?group_id=$group_id&first=$LastPage'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>&nbsp; </td>";
 else
 echo " 
            <td nowrap align='right'>&nbsp;&nbsp;
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2_dead.gif' width='20' height='14' border='0'>
            </td>";
echo "
          </tr>
         </form>
        </table>      </td>
    </tr>
  </table>
      </td>
            <td class='formmezo' align='center'>
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
      <td class='formmezo' align='right'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
            <form>
            <input type='hidden' name='group_id' value='$group_id'>
            <td nowrap class='formmezo'>$word[sort_order]: </td>
            <td nowrap><span class='szoveg'> 
              <select onChange='JavaScript: this.form.submit();' name=sortm>
                <option value=1 $sel_sort[1]>$word[by_date_desc]</option>
                <option value=2 $sel_sort[2]>$word[by_date_asc]</option>
              </select>
              </span>
            </td>
            </form>
          </tr>
        </table>

      </td>
    </tr>
   </table>
";
}
?>
