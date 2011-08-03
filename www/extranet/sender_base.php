<? 

include "main.php";

include "auth.php";
$weare=24;
include "cookie_auth.php";
include "sender_gen.php";
    
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

$gqpart="";
if ($allgroups) {
    if (!$_MX_superadmin) {
        $allmy=array();
        $res=mysql_query("select distinct group_id from members where user_id='$active_userid' 
                          and membership in ('moderator','owner')");
        while ($k=mysql_fetch_array($res)) {
            $allmy[]=$k["group_id"];
        }
        $gqpart=" and groups.id in (". implode(",",$allmy) . ")";
    }
}
else {
    $gqpart=" and groups.id='$group_id'";
}
$mquery = "select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     $gqpart and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'";
$mres = mysql_query($mquery);
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
    $query="delete from sender_base where id='$delete_id'";
    //$query="delete from sender_base where id='$delete_id' and group_id='$group_id'";
    mysql_query($query);
   	logger($query,$group_id,"","","sender_base");    
    $query="delete from sender_base_property where sender_base_id='$delete_id'";
    mysql_query($query);
	logger($query,$group_id,"","","sender_base");    
}
$copy_base=get_http('copy_base','');
if ($copy_base) {
    mx_copy_base($copy_base,$group_id);
}

$wh="";
$subs=get_http("subs","");
$nams=get_http("nams","");
if ($subs) $wh.=" and subject like '%$subs%'";
if ($nams) $wh.=" and name like '%$nams%'";

$qmain=" from sender_base where group_id='$group_id' $wh";
logger("select *".$qmain,$group_id,"",$info="","sender_base");
$res=mysql_query("select count(*) $qmain");
if ($res && mysql_num_rows($res))
    $maxrecords=mysql_result($res,0,0);
else
    $maxrecords=0;
$stat_text="$word[total_of] $maxrecords $word[base]";

$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 25;
$pagenum=intval(get_http('pagenum',1));
$first=intval(get_http('first',0));
if($pagenum<1) $pagenum=1;
if($first<0) $first=0;
if(empty($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
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
    case 1: $order = "order by name"; break;
    case 2: $order = "order by name desc"; break;
    case 3: $order = "order by date,name"; break;
    case 4: $order = "order by date desc,name"; break;
}

printhead();

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
        $agch=$allgroups?" checked":"";
        echo "<tr><td class='bgkiemelt2' align=left width=30%><span class=szovegvastag>$word[group]</span><span class='szoveg'> (<input type='checkbox' onclick=\"location='sender_base.php?group_id=$group_id&allgroups=$antiallgroups';\"$agch>$word[st_allgroups])</span> <span class=szovegvastag>$word[t_name]</span></td>
              <td class='bgkiemelt2' align=left width=25%><span class=szovegvastag>$word[sb_subject]</td>
              <td class='bgkiemelt2' align=left width=30%><span class=szovegvastag>&nbsp;</span></td>
              <td class='bgkiemelt2 tac' width=15%><span class=szovegvastag>$word[t_date]</span></td>
              </tr>
              <tr>
		      <td $bgrnd align=right colspan='4'><span class=szoveg>
              <a href='sender_base_ch.php?$parms'>$word[base_new]</a>&nbsp;</span></td>
              </tr>\n";
        while($row=mysql_fetch_array($rst)) {
            $index++;
            $bgclass=$bgcolor="";
            if ($index%2)
               $bgclass="bgvilagos2";
            else
               $bgcolor="bgcolor=white";
            $actions="&nbsp;";
            $preview="<a onClick=\"window.open('sender_base_preview.php?$parms&base_id=$row[id]&preview=1','preview');\" HREF='#'>$word[base_preview]</a>&nbsp;";
            $modform="<a href='sender_base_ch.php?$parms&base_id=$row[id]'>$word[base_change]</a>&nbsp;";
            $delname=ereg_replace("[\"'\r\n]","",$row["name"]);
            $delete="<a href='sender_base.php?$parms&delete_id=$row[id]' onClick=\"return(confirm('$word[sender_base_delete_confirm]: $delname'))\">$word[sender_base_delete]</a>&nbsp;";
            $send="<a href='sender_timer_ch.php?$parms&base_id=$row[id]'>$word[send]</a>";
            $copy_base="<a href='sender_base.php?$parms&amp;copy_base=$row[id]'>$word[copy_base]</a>&nbsp;";
            $title=htmlspecialchars($row["name"]);
            $subject=htmlspecialchars($row["subject"]);
            echo "<tr>
		          <td $bgcolor class='$bgclass tal'><span class=szoveg>$title</span></td>
		          <td $bgcolor class='$bgclass tal'><span class=szoveg>$subject</span></td>
		          <td $bgcolor class='$bgclass tar'><span class=szoveg>$copy_base $modform $preview $delete $send</span></td>
                  <td $bgcolor class='$bgclass tar'><span class=szoveg>$row[date]</span></td>
		          </tr>\n";
        }
        printnavigation();
    }
}
else {
      print"<tr>
          <td bgcolor='white' align=right colspan='2'><span class=szoveg>
          <a href='sender_base_ch.php?$parms'>$word[base_new]</a>&nbsp;</span></td>
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
  global $subs,$nams;

  $sel_sort[$fsort] = "selected";
  $params="group_id=$group_id";
  
  echo "
  <tr>
    <td colspan='4' align='center' class='formmezo'>
        <form name='subsnams'>
        <input type='hidden' name='group_id' value='$group_id'>
        $word[subj_search]: <input type='text' name='subs' value='$subs' size='18'>
        &nbsp; &nbsp;
        $word[name_search]: <input type='text' name='nams' value='$nams' size='18'>
        <input type='submit' name='go' value='$word[go]'>
        </form>
    </td>
  </tr>
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
            <td nowrap align='right'>              <a href='$_MX_var->baseUrl/sender_base.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/sender_base.php?$params&first=$OnePageLeft'><img
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
              <a href='$_MX_var->baseUrl/sender_base.php?$params&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/sender_base.php?$params&first=$LastPage'><img
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
            <td nowrap class='formmezo' align='center'> $word[base_page]</td>
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

function mx_copy_base($copy_base,$group_id) {
    
    $base_id=mysql_escape_string($copy_base);
    $res=mysql_query("select * from sender_base where id='$base_id'");
    //$res=mysql_query("select * from sender_base where id='$base_id' and group_id='$group_id'");
    if ($res && mysql_num_rows($res)) {
        $k=mysql_fetch_array($res);
        if (ereg("^(.+-)([0-9]+)$",$k["name"],$regs)) {
            $next=$regs[2]+1;
            $title="$regs[1]$next";
        }
        else {
            $title="$k[name]-2";
        }
        $title=mysql_escape_string($title);
        $insert="insert into sender_base set name='$title'";
        foreach ($k as $dbn=>$dbv) {
            if ($dbn!="name" && $dbn!="id" && $dbn!="date" && !ereg("^[0-9]+$",$dbn)) {
                $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
            }
        }
        $insert.=",date=now()";
//print "<br>$insert<br>".mysql_error();        
        $irs=mysql_query($insert) or die(mysql_error());
        $new_base_id=mysql_insert_id();
        if ($irs && $new_base_id) {
            $r2=mysql_query("select * from sender_base_property where sender_base_id='$base_id'");
            while ($k=mysql_fetch_array($r2)) {
                $insert="insert into sender_base_property set sender_base_id='$new_base_id'";
                foreach ($k as $dbn=>$dbv) {
                    if ($dbn!="sender_base_id" && $dbn!="id" && !ereg("^[0-9]+$",$dbn)) {
                        $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                    }
                }
                $irs=mysql_query($insert);
//print "<br>$insert<br>".mysql_error();        
            }
            $r3=mysql_query("select * from sender_base_uploaded_files where base_id='$base_id'");
            $ins=array();
            while ($k=mysql_fetch_array($r3)) {
                $ins[]="('$new_base_id','$k[path]','$k[type]','$k[filesize]','$k[width]','$k[height]')";
            }
            if (count($ins)>0) {
                $inss=implode(",",$ins);
                $insert="insert into sender_base_uploaded_files (base_id,path,type,filesize,width,height) values $inss";
                $irs=mysql_query($insert);
//print "<br>$insert<br>".mysql_error();        
            }
        }
    }
}

?>
