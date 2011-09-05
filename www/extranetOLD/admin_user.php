<?

include "auth.php";

$Amfsort=get_cookie("admin_u_sortt");
$Amfppage=get_cookie("admin_u_perpage");
$admin_filt_email=get_cookie("admin_filt_email");
$admin_filt_name=get_cookie("admin_filt_name");

$sortt = (isset($_GET['sortt']) || empty($Amfsort)) ? get_http('sortt',4) : $Amfsort;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Amfppage)) ? get_http('maxPerPage',25) : $Amfppage;

$filt_clear=get_http('filt_clear','');
$filt_email=get_http('filt_email','');
$filt_name=get_http('filt_name','');

if (empty($filt_email) && isset($admin_filt_email) && !isset($filt_clear)) {
  $filt_email=$admin_filt_email;
}

if (empty($filt_name) && isset($admin_filt_name) && !isset($filt_clear)) {
  $filt_name=$admin_filt_name;
}

setcookie("admin_u_sortt",$sortt,time()+30*24*3600);
setcookie("admin_u_perpage",$maxPerPage,time()+30*24*3600);

if (!empty($filt_clear)) {
  setcookie("admin_filt_email");
  setcookie("admin_filt_name");
  $filt_email='';
  $filt_name='';
}
else {
  setcookie("admin_filt_email",$filt_email,time()+30*24*3600);
  setcookie("admin_filt_name",$filt_name,time()+30*24*3600);
}

$_MX_superadmin=0;
$weare = 202;
include "cookie_auth.php";  
if (!$_MX_superadmin) {
    exit;
}
include "menugen.php";

include "./lang/$language/admin_user.lang";

$filt_emailq=slasher($filt_email);
$filt_nameq=slasher($filt_name);  
$hfilt_email=slasher($filt_email,-1);
$hfilt_name=slasher($filt_name,-1);  
$sql="select count(*) from user where name like '%$filt_nameq%' and email like '%$filt_emailq%'";
  //echo $sql;
$res = mysql_query($sql);
$maxrecords = mysql_result($res,0,0);
$pagenum=intval(get_http('pagenum',1));
$first=intval(get_http('first',0));
$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 15;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;
if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                         
PrintHead();

if (!$sortt) $sortt=1;

switch($sortt) {
    case 1: $order=" email asc "; break;
    case 2: $order=" email desc "; break;
    case 3: $order=" name asc "; break;
    case 4: $order=" name desc "; break;
}

$q = mysql_query("SELECT * from user where name like '%$filt_nameq%' and email like '%$filt_emailq%' 
                  order by $order limit $first,$maxPerPage");
$pagenum=(int)($first / $maxPerPage) + 1;
$maxpages = ceil($maxrecords / $maxPerPage);
PrintNavigation($maxpages,$pagenum);

if($q && mysql_num_rows($q)) { 
    print "<CENTER>
        <table border=0 cellpadding=4 cellspacing=0 width='100%'>
        <tr>    
        <td class=LEFTTITLE valign='top'>$word[au_email]</td>
        <td class=LEFTTITLE valign='top'>$word[au_name]</td>  
        <td class=LEFTTITLE valign='top'>$word[au_operations]</td>
        </tr>\n";

    $index = 0;
    $col = 2;
    while($row=mysql_fetch_array($q)) {
        $index++;
        $col==2?$col=1:$col=2;
        $name=nl2br(htmlspecialchars($row["name"]));
        $owned_groups="";
        $res=mysql_query("select groups.title from groups,members where groups.id=members.group_id 
                          and members.membership='owner' and members.user_id='$row[id]'");
        if ($res && mysql_num_rows($res)) {
            while ($k=mysql_fetch_array($res)) {
                if (empty($owned_groups))
                    $owned_groups=$k["title"];
                else
                    $owned_groups.=", $k[title]";
            }
        }
        if (empty($owned_groups))
            $dellink="<a href='admin_useru.php?action=delete&user_id=$row[id]' onClick=\"return(confirm('$word[au_sure_delete]'))\">$word[au_delete]&nbsp;</a>";
        else
            $dellink="<a href='javascript: alert(\"A(z) $row[email] usert nem lehet törölni, mert tulajdonosa a(z) $owned_groups csoport(ok)nak.\");'>$word[au_delete]&nbsp;</a>";
        print "<form name=form$row[id]>
            <tr>
            <td class=COLUMN$col valign='top'><span class=LITTLE>
            <a href='mailto: $row[email]'>$row[email]</a> (id=$row[id])</span></td>
            <td class=COLUMN$col valign='top'><span class=LITTLE>$name</span></td>
            <td class=COLUMN$col valign='top'><span class=LITTLE>$dellink <a href='admin_newuser.php?chuser_id=$row[id]'>$word[au_edit]</a></span></td>
            </tr>\n";
        }
    print "<tr>
        <td class=bgkiemelt2 colspan=6>&nbsp;</td>
        </tr>
        </table>\n";
    PrintNavigation($maxpages,$pagenum);
} 
else {
    print "<CENTER>
        <br><span class=szovegvastag>$word[au_no_user]</span>
        </CENTER>\n";
}

include "footer.php";

//****[ PrintHead ]***********************************************************
function PrintHead() {

global $_MX_var,$maxrecords,$deleted_name,$word;

print "
<table border='0' cellpadding='2' cellspacing='0' width='100%'>
    <tr>
        <td class=formmezo>$word[admin_user]</td>
    </tr>
    <tr>    
        <td class='bgvilagos2'>
            <div class='fl'>$word[au_sum] $maxrecords $word[au_user]</div>
            <div class='fr'><a href='admin_newuser.php'>$word[au_new_user]</a></div>
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
global $_MX_var,$first,$hfilt_email,$hfilt_name, $word;

 $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
 $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
 $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;

  $sel_sort[$sortt] = "SELECTED"; // set selected method in dropdown menu
  
  echo "
  <table width='100%' border='0' cellspacing='0' cellpadding='0' class='bgcolor tac'>
    <tr> 
      <td align='left'> 
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
          <form name=inputs>";
 if ($first>0)
 echo " 
            <td nowrap align='right'>
                <a href='admin_user.php?first=0'>
                    <img src='$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'>
                </a>
                <a href='admin_user.php?first=$OnePageLeft'>
                    <img src='$_MX_var->application_instance/gfx/down1.gif' width='20' height='14' border='0'>
                </a>
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
              <a href='admin_user.php?first=$OnePageRight'><img
                src='$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='admin_user.php?first=$LastPage'><img
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
            <td nowrap class='tac formmezo'>$word[au_view] </td>
            <td nowrap align='center'> 
              <input type='text' name='maxPerPage' value='$maxPerPage' size='3' maxlength='3'>
            </td>
            <td nowrap class='tac formmezo'> $word[au_user_page]</td>
           </form>
          </tr>
        </table>
      </td>
      <td align='right'> 
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
           <form>
            <td nowrap class='tac formmezo'>$word[au_order]</td>
            <td nowrap><span class='szoveg'> 
              <select onChange='JavaScript: this.form.submit();' name=sortt>
                <option value=1 $sel_sort[1]>$word[au_by_email_addr_asc]</option>
                <option value=2 $sel_sort[2]>$word[au_by_email_addr_desc]</option>
                <option value=3 $sel_sort[3]>$word[au_by_name_asc]</option>
                <option value=4 $sel_sort[4]>$word[au_by_name_desc]</option>
              </select>
              </span>
            </td>
           </form>
          </tr>
        </table>
      </td>
    </tr>
    <form method='post'>
    <tr>
      <td colspan=3 class='formmezo'>
      $word[au_filter]&nbsp;
      $word[au_email]<input type='text' name='filt_email' value=\"$hfilt_email\">
      $word[au_nick]<input type='text' name='filt_name' value=\"$hfilt_name\">
      <input type=submit value='$word[au_go]'>
      <input type=submit value='$word[au_del_filter]' name='filt_clear'>&nbsp;
      </td>
    </tr>
    </form>
  </table>
  ";
}


?>
