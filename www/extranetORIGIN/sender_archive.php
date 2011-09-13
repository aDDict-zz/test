<?
include "auth.php";
$weare=148;
include "cookie_auth.php";

$Amfsort=get_cookie("Amfsort");
$Amfppage=get_cookie("Amfppage");
$Amallgroups=get_cookie("Amallgroups");

$fsort = (isset($_GET['fsort']) || empty($Amfsort)) ? get_http('fsort',4) : $Amfsort;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Amfppage)) ? get_http('maxPerPage',25) : $Amfppage;
$allgroups = (isset($_GET['allgroups']) || empty($Amallgroups)) ? get_http('allgroups',0) : $Amallgroups;
$antiallgroups=$allgroups?0:1;

setcookie("Amfsort",$fsort,time()+30*24*3600);
setcookie("Amfppage",$maxPerPage,time()+30*24*3600);
setcookie("Amallgroups",$allgroups,time()+30*24*3600);

$mres = mysql_query("select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); exit; }

$title=$rowg["title"];
$active_membership=$rowg["membership"];

$group_names=array();
  
include "menugen.php";
include "./lang/$language/sender.lang";
include "./lang/$language/dategen.lang";
include "_sender.php";
$_MX_sender = new MxSender();

if ($delete_id) 
{
	$changes="";	
	$res=mysql_query("select name,base_id,sender_id,filter_id,stype,stime,notice,test_email,smod,sdays,active,sdate from sender_archive where id='$delete_id' and group_id='$group_id'");
	$k=mysql_fetch_array($res);
	if (mysql_num_rows($res)>0)
	{
		foreach ($k as $key => $element) 
			$changes.= "*#*".$key.":: ".$element." -> , ";
	}		
	if ($changes != NULL)
	{
		mysql_query("insert into sender_log set user_id='$active_userid',group_id='$group_id',timer_id='$timer_id',log_desc='$changes',date_mod=now(),chtype='st_torolve'");
	}

    $delete_id=mysql_escape_string($delete_id);
    mysql_query("delete from sender_archive where id='$delete_id' and group_id='$group_id'");
}

$gqpart="";
if ($allgroups) {
    if (!$_MX_superadmin) {
        $allmy=array();
        $res=mysql_query("select distinct group_id from members where user_id='$active_userid' 
                          and membership in ('moderator','owner')");
        while ($k=mysql_fetch_array($res)) {
            $allmy[]=$k["group_id"];
        }
        $gqpart=" and group_id in (". implode(",",$allmy) . ")";
    }
}
else {
    $gqpart=" and group_id='$group_id'";
}

$qmain="from sender_archive where test='no' $gqpart";

$res=mysql_query("select count(*) $qmain");
if ($res && mysql_num_rows($res))
    $maxrecords=mysql_result($res,0,0);
else
    $maxrecords=0;
$stat_text="$word[total_of] $maxrecords $word[timer]";

$pagenum=get_http("pagenum","");
$first=get_http("first","");
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

if(!$fsort)
    $fsort=4;

switch ($fsort) {
    case 1: $order = "order by group_id,name"; break;
    case 2: $order = "order by group_id,name desc"; break;
    case 3: $order = "order by id"; break;
    case 4: $order = "order by id desc"; break;
}

printhead();

printnavigation();
$agch=$allgroups?" checked":"";
$viewlogs="<a href='#' onClick='window.open(\"view_log.php?group_id=$group_id\", \"fefem\", \"width=650,height=500,scrollbars=yes,resizable=yes\"); return false;'>Log</a>";

echo "<tr>
      <td class='bgkiemelt2' width=12%><span class=szovegvastag>$word[group]</span><span class='szoveg'> (<input type='checkbox' onclick=\"location='sender_archive.php?group_id=$group_id&allgroups=$antiallgroups';\"$agch>$word[st_allgroups])</span></td>
      <td class='bgkiemelt2' width=22%><span class=szovegvastag>$word[st_name]</span></td>
      <td class='bgkiemelt2' width=17%><span class=szovegvastag>$word[t_base]</span></td>
      <td class='bgkiemelt2' width=24%><span class=szovegvastag>$word[t_type]</span></td>
      <td class='bgkiemelt2' width=10%><span class=szovegvastag>$word[t_active]</span></td>
      <td class='bgkiemelt2' width=15%><span class=szovegvastag>$word[st_sdate]</span></td>
      </tr>
      <tr>
		<td bgcolor=white align=right colspan='6'><span class=szoveg>$viewlogs</span></td>
		</tr>\n";
if ($maxrecords) {
    $rst=mysql_query("select * $qmain $order limit $first,$maxPerPage");
    $index = $first;
    if ($rst && mysql_num_rows($rst)) {
        $index++;
        if ($index%2)
           $bgrnd="class=bgvilagos2";
        else
           $bgrnd="bgcolor=white";
        while($row=mysql_fetch_array($rst)) {
            $index++;
            if ($index%2)
               $bgrnd="class=bgvilagos2";
            else
               $bgrnd="bgcolor=white";
            $actions="&nbsp;";
			$viewlog="<a href='#' onClick='window.open(\"view_log.php?group_id=$group_id&timer_id=$row[sender_timer_id]\", \"fefem\", \"width=650,height=500,scrollbars=yes,resizable=yes\"); return false;'>Log</a>";
            $delname=ereg_replace("[\"'\r\n]","",$row["name"]);
            $title=htmlspecialchars($row["name"]);
            $base="";
            $r2=mysql_query("select name from sender_base where id='$row[base_id]'");
            if ($r2 && mysql_num_rows($r2)) {
                $base=htmlspecialchars(mysql_result($r2,0,0));
            }
            $active=$word["st_$row[active]"];
            $type=$word["st_$row[stype]"].": ";
	    if ($row["stype"]=="single") {
            	$errdesc=$_MX_sender->GetStatus($row["sender_timer_id"],'archive');
	    }
	    else {
	        // itt nyilvan nem nezhetjuk a legutobbi hibat, hanem azt amit logoltunk..........
		if (empty($row["last_error"])) {
		    $errdesc=array($word["sent_message"],"");
		}
		else {
	    	    $errdesc=array($word["permanent_error_unsent"] . " " .  $row["last_error"],"");
		}
	    }
            if ($row["stype"]=="single") {
                $type.=$row["sdate"];
            }
            elseif ($row["stype"]=="cyclical") {
                for ($i=0; $i<7; $i++) {
                    if (substr($row["sdays"],$i,1)=="X") {
                        $type.=$word["st_day$i"]." ";
                    }
                }
                $type.=$row["stime"];
            }
            elseif ($row["stype"]=="havonta") {
                $type.=" $row[smonthday]., $row[stime]";
            }
            else {
                $type.=" $errdesc[3]";
            }
            $group_name=htmlspecialchars(mx_get_group_names($row["group_id"]));
            echo "<tr>
		          <td style='background-color:#ddd;'><b>$group_name</b></td>
		          <td style='background-color:#ddd;'>$title</td>
		          <td style='background-color:#ddd;'>$base</td>
		          <td style='background-color:#ddd;'>$type</td>
		          <td style='background-color:#ddd;'>$active</td>
		          <td align=right style='background-color:#ddd;'>$row[dateadd]&nbsp;&nbsp;$viewlog</td>
		          </tr>
                  <tr><td colspan='6' style='border-bottom:1px $_MX_var->main_table_border_color solid; background-color:#ddd;'>". htmlspecialchars($errdesc[0]) ."</span></td></tr>\n";
        }
        printnavigation();
    }
}

printfoot();

include "footer.php";

######################### functions ####################################

function printhead() {

    global $_MX_var,$stat_text,$userlist_yn,$group_id,$pagenum,$word;

    echo "
<style>
#maintbl td {
	FONT-SIZE: 10pt; COLOR: #000000; FONT-FAMILY: Arial, Helvetica, sans-serif
}
</style>
    <TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0 id='maintbl'>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <tr>
		<td class=bgkiemelt2 align=left colspan='6'><span class=szovegvastag>$stat_text</span></td>
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
  global $_MX_var,$group_id,$pagenum,$LastPage,$OnePageLeft,$OnePageRight,$fsort;
  global $_MX_var,$pagenum,$maxpages,$first,$word,$maxPerPage;

  $sel_sort[$fsort] = "selected";
  $params="group_id=$group_id";
  
  echo "
<tr><td colspan='6'><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
<tr>
    <td>
    <table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
    	<td class='formmezo' align='left' width='33%'>
	<table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='group_id' value='$group_id'>
	  <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'>              <a href='$_MX_var->baseUrl/sender_archive.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/sender_archive.php?$params&first=$OnePageLeft'><img
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
              <a href='$_MX_var->baseUrl/sender_archive.php?$params&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/sender_archive.php?$params&first=$LastPage'><img
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
            <td nowrap class='formmezo' align='center'>$word[view]:</td>
            <td nowrap align='center'> 
              <input type='text' name='maxPerPage' value='$maxPerPage' size='3' maxlength='3'>
            </td>
            <td nowrap class='formmezo' align='center'> $word[timer_page]</td>
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
            <td nowrap> 
              <select onChange='JavaScript: this.form.submit();' name=fsort>
                <option value=1 $sel_sort[1]>$word[by_name_asc]</option>
                <option value=2 $sel_sort[2]>$word[by_name_desc]</option>
                <option value=3 $sel_sort[3]>$word[by_date_asc]</option>
                <option value=4 $sel_sort[4]>$word[by_date_desc]</option>
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

function mx_get_group_names($gid) {

    global $_MX_var,$group_names;

    $gn="";
    if (isset($group_name["$gid"])) {
        $gn=$group_name["$gid"];
    }
    else {
        $r=mysql_query("select name,title from groups where id='$gid'");
        if ($r && mysql_num_rows($r)) {
            /*if (strlen(mysql_result($r,0,0))) {
                $gn=mysql_result($r,0,0);
            }
            else {*/
                $gn=mysql_result($r,0,1);
            //}
            $group_name["$gid"]=$gn;
        }
    }
    return $gn;
}
 
?>
