<?
include "auth.php";
$weare=80;
include "cookie_auth.php";

$Amfsort=get_cookie("Amfsort");
$Amfppage=get_cookie("Amfppage");

$fsort = (isset($_GET['fsort']) || empty($Amfsort)) ? get_http('fsort',4) : $Amfsort;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Amfppage)) ? get_http('maxPerPage',25) : $Amfppage;
    
setcookie("Amfsort",$fsort,time()+30*24*3600);
setcookie("Amfppage",$maxPerPage,time()+30*24*3600);

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
    $query="delete from sender_contents where id='$delete_id' and group_id='$group_id'";
    mysql_query($query);
	logger($query,$group_id,"","delete sender contents id=$delete_id","sender_contents"); 
}

$qmain=" from sender_contents where group_id='$group_id'";
$res=mysql_query("select count(*) $qmain");
if ($res && mysql_num_rows($res))
    $maxrecords=mysql_result($res,0,0);
else
    $maxrecords=0;
$stat_text="$word[total_of] $maxrecords $word[contents]";

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
    case 1: $order = "order by name desc"; break;
    case 2: $order = "order by name"; break;
}

printhead();

$parms="group_id=$group_id";
if ($maxrecords) {
	$query="select * $qmain $order limit $first,$maxPerPage";
    $rst=mysql_query($query);
	logger($query,$group_id,"","","sender_contents");     
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
              <td class='bgkiemelt2' align=left width=35%><span class=szovegvastag>$word[t_address]</span></td>
              <td class='bgkiemelt2' align=left width=25%><span class=szovegvastag>$word[t_dependent]</span></td>
              <td class='bgkiemelt2' align=left width=20%><span class=szovegvastag>&nbsp;</span></td>
              </tr>
              <tr>
		      <td $bgrnd align=right colspan='4'><span class=szoveg>
              <a href='sender_contents_ch.php?$parms'>$word[contents_new]</a>&nbsp;</span></td>
              </tr>\n";
        while($row=mysql_fetch_array($rst)) {
            $index++;
            if ($index%2)
               $bgrnd="class=bgvilagos2";
            else
               $bgrnd="bgcolor=white";
            $modform="<a href='sender_contents_ch.php?$parms&contents_id=$row[id]'>$word[contents_change]</a>&nbsp;";
            $delname=ereg_replace("[\"'\r\n]","",$row["name"]);
            $delete="<a href='sender_contents.php?$parms&delete_id=$row[id]' onClick=\"return(confirm('$word[sender_contents_delete_confirm]: $delname'))\">$word[sender_contents_delete]</a>&nbsp;";
            $dependent="&nbsp;";
            $r3=mysql_query("select variable_name,variable_type from demog where variable_name='$row[dependent_name]'");
            if ($r3 && mysql_num_rows($r3)) {
                $k3=mysql_fetch_array($r3);
                if (($k3["variable_type"]=="enum" || $k3["variable_type"]=="matrix") && ereg("^[0-9_,]+$",$row["dependent_value"])) {
                    $alldep=array();
                    $dpp=explode(",",$row["dependent_value"]);
                    foreach ($dpp as $dp) {
                        if ($k3["variable_type"]=="enum") {
                            $dpin="$dp";
                        }
                        else {
                            $dpin=str_replace("_",",",$dp);
                        }
                        $r7=mysql_query("select enum_option from demog_enumvals where id in ($dpin)");
                        if ($r7 && mysql_num_rows($r7)) {
                            $mepr=array();
                            while ($k7=mysql_fetch_array($r7)) {
                                $mepr[]=$k7["enum_option"];
                            }
                            $alldep[]=implode("=",$mepr);
                        }
                    }
                    $dependent_value=implode(" vagy ",$alldep);
                }
                else {
                    $dependent_value=$row["dependent_value"];
                }
                $dependent=htmlspecialchars($k3["variable_name"]."= \"$dependent_value\"");
            }
            $address="";
            if ($row["html_address"]) {
                $addr=htmlspecialchars($row["html_address"]);
                $address.="<a href='$addr' target='_blank'>$addr</a>";
            }
            if ($row["plain_address"]) {
                $addr=htmlspecialchars($row["plain_address"]);
                $address.=" <a href='$addr' target='_blank'>$addr</a>";
            }
            $title=htmlspecialchars($row["name"]);
            echo "<tr>
		          <td $bgrnd align=left width=20%><span class=szoveg>$title</span></td>
		          <td $bgrnd align=left width=35%><span class=szoveg>$address</span></td>
		          <td $bgrnd align=left width=25%><span class=szoveg>$dependent</span></td>
		          <td $bgrnd align=right width=20%><span class=szoveg>$modform $delete</span></td>
		          </tr>\n";
        }
        printnavigation();
    }
}
else {
      print"<tr>
          <td bgcolor='white' align=right colspan='4'><span class=szoveg>
          <a href='sender_contents_ch.php?$parms'>$word[contents_new]</a>&nbsp;</span></td>
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
            <td nowrap align='right'>              <a href='$_MX_var->baseUrl/form.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/form.php?$params&first=$OnePageLeft'><img
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
              <a href='$_MX_var->baseUrl/form.php?$params&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/form.php?$params&first=$LastPage'><img
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
            <td nowrap class='formmezo' align='center'> $word[contents_page]</td>
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
