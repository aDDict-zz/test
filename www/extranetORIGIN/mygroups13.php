<?

include "auth.php";
$weare=17;
$_MX_superadmin=0;

include "cookie_auth.php";  
include "common.php";

$language=select_lang();
include "./lang/$language/mygroups13.lang";  
include "./lang/$language/export_import.lang";
include "_demog.php";
  
set_time_limit(0);

$_MX_demog = new MxDemog();
$id_ok=0;
$unique_col="";
$mres = mysql_query("select title,unique_col from groups,members where groups.id=members.group_id
                     and groups.id='$sgroup_id' and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres)) {
    $id_ok=1;  
    $title=mysql_result($mres,0,0);
    $unique_col=mysql_result($mres,0,1);
}
if (!$id_ok) {
    header("Location: $_MX_var->baseUrl/index.php?no_group=1"); 
    exit; 
}

$messages=array();

$supported_codesets=array();
$rec=mysql_query("select codepage,description from codepages order by sortorder");
while ($kec=mysql_fetch_array($rec)) {
    $supported_codesets["$kec[codepage]"]=$kec["description"];
}

if (isset($_POST["importvars"])) {
    $codeset=$_POST["codeset"];
    if (!isset($supported_codesets["$codeset"])) {
        $scc=array_keys($supported_codesets);
        $codeset=$scc[0];
    }
    $filepath=$_MX_var->member_import_temp_dir . md5(time().$REMOTE_ADDR);
    $csv_file=$_FILES['csv_file']['tmp_name'];
    if (!empty($csv_file) && $csv_file!="none") {
        $import_success=0;
        move_uploaded_file("$csv_file","$filepath");
        $handle = fopen($filepath, "r");
        if ($handle) {
            $irow=1;
            while (($data = fgetcsv($handle, 65536, ";")) !== FALSE) {
                if ($codeset!="utf8") {
                    for ($icd=0;$icd<count($data);$icd++) {
                        $data[$icd] = @iconv($codeset,"utf8//IGNORE",$data[$icd]);
                    }
                }
                list (,$error) = $_MX_demog->add($group_id,1,"csv",$data);
                if (empty($error)) {
                    $import_success++;
                }
                else {
                    $messages[]="Import hiba a(z) $irow. sorban: $error";
                }
                $irow++;
            }
            fclose($handle);
        }
        unlink($filepath);
        $messages[]="$import_success változó importja volt sikeres.";
    }
    else {
        $messages[]=$word["err_noinput"];
    }
}

if (isset($_GET["delnosucc"])) {
    $parts=explode("|",$_GET["delnosucc"]);
    $parts[0]=htmlspecialchars($parts[0]);
    $parts[1]=htmlspecialchars($parts[1]);
    $messages[]="$word[nds_1] $parts[0] $word[nds_2] $parts[1]."; 
}
elseif (isset($_GET["delsucc"])) {
    $part=htmlspecialchars($_GET["delsucc"]);
    $messages[]="$word[ds_1] $part $word[ds_2]"; 
}

$group_demog_filter=" and ( concat(',',d.groups,',') like '%,$group_id,%' or d.groups='' )";
if (isset($_POST["add_demogs"]) && count($_POST["demoglist"])) {
    foreach ($_POST["demoglist"] as $cdemog_id) {
        $cdemog_id=mysql_escape_string($cdemog_id);
        $cq="select * from demog d where id='$cdemog_id' ";
        //if (!$_MX_superadmin) {
        // 2007-04-23 valtozas - az osszes demog info valtoztathatosaga nemcsak a csoporthoz hozzaadhatoakra vonatkozik hanem mindre
        if (!$_MX_superadmin && !$_MX_change_variables) {
            $cq.=$group_demog_filter;
        }
        $mres = mysql_query($cq);
        if ($mres && mysql_num_rows($mres)) {
            mx_add_demog($mres);
        }
    }
}
elseif (isset($_POST["delete_demogs"]) && count($_POST["demoglist"])) {
    foreach ($_POST["demoglist"] as $cdemog_id) {
        $cdemog_id=mysql_escape_string($cdemog_id);
        $cq="select variable_name from demog where id='$cdemog_id' ";
        $res=mysql_query($cq);
        if ($res && mysql_num_rows($res)) {
            $varname=mysql_result($res,0,0);
            if ($varname!=$unique_col) {
            	$sql1=mysql_escape_string("delete from vip_demog where demog_id='$cdemog_id' and group_id='$group_id'");
				$sql2=mysql_escape_string("alter table users_$title drop column ui_$varname");
				$q="insert into xmlreq (job_type,status,job_input,date,group_id) values 
                     	('del_from_group','queued','".$sql1."|;|".$sql2."',now(),'$group_id')";
				mysql_query($q);
				header("Location: $_MX_var->baseUrl/xmlreqlist.php?group_id=$group_id");		
            }
        }
    }
}
include "menugen.php";
print '<link rel="stylesheet" type="text/css" href="./js/common.css"/>
<link rel="stylesheet" type="text/css" href="./js/lists.css"/>
<!--<script language="JavaScript" type="text/javascript" src="./js/core.js"></script>
<script language="JavaScript" type="text/javascript" src="./js/events.js"></script>
<script language="JavaScript" type="text/javascript" src="./js/css.js"></script>
<script language="JavaScript" type="text/javascript" src="./js/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="./js/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="./js/dragsort.js"></script>
<script language="JavaScript" type="text/javascript" src="./js/cookies.js"></script>
<script src="xmlreq.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript"><!--
	var dragsort = ToolMan.dragsort()
	var junkdrawer = ToolMan.junkdrawer()

	window.onload = function() {

		dragsort.makeListSortable(document.getElementById("phonetic2"),
				verticalOnly, saveOrder)

	}

	function verticalOnly(item) {
		item.toolManDragGroup.verticalOnly()
	}

	function speak(id, what) {
		var element = document.getElementById(id);
		element.innerHTML = "Clicked " + what;
	}

	function saveOrder(item) {
		var group = item.toolManDragGroup
		var list = group.element.parentNode
		var id = list.getAttribute("id")
		if (id == null) return
		group.register("dragend", function() {
			ToolMan.cookies().set("list-" + id, 
					junkdrawer.serializeList(list), 365)
		})
	}
</script>-->';
$filters="";

if (in_array($dtype,$_MX_demog->dtypes)) {
    $filters.=" and d.variable_type='$dtype'";
    $fcl="background-color:#fcc;";
}
else {
    $dtype="";
    $fcl="";
}
$dtypesel="<select name='dtype' style='$fcl' onchange='this.form.submit();'>\n<option value=''>-- $word[t_vartype] --</option>\n";
foreach ($_MX_demog->dtypes as $dt) {
    $dt==$dtype?$sel=" selected":$sel="";
    $dtypesel.="<option value='$dt'$sel>". $word["vdt_$dt"] ."</option>\n";
}
$dtypesel.="</select>\n";

$dgroups=array("sel"=>$word["dg_sel"]);
$rs=mysql_query("select id,name from demog_group where group_id='$group_id'");
if ($rs && mysql_num_rows($rs)) {
    while ($k=mysql_fetch_array($rs)) {
        $dgroups["g$k[id]"]="- $k[name]";
    }
}
$mainq="demog d,vip_demog vd where d.id=vd.demog_id and vd.group_id='$group_id'";
logger($q,$group_id,"","demographic info","groups");
$dgroups["nosel"]=$word["dg_nosel"];
$dgroups["all"]=$word["dg_all"];
$addbutton=1;
$delbutton=1;
$ordby=" order by ordernum ";
if ($dgroup=="nosel") {
    $nota=array();
    $r2=mysql_query("select demog_id from vip_demog where group_id='$group_id'");
    while ($k2=mysql_fetch_array($r2)) {
        $nota[]=$k2["demog_id"];
    }
    if (count($nota)) {
        $filters.="and d.id not in (". implode(",",$nota) .")";
    }
    $mainq="demog d where 1";
    $delbutton=0;
	$ordby="";
}
elseif (ereg("^g([0-9]+)$",$dgroup,$drg) && isset($dgroups["$dgroup"])) {
    $filters.=" and vd.demog_group_id='$drg[1]'";
    $addbutton=0;
}
elseif ($dgroup=="all") {
    $mainq="demog d where 1";
	$ordby="";
}
else {
    $dgroup="sel";
    $addbutton=0;
}
$dgroupsel="<select name='dgroup' style='background-color:#bcd;' onchange='this.form.submit();'>\n";
foreach ($dgroups as $dgval=>$dgtxt) {
    $dgval==$dgroup?$sel=" selected":$sel="";
    $dgroupsel.="<option value='$dgval'$sel>". htmlspecialchars($dgtxt) ."</option>\n";
}
$dgroupsel.="</select>\n";
// 'selectable' are demogs which are assigned to group (or global); show all only for superadmins
if (!($_MX_superadmin || $_MX_change_variables) && $dgroup=="all" || $dgroup=="nosel") {
    $filters.=$group_demog_filter;
}

$sortsel="<select name='sort' onchange='this.form.submit();'>";
$sorts=array("0"=>"variable_name","1"=>"question");
$order="";
foreach ($sorts as $sval=>$stxt) {
    $sel="";
    if ($sval==$sort) {
        $sel=" selected";
        $order="order by d.$stxt";
    }
    $sortsel.="<option value='$sval'$sel>".$word["dsort_$stxt"]."</option>";
}
$sortsel.="</select>";
if (empty($order)) {
    $sort=0;
    $order="order by d.name";
}
$perpage=intval($perpage);
if ($perpage<5) { $perpage=5; }
if ($perpage>500) { $perpage=500; }
$fcl="";
if (!empty($sname)) {
    $fcl="background-color:#fcc;";
    $filters.=" and d.variable_name like '%$ssname%'";
}
$namefilter="<input name='sname' size='10' style='$fcl width: 200px;'  value=\"" . htmlspecialchars($sname) . "\">";
$fcl="";
if (!empty($sdesc)) {
    $fcl="background-color:#fcc;";
    $filters.=" and d.question like '%$ssdesc%'";
}
$descfilter="<input name='sdesc' size='10' style='$fcl width: 200px;' value=\"" . htmlspecialchars($sdesc) . "\">";

// vip_demog records and the structure of the users* tables *MUST* be logically identical.
// the unique demog info is always present at group level and cannot be dropped or assigned, it is given at the creation of new groups.

$count=0;

$res=mysql_query("select count(d.id) from $mainq $filters");
if ($res && mysql_num_rows($res)) {
    $count=mysql_result($res,0,0);
}
$pages_total=floor($count/$perpage)+1;
if ($pagenum) $off=$pagenum-1;
if ($off>$pages_total-1) $off=$pages_total-1; 
if ($off<0) $off=0;
$limstart=$off*$perpage;

$allparm=$_MX_demog->GetParams();
$hallparm=$_MX_demog->GetParams(1);
print "<script>
       function enumwindow(demog_id,isvert) {
       window.open('mygroups13_enumvals.php?from=1&$allparm&demog_id='+demog_id+'&optvert='+isvert, 'm_d_i', 'width=600,height=500,left=150,top=200,scrollbars=yes,resizable=yes;'); return false; }
       </script>
       <div class='bordercolor'>\n";
PrintNavigation($off,$pages_total,$sortsel,$perpage);

print "<table cellpadding='2' cellspacing='0' border='0' width='100%' class='bbordercolor'>
        <tr><td class='formmezo'><form action='mygroups13.php' class='formmezo'>$dgroupsel";
print $_MX_demog->GetParams(1,array("dgroup"));
print "</form></td>
       <td class='formmezo' ><form action='mygroups13.php' class='formmezo'>$dtypesel";
print $_MX_demog->GetParams(1,array("dtype"));
print "</form></td>
       <td class='formmezo'><form action='mygroups13.php' class='formmezo'>$word[namefilter]: $namefilter";
print $_MX_demog->GetParams(1,array("sname"));
print "</form></td>
       <td class='formmezo' align='right'><form action='mygroups13.php' class='formmezo'>$word[descfilter]: $descfilter";
print $_MX_demog->GetParams(1,array("sdesc"));
if ($filters!="")
	print "</form></td></tr></table><div width='100%'><div width='50%' align='left' style='float: left;' padding:2px 0;'><!--<span class='alarm'>$word[st_sortitemdis]</span>--></div>
	   <div width='50%' align='right' style='float: right;' padding:2px 0;'><span class='szovegvastag'><a href='mygroups13_edit.php?$allparm'>$word[demog_new]</a></span></div></div><br />";
else
	print "</form></td></tr></table><div width='100%'><div width='50%' align='left' style='float: left;' padding:2px 0;'><!--<span class='alarm'>$word[st_sortitem]</span>--></div>
	   <div width='50%' align='right' style='float: right;' padding:2px 0;'><span class='szovegvastag'><a href='mygroups13_edit.php?$allparm'>$word[demog_new]</a></span></div></div><br />";

if (count($messages)) {
    print "<div style='padding:10px;'><span class='szovegvastag'>". implode("<br>",$messages) ."</span></div>"; 
}

// Demog import interface start
$codeset_options="";
foreach ($supported_codesets as $cs => $desc) {
    $sel=($cs==$codeset?" selected":"");
    $codeset_options.="<option value='$cs'$sel>$desc</option>";
}

print "
  <form  method='post' enctype='multipart/form-data'>
  <table border=0 cellspacing=0 cellpadding=1 class='bgcolor' width=100%>
  <tr>
    <td class=bgvilagos2 style='vertical-align:middle;'><span class='szoveg'>Változók importálása:</td>
    <td class=bgvilagos2 style='vertical-align:middle;'><INPUT TYPE='hidden' name='MAX_FILE_SIZE' value='5000000'><INPUT TYPE='file' size='40' name='csv_file'></td>
    <td class=bgvilagos2 style='vertical-align:middle;'>$word[file_charset]: <select name='codeset'>$codeset_options</select></span></td>
    <td class=bgvilagos2 style='vertical-align:middle;'><input class='tovabbgomb' type='submit' name='importvars' value='$word[import]'></td>
  </tr>
  </table>
  </form>
";
// Demog import interface end

print "<form action='mygroups13.php' method='post'>$hallparm<input type='hidden' name='delete_demogs' value='0'>\n";

//print("select d.* from $mainq $filters $ordby limit $limstart,$perpage<br>");
$r1=mysql_query("select d.* from $mainq $filters $ordby limit $limstart,$perpage");
$index=0;                 
$addbutton=$addbutton?"<input type='submit' name='add_demogs' value='$word[add]'>":"";
$delbutton=$delbutton?"<input type='button' name='ddemogs' value='$word[remove]' onClick='if(confirm(\"$word[deltext]\")) { this.form.submit(); this.form.delete_demogs=1;}'>":"";
if ($r1 && mysql_num_rows($r1)) {
    print " <table width='962' class='addpadding'>
                <tr>
                    <td class='bgkiemelt2' width='80px;'><span class='szovegvastag'>$word[t_code]</span></td>
                    <td class='bgkiemelt2' width='450px;'><span class='szovegvastag'>$word[t_question]</span></td>
                    <td class='bgkiemelt2' width='180px;'><span class='szovegvastag'>$word[t_varname]</span></td>
                    <td class='bgkiemelt2' width='100px;'><span class='szovegvastag'>$word[t_vartype]</span></td>
                    <td class='bgkiemelt2' align='center' width='350px'><span class='szovegvastag'>$addbutton$delbutton</span></td>
                </tr>";
            /*</table>\n";*/
	if ($filters=="" && $dgroup!="all") 
	{
		//print '<ul id="phonetic2" class="boxy">';
		$cursor="cursor:move;";
	}
	else
	{
		//print '<ul id="phonetic" class="boxy" style="cursor:default;">';
		$cursor="cursor:default;";
	}
    while ($k=mysql_fetch_array($r1)) {
        $index++;
        ($index%2)?$bgrnd=" BACKGROUND-COLOR: #eeeeee; ":$bgrnd=" BACKGROUND-COLOR: #ffffff; ";
        $id=$k["id"];
        $evls="";
        if ($k["variable_type"]=="matrix") {
            $evls.="<a href='#' onClick='enumwindow($k[id],1); return false;'>$word[vdt_vertical]</a> ";
        }
        if ($k["variable_type"]=="enum" || $k["variable_type"]=="enum_other" || $k["variable_type"]=="matrix") {
            $evls.="<a href='#' onClick='enumwindow($k[id],0); return false;'>$word[vdt_enum_values]</a> ";
        }
        $typename=$word["vdt_$k[variable_type]"];
        $chlink="";
        // 2007-03-31 - $_MX_change_variables - az ilyen felhasznalo valtoztathat minden, a csoporthoz hozzaadhato demog infot
        // a ...  || $_MX_change_variables-t  csak igy siman a vegere lehet tenni mert eleve csak a csoporthoz adhato demog infok vannak felsorolva.
        // megis mindet valtoztathatja, ld. fentebb, 2007-04-23
        if ($k["groups"]=="$group_id" || $_MX_superadmin || $_MX_change_variables) {
            $chlink="<a href='mygroups13_edit.php?demog_id=$id&$allparm'> $word[vd_change]</a>";
        }
        $dellink="";
        if ($_MX_superadmin && $dgroup!="sel") {
            $dellink="<a href='mygroups13_in.php?demog_id=$k[id]&$allparm' onclick='return(confirm(\"$word[delete_sure]\"));'>$word[delete]</a>";
        }
        $copylink="<a href='mygroups13_edit.php?copy_demog_id=$k[id]&$allparm'>$word[vd_copy]</a>";
        $uniqueaddon="";
        if ($unique_col==$k["variable_name"]) {
            $uniqueaddon="<b>[$word[unique]]</b>";
        }
		if (strlen($k[question])>80) $k[question]=mb_substr($k[question],0,80,"UTF-8")."...";
        echo "
                        <tr>
                            <td style='width: 80px; $bgrnd'><span class='szoveg'>" . htmlspecialchars($k["code"]) . "</span></td>
                            <td style='width: 450px; $bgrnd'><span class='szoveg'>" . htmlspecialchars($k["question"]) . "</span></td>
					        <td style='width: 180px; $bgrnd'><span class='szoveg'>$k[variable_name]</span></td>
					        <td style='width: 100px; $bgrnd'><span class='szoveg'>$typename $uniqueaddon</span></td>
					        <td style='width: 244px; $bgrnd'><span class='szoveg'>$evls $chlink $dellink $copylink <input type='checkbox' name='demoglist[]' value='$id'></span></td>
					    </tr>\n";
    }
    print "</table></form>\n";
}
else {
    print "<tr><td bgcolor='white' colspan='4'><span class='szovegvastag'> $word[no_demog]</span></td></tr>\n";
}

PrintNavigation($off,$pages_total,$sortsel,$perpage);
print "</div>";
include "footer.php";

/* ######################### functions #################################### */

function mx_add_demog($mres) {

    global $_MX_var,$title,$group_id;
    $mm=mysql_fetch_array($mres);
    $result = mysql_query ("select * from users_$title limit 0");
    if ($result) {
//print "*1*";    
        $fields = mysql_num_fields ($result);
        $i = 0;
        $found=0;
        $retcode=1;
        while ($i < $fields) {
            $name  = mysql_field_name  ($result, $i);
            $i++;
            if ($name=="ui_$mm[variable_name]") {
                $found=1;
            }
        }
        if (!$found) {
//print "*2*";    
            if ($mm["variable_type"]=="number")
                $createstr="ui_$mm[variable_name] int default '0' not null";
            elseif ($mm["variable_type"]=="date")
                $createstr="ui_$mm[variable_name] date default '0000-00-00' not null";
            elseif ($mm["variable_type"]=="enum" || $mm["variable_type"]=="enum_other")
                $createstr="ui_$mm[variable_name] varchar(75) default '' not null";
            else 
                $createstr="ui_$mm[variable_name] varchar(255) default '' not null";        

            $sql1=mysql_escape_string("alter table users_$title add $createstr");
			$sql2=mysql_escape_string("insert into vip_demog (demog_id,group_id,dateadd,tstamp) values ('$mm[id]','$group_id',now(),now())");
			mysql_query("insert into xmlreq (job_type,status,job_input,date,group_id) values 
                     	('add_to_group','queued','".$sql1."|;|".$sql2."',now(),'$group_id')");
			header("Location: $_MX_var->baseUrl/xmlreqlist.php?group_id=$group_id");		
        }
        return $retcode;
    }
    else {
        return 0;
    }
}
  
function PrintNavigation($off,$pages_total,$sortsel,$perpage) {

    global $_MX_var,$word,$_MX_demog;

    $noff=$_MX_demog->GetParams(0,array("off"));
    $hnoff=$_MX_demog->GetParams(1,array("off"));
    $prevpage=$off-1;
    $pagenum=$off+1;
    $lastpage=$pages_total-1;

    print "<table border='0' cellspacing='0' cellpadding='0' width='100%'>
           <tr>
           <td>
           <table border='0' cellspacing='0' cellpadding='0' width='100%'>
           <tr>
    	   <td class='formmezo' align='left' width='33%'>
			
	       <table border='0' cellspacing='0' cellpadding='0'>
           
	       <tr><form name='inputs'>$hnoff";
    if ($off>0) {
        print "<td nowrap class='formmezo' align='right'>
               <a href='mygroups13.php?$noff&off=0'><img src='$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
               <a href='mygroups13.php?$noff&off=$prevpage'><img src='$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
               </td>";
    }
    else {
        print "<td nowrap  class='formmezo' align='right'>&nbsp;&nbsp;
               <img src='$_MX_var->application_instance/gfx/down2_dead.gif' width='20' height='14' border='0'>
               <img src='$_MX_var->application_instance/gfx/down1_dead.gif' width='20' height='14' border='0'>
               </td>";
    }
    print "<td nowrap align='right'><input type='text' id='pnum' name='pagenum' size='3' maxlength='3' value='$pagenum'></td>
           <td nowrap class='formmezo'>&nbsp;/ $pages_total</td>";
    if ($off<$pages_total-1) {
        print "<td nowrap align='right'>
               <a href='mygroups13.php?$noff&off=$pagenum'><img src='$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
               <a href='mygroups13.php?$noff&off=$lastpage'><img src='$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>
               </td>";
    }
    else {
        print "<td nowrap align='right'>&nbsp;&nbsp;
               <img src='$_MX_var->application_instance/gfx/up1_dead.gif' width='20' height='14' border='0'>
               <img src='$_MX_var->application_instance/gfx/up2_dead.gif' width='20' height='14' border='0'>
               </td>";
    }
    $hnoperp=$_MX_demog->GetParams(1,array("perpage"));
    $hnosort=$_MX_demog->GetParams(1,array("sort"));
    print "</tr>
           </table></form>
           </td>
           </tr>
           </table>
           </td>
    	   <td class='formmezo' align='center' width='33%'>
           <table border='0' cellspacing='0' cellpadding='0'>
           <tr> 
			<form action='mygroups13.php'>$hnoperp
           <td nowrap class='formmezo' align='center'>$word[view]:</td>
           <td nowrap align='center'><input type='text' id='ppage' name='perpage' value='$perpage' size='3' maxlength='3'></td>
           <td nowrap class='formmezo' align='center'> $word[demog_perpage]</td>
           </tr>
           </table></form>
           </td>
    	   <td class='formmezo' align='right' width='33%'>
           <table border='0' cellspacing='0' cellpadding='0'><form action='mygroups13.php'>$hnosort
           <tr>";
			//<td nowrap class='formmezo'>$word[sort_order]:</td>
           //<td nowrap>$sortsel</td>
	print "</tr>
           </table></form>
           </td>
           </tr>
           </table></td></tr>\n";
}
?>
