<?
include "auth.php";
$sgweare=19;
$weare=19;
include "cookie_auth.php";  

$multiid = get_http('multiid','');
$pagenum = get_http('pagenum','');
$letterfilt = get_http('letterfilt','');
$first = get_http('first','');
$maxPerPage = get_http('maxPerPage','');
$filter_archive_show = get_http('filter_archive_show','');
$action = get_http('action','');
$filter_delete = get_http('filter_delete','');
$filter_id = get_http('filter_id','');
$faction = get_http('faction','');
$add = get_http('add','');
$group_id=intval($group_id);
$multiid=intval($multiid);
$mres = mysql_query("select title from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); exit; }
$title=$rowg["title"];
  
if (empty($filter_archive_show) && isset($_COOKIE["Bofilter_archive_show"])) 
    $filter_archive_show=$_COOKIE["Bofilter_archive_show"];
if ($filter_archive_show!="all")
    $filter_archive_show="live";
setcookie("Bofilter_archive_show",$filter_archive_show,time()+30*24*3600);

if (empty($maxPerPage) && isset($_COOKIE["Amperpage"])) 
    $maxPerPage=$_COOKIE["Amperpage"];
if (!isset($first) && isset($_COOKIE["Am15first"])) 
    $first=$_COOKIE["Am15first"];
if (empty($letterfilt) && isset($_COOKIE["Amletterfilt"])) 
    $letterfilt=$_COOKIE["Amletterfilt"];
    
setcookie("Amperpage",$maxPerPage,time()+30*24*3600);
setcookie("Am15first",$first,time()+30*24*3600);
setcookie("Amletterfilt",$letterfilt,time()+30*24*3600);

include "menugen.php";
include "./lang/$language/filter.lang";

if ($filter_archive_show=="all") {
    $aas_sel="selected";
    $fqpart="";
}
else {
    $aas_sel="";
    $fqpart=" and archived='no'";
}

if ($action=="archive") {
    if ($add==1) {
        $q="update filter set archived='yes' where id='$filter_id'";
        mysql_query($q);
        logger($q,$group_id,"","filter_id=$filter_id","filter");        
    }
    else {
        $q="update filter set archived='no' where id='$filter_id'";
        mysql_query($q);
        logger($q,$group_id,"","filter_id=$filter_id","filter");        
    }
}

if ($faction=="filter_delete") {
    $delfilts=array();
    foreach ($_POST as $pvar=>$pval) {
        if (ereg("filter_delete_cb_([0-9]+)",$pvar,$regs)) {
            $delfilts[]=$regs[1];
        }
    }
    if (count($delfilts)) {
        mx_filter_delete($delfilts);
    }
}

if ($filter_delete) {
    mx_filter_delete(array(intval($filter_delete)));
}

if (!ereg("^[a-z]$",$letterfilt))
    $letterfilt="";
if (strlen($letterfilt))
    $lqpart="and name like '$letterfilt%'";
else
    $lqpart="";

$qmain="from filter where group_id='$group_id' $fqpart $lqpart";
logger($qmain,$group_id,"","","filter");
$res=mysql_query("select count(*) $qmain");
if ($res && mysql_num_rows($res))
    $maxrecords=mysql_result($res,0,0);
else
    $maxrecords=0;

$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 50;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if($first<0) $first=0;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;
if($first>=$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
$pagenum=(int)($first / $maxPerPage) + 1;
$maxpages = ceil($maxrecords / $maxPerPage);
$LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
$OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = -1;
$OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;

$res=mysql_query("select * $qmain order by name limit $first,$maxPerPage"); //echo "select * $qmain order by name limit $first,$maxPerPage"; die();
  
echo "<script>
        function f_go(letter) {
            location='mygroups15.php?group_id=$group_id&letterfilt='+letter;
        }
        function fdeletes(letter) {
            var f=document.factions;
            f.faction.value='filter_delete';
            if (confirm('Biztosan törölni szeretné a kijelölt filtereket?')) {
                f.submit();
            }
        }
      </script>  
      <TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
      <TR>
      <TD class=MENUBORDER width='100%'>
      <TABLE cellSpacing=1 cellPadding=1 width='100%' border=0>\n";
  echo "<tr><td colspan=3 class='BACKCOLOR'><span class='szovegvastag'>\n";
    for ($i=65;$i<91;$i++) {
        $char=chr($i);
        $fchar=strtolower($char);
        if ($fchar==$letterfilt)
            echo "&nbsp;&nbsp;$char&nbsp;&nbsp;&nbsp;";
        else
            echo "<a href=\"javascript:f_go('$fchar');\">$char</a> ";
    }
  echo "<a href=\"javascript:f_go('all');\">Mind</a> 
        </span></td></tr>\n";

if ($res && mysql_num_rows($res)) {
echo "<tr>
      <td class=BACKCOLOR colspan='3' valign='top' align='left'><span class='szovegvastag'>
      <a href='$_MX_var->baseUrl/mygroups15_edit.php?group_id=$group_id'>$word[newfilt_adv]</a><br>
      <a href='$_MX_var->baseUrl/filter_wizard.php?group_id=$group_id'>$word[newfilt_wiz]</a>
      </span></td>
      </tr>";    
    PrintNavigation();
   echo "<form><input type='hidden' name='group_id' value='$group_id'><tr>
         <td class=bgkiemelt2 valign='top'><span class='szovegvastag'>$word[vf_name]</span></td>
         <td class=bgkiemelt2 valign='top'><span class='szovegvastag'>$word[vf_expression]&nbsp;</span></td>
         <td class=bgkiemelt2 valign='top'><span class='szovegvastag'><select name='filter_archive_show' onChange='JavaScript: this.form.submit();'><option value='live'>$word[a_not]</option><option value='all' $aas_sel>$word[a_all]</option> </select></span></td>
         </tr></form>
         <tr><td class='trborderblue bgkiemelt2' valign='top' colspan='3' style='text-align:right;'><a href='#' onclick='fdeletes()'>A kijelölt filterek törlése</a></td></tr>
         <form method='post' name='factions' id='factions'><input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='faction' value=''>\n";
   $i=1;
   while ($k=mysql_fetch_array($res)) {
       printrow($i);
       $i++;
   }
    echo " <tr><td class='trborderblue bgkiemelt2' valign='top' colspan='3' style='text-align:right;'><a href='#' onclick='fdeletes()'>A kijelölt filterek törlése</a></td></tr> </form>";
    PrintNavigation();
}
else
    echo "<tr>
          <td class=BACKCOLOR colspan='3' valign='top'><span class='szoveg'>$word[vf_nofilter]</span></td>
          </tr>\n";

echo "<tr>
      <td class=BACKCOLOR colspan='3' valign='top' align='left'><span class='szovegvastag'>
      <a href='$_MX_var->baseUrl/mygroups15_edit.php?group_id=$group_id'>$word[newfilt_adv]</a><br>
      <a href='$_MX_var->baseUrl/filter_wizard.php?group_id=$group_id'>$word[newfilt_wiz]</a>
      </span></td>
      </tr>
      </table>
      </td>
      </tr>
      </table>\n";

include "footer.php";

######################### functions #############################

function printrow($i) {

    global $_MX_var,$k,$multiid,$group_id,$word;

    $id=$k["id"];

    $name=htmlspecialchars($k["name"]);    

    if ($k["archived"]=="no")
        $archive="<a onClick=\"return confirm('$word[a_add] $name $word[a_add2]');\" href='mygroups15.php?group_id=$group_id&action=archive&filter_id=$id&add=1'>$word[a_addlink]</a>";
    else
        $archive="<a onClick=\"return confirm('$word[a_subs] $name $word[a_subs2]');\" href='mygroups15.php?group_id=$group_id&action=archive&filter_id=$id&add=0'>$word[a_subslink]</a>";

    if ($k["ftype"]=="wizard") {
        $href="<a href=$_MX_var->baseUrl/filter_wizard.php?group_id=$group_id&id=$k[id]>$word[vf_change]</a><br>
               <a onClick=\"return confirm('$word[adv_edit_alert]');\" href='$_MX_var->baseUrl/mygroups15_edit.php?group_id=$group_id&id=$k[id]&force_advanced=1'>$word[adv_edit]</a>";
    }
    else
        $href="<a href=$_MX_var->baseUrl/mygroups15_edit.php?group_id=$group_id&id=$k[id]>$word[vf_change]</a>";

    if ($i%2 == 1) {
        $bgcolor = 'oddbgcolor';
    }
    else {
        $bgcolor = 'evenbgcolor';
    }
    echo "<tr class='$bgcolor'>
          <td valign='top' class='trborderblue'><span class='szoveg'>$k[name]&nbsp;</span></td>
          <td valign='top' class='trborderblue'><span class='szoveg'>$k[query_text]&nbsp;</span></td>
          <td valign='top' class='trborderblue'><span class='szoveg'>$href<br>$archive<br><input type='checkbox' name='filter_delete_cb_$k[id]' value=1> <a onClick=\"return confirm('$word[filt_delete_sure_p] $k[name] $word[filt_delete_sure_s]');\" href='$_MX_var->baseUrl/mygroups15.php?filter_delete=$k[id]&group_id=$group_id'>$word[filt_delete]</a></span></td>
          </tr>\n"; }

//****[ PrintNavigation ]*****************************************************
function PrintNavigation() {
  global $_MX_var,$group_id,$pagenum,$LastPage,$OnePageLeft,$OnePageRight,$sortmmm,$filt_demog;
  global $_MX_var,$pagenum,$maxpages,$first,$word,$maxPerPage,$letterfilt;

  $sel_sort[$sortmmm] = "selected";
  $params="group_id=$group_id&letterfilt=$letterfilt";
  
  echo "
<tr><td colspan=3><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
<tr>
    <td>
    <table border=0 cellspacing=0 cellpadding=0 width='100%'>
    <tr>
        <td class='formmezo' align='left' width='33%'>
    <table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='letterfilt' value='$letterfilt'>
          <input type='hidden' name='filt_demog' value='$filt_demog'>
      <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'>              <a href='$_MX_var->baseUrl/mygroups15.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/mygroups15.php?$params&first=$OnePageLeft'><img
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
            <td nowrap align='right'> &nbsp;
              <a href='$_MX_var->baseUrl/mygroups15.php?$params&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/mygroups15.php?$params&first=$LastPage'><img
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
    </tr>
  </table>
      </td>
        <td class='formmezo' align='center' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
  <input type='hidden' name='group_id' value='$group_id'>
  <input type='hidden' name='letterfilt' value='$letterfilt'>
  <input type='hidden' name='filt_demog' value='$filt_demog'>
            <td nowrap class='formmezo' align='center'>$word[view]:</td>
            <td nowrap align='center'> 
              <input type='text' name='maxPerPage' value='$maxPerPage' size='3' maxlength='3'>
            </td>
            <td nowrap class='formmezo' align='center'> $word[members_page]</td>
</form>
          </tr>
        </table>
      </td>
        <td class='formmezo' align='right' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <!--<tr> 
  <form>
  <input type='hidden' name='group_id' value='$group_id'>
  <input type='hidden' name='filt_demog' value='$filt_demog'>
            <td nowrap class='formmezo'>$word[sort_order]: </td>
            <td nowrap> 
              <select onChange='JavaScript: this.form.submit();' name=sortmmm>
                <option value=1 $sel_sort[1]>$word[by_email_asc]</option>
                <option value=2 $sel_sort[2]>$word[by_email_asc]</option>
                <option value=3 $sel_sort[3]>$word[by_stat_asc]</option>
                <option value=4 $sel_sort[4]>$word[by_stat_desc]</option>
                <option value=5 $sel_sort[5]>$word[by_name_asc]</option>
                <option value=6 $sel_sort[6]>$word[by_name_desc]</option>
              </select>
            </td>
</form>
          </tr>-->
        </table>

      </td>
    </tr>
    </table></td></tr>
  ";
}

function mx_filter_delete($filters) {

    global $group_id;

    $flist = implode(",",$filters);
    $q="delete from filter where id in ($flist) and group_id='$group_id'";
    mysql_query($q);
    logger($q,$group_id,"","filter_id in ($flist)","filter");           
    if (mysql_affected_rows()) {
        mysql_query("delete from filter_data where filter_id in ($flist)");
    }
}
?>
