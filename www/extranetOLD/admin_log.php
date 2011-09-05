<?



if (empty($sortt) && isset($admin_sortt)) {
  $sortt=$admin_sortt;
}

if (empty($maxPerPage) && isset($admin_perpage)) {
  $maxPerPage=$admin_perpage;
}

if (empty($filt_group_name) && isset($admin_filt_group_name) && !isset($filt_clear)) {
  $filt_group_name=$admin_filt_group_name;
}

setcookie("admin_sortt",$sortt,time()+30*24*3600);
setcookie("admin_perpage",$maxPerPage,time()+30*24*3600);

if (isset($filt_clear)) {
  setcookie("admin_filt_group_name");
  $filt_group_name='';
}
else
  setcookie("admin_filt_group_name",$filt_group_name,time()+30*24*3600);

$_MX_superadmin=0;
include "auth.php";
$weare = '200';
include "cookie_auth.php";  

$view_logs="no";

$rlusr=mysql_query("select view_logs from user where id='$active_userid'");
if ($rlusr && mysql_num_rows($rlusr)) {
    $view_logs=mysql_result($rlusr,0,0);
}

if (!$_MX_superadmin && $view_logs!="yes") {
    exit;
}
include "menugen.php";

if (!$sortt) $sortt=8;

//--------------------------------------------------------------------------//
//                              M A I N                                     //
//--------------------------------------------------------------------------//

$tabledesc=array(
"multigroup"=>"",
"multi"=>"",
"users_"=>"tagok",
"messages"=>"üzenetek",
"sender_base"=>"levélküldő keret",
"sender_contents"=>"levélküldő tartalom",
"form"=>"kérdőív",
"form_page_box"=>"kérdőív doboz",
"form_page"=>"kérdőív oldal",
"form_element"=>"kérdőív elemei",
"form_css"=>"kérdőív css",
"form_element_enumvals"=>"kérdőív enum értékek",
"trackf"=>"kattintó tagok",
"unsub"=>"leiratkozott tagok",
"feedback"=>"ct statisztika",
"bounced_back"=>"visszapattanó tagok"
);

$sql = "from tracking t left join user u on t.user_id=u.id where 1";

$tipusok=array('bejelentkezés','kijelentkezés','lekérdezés','hozzáadás','módositás','törlés');
$tipus=abs(intval($tipus));

if ($tipus>count($tipusok)) {
    $tipus=0;
}
$tipus_options="";
$tipus_found=0;
$tind=0;
$users="";
$sqlu = mysql_query("select id,name from user order by name");
while($row=mysql_fetch_array($sqlu)) {
	if ($user==$row[id]) $sel="selected"; else $sel="";
    $users.="<option $sel value='$row[id]'>$row[name]</option>\n";	
}
foreach ($tipusok as $top) {
    $tind++;
    $sel="";
    if ($tipus==$tind) {
        $sql.=" and t.action='$top'";
        $sel=" selected";
    }
    $tipus_options.="<option$sel value='$tind'>$top</option>\n";
}
if ($search_text) $sql.=" and (t.table_name like '%$search_text%' or t.query like '%$search_text%' or t.info like '%$search_text%')";
if ($user) $sql.=" and t.user_id=$user";
if ($datumtol) $sql.=" and t.date>='$datumtol'";
if ($datumig) $sql.=" and t.date<='$datumig 23:59:59'";

echo "SELECT COUNT(t.id) AS maxrecords $sql";
$res = mysql_query("SELECT COUNT(t.id) AS maxrecords $sql");
$l=mysql_fetch_array($res);                                         
$maxrecords = $l["maxrecords"];                                          
$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 50;

$pagenum=intval($_REQUEST["pagenum"]);
if($pagenum<1) $pagenum=1;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;
if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                    

PrintHead();

$sql.=" order by date desc limit $first,$maxPerPage";
$q = mysql_query("select t.*,u.name $sql");

    $pagenum=(int)($first / $maxPerPage) + 1;
    $maxpages = ceil($maxrecords / $maxPerPage);
    PrintNavigation($maxpages,$pagenum,0);
if ($q && mysql_num_rows($q)) { 
    // ki, mikor, mit csinalt, hol, 
?> 

<center>
    <table border="0" cellpadding="4" cellspacing="0" width="100%">
        <tr>    
            <td class="lefttitle" valign="top">Felhasználó</td>
            <td class="lefttitle" valign="top">Dátum</td>
            <td class="lefttitle" valign="top">Tipus</td>  
            <td class="lefttitle" valign="top">Oldal</td>  
            <td class="lefttitle" valign="top">Csoport</td>  
            <td class="lefttitle" valign="top">Objektum neve</td>
            <td class="lefttitle" valign="top">Url</td>
            <td class="lefttitle" valign="top">Egyeb</td>
        </tr>
        
<?
    $index = 0;
    $col = 2;
    while($row=mysql_fetch_array($q)) {
        $index++;
        if($col==2) $col = 1; else $col = 2;
        $page_name="";
        $group_name="";
        if ($row["weare"]) {
            $page_name=$word["menu_$row[weare]"];
        }
        elseif ($row["sgweare"]) {
            $page_name=$word["menu_sg$row[sgweare]"];
        }
        elseif ($row["adminweare"]) {
            $page_name=$word["menu_admin$row[adminweare]"];
        }
        $gq="";
        if ($row["multigroup"] && $row["group_id"]) {
            $gq="select title from multi where id='$row[group_id]'";  
        }
        elseif ($row["group_id"]) {
            $gq="select title from groups where id='$row[group_id]'";  
        }
        if (!empty($gq)) {
            $gr=mysql_query($gq);
            if ($gr && mysql_num_rows($gr)) {
                $group_name=mysql_result($gr,0,0);    
            }
        }
        if (!empty($tabledesc["$row[table_name]"])) {
            $row["table_name"]=$tabledesc["$row[table_name]"];
        }
        $row["query"] = hsc($row["query"],0);
?>
        <tr>
            <td class="column<?=$col;?>" valign="top">
                <span class="little"><?=$row['name'];?></span>
            </td>
            <td class="column<?=$col;?>" valign="top">
                <span class="little"><?=$row['date'];?></span>
            </td>
            <td class="column<?=$col;?>" valign="top">
                <span class="little"><?=$row['action'];?></span>
            </td>
            <td class="column<?=$col;?>" valign="top">
                <span class="little"><?=$page_name;?></span>
            </td>
            <td class="column<?=$col;?>" valign="top">
                <span class="little"><?=$group_name;?></span>
            </td>
            <td class="column<?=$col;?>" valign="top">
                <span class="little"><?=$row['table_name'];?></span>
            </td>
            <td class="column<?=$col;?>" valign="top" style="overflow: hidden; width: 50px;">
                <a title="<?=$row['url'];?>" class="little" href="<?=$row[url];?>">link</a>
            </td>
            <td class="column<?=$col;?>" valign="top">
                <span class="little"><?=$row['info'];?></span>
            </td>	    
        </tr>
        <tr>
            <td colspan="8" class="column<?=$col;?>">
                <div name="extview" style="display: block;" class=LITTLE><?=$row['query'];?></div>
			</td>	    
        </tr>
<? } ?>
        <tr>
            <td class="bgkiemelt2" colspan="8">&nbsp;</td>
        </tr>
    </table>

<? PrintNavigation($maxpages,$pagenum,1); ?>
<? } else { ?>
    
    <br>
    <span class=szovegvastag>Nincs a megadott feltételeknek megfelelő log.</span>
</center>
<?}

include "footer.php";

//--------------------------------------------------------------------------//
//                           F U N C T I O N S                              //
//--------------------------------------------------------------------------//  
  
//****[ PrintHead ]***********************************************************
function PrintHead() {

global $_MX_var,$maxrecords,$deleted_name,$word;

print "
<script>
function chowner(group_id) {
  winopts     = \"toolbar=no,location=no,directories=no,status=no,\";
  winopts     = winopts + \"menubar=no,scrollbars=yes,resizable=yes,width=400,height=350\";
  SowFrame   = window.open(\"admin_change_owner.php?group_id=\"+group_id, \"gu_extend\"+group_id, winopts);
} 
function chview(ds) {
	var x=document.getElementsByName('extview');
	if (document.getElementById('switch'+ds).checked == true) st='block'; else st='none';
	for (c=0; c<=(x.length)-1; c++) {
		document.getElementsByName('extview')[c].style.display=st;
	}
}
</script>
<link rel='stylesheet' type='text/css' media='all' href='./js/calendar-mos.css' title='green' />
<script type='text/javascript' src='./js/calendar_mini.js'></script>
<script type='text/javascript' src='./js/calendar-sr.js'></script>
<table border='0' cellpadding='2' cellspacing='0' width='100%'>
<tr>
<td class=formmezo>Log</td>
</tr>\n";

}
//----------------------------------------------------------------------------


//****[ PrintNavigation ]*****************************************************
function PrintNavigation($maxpages, $pagenum, $ds) {
global $_MX_var,$sortt;                    // sort method
global $_MX_var,$maxPerPage;               // things for leafing
global $_MX_var,$maxrecords;
global $_MX_var,$first,$filt_group_name,$tipus_options,$search_text,$users,$datumtol,$datumig,$switchview0,$tipus,$user;

 $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
 $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
 $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;

$sel_sort[$sortt] = "SELECTED"; // set selected method in dropdown menu
if (!isset($datumig)) $datumig=date("Y.m.d");
if ($switchview0=='on') $ch="checked";

  echo "<form>
  <table width='100%' border='0' cellspacing='0' cellpadding='3' align='center' class='bgred'>
    <tr> 
      <td align='left'> 
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'><a href='admin_log.php?first=0&maxPerPage=$maxPerPage&switchview0=$switchview0&tipus=$tipus&search_text=$search_text&user=$user&datumtol=$datumtol&datumig=$datumig'><img
                src='$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='admin_log.php?first=$OnePageLeft&maxPerPage=$maxPerPage&switchview0=$switchview0&tipus=$tipus&search_text=$search_text&user=$user&datumtol=$datumtol&datumig=$datumig'><img
                src='$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'></a>
            </td>";
 else
 echo " 
            <td nowrap align='right'>
              <img src='$_MX_var->application_instance/gfx/down2_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->application_instance/gfx/down1_dead.gif' width='20' height='14' border='0'>
            </td>";
 echo "
            <td nowrap align='right'> 
              <input type='text' name='pagenum' size='3' maxlength='3' value='$pagenum'>
            </td>
            <td nowrap><span class='szoveg'> / $maxpages</span></td>";
if ($first<$LastPage)
echo "
            <td nowrap> &nbsp;
              <a href='admin_log.php?first=$OnePageRight&maxPerPage=$maxPerPage&switchview0=$switchview0&tipus=$tipus&search_text=$search_text&user=$user&datumtol=$datumtol&datumig=$datumig'><img
                src='$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='admin_log.php?first=$LastPage&maxPerPage=$maxPerPage&switchview0=$switchview0&tipus=$tipus&search_text=$search_text&user=$user&datumtol=$datumtol&datumig=$datumig'><img
                src='$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>&nbsp; </td>";
 else
 echo " 
            <td nowrap align='right'>
              <img src='$_MX_var->application_instance/gfx/up1_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->application_instance/gfx/up2_dead.gif' width='20' height='14' border='0'>
            </td>";
echo "
          </tr>
        </table>
      </td>
      <td> 
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
            <td nowrap align='center'><span class='szovegvastag'>Nézet: </span></td>
            <td nowrap align='center'> 
              <input type='text' name='maxPerPage' value='$maxPerPage' size='3' maxlength='3'>
            </td>
            <td nowrap align='center'><span class='szoveg'>&nbsp;csoport/oldal</span></td>
          </tr>
        </table>
      </td>
      <td><input type='checkbox' onchange='chview($ds);' name='switchview$ds' $ch id='switch$ds' /><span class='szoveg'>bővitett</span>
      </td>
      <td> 
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
            <td nowrap><span class='szovegvastag'>
			Tipus:</span></td>
			<td nowrap>
            <select name=tipus>
				<option value='0'> - minden esemény - </option>
                $tipus_options
			</select>
            </td>
          </tr>
        </table>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class='szoveg'>
            Keresett szó: <input type='text' name='search_text' value='$search_text' /> 
        </td>      
    	<td>
    	    <select name=user>
				<option value='0'> - minden felhasznalo - </option>
                $users
			</select>
    	</td>    	
    	<td>Dátumtól:
    	<input type='text' readonly='readonly' onclick='this.value=\"\"' name='datumtol' id='datumtol$ds' size='8'/ value='$datumtol'>
		<input type='button' onclick='return showCalendar(\"datumtol$ds\", \"y.mm.d\");' value='...'/>    	
    	</td>
    	<td>Dátumig:
    	<input type='text' readonly='readonly' onclick='this.value=\"\"' name='datumig' id='datumig$ds' size='8'/ value='$datumig'>
		<input type='button' onclick='return showCalendar(\"datumig$ds\", \"y.mm.d\");' value='...'/>    	
    	</td>
 		<td>
     	<input type=submit value='K E R E S' name='keres'>    	
		</td>
    </tr>    
    <tr>
      <td colspan=3>
<!--      <span class='szovegvastag'>Szűrő:&nbsp;</span> 
      <span class='szoveg'>
      A csoport neve:<input type='text' name='filt_group_name' value='$filt_group_name'>
      <input type=submit value='Mehet'>
      <input type=submit value='Szűrő törlése' name='filt_clear'>
      </span>-->
      </td>
    </tr>
  </table></form>
  <script type='text/javascript'>chview(0);</script>
  ";
}
//----------------------------------------------------------------------------

?>
