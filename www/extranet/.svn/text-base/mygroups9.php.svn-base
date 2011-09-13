<?
include "auth.php";
$weare=12;
include "cookie_auth.php";  

$m9sortd=get_cookie('m9sortd');
$m9perpage=get_cookie("m9perpage");
$m9showgroup=get_cookie("m9showgroup");

$sortd = (isset($_GET['sortd']) || empty($m9sortd)) ? get_http('sortd',1) : $m9sortd;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($m9perpage)) ? get_http('maxPerPage',25) : $m9perpage;
$showgroup = (isset($_POST['showgroup']) || empty($m9showgroup)) ? get_http('showgroup',-1) : $m9showgroup;


$sortd=intval($sortd);
$maxPerPage=intval($maxPerPage);

setcookie("m9sortd",$sortd,time()+30*24*3600);
setcookie("m9perpage",$maxPerPage,time()+30*24*3600);
setcookie("m9showgroup",$showgroup,time()+30*24*3600);  

$delgroup=get_http('delgroup','');
$newgroup=get_http('newgroup','');
$first=get_http('first','');
//$maxPerPage=get_http('maxPerPage','');
//$sortd=get_http('sortd','');
$user_id=get_http('user_id','');
$addtogroup=get_http('addtogroup','');
/*
$showgroup=get_http('showgroup','');
$showgroup=intval($showgroup);
if (!$showgroup)
    $showgroup=-1;
*/
    
$mres = mysql_query("select groups.* from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); exit; }
$title=$rowg["title"];
$unique_col=$rowg["unique_col"];
  
include "menugen.php";
include "./lang/$language/mygroups9.lang";

sel_java();

$i=0;

if ($err) 
    echo "<br><TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
        <TR>
        <TD class=MENUBORDER width='100%'>
        <TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
        <tr>
        <td class=BACKCOLOR colspan='3' align='left'>
        <span class='szovegvastag'>$word[title_error]</span></td>
        </tr>
        </table>
        </td>
        </tr>
        </table>\n";


echo "<br><TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
      <TR>
      <TD class=MENUBORDER width='100%'>
      <TABLE cellSpacing=1 cellPadding=1 width='100%' border=0>\n";  
$select="<select name='showgroup' onchange='this.form.submit()'><option value=-1>$word[ug_allmembers]</option>";
$select_addto="<select name='addtogroup' onchange='this.form.submit()'><option value=-1>$word[not_specified]</option>";  
$tres = mysql_query("select * from user_group where group_id='$group_id'");
logger("",$group_id,"","group_id=$group_id","user_group");
if ($tres && mysql_num_rows($tres)) {
    echo "<tr>
        <td class=bgkiemelt2 align='right' width='15%'><span class=szovegvastag>&nbsp;No.&nbsp;</span></td>
        <td class=bgkiemelt2 width='60%'><span class=szovegvastag>$word[ug_]</span></td>
        <td class=bgkiemelt2 width='25%'><span class=szovegvastag>&nbsp;</span></td>
        </tr>\n";
    while ($k=mysql_fetch_array($tres)) {
        $i++;
        if ($k["id"]==$showgroup)
            $selected="selected";
        else
            $selected="";
        if ($i%2 == 1) {
            $bgcolor = 'oddbgcolor';
        }
        else $bgcolor = 'evenbgcolor';

        $select.="<option value=$k[id] $selected>$k[name] $word[ug_ug_members]</option>";
        $select_addto.="<option value=$k[id] $selected>$k[name]</option>";  
        echo "<tr class='$bgcolor'>
            <td align='right'><span class=szoveg>&nbsp;$i.&nbsp;</span></td>
            <td><span class=szoveg>$k[name]</span></td>
            <td align='center'><span class=szoveg>
            <a href='$_MX_var->baseUrl/mygroups9u.php?delgroup=$k[id]&group_id=$group_id'>$word[ug_delete]</a></span></td>
            </tr>\n";
    }
}
else 
    echo "<tr>
          <td colspan=3 class=bgkiemelt2 align='center'><span class=szovegvastag>$word[ug_no_ug]</span></td>
          </tr>\n";
   
$select.="</select>";
$select_addto.="</select>";
echo "<form action='mygroups9u.php' method='post'>
    <input type='hidden' name='group_id' value='$group_id'>
    <tr>
    <td colspan=3 class=bgkiemelt2 align='right'>
    <span class=szoveg>$word[ug_newgroup]:</span>&nbsp;<input name='newgroup'>
    <input class=tovabbgomb type='submit' value='$word[submit3]'>
    </td>
    </tr>
    </form>
    </table>
    </td></tr>
    </table>
    <br><TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
    <TR>
    <TD width='100%'>
    <TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
    <form method='post'>
    <input type='hidden' name='group_id' value='$group_id'>
    <tr>
    <td align='left'><span class=szoveg>
    $select
    </span></td>
    </tr>
    </form>
    </table>
    </td></tr>
    </table>\n";

$res=mysql_query("select id from user_group where id='$showgroup' and group_id='$group_id'");
if (!$res || !mysql_num_rows($res))
    $showgroup=-1;

if ($showgroup==-1)
    $query="from users_$title where bounced='no' and validated='yes' and robinson='no'";
else
    $query="from user_group_members,users_$title where user_group_members.user_id=users_$title.id 
            and user_group_id='$showgroup' and  bounced='no' and validated='yes' and robinson='no'";

$maxrecords=0;
$sql = "SELECT count(*) $query";
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

$unique_addid=0;
if (empty($unique_col)) {
    $unique_col="email";
    $unique_addid=1;
}
if ($unique_col=="email")
    $unique_title=$word["email"];
else
    $unique_title=$unique_col;
if ($unique_addid)
    $unique_title.=" (id)";

if (!$sortd)
    $sortd=1;

if ($sortd==1) {
    $order="order by ui_$unique_col asc";
    if ($unique_addid) $order.=",id asc";
}
else {
    $order="order by ui_$unique_col desc";
    if ($unique_addid) $order.=",id desc";
}

if ($maxrecords)
PrintNavigation();

echo "<TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
    <TR>
    <TD class=MENUBORDER width='100%'>
    <TABLE cellSpacing=1 cellPadding=1 width='100%' border=0>
    <form method='post' action='mygroups9u.php' name='myinputs'>
    <input type='hidden' name='group_id' value='$group_id'>
    <input type='hidden' name='showgroup' value='$showgroup'>      
    <tr>
    <td class=bgkiemelt2 align='right' width='7%'><span class=szovegvastag>&nbsp;$word[no]&nbsp;</span></td>
    <td class=bgkiemelt2 width='58%'><span class=szovegvastag>$unique_title</span></td>\n";
if ($showgroup==-1)
    echo "<td class=bgkiemelt2 width='35%' align='center'>
          <span class=szoveg>$word[ug_addto]:&nbsp;</span>$select_addto<br>\n";
else
    echo "<td class=bgkiemelt2 width='35%' align='center'>
          <span class=szoveg>$word[ug_copyto]:&nbsp;</span>$select_addto<br>
          <input class=tovabbgomb type='submit' name='delusers' value='$word[ug_delfrom_ug]'><br>\n";
echo "<a href=\"javascript:select_all()\"><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/selectall.gif' border='0' alt='$word[select_all]'></a>
      <a href=\"javascript:deselect_all()\"><img src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/selectnone.gif' border='0' alt='$word[select_none]'></a>
      </td></tr>\n";

$i=$first;
$tres = mysql_query("select users_$title.id,ui_$unique_col $query $order limit $first,$maxPerPage");
if ($tres && mysql_num_rows($tres)) {
    while ($k=mysql_fetch_array($tres)) {
        $i++;
        $id_=$k["id"];
        $unique_data=$k["ui_$unique_col"];
        if ($unique_addid)
            $unique_data.=" ($k[id])";
        if ($i%2 == 1) {
            $bgcolor = 'oddbgcolor';
        }
        else $bgcolor = 'evenbgcolor';            
        echo "<tr class='$bgcolor'>
            <td align='right' width='7%'><span class=szoveg>&nbsp;$i.&nbsp;</span></td>
            <td width='58%'><span class=szoveg>$unique_data&nbsp;&nbsp; 
            <a href='#' onClick='window.open(\"$_MX_var->baseUrl/members_demog_info.php?group_id=$group_id&user_id=$k[id]\", \"m_d_i\", \"width=510,height=400,scrollbars=yes,resizable=yes\"); return false;'>demog info</a></td>
            <td width='35%' align='center'><input type='checkbox' name='user_id[$id_]'></td>
            </tr>\n";
        }
    }
else 
    echo "<tr>
          <td colspan=3 class=bgkiemelt2 align='center'><span class=szovegvastag>$word[ug_no_members]</span></td>
          </tr>\n";
   
echo "</form>
    </table>
    </td></tr>
    </table>";

if ($maxrecords)
    PrintNavigation();

include "footer.php";
 
//****[ PrintNavigation ]*****************************************************
function PrintNavigation() {
global $_MX_var,$word, $root, $multiid, $sortd, $maxPerPage, $maxrecords, $first, $maxpages, $pagenum, $group_id, $unique_col; 
  
  $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
  $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
  $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;
  $sel_sort[$sortd] = "selected"; 
  
  echo "
    <table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
    	<td class='formmezo' align='left' width='33%'>
	  <table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='group_id' value='$group_id'>
          <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'>&nbsp;&nbsp;
              <a href='$_MX_var->baseUrl/mygroups9.php?group_id=$group_id&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/mygroups9.php?group_id=$group_id&first=$OnePageLeft'><img
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
              <a href='$_MX_var->baseUrl/mygroups9.php?group_id=$group_id&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/mygroups9.php?group_id=$group_id&first=$LastPage'><img
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
	  <td nowrap align='center' class='formmezo'>&nbsp;$word[view]: </td>
            <td nowrap align='center'> 
              <input type='text' name='maxPerPage' value='$maxPerPage' size='2' maxlength='3'>
            </td>
            <td nowrap align='center' class='formmezo'>&nbsp;$word[member_page]</td>
</form>
          </tr>
        </table>
      </td>
    	<td class='formmezo' align='center' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
          <input type='hidden' name='group_id' value='$group_id'>
	  <td nowrap class='formmezo'>&nbsp;$word[sort_order]:</td>
            <td nowrap><span class='szoveg'> 
              <select onChange='JavaScript: this.form.submit();' name=sortd>
                <option value=1 $sel_sort[1]>$unique_col $word[by_asc]</option>
                <option value=2 $sel_sort[2]>$unique_col $word[by_desc]</option>
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

###########################################################################

function sel_java() {

echo "
<script language=\"JavaScript\">
function select_all()
{
  len = document.myinputs.elements.length;
  var i=0;
  for(i=0; i < len; i++) {
    if (document.myinputs.elements[i].type == 'checkbox')
    { document.myinputs.elements[i].checked = true }
  }

}
function deselect_all()
{
  len = document.myinputs.elements.length;
  var i=0;
  for(i=0; i < len; i++) {
    if (document.myinputs.elements[i].type == 'checkbox')
    { document.myinputs.elements[i].checked = false }
  }

}
</script>
";
}
?>
