<?
include "auth.php";
$weare=82;
include "cookie_auth.php";

$Ammcsort=get_cookie("Ammcsort");
$Ammcppage=get_cookie("Ammcppage");

$fsort = (isset($_GET['fsort']) || empty($Ammcsort)) ? get_http('fsort',4) : $Ammcsort;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Ammcppage)) ? get_http('maxPerPage',25) : $Ammcppage;
    
setcookie("Ammcsort",$fsort,time()+30*24*3600);
setcookie("Ammcppage",$maxPerPage,time()+30*24*3600);

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
  
include "menugen.php";
include "./lang/$language/sender.lang";

if ($delete_id) {
    $delete_id=mysql_escape_string($delete_id);
    $query="delete from message_category where id='$delete_id' and group_id='$group_id'";
    mysql_query($query);
    mysql_query("update messages set message_category_id=0 where message_category_id='$delete_id'");
    mysql_query("update sender_timer set message_category_id=0 where message_category_id='$delete_id'");
	logger($query,$group_id,"","delete message category id=$delete_id","message_category"); 
}

$qmain=" from message_category where group_id='$group_id'";
$res=mysql_query("select count(*) $qmain");
if ($res && mysql_num_rows($res))
    $maxrecords=mysql_result($res,0,0);
else
    $maxrecords=0;
$stat_text="$word[total_of] $maxrecords $word[message_category]";

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
    $fsort=3;

switch ($fsort) {
    case 1: $order = "order by name"; break;
    case 2: $order = "order by name desc"; break;
}

printhead();

$parms="group_id=$group_id";
if ($maxrecords) {
	$query="select * $qmain $order limit $first,$maxPerPage";
    $rst=mysql_query($query);
	logger($query,$group_id,"","","message_category");     
    $index = $first;
    if ($rst && mysql_num_rows($rst)) {
        printnavigation();
        $index++;
        if ($index%2)
           $bgrnd="class=bgvilagos2";
        else
           $bgrnd="bgcolor=white";

        echo "<tr>
              <td class='bgkiemelt2' align=left width=20%><span class=szovegvastag>$word[t_name]</span></td>
              <td class='bgkiemelt2' align=left width=20%><span class=szovegvastag>&nbsp;</span></td>
              </tr>
              <tr>
		      <td $bgrnd align=right colspan='4'><span class=szoveg>
              <a href='message_category_ch.php?$parms'>$word[message_category_new]</a>&nbsp;</span></td>
              </tr>\n";
        while($row=mysql_fetch_array($rst)) {
            $index++;
            if ($index%2)
               $bgrnd="class=bgvilagos2";
            else
               $bgrnd="bgcolor=white";
            $modform="<a href='message_category_ch.php?$parms&message_category_id=$row[id]'>$word[message_category_change]</a>&nbsp;";
            $delname=ereg_replace("[\"'\r\n]","",$row["name"]);
            $delete="<a href='message_category.php?$parms&delete_id=$row[id]' onClick=\"return(confirm('$word[message_category_delete_confirm]: $delname'))\">$word[message_category_delete]</a>&nbsp;";
            $title=htmlspecialchars($row["name"]);
            echo "<tr>
		          <td $bgrnd align=left width=20%><span class=szoveg>$title</span></td>
		          <td $bgrnd align=right width=20%><span class=szoveg>$modform $delete</span></td>
		          </tr>\n";
        }
        printnavigation();
    }
}
else {
      print"<tr>
          <td bgcolor='white' align=right colspan='4'><span class=szoveg>
          <a href='message_category_ch.php?$parms'>$word[message_category_new]</a>&nbsp;</span></td>
          </tr>\n";
}

printfoot();

include "footer.php";

######################### functions ####################################

function printhead() {

    global $_MX_var,$stat_text,$userlist_yn,$group_id,$pagenum,$word;

    echo "<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <tr>
		<td class=bgkiemelt2 align=left colspan='4'><span class=szovegvastag>$stat_text</span></td>
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
<tr><td colspan='4'><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
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
            <td nowrap align='right'>              <a href='$_MX_var->baseUrl/message_category.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/message_category.php?$params&first=$OnePageLeft'><img
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
              <a href='$_MX_var->baseUrl/message_category.php?$params&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/message_category.php?$params&first=$LastPage'><img
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
            <td nowrap class='formmezo' align='center'> $word[message_category_page]</td>
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
