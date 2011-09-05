<?
include "auth.php";
include "decode.php";
$weare=34;
include "cookie_auth.php";
include "common.php";

$rcid=mt_rand(10000000,99999999);
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
include "./lang/$language/form.lang";

$delete_id = intval($_GET["delete_id"]);
$active = intval($_GET["active"]);
$noactive = intval($_GET["noactive"]);

if ($delete_id) {
	$query="delete from form where id='$delete_id' and group_id='$group_id'";
    $res=mysql_query($query);
	logger($query,$group_id,"","active form id=$active","form");        
    if ($res) {
    	$query="delete from form_page_box where form_id='$delete_id'";
        mysql_query($query);
		logger($query,$group_id,"","form_id=$delete_id","form_page_box");                
		$query="delete from form_page where form_id='$delete_id'";
        mysql_query($query);
		logger($query,$group_id,"","form_id=$delete_id","form_page");                        
		$query="delete from form_element where form_id='$delete_id'";
        mysql_query($query);
        logger($query,$group_id,"","form_id=$delete_id","form_element");                        
		$query="delete from form_css where form_id='$delete_id'";
        mysql_query($query);
        logger($query,$group_id,"","form_id=$delete_id","form_css");                        
    }
}

if ($active) {
	$query="update form set active='yes' where id='$active' and group_id='$group_id'";
    mysql_query($query);
	logger($query,$group_id,"","active form id=$active","form");    
}

if ($noactive) {
	$query="update form set active='no' where id='$noactive' and group_id='$group_id'";
    mysql_query($query);
	logger($query,$group_id,"","active form id=$noactive","form");
}

$copy_form=intval($_GET["copy_form"]);
if ($copy_form) {
    mx_copy_form($copy_form,$group_id);
}

$qmain=" from form where group_id='$group_id'";
$res=mysql_query("select count(*) $qmain");
if ($res && mysql_num_rows($res))
    $maxrecords=mysql_result($res,0,0);
else
    $maxrecords=0;
$stat_text="$word[total_of] $maxrecords $word[iform_title]";

$first=get_http('first',0);
$pagenum=get_http('pagenum',1);

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
    case 1: $order = "order by title"; break;
    case 2: $order = "order by html_name"; break;
    case 3: $order = "order by date desc"; break;
}

printhead();

$parms="group_id=$group_id";
if ($maxrecords) {
	$query="select * $qmain $order limit $first,$maxPerPage";
    $rst=mysql_query($query);
    logger($query,$group_id,"","","form");
    $index = $first;
    if ($rst && mysql_num_rows($rst)) {
        printnavigation();
        $index++;
        if ($index%2)
           $bgrnd="class=bgvilagos2";
        else
           $bgrnd="bgcolor=white";
        echo "<tr><td class='bgkiemelt2' align=left width=40%><span class=szovegvastag>$word[t_title]</span></td>
              <td class='bgkiemelt2' align=left width=20%><span class=szovegvastag>$word[t_html_name]</span></td>
              <td class='bgkiemelt2' align=left width=15%><span class=szovegvastag>$word[t_date]</span></td>
              <td class='bgkiemelt2' align=left width=25%><span class=szovegvastag>$word[t_actions]</span></td>
              </tr>
              <tr>
		      <td $bgrnd align=right colspan='5'><span class=szoveg>
              <a href='form_ch.php?$parms'>$word[iform_new]</a>&nbsp;</span></td>
              </tr>\n";
        while($row=mysql_fetch_array($rst)) {
            $index++;
            if ($row["updater"]=="yes") {
                if ($index%2) $bgst="edd"; else $bgst="fee";
                $bgrnd="style='background-color:#$bgst;'";
            }
            else {
                if ($index%2) $bgrnd="class=bgvilagos2"; else $bgrnd="bgcolor=white";
            }
            $actions="&nbsp;";
            $generate="<a href='form_generate.php?$parms&form_id=$row[id]'>$word[iform_export]</a>&nbsp;";
            $preview="<a onClick=\"window.open('form_generate.php?$parms&form_id=$row[id]&preview=1&cid=$rcid','preview');\" HREF='#'>$word[iform_preview]</a>&nbsp;";
            $modform="<a href='form_ch.php?$parms&form_id=$row[id]'>$word[iform_change]</a>&nbsp;";
            $elements="<a href='form_elements.php?$parms&form_id=$row[id]'>$word[iform_elements]</a>&nbsp;";
            $css="<a href='form_css.php?$parms&form_id=$row[id]'>$word[iform_css]</a>&nbsp;";
            $delete="<a href='form.php?$parms&delete_id=$row[id]' onClick=\"return(confirm('$word[iform_confirm]'))\">$word[iform_delete]</a>&nbsp;";
            if ($row['active'] == 'no') {
                $active="<a href='form.php?$parms&active=$row[id]'>$word[iform_noactive]</a>";
            } else {
                $active="<a href='form.php?$parms&noactive=$row[id]'>$word[iform_active]</a>";
            }
            $title=htmlspecialchars($row["title"]);
            $html_name=htmlspecialchars($row["html_name"]);
            $copy_form="<a href='form.php?$parms&amp;copy_form=$row[id]'>$word[copy_form]</a>";
            $data_forward="<a href='data_forward.php?$parms&amp;form_id=$row[id]'>$word[iform_data_forward]</a>";
            $viral="<a href='form_viral.php?$parms&amp;form_id=$row[id]'>$word[iform_viral]</a>";

            echo "<tr>
		          <td $bgrnd align=left width=40%><span class=szoveg>$title</span></td>
		          <td $bgrnd align=left width=20%><span class=szoveg>$html_name [$active]</span></td>
		          <td $bgrnd align=left width=15%><span class=szoveg>$row[date]</span></td>
		          <td $bgrnd align=left width=25%><span class=szoveg>$elements $css $delete $copy_form<br>
                  $modform $generate $preview<br>$data_forward $viral</span></td>
		          </tr>\n";
        }
        printnavigation();
    }
}
else {
      print"<tr>
          <td bgcolor='white' align=right colspan='5'><span class=szoveg>
          <a href='form_ch.php?$parms'>$word[iform_new]</a>&nbsp;</span></td>
          </tr>\n";
}

printfoot();

include "footer.php";

######################### functions ####################################

function printhead() {

    global $_MX_var,$stat_text,$userlist_yn,$filt_demog_options,$group_id,$pagenum,$word;

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
            <td nowrap class='formmezo' align='center'> $word[members_page]</td>
</form>
          </tr>
        </table>
      </td>
    	<td class='formmezo' align='right' width='33%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
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
</form>
          </tr>
        </table>

      </td>
    </tr>
    </table></td></tr>
  ";
}

function mx_copy_form($copy_form,$group_id) {
    
    $form_id=mysql_escape_string($copy_form);
    $res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
    if ($res && mysql_num_rows($res)) {
        $k=mysql_fetch_array($res);
        if (ereg("^(.+)([0-9]+)$",$k["title"],$regs)) {
            $next=$regs[2]+1;
            $title="$regs[1]$next";
        }
        else {
            $title="$k[title]2";
        }
        $title=mysql_escape_string($title);
        $insert="insert into form set title='$title',date=now()";
        foreach ($k as $dbn=>$dbv) {
            if ($dbn!="title" && $dbn!="date" && $dbn!="id" && !ereg("^[0-9]+$",$dbn)) {
                $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
            }
        }
        $irs=mysql_query($insert);
//print "<br>$insert<br>".mysql_error();        
        $new_form_id=mysql_insert_id();
        if ($irs && $new_form_id) {
            $r2=mysql_query("select object_name,value from form_css where form_id='$form_id'");
            while ($k=mysql_fetch_array($r2)) {
                $insert="insert into form_css set form_id='$new_form_id'";
                foreach ($k as $dbn=>$dbv) {
                    if (!ereg("^[0-9]+$",$dbn)) {
                        $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                    }
                }
                $irs=mysql_query($insert);
//print "<br>$insert<br>".mysql_error();        
            }
            $r2=mysql_query("select * from form_element where form_id='$form_id'");
            while ($k=mysql_fetch_array($r2)) {
                $insert="insert into form_element set form_id='$new_form_id'";
                foreach ($k as $dbn=>$dbv) {
                    if ($dbn!="form_id" && $dbn!="id" && !ereg("^[0-9]+$",$dbn)) {
                        $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                    }
                }
                $irs=mysql_query($insert);
//print "<br>$insert<br>".mysql_error();        
                $new_form_element_id=mysql_insert_id();
                if ($irs && $new_form_element_id) {
                    $r3=mysql_query("select * from form_element_enumvals where form_element_id='$k[id]'");
                    while ($l=mysql_fetch_array($r3)) {
                        $insert="insert into form_element_enumvals set form_element_id='$new_form_element_id'";
                        foreach ($l as $dbn=>$dbv) {
                            if ($dbn!="form_element_id" && $dbn!="id" && !ereg("^[0-9]+$",$dbn)) {
                                $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                            }
                        }
                        $irs=mysql_query($insert);
//print "<br>$insert<br>".mysql_error();        
                    }
                    $r4=mysql_query("select * from form_element_dep where form_element_id='$k[id]'");
                    while ($l=mysql_fetch_array($r4)) {
                        $insert="insert into form_element_dep set form_element_id='$new_form_element_id'";
                        foreach ($l as $dbn=>$dbv) {
                            if ($dbn!="form_element_id" && $dbn!="id" && !ereg("^[0-9]+$",$dbn)) {
                                $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                            }
                        }
                        $irs=mysql_query($insert);
                        $new_form_element_dep_id=mysql_insert_id();              
                        if ($new_form_element_dep_id) {
                            $sql="SELECT form_id,dependency from form_element where form_id=$new_form_id and dependency REGEXP '(D$l[id])([^0-9])|(D$l[id]$)'";
                            //echo $sql."<br>";
                            $r5=mysql_query($sql);
                            while ($fp=mysql_fetch_array($r5)) {
                                $new_dep=preg_replace("/(D$l[id])([^0-9])|(D$l[id]$)/","D$new_form_element_dep_id$2",$fp["dependency"]);
                                $upd_form_page="update form_element set dependency='$new_dep' where form_id=$new_form_id and dependency='$fp[dependency]'";
                            //echo $upd_form_page."<br>";                                
                                mysql_query($upd_form_page);
                            }
                        }                        
//print "<br>$insert<br>".mysql_error();        
                    }                    
                }
            }
            $r2=mysql_query("select * from form_page where form_id='$form_id'");
            while ($k=mysql_fetch_array($r2)) {
                $insert="insert into form_page set form_id='$new_form_id'";
                foreach ($k as $dbn=>$dbv) {
                    if ($dbn!="form_id" && !ereg("^[0-9]+$",$dbn)) {
                        $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                    }
                }
                $irs=mysql_query($insert);
//print "<br>$insert<br>".mysql_error();        
            }
            $r2=mysql_query("select * from form_page_box where form_id='$form_id'");
            while ($k=mysql_fetch_array($r2)) {
                $insert="insert into form_page_box set form_id='$new_form_id'";
                foreach ($k as $dbn=>$dbv) {
                    if ($dbn!="form_id" && !ereg("^[0-9]+$",$dbn)) {
                        $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                    }
                }
                $irs=mysql_query($insert);
//print "<br>$insert<br>".mysql_error();        
            }
            $r2=mysql_query("select * from form_page_dep where form_id='$form_id'");
            while ($k=mysql_fetch_array($r2)) {
                $insert="insert into form_page_dep set form_id='$new_form_id'";
                foreach ($k as $dbn=>$dbv) {
                    if ($dbn!="form_id" && $dbn!="id" && !ereg("^[0-9]+$",$dbn)) {
                        $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                    }
                }
                $irs=mysql_query($insert);
                $new_form_page_dep_id=mysql_insert_id();              
                if ($new_form_page_dep_id) {
                    $sql="SELECT form_id,dependency from form_page where form_id=$new_form_id and dependency REGEXP '(D$k[id])([^0-9])|(D$k[id]$)'";
                    $r5=mysql_query($sql);
                    while ($fp=mysql_fetch_array($r5)) {
                        $new_dep=preg_replace("/(D$k[id])([^0-9])|(D$k[id]$)/","D$new_form_page_dep_id$2",$fp["dependency"]);
                        $upd_form_page="update form_page set dependency='$new_dep' where form_id=$new_form_id and dependency='$fp[dependency]'";
                        mysql_query($upd_form_page);
                    }
                }

//print "<br>$insert<br>".mysql_error();        
            }            
            $r2=mysql_query("select * from form_images where form_id='$form_id'");
            while ($k=mysql_fetch_array($r2)) {
                $insert="insert into form_images set form_id='$new_form_id'";
                foreach ($k as $dbn=>$dbv) {
                    if ($dbn!="form_id" && $dbn!="id" && !ereg("^[0-9]+$",$dbn)) {
                        $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                    }
                }
                $irs=mysql_query($insert);
//print "<br>$insert<br>".mysql_error();        
            }
            $r2=mysql_query("select * from form_endlink where form_id='$form_id'");
            while ($k=mysql_fetch_array($r2)) {
                $insert="insert into form_endlink set form_id='$new_form_id'";
                foreach ($k as $dbn=>$dbv) {
                    if ($dbn!="form_id" && $dbn!="id" && !ereg("^[0-9]+$",$dbn)) {
                        $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                    }
                }
                $irs=mysql_query($insert);
                $new_form_endlink_id=mysql_insert_id();
//print "<br>$insert<br>".mysql_error();
                if ($irs && $new_form_endlink_id) {
                    $r3=mysql_query("select * from form_endlink_dep where form_endlink_id='$k[id]'");
                    while ($k=mysql_fetch_array($r3)) {
                        $insert="insert into form_endlink_dep set form_endlink_id='".$new_form_endlink_id."'";
                        foreach ($k as $dbn=>$dbv) {
                            if ($dbn!="form_endlink_id" && $dbn!="id" && !ereg("^[0-9]+$",$dbn)) {
                                $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                            }
                        }
                        $irs=mysql_query($insert);
                        $new_form_endlink_dep_id=mysql_insert_id();              
                        if ($new_form_endlink_dep_id) {
                            $sql="SELECT form_id,dependency from form_endlink where form_id=$new_form_id and dependency REGEXP '(D$k[id])([^0-9])|(D$k[id]$)'";
                            $r5=mysql_query($sql);
                            while ($fp=mysql_fetch_array($r5)) {
                                $new_dep=preg_replace("/(D$k[id])([^0-9])|(D$k[id]$)/","D$new_form_endlink_dep_id$2",$fp["dependency"]);
                                $upd_form_page="update form_endlink set dependency='$new_dep' where form_id=$new_form_id and dependency='$fp[dependency]'";
                                mysql_query($upd_form_page);
                            }
                        }                        
//                  print "<br>$insert<br>".mysql_error();                            
                    }
                }            
            }                
            $r2=mysql_query("select * from form_viral where form_id='$form_id'");
            while ($k=mysql_fetch_array($r2)) {
                $insert="insert into form_viral set form_id='$new_form_id'";
                foreach ($k as $dbn=>$dbv) {
                    if ($dbn!="form_id" && $dbn!="id" && !ereg("^[0-9]+$",$dbn)) {
                        $insert.=",$dbn='". mysql_escape_string($dbv) ."'";
                    }
                }
                $irs=mysql_query($insert);
//print "<br>$insert<br>".mysql_error();        
            }
        }
    }
}

?>
