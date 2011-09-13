<?
include "auth.php";
$weare=20;
include "cookie_auth.php";  
include "common.php";
$language=select_lang();
include "./lang/$language/statistics.lang";
  
$m4512sortd=get_cookie("m4512sortd");
$m4512perpage=get_cookie("m4512perpage");
$m4512showgroup=get_cookie("m4512showgroup");

$sortd = (isset($_GET['sortd']) || empty($m4512sortd)) ? get_http('sortd',4) : $m4512sortd;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($m4512perpage)) ? get_http('maxPerPage',25) : $m4512perpage;
$showgroup = (isset($_GET['showgroup']) || empty($m4512showgroup)) ? get_http('showgroup',0) : $m4512showgroup;

setcookie("m4512sortd",$sortd,time()+30*24*3600);
setcookie("m4512perpage",$maxPerPage,time()+30*24*3600);
setcookie("m4512showgroup",$showgroup,time()+30*24*3600);  

$pagenum=get_http("pagenum","");
$first=get_http("first","");

$mres = mysql_query("select title,num_of_mess,membership from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid'
                     and (membership='owner' or membership='moderator' or membership='client')");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else 
    exit; 

$act_memb=$row["membership"];
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
    if (!in_array(3,$stattypes) || !in_array(1,$stattypes) ) {
        // user has no right to see detailed stats for CT pop-up [3] or to CT at all [1].
        exit;
    }
}

if ($feed_id) {
$r5=mysql_query("select url from feedback where id='$feed_id' and group_id='$group_id' and message_id='$message_id'");
if ($r5 && mysql_num_rows($r5))
   $f_url=mysql_result($r5,0,0);
else
   exit;
}
else
   $f_url="&lt;$word[ct_any]&gt;";

logger("",$group_id,"","message_id:$message_id, url:$f_url","trackf");	

$title=$row["title"];
  

$_MX_popup = 1;
include "menugen.php";


?>

<br>
<table width=100% border=0 cellspacing=0 cellpadding=0>
  <tr>
    <td width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=0>
        <tr>
          <td width='100%'><span class='szovegvastag'><?=$word['az'];?> <?=$f_url;?> <?=$word['ctu_'];?></span></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>

<?
  if ($feed_id)
     $res = mysql_query("SELECT distinct user_id from trackf where feed_id='$feed_id'");
  else { 
     $res = mysql_query("select distinct user_id from feedback,trackf where 
                         feedback.id=trackf.feed_id and 
			 feedback.message_id='$message_id' and feedback.group_id='$group_id'");
  }
  if($res && mysql_num_rows($res))                                           
     $maxrecords = mysql_num_rows($res);
  $maxPerPage=intval($maxPerPage);
  if($maxPerPage<1) $maxPerPage = 15;
  $pagenum=intval($pagenum);
  if($pagenum<1) $pagenum=1;
  if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
  if(!$first) $first = 0;
  if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
  $pagenum=(int)($first / $maxPerPage) + 1;
  $maxpages = ceil($maxrecords / $maxPerPage);
  if (!$sortd)
    $sortd=1;
  if ($sortd==1)
    $order="order by url asc";
  else
    $order="order by url desc";
  
  if ($maxrecords) {
    PrintNavigation();
    echo "<TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
<TR>
<TD class=MENUBORDER width='100%'>
<TABLE cellSpacing=1 cellPadding=1 width='100%' border=0>
<tr>
<td class=bgkiemelt2 align='center' width=16%><span class=szovegvastag>$word[ctu_num]</span></td>
<td class=bgkiemelt2 align='center' width=74%><span class=szovegvastag>$word[email]</span></td>
</tr>
";
  if ($feed_id)
     $rr=mysql_query("select count(*),user_id from trackf where feed_id='$feed_id' group by user_id limit $first,$maxPerPage");
  else
     $rr=mysql_query("select count(*),user_id from feedback,trackf where 
                         feedback.id=trackf.feed_id and 
			 feedback.message_id='$message_id' and feedback.group_id='$group_id' 
			 group by user_id limit $first,$maxPerPage");
  if ($rr && mysql_num_rows($rr)) {
    while ($k=mysql_fetch_array($rr)) {
      $tres2 = mysql_query("select ui_email from users_$title where id='$k[1]'");
      if ($tres2 && mysql_num_rows($tres2)) 
         $k2=mysql_fetch_array($tres2);
      else $k2="";

      echo "
<tr>
<td valign='center' align='left' class=BACKCOLOR width=16%><span class='szoveg'>$k[0]</span></td>
<td valign='center' align='left' class=BACKCOLOR width=28%><span class='szoveg'>$k2[ui_email]&nbsp;</span></td>
</tr>
";
      }
echo "
</table>
</td></tr></table>      
";
    }
PrintNavigation();

echo "</body>
</html>";
}

//****[ PrintNavigation ]*****************************************************
function PrintNavigation() {
global $_MX_var,$word, $root, $sortd, $maxPerPage, $maxrecords, $first, $maxpages, $pagenum, $group_id; 
global $_MX_var,$message_id, $feed_id;
  
  $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
  $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
  $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;
  $sel_sort[$sortd] = "SELECTED"; 
  
  echo "
    <table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
    	<td class='formmezo' align='left' width='33%'>
	  <table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='message_id' value='$message_id'>
          <input type='hidden' name='feed_id' value='$feed_id'>	  
          <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'>&nbsp;&nbsp;
              <a href='$_MX_var->baseUrl/clickthrough_url.php?feed_id=$feed_id&message_id=$message_id&group_id=$group_id&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/clickthrough_url.php?feed_id=$feed_id&message_id=$message_id&group_id=$group_id&first=$OnePageLeft'><img
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
              <input type='text' name='pagenum' size='2' maxlength='3' value='$pagenum'>
            </td>
            <td nowrap class='formmezo'>&nbsp; / $maxpages</td>";
if ($first<$LastPage)
echo "
            <td nowrap align='right'> &nbsp;
              <a href='$_MX_var->baseUrl/clickthrough_url.php?feed_id=$feed_id&message_id=$message_id&group_id=$group_id&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/clickthrough_url.php?feed_id=$feed_id&message_id=$message_id&group_id=$group_id&first=$LastPage'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>&nbsp; </td>";
 else
 echo " 
            <td nowrap align='right'>&nbsp;&nbsp;
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up2_dead.gif' width='20' height='14' border='0'>
            </td>";
echo "
           </form>
          </tr>
        </table>
      </td>
    	<td class='formmezo' align='center' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='message_id' value='$message_id'>
          <input type='hidden' name='feed_id' value='$feed_id'>	  
	  <td nowrap align='center' class='formmezo'>&nbsp;$word[view]: </td>
            <td nowrap align='center'> 
              <input type='text' name='maxPerPage' value='$maxPerPage' size='2' maxlength='3'>
            </td>
            <td nowrap align='center' class='formmezo'>&nbsp;$word[member_page]</td>
</form>
          </tr>
        </table>
      </td>
    	<td class='formmezo' align='center' width='33%'>&nbsp;<!--
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='message_id' value='$message_id'>
          <input type='hidden' name='feed_id' value='$feed_id'>	  	  
	  <td nowrap class='formmezo'>&nbsp;$word[sort_order]:</td>
            <td nowrap><span class='szoveg'> 
              <select onChange='JavaScript: this.form.submit();' name=sortd>
                <option value=1 $sel_sort[1]>$word[by_ct_url_asc]</option>
                <option value=2 $sel_sort[2]>$word[by_ct_url_desc]</option>
              </select>
              </span>
            </td>
</form>
          </tr>
        </table>-->
      </td>
    </tr>
  </table>
 </td>
</tr>
  ";
}

?>
