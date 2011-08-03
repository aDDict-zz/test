<?
include "auth.php";
include "decode.php";
$weare=151;
$sgweare=151;
include "cookie_auth.php";

if (empty($sortmmm) && isset($Amsortmmm)) 
    $sortmmm=$Amsortmmm;

if (empty($maxPerPage) && isset($Amperpage)) 
    $maxPerPage=$Amperpage;
    
setcookie("Amsortmmm",$sortmmm,time()+30*24*3600);
setcookie("Amperpage",$maxPerPage,time()+30*24*3600);

$multiid=get_http("multiid",0);

if ($_MX_superadmin) {
    $mres = mysql_query("select title from multi where id='$multiid'");
    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else {
        header("Location: $_MX_var->baseUrl/index.php"); exit; 
    }
    $title=$rowg["title"];
    $active_membership="";
}
else {
    $mres = mysql_query("select title,membership 
                        from multi,multi_members where multi.id=multi_members.group_id
                         and multi.id='$multiid' and user_id='$active_userid' 
                         and membership='moderator'");

    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else {
        header("Location: $_MX_var->baseUrl/index.php"); exit; 
    }
    $title=$rowg["title"];
    $active_membership=$rowg["membership"];
}
  
include "menugen.php";
include "./lang/$language/moderators.lang";

// eddig a pontig a moderator es az admin juthat el.

if ($deluser_id) {
    $old_membership="";
    $res=mysql_query("select membership from multi_members where user_id='$deluser_id' and group_id='$multiid'");
    logger($query,$multiid,"","user_id=$deluser_id","members");                    
    if ($res && mysql_num_rows($res)) {
        if (mx_can_i_modify_membership(mysql_result($res,0,0))) {
        	$query="delete from multi_members where user_id='$deluser_id' and group_id='$multiid'";
            mysql_query($query);
            logger($query,$multiid,"","user_id=$deluser_id","members");
			$query="delete from stattype_user where user_id='$deluser_id' and group_id='$multiid'";            
            mysql_query($query);
            logger($query,$multiid,"","user_id=$deluser_id","stattype");
			$query="delete from page_user where user_id='$deluser_id' and group_id='$multiid'";
            mysql_query($query);
            logger($query,$multiid,"","user_id=$deluser_id","page_user");
        }
    }
}

$qmain=" from user,multi_members where user.id=multi_members.user_id and multi_members.group_id='$multiid'";
logger($qmain,$multiid,"","","users,members");
$res=mysql_query("select count(*) $qmain");
if ($res && mysql_num_rows($res))
    $maxrecords=mysql_result($res,0,0);
else
    $maxrecords=0;
$stat_text="$word[total_of] $maxrecords $word[m_members]";

$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 25;
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

if(!$sortmmm)
    $sortmmm=1;

switch ($sortmmm) {
    case 1: $order = "order by email asc"; break;
    case 2: $order = "order by email desc"; break;
    case 3: $order = "order by create_date asc"; break;
    case 4: $order = "order by create_date desc"; break;
    case 5: $order = "order by name asc"; break;
    case 6: $order = "order by name desc"; break;
}

printhead();

$parms="multiid=$multiid";
if ($maxrecords) {
    $rst=mysql_query("select user.name,user.email,multi_members.* $qmain $order limit $first,$maxPerPage");
    $index = $first;
    if ($rst && mysql_num_rows($rst)) {
        printnavigation();
        $index++;
        if ($index%2)
           $bgrnd="class=bgvilagos2";
        else
           $bgrnd="bgcolor=white";
        echo "<td class='bgkiemelt2' align=left width=20%><span class=szovegvastag>$word[t_name]</span></td>
              <td class='bgkiemelt2' align=left width=25%><span class=szovegvastag>$word[t_email]</span></td>
              <td class='bgkiemelt2' align=left width=15%><span class=szovegvastag>$word[t_status]</span></td>
              <td class='bgkiemelt2' align=left width=15%><span class=szovegvastag>$word[t_kind]</span></td>
              <td class='bgkiemelt2' align=left width=25%><span class=szovegvastag>$word[t_actions]</span></td>
              </tr>
              <tr>
		      <td $bgrnd align=right colspan='5'><span class=szoveg>
              <a href='#' onClick='window.open(\"multi_moderators_popup.php?$parms\", \"moder\", \"width=500,height=250,scrollbars=yes,resizable=yes\"); return false;'>$word[newam]</a>&nbsp;</span></td>
              </tr>\n";
        while($row=mysql_fetch_array($rst)) {
            $index++;
            if ($index%2)
               $bgrnd="class=bgvilagos2";
            else
               $bgrnd="bgcolor=white";
            $actions="&nbsp;";
            if (mx_can_i_modify_membership($row["membership"])) {
                $deluser="<a href='multi_moderators.php?$parms&deluser_id=$row[user_id]' onClick=\"return(confirm('$word[sure_delete]'))\">$word[delete]</a>&nbsp;";
                $actions=$deluser;
            }
            $row["name"]=decode_mime_string($row["name"]);
            $membership=$row["membership"];
            if ($row["trusted_affiliate"]=="yes")
                $aadd=" [$word[trusted_affiliate_short]]";
            else
                $aadd="";
            echo "<tr>
		          <td $bgrnd align=left width=20%><span class=szoveg>$row[name]$moduserb</span></td>
		          <td $bgrnd align=left width=25%><span class=szoveg>$row[email]</span></td>
		          <td $bgrnd align=left width=15%><span class=szoveg>$row[create_date]</span></td>
		          <td $bgrnd align=left width=15%><span class=szoveg>$word[$membership]$aadd</span></td>
		          <td $bgrnd align=left width=25%><span class=szoveg>$actions</span></td>
		          </tr>\n";
        }
        echo "</form>";
        printnavigation();
    }
}

printfoot();

include "footer.php";

######################### functions ####################################

function printhead() {

    global $_MX_var,$stat_text,$userlist_yn,$filt_demog_options,$multiid,$pagenum,$word;

    echo "<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <tr>
		<td class=bgkiemelt2 align=left colspan=5><span class=szovegvastag>$stat_text</span></td>
		</tr>\n";
}

function printfoot() {

    echo "</table>
        </td>
        </tr>
        </table>";
}

//****[ PrintNavigation ]*****************************************************
function PrintNavigation() {
  global $_MX_var,$multiid,$pagenum,$LastPage,$OnePageLeft,$OnePageRight,$sortmmm,$filt_demog;
  global $_MX_var,$pagenum,$maxpages,$first,$word,$maxPerPage;

  $sel_sort[$sortmmm] = "selected";
  $params="group_id=$multiid";
  
  echo "
<tr><td colspan=5><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
<tr>
    <td>
    <table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
    	<td class='formmezo' align='left' width='33%'>
	<table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='multiid' value='$multiid'>
          <input type='hidden' name='filt_demog' value='$filt_demog'>
	  <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'>              <a href='$_MX_var->baseUrl/moderators.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/moderators.php?$params&first=$OnePageLeft'><img
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
              <a href='$_MX_var->baseUrl/moderators.php?$params&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/moderators.php?$params&first=$LastPage'><img
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
  <input type='hidden' name='multiid' value='$multiid'>
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
          <tr> 
  <form>
  <input type='hidden' name='multiid' value='$multiid'>
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
          </tr>
        </table>

      </td>
    </tr>
    </table></td></tr>
  ";
}
?>

