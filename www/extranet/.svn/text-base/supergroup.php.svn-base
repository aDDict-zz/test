<?
  include "auth.php";
  $_MX_superadmin=0;
  include "cookie_auth.php"; 
  
$sgortd=get_cookie("sgortd");
$sgperpage=get_cookie("sgperpage");
$sortm = (isset($_GET['sortd']) || empty($sgortd)) ? get_http('sortd',4) : $sgortd;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($sgperpage)) ? get_http('maxPerPage',25) : $sgperpage;

  $sortd=intval($sortd);
  $maxPerPage=intval($maxPerPage);
  
  if (empty($sortd) && isset($sgortd)) 
    $sortd=$sgortd;

  if (empty($maxPerPage) && isset($sgperpage)) 
    $maxPerPage=$sgperpage;

  setcookie("sgsortd",$sortd,time()+30*24*3600);
  setcookie("sgperpage",$maxPerPage,time()+30*24*3600);
  
  $multiid=intval(get_http("multiid",0));
  $mres = mysql_query("select * from multi,multi_members where multi.id=multi_members.group_id
                         and multi.id='$multiid' and user_id='$active_userid' 
                         and membership='moderator'");
  if ($mres && mysql_num_rows($mres))
     $row=mysql_fetch_array($mres);  
  else {
     header("Location: index.php"); exit; }
  
  $sgweare=101;
  include "menugen.php";
  include "./lang/$language/supergroup.lang";  

  $maxrecords=0;
  $first = get_http('first',0);
  $pagenum = get_http('pagenum',1);
  $sql = "SELECT count(*) FROM multigroup,groups where multiid='$multiid' and multigroup.groupid=groups.id";
  $res = mysql_query($sql);
  if ($res && mysql_num_rows($res))
    $maxrecords = mysql_result($res,0,0);
  $maxPerPage=intval($maxPerPage);
  if($maxPerPage<1) $maxPerPage = 15;
  $pagenum=intval($pagenum);
  if($pagenum<1) $pagenum=1;
  if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
  if(!$first) $first = 0;
  if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
  $pagenum=(int)($first / $maxPerPage) + 1;
  $maxpages = ceil($maxrecords / $maxPerPage);
  if ($sortd==1)
    $order="order by groups.title asc";
  else
    $order="order by groups.title desc";
  
  if ($maxrecords)
    PrintNavigation();

echo "<TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
  <TR>
    <TD class=MENUBORDER width='100%'>
      <TABLE cellSpacing=1 cellPadding=1 width='100%' border=0>
";
  $isinmulti[]="543"; //permission group
  $i=$first;
  $tres = mysql_query("select groups.id,groups.title from multigroup,groups where multiid='$multiid' and multigroup.groupid=groups.id $order limit $first,$maxPerPage");
  if ($tres && mysql_num_rows($tres)) {
  echo "<tr>
<td class=bgkiemelt2 align='right' width='15%'><span class=szovegvastag>&nbsp;</span></td>
<td class=bgkiemelt2 width='60%'><span class=szovegvastag>$word[groups]</span></td>
<td class=bgkiemelt2 width='25%'><span class=szovegvastag>&nbsp;</span></td>
</tr>
";
  while ($k=mysql_fetch_array($tres)) {
     $i++;
  echo "<tr>
<td class=BACKCOLOR align='right'><span class=szoveg>&nbsp;$i.&nbsp;</span></td>
<td class=BACKCOLOR><span class=szoveg>$k[title]</span></td>
<td class=BACKCOLOR align='center'><span class=szoveg><a href='$_MX_var->baseUrl/supergroupu.php?delgroup=$k[id]&multiid=$multiid'>$word[multi_delete]</a></span></td>
</tr>
";
   }
  }
  else {
  echo "<tr>
<td colspan=3 class=bgkiemelt2 align='center'><span class=szovegvastag>$word[multi_no_members]</span></td>
</tr>
";
  } 
  $res=mysql_query("select distinct groupid from multigroup");
  if ($res && mysql_num_rows($res))
    while ($k=mysql_fetch_array($res))
        $isinmulti[]=$k["groupid"];
  
  $i=0;
  $select="<select name='addgroup' onchange='this.form.submit()'><option value=-1>$word[not_specified]</option>";

  if ($_MX_superadmin) {
    $q="select id,title from groups order by title";
  }
  else {
    $q="select id,title from groups m inner join members mm where m.id=mm.group_id and mm.user_id='$active_userid' and mm.membership='moderator'";
  }
  
  $tres = mysql_query($q);
  if ($tres && mysql_num_rows($tres)) 
    while ($k=mysql_fetch_array($tres)) {
      //if (!in_array($k["id"],$isinmulti)) {
        $i++;
	$select.="<option value=$k[id]>$k[title]</option>";
      //}
    }
  if ($i)
  echo "<form action='supergroupu.php'>
<input type='hidden' name='multiid' value='$multiid'>
<tr>
<td colspan=3 class=bgkiemelt2 align='right'>
<span class=szoveg>$word[multi_addgroup]:&nbsp;$select</select>
</span></td>
</tr>
</form>
";

echo "
</table>
</td></tr>
</table>
";

if ($maxrecords)
  PrintNavigation();

include "footer.php";
 
//****[ PrintNavigation ]*****************************************************
function PrintNavigation() {
global $_MX_var,$word, $root, $multiid, $sortd, $maxPerPage, $maxrecords, $first, $maxpages, $pagenum; 
  
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
          <input type='hidden' name='multiid' value='$multiid'>
          <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'>&nbsp;&nbsp;
              <a href='$_MX_var->baseUrl/supergroup.php?multiid=$multiid&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/supergroup.php?multiid=$multiid&first=$OnePageLeft'><img
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
              <a href='$_MX_var->baseUrl/supergroup.php?multiid=$multiid&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/supergroup.php?multiid=$multiid&first=$LastPage'><img
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
          <input type='hidden' name='multiid' value='$multiid'>
	  <td nowrap align='center' class='formmezo'>&nbsp;$word[view]: </td>
            <td nowrap align='center'> 
              <input type='text' name='maxPerPage' value='$maxPerPage' size='2' maxlength='3'>
            </td>
            <td nowrap align='center' class='formmezo'>&nbsp;$word[group_page]</td>
</form>
          </tr>
        </table>
      </td>
    	<td class='formmezo' align='center' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
          <input type='hidden' name='multiid' value='$multiid'>
	  <td nowrap class='formmezo'>&nbsp;$word[sort_order]:</td>
            <td nowrap><span class='szoveg'> 
              <select onChange='JavaScript: this.form.submit();' name=sortd>
                <option value=1 $sel_sort[1]>$word[by_groupname_asc]</option>
                <option value=2 $sel_sort[2]>$word[by_groupname_desc]</option>
              </select>
              </span>
            </td>
</form>
          </tr>
        </table>
      </td>
    </tr>
  </table>
 </td>
</tr>
  ";
}


?>
