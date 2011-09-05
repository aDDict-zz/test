<?
include "auth.php";
$weare = 201;
$pagenum = get_http("pagenum", "");
$first = get_http("first", "");

$admin_sortt = get_cookie('admin_sortt');
$admin_perpage = get_cookie('admin_perpage');
$admin_filt_group_name = get_cookie('admin_filt_group_name');

$filt_clear=get_http('filt_clear','');
$filt_group_name=get_http('filt_group_name','');

$sortt = (isset($_GET['sortt']) || empty($admin_sortt)) ? get_http('sortt',4) : $admin_sortt;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($admin_perpage)) ? get_http('maxPerPage',25) : $admin_perpage;

if (empty($filt_group_name) && isset($admin_filt_group_name) && empty($filt_clear)) {
  $filt_group_name=$admin_filt_group_name;
}

setcookie("admin_sortt",$sortt,time()+30*24*3600);
setcookie("admin_perpage",$maxPerPage,time()+30*24*3600);

if (!empty($filt_clear))
{
  setcookie("admin_filt_group_name");
  $filt_group_name='';
}
else
  setcookie("admin_filt_group_name",$filt_group_name,time()+30*24*3600);

$_MX_superadmin=0;
include "cookie_auth.php";  
if (!$_MX_superadmin) {
    exit;
}
include "menugen.php";

$deleted_name="";
if ($delete && $group_id)
   delete_group();

/*if ($choutsource && $group_id) 
     mysql_query("update groups set outsource='$outsource',tstamp=now() where id='$group_id'");*/

if (!$sortt) $sortt=8;

//--------------------------------------------------------------------------//
//                              M A I N                                     //
//--------------------------------------------------------------------------//

  $filt_group_name=addslashes($filt_group_name);
  $sql  = "SELECT COUNT(id) AS maxrecords FROM groups where title like '%$filt_group_name%'";    
  $res = mysql_query($sql);
  $l=mysql_fetch_array($res);                                         
  $maxrecords = $l["maxrecords"];                                          
  $maxPerPage=intval($maxPerPage);
  if($maxPerPage<1) $maxPerPage = 15;
  $pagenum=intval($pagenum);
  if($pagenum<1) $pagenum=1;
  if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
  if(!$first) $first = 0;
  if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                         
  PrintHead();

  switch($sortt)
  {
    case 1: $order=" title asc,create_date desc "; break;
    case 2: $order=" title desc,create_date desc "; break;
    case 3: $order=" num_of_mess asc,create_date desc "; break;
    case 4: $order=" num_of_mess desc,create_date desc "; break;
    case 7: $order=" create_date asc,title asc "; break;
    case 8: $order=" create_date desc,title asc "; break;
    case 9: $order=" last_date asc,create_date desc "; break;
    case 10: $order=" last_date desc,create_date desc "; break;
  }

  $q = mysql_query("select id,title,num_of_mess,create_date,last_date,owner_id,sms_send,important
                    from groups
                    where title like '%$filt_group_name%'
                    order by $order limit $first,$maxPerPage");
  if($q && mysql_num_rows($q)) { 
    $pagenum=(int)($first / $maxPerPage) + 1;
    $maxpages = ceil($maxrecords / $maxPerPage);
    PrintNavigation($maxpages,$pagenum);

print " 
<CENTER>
<table border=0 cellpadding=4 cellspacing=0 width='100%'>
<tr>    
<td class=LEFTTITLE valign='top'>A csoport neve</td>
<td class=LEFTTITLE valign='top'>Üzenetek száma</td>
<td class=LEFTTITLE valign='top'>Tagok száma</td>  
<td class=LEFTTITLE valign='top'>Első bejegyzés<br>Utolsó bejegyzés</td>
<td class=LEFTTITLE valign='top'>&nbsp;</td>
<td class=LEFTTITLE valign='top'>Műveletek</td>
</tr>
";

    $index = 0;
    $col = 2;
    while($row=mysql_fetch_array($q)) {
      $index++;
      if($col==2) $col = 1; else $col = 2;
      /*if ($row["outsource"]=='yes')
        { $ysel='selected'; $nsel=''; }
      else
        { $nsel='selected'; $ysel=''; }*/
$group_title=nl2br(htmlspecialchars($row["title"]));
$rnom=mysql_query("select count(*) from users_$group_title where validated='yes' and robinson='no'");
if ($rnom && mysql_num_rows($rnom))
    $num_of_memb=mysql_result($rnom,0,0);
else
    $num_of_memb=0;
$biztos_torli="Biztosan ki akarja törölni a(z) $group_title csoportot? Ez maga után vonja az összes tag, a tagok adatai, a csoport üzenetei és a csoport minden egyéb adatának a törlését.";
$delhash=($row["id"]*907333)%4111;
if ($row["sms_send"]=="yes")
    $ssch="<a onClick=\"return confirm('Megtiltja az sms-ek küldését a csoportból?');\" href='admin_statisticu.php?group_id=$row[id]&sms_send=no'>[küldhet sms-eket]</a>";
else
    $ssch="<a onClick=\"return confirm('Engedélyezi sms-ek küldését a csoportból?');\" href='admin_statisticu.php?group_id=$row[id]&sms_send=yes'>[nem küldhet sms-eket]</a>";
if ($row["important"]=="yes")
    $impch="<a onClick=\"return confirm('Megtiltja a csoport üzenetei felvételét az \\'important\\' spool direktóriumba?');\" href='admin_statisticu.php?group_id=$row[id]&important=no'>[&quot;important&quot;]</a>";
else
    $impch="<a onClick=\"return confirm('A csoport üzenetei menjenek az \\'important\\' spool direktóriumba?');\" href='admin_statisticu.php?group_id=$row[id]&important=yes'>[nem &quot;important&quot;]</a>";
print "
  <form name=form$row[id]>
  <tr>
    <td class=COLUMN$col valign='top'><span class=LITTLE>$group_title</span></td>
    <td class=COLUMN$col valign='top'><span class=LITTLE>$row[num_of_mess]</span></td>
    <td class=COLUMN$col valign='top'><span class=LITTLE>$num_of_memb</span></td>
    <td class=COLUMN$col valign='top'><span class=LITTLE>$row[create_date]<br>$row[last_date]</span></td>
    <td class=COLUMN$col valign='top'><span class=LITTLE>&nbsp;<!--
     <input type='hidden' name='choutsource' value=0>             
     <input type='hidden' name='group_id' value=$row[id]>             
     <select name='outsource' onChange=\"document.form$row[id].choutsource.value=1; document.form$row[id].submit();\">
     <option value='no' $nsel>Nem</option>
     <option value='yes' $ysel>Igen</option>     
     </select>    -->
    </span></td>    
    <td class=COLUMN$col valign='top'><span class=LITTLE>
       &nbsp;<a href='admin_megye_varos.php?group_id=$row[id]'>Megye/város/irszám hibák</a><br>
       &nbsp;
       <a href='moderators.php?group_id=$row[id]'>A csoport kiemelt tagjai</a><br>
       <a onClick=\"return confirm('$biztos_torli');\" href='admin_group_delete.php?group_id=$row[id]&delete=$delhash'>törlés</a>&nbsp;&nbsp;&nbsp;&nbsp;$ssch&nbsp;$impch</span></td>
  </tr>
  <tr>
    <td class=COLUMN$col colspan='6' style='height:8px;'></td>
  </tr>
  </form>
";
       //<a href='javascript:chowner($row[id])'>A csoport tulajdonosa</a><br>
    }
print "
<tr>
  <td class=bgkiemelt2 colspan=6>&nbsp;</td>
</tr>
</table>";
  PrintNavigation($maxpages,$pagenum);
  } else {
  PrintNavigation($maxpages,$pagenum);
print "
<CENTER>
<br><span class=szovegvastag>Nincs egy csoport sem.</span>
</CENTER>
";
}

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
</script>
<table border='0' cellpadding='2' cellspacing='0' width='100%'>
<tr>
<td class=formmezo>$word[admin_statistic]</td>
</tr>\n";
if (!empty($deleted_name))
print "<tr align='left'>    
<td class='bgvilagos2' valign='top' align='center' colspan='2'><b>A(z) $deleted_name csoport törlése sikeres volt.</b></td>
</tr>";
print "<tr>    
    <td class='bgvilagos2' valign='top' align='left'>
        <div class='fl'>Összesen $maxrecords csoport</div>
        <div class='fr'><a href='admin_newgroup.php'>Új csoport</a></div>
    </td>
</tr>
</table>\n";
}
//----------------------------------------------------------------------------


//****[ PrintNavigation ]*****************************************************
function PrintNavigation($maxpages, $pagenum) {
global $_MX_var,$sortt;                    // sort method
global $_MX_var,$maxPerPage;               // things for leafing
global $_MX_var,$maxrecords;
global $_MX_var,$first,$filt_group_name;

 $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
 $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
 $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;

  $sel_sort[$sortt] = "SELECTED"; // set selected method in dropdown menu
  
  echo "
  <table width='100%' border='0' cellspacing='0' cellpadding='0' class='bgcolor tal'>
    <tr> 
      <td align='left'> 
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
          <form name=inputs>";
 if ($first>0)
 echo " 
            <td nowrap align='right'><a href='admin_statistic.php?first=0'><img
                src='$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='admin_statistic.php?first=$OnePageLeft'><img
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
            <td nowrap class='formmezo'> / $maxpages</td>";
if ($first<$LastPage)
echo "
            <td nowrap> &nbsp;
              <a href='admin_statistic.php?first=$OnePageRight'><img
                src='$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='admin_statistic.php?first=$LastPage'><img
                src='$_MX_var->application_instance/gfx/up2.gif' width='20' height='14' border='0'></a>&nbsp; </td>";
 else
 echo " 
            <td nowrap align='right'>
              <img src='$_MX_var->application_instance/gfx/up1_dead.gif' width='20' height='14' border='0'>
              <img src='$_MX_var->application_instance/gfx/up2_dead.gif' width='20' height='14' border='0'>
            </td>";
echo "
           </form>
          </tr>
        </table>
      </td>
      <td align='center'> 
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
           <form>
            <td nowrap align='center' class='formmezo'>Nézet: </td>
            <td nowrap align='center'> 
              <input type='text' name='maxPerPage' value='$maxPerPage' size='3' maxlength='3'>
            </td>
            <td nowrap align='center' class='formmezo'> csoport/oldal</td>
           </form>
          </tr>
        </table>
      </td>
      <td align='right'> 
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
           <form>
            <td nowrap class='formmezo'>Sorrend:</td>
            <td nowrap class='formmezo'> 
              <select onChange='JavaScript: this.form.submit();' name=sortt>
                <option value=1 $sel_sort[1]>A csoport neve szerint - növekvő</option>
                <option value=2 $sel_sort[2]>A csoport neve szerint - csökkenő</option>
                <option value=3 $sel_sort[3]>Üzenetek száma szerint - növekvő</option>
                <option value=4 $sel_sort[4]>Üzenetek száma szerint - csökkenő</option>
                <option value=7 $sel_sort[7]>Első bejegyzés ideje szerint - növekvő</option>
                <option value=8 $sel_sort[8]>Első bejegyzés ideje szerint - csökkenő</option>
                <option value=9 $sel_sort[9]>Utolsó bejegyzés ideje szerint - növekvő</option>
                <option value=10 $sel_sort[10]>Utolsó bejegyzés ideje szerint - csökkenő</option>
              </select>
            </td>
           </form>
          </tr>
        </table>
      </td>
    </tr>
    <form method='post'>
    <tr>
      <td colspan=3 class='formmezo'>Szűrő:&nbsp; 
      A csoport neve:<input type='text' name='filt_group_name' value='$filt_group_name'>
      <input type=submit value='Mehet'>
      <input type=submit value='Szűrő törlése' name='filt_clear'>&nbsp;
      </td>
    </tr>
    </form>
  </table>
  ";
}
//----------------------------------------------------------------------------

//****[ delete group ]*****************************************************
function delete_group() {

  global $_MX_var,$group_id,$deleted_name;  

  $q = "SELECT tix,title FROM groups WHERE id='$group_id'";
  $r=mysql_query($q);
  if ($r && mysql_num_rows($r))
  {
   $l=mysql_fetch_array($r);
   $TIX=$l["tix"];
   $q2 = "SELECT * FROM messages$TIX WHERE group_id='$group_id'";
   $r2=mysql_query($q2);
   if ($r2 && mysql_num_rows($r2))
   {
    while($k=mysql_fetch_array($r2))
    {
     $mess_id=$k["id"];
     $q3="delete from mailarchive$TIX where id='$mess_id'";
     $r3=mysql_query($q3);
     $q3="delete from bodies$TIX where id='$mess_id'";
     $r3=mysql_query($q3);
    }
   }
   $q3="delete from messages$TIX where group_id='$group_id'";
   $r3=mysql_query($q3);
   $q3="delete from threads$TIX where group_id='$group_id'";
   $r3=mysql_query($q3);
   $q3="delete from featured_groups where group_id='$group_id'";
   $r3=mysql_query($q3);
   $q3="delete from members where group_id='$group_id'";
   $r3=mysql_query($q3);
   $q3="delete from validation where group_id='$group_id'";
   $r3=mysql_query($q3);
   $q3="delete from event where group_id='$group_id'";
   $r3=mysql_query($q3);
   $q3="delete from groups where id='$group_id'";
   $r3=mysql_query($q3);
   $deleted_name=$l["title"];
  }
}

?>
