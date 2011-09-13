<?
include "auth.php";
include "decode.php";
$weare=147;
include "cookie_auth.php";

/*if (empty($fsort) && isset($Xmfsort)) 
    $fsort=$Xmfsort;*/

if (empty($maxPerPage) && isset($Xmfppage)) 
    $maxPerPage=$Xmfppage;
    
//setcookie("Xmfsort",$fsort,time()+30*24*3600);
setcookie("Xmfppage",$maxPerPage,time()+30*24*3600);

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

########## restart/abort jobs & set regenerate timeout
$ext_dl_regen_options=array("half_hour"=>30*60,"one_hour"=>60*60,"half_day"=>12*60*60,"one_day"=>24*60*60,"one_week"=>7*24*60*60);
$xmlreq_id=get_http("xmlreq_id",0);
if (!empty($xmlreq_id)) {
    //$ext_dl_regen=get_http("ext_dl_regen",1800);
    //$ext_dl_regen_sbmt=get_http("ext_dl_regen_sbmt","");
    $job_status_new=get_http("job_status_new","");
    if (!empty($job_status_new)) {
        if (in_array($job_status_new,array("aborted","queued"))) {
            mysql_query("update xmlreq set status='$job_status_new' where id='$xmlreq_id'");
        }
    }
    //elseif (!empty($ext_dl_regen_sbmt) && in_array($ext_dl_regen,$ext_dl_regen_options)) {
    //    mysql_query("update xmlreq set ext_dl_regen='$ext_dl_regen' where id='$xmlreq_id'");
    //}
    header("Location: liveexport.php?group_id=$group_id"); exit;
}
##########
  
include "menugen.php";
include "./lang/$language/xmlreqlist.lang";

$delete_id = intval($delete_id);
$active = intval($active);
$noactive = intval($noactive);

if ($delete_id) {
    mysql_query("delete from xmlreq where id='$delete_id' and group_id='$group_id'");
}

$qmain=" from xmlreq where group_id='$group_id' and ext_dl='yes'";
$res=mysql_query("select count(*) $qmain");
if ($res && mysql_num_rows($res))
    $maxrecords=mysql_result($res,0,0);
else
    $maxrecords=0;
$stat_text="$word[total_of] $maxrecords $word[iform_title]";

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

/*if(!$fsort)
    $fsort=3;

switch ($fsort) {
    case 1: $order = "order by title"; break;
    case 2: $order = "order by html_name"; break;
    case 3: $order = "order by date desc"; break;
}*/
$order=" order by date desc";

print "
<script type='text/javascript'>
 var lrsc_processing='$word[s_processing]';
 var lrsc_queued='$word[s_queued]';
 var lrsc_ready='$word[s_ready]';
 var lrsc_aborted='$word[s_aborted]';
</script>
<script src='xmlreq2.js' type='text/javascript'></script>
<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
<TR>
<TD>
<TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
<tr>
<td class=bgkiemelt2 align=left colspan=7><span class=szovegvastag>$stat_text</span></td>
</tr>\n";

$reqlist="";
$parms="group_id=$group_id";
if ($maxrecords) {
    $rst=mysql_query("select * $qmain $order limit $first,$maxPerPage");
    $index = $first;
    if ($rst && mysql_num_rows($rst)) {
        printnavigation();
        $index++;
        if ($index%2)
           $bgrnd="class=bgvilagos2";
        else
           $bgrnd="bgcolor=white";
        echo "<tr><td class='bgkiemelt2' align=left width=14%><span class=szovegvastag>$word[job_date]</span></td>
              <td class='bgkiemelt2' align=left width=13%><span class=szovegvastag>$word[job_type]</span></td>
              <td class='bgkiemelt2' align=left width=18%><span class=szovegvastag>$word[job_description]</span></td>
              <td class='bgkiemelt2' align=left width=12%><span class=szovegvastag>$word[job_status]</span></td>
              <td class='bgkiemelt2' align=left width=21%><span class=szovegvastag>$word[job_errors]</span></td>
              <td class='bgkiemelt2' align=left width=15%><span class=szovegvastag>$word[job_regen]</span></td>
              <td class='bgkiemelt2' align=left width=7%><span class=szovegvastag>$word[job_restart_abort]</span></td>
              </tr>\n";
        while($row=mysql_fetch_array($rst)) {
            $index++;
            if ($index%2)
               $bgrnd="class=bgvilagos2";
            else
               $bgrnd="bgcolor=white";
            $actions="&nbsp;";
            //$delete="<a href='xmlreqlist.php?$parms&delete_id=$row[id]' onClick=\"return(confirm('$word[iform_confirm]'))\">$word[iform_delete]</a>&nbsp;";
            $job_status=htmlspecialchars($word["s_$row[status]"]);
            if ($row["status"]=="processing" || $row["status"]=="queued") {
                if ($row["status"]=="processing") {
                    $percent=0;
                    if ($row["progress_max"]>0) {
                        $percent=floor($row["progress"]/$row["progress_max"]*100);
                        $percent=min($percent,99);
                    }
                    $job_status.=" $percent%";
                }
                $reqlist.="item_send ($group_id,$row[id]);\n";
            }
            
            $job_date=htmlspecialchars($row["date"]);
            $job_description=htmlspecialchars($row["description"]);
            $job_type=htmlspecialchars($word["t_$row[job_type]"]);
            $ismess=array();
            $messages="";
            if (strlen($row["job_errors"])) {
                $ismess[]=nl2br(htmlspecialchars($row["job_errors"]));
            }
            if (strlen($row["job_output"]) && $row["job_type"]!="export_member") {
                $ismess[]=nl2br(htmlspecialchars($row["job_output"]));
            }
            if (strlen($row["job_output"]) && $row["job_type"]=="export_member" && $row["downloaded"]=="no") {
                if ($row["ext_dl_auth"]=="in-url" && $row["ext_dl"]=="yes") {
                    $ext_dl_password=htmlspecialchars($row["ext_dl_password"]);
                    $ext_dl_username=htmlspecialchars($row["ext_dl_username"]);
                    $url="$_MX_var->baseUrl/xrd.php?xrd=$row[id]&csvpass=$ext_dl_password&csvuser=$ext_dl_username";
                }
                else {
                    $url="$_MX_var->baseUrl/xrd.php?xrd=$row[id]";
                }
                if ($row["ext_dl"]=="yes") {
                    $job_status=htmlspecialchars($word["s_ready"]);
                }
                if ($row["ext_dl"]=="yes" || $row["downloaded"]=="no") {
                    $ismess[]="<a href='$url'>$word[export_download]</a><br>$url";
                }
            }
            if (count($ismess)) {
                $messages=implode("<br>",$ismess);
            }
            if (!empty($row["logfile"]) && $row["job_type"]!="export_member") {
                $loglink="<a href='safelog.php?xmlreq_id=$row[id]'>$word[logfile]</a>";
            }
            else {
                $loglink="";
            }
            $job_status_action="";
            if ($row["status"]=="aborted") {
                $job_status_action="<a href='liveexport.php?group_id=$group_id&xmlreq_id=$row[id]&job_status_new=queued'>$word[js_restart]</a>";
            }
            elseif ($row["status"]=="queued" || $row["status"]=="processing") {
                $job_status_action="<a href='liveexport.php?group_id=$group_id&xmlreq_id=$row[id]&job_status_new=aborted'>$word[js_abort]</a>";
            }
            $rname=array_search($row["ext_dl_regen"],$ext_dl_regen_options);
            echo "<tr>
		          <td $bgrnd align=left width=14%><span class=szoveg>$job_date</span></td>
		          <td $bgrnd align=left width=13%><span class=szoveg>$job_type</span></td>
		          <td $bgrnd align=left width=18%>
					<a href='csv_export.php?group_id=$group_id&xmlreq_id=".$row["id"]."'>$job_description =></a></td>
		          <td id='reqdiv$row[id]' $bgrnd align=left width=12%><span class=szoveg>$job_status</span></td>
		          <td id='reqerr$row[id]' $bgrnd align=left width=21%><span class=szoveg>$messages $loglink</span></td>
                  <td id='regen$row[id]' $bgrnd width=15%>".$word["job_regen_$rname"]."</td>
                  <td $bgrnd width=7% style='text-align:center;'>$job_status_action</td>
		          </tr>\n";
        }
        printnavigation();
    }
}
else {
      print"<tr>
          <td bgcolor='white' align=right colspan='5'><span class=szoveg>
          &nbsp;</span></td>
          </tr>\n";
}

echo "</table></td></tr></table>
<script>
$reqlist
</script>
";

include "footer.php";

######################### functions ####################################

function PrintNavigation() {
  global $_MX_var,$group_id,$pagenum,$LastPage,$OnePageLeft,$OnePageRight,$fsort,$filt_demog;
  global $_MX_var,$pagenum,$maxpages,$first,$word,$maxPerPage;

  $sel_sort[$fsort] = "selected";
  $params="group_id=$group_id";
  
  echo "
<tr><td colspan=5><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
<tr>
    <td>
    <table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
    	<td class='formmezo' align='left' width='33%'>
	<table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='filt_demog' value='$filt_demog'>
	  <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'>              <a href='$_MX_var->baseUrl/xmlreqlist.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/xmlreqlist.php?$params&first=$OnePageLeft'><img
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
              <a href='$_MX_var->baseUrl/xmlreqlist.php?$params&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/xmlreqlist.php?$params&first=$LastPage'><img
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
            <td nowrap class='formmezo' align='center'> $word[members_page]</td>
</form>
          </tr>
        </table>
      </td>
    	<td class='formmezo' align='right' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
<!--  <form>
  <input type='hidden' name='group_id' value='$group_id'>
  <input type='hidden' name='filt_demog' value='$filt_demog'>
            <td nowrap class='formmezo'>$word[sort_order]: </td>
            <td nowrap> 
              <select onChange='JavaScript: this.form.submit();' name=fsort>
                <option value=1 $sel_sort[1]>$word[by_name_asc]</option>
                <option value=2 $sel_sort[2]>$word[by_html_asc]</option>
                <option value=3 $sel_sort[3]>$word[by_date_desc]</option>
              </select>
            </td>
</form>-->
          </tr>
        </table>

      </td>
    </tr>
    </table></td></tr>
  ";
}
?>
