<?
include "auth.php";
include "decode.php";
$weare=51;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/bounce.lang";

if (empty($sortm) && isset($Bo2sortm)) 
    $sortm=$Bo2sortm;
    
if (empty($maxPerPage) && isset($Bo2perpage)) 
    $maxPerPage=$Bo2perpage;
    
setcookie("Bo2sortm",$sortm,time()+30*24*3600);
setcookie("Bo2perpage",$maxPerPage,time()+30*24*3600);


$_MX_popup = 1;
include "menugen.php";


$pemail=slasher($email,0);
$hemail=slasher($email,-1);
$email=slasher($email);
$group_id=intval($group_id);

# create list of group_ids user has access to.
# if there is none, user does not have access to this script.
# here is a little problem with authentication, because on the site auth is based on the 
# single group_id, user_id and the kind of membership in that group, if all is ok, he can
# operate on that group. In this script, however, user operates on all his groups 
# (depending, again, on kind of the membership!). To stick to some conventions, access  
# will not be granted if user is not member of the given group_id.
 
$access=0;
$group_id_list="";
$mres = mysql_query("select groups.id,title,num_of_mess from groups,members where groups.id=members.group_id
                     and user_id='$active_userid' 
                     and (membership='owner' or membership='moderator' or membership='support' $admin_addq)");
if ($mres && mysql_num_rows($mres)) {
    while ($k=mysql_fetch_array($mres)) {
        if ($k["id"]==$group_id) {
            $access=1; # putting this out of the 'if' statement would be enough, see the above comment.
            $title=$k["title"]; # this also comes from the old convention.
        }
        empty($group_id_list)?$group_id_list="$k[id]":$group_id_list.=",$k[id]";
        $titles["$k[id]"]=$k["title"];
    }
}
if (!$access) 
    exit;

$maxrecords=0;
$res=mysql_query("select count(*) from bounced_back where group_id in ($group_id_list) and email='$email' 
                  and project='maxima'");
if ($res && mysql_num_rows($res)) 
    $maxrecords=mysql_result($res,0,0);

$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 15;
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

echo "<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
    <TR>
    <TD>
    <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
    <tr>
    <td class=bgkiemelt2 align=left colspan=4><span class=szovegvastag>$hemail $word[bounced_mails]
    <br>($word[inall] $maxrecords)</span></td>
    </tr>\n";


if (!$sortm)
    $sortm=1;
if (!empty($limitord))
    $order="order by $limitord";
else {    
    switch ($sortm) {
        case 1: $order = "order by date asc"; break;
        case 2: $order = "order by date desc"; break;
    }
}

$res=mysql_query("select * from error_code order by description");
if ($res && mysql_num_rows($res)) {
    while ($k=mysql_fetch_array($res)) {
        $error_codes["$k[code]"]=$k["description"];
    }
}

$query = "select * from bounced_back where group_id in ($group_id_list) and email='$email' 
          and project='maxima' $order limit $first,$maxPerPage";

//echo nl2br(htmlspecialchars($query));
$rst=mysql_query($query);
$index = $first;
if ($rst && mysql_num_rows($rst)) {
    printnavigation();
    echo "<form action='bounceu.php' method='post'>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='pagenum' value='$pagenum'><tr>
          <td class='bgkiemelt2' align=left width=7%><span class=szovegvastag>&nbsp;</span></td>
          <td class='bgkiemelt2' align=left width=31%><span class=szovegvastag>$word[t_error]</span></td>
          <td class='bgkiemelt2' align=left width=31%><span class=szovegvastag>$word[t_date]</span></td>
          <td class='bgkiemelt2' align=left width=31%><span class=szovegvastag>$word[t_group]</span></td>
          </tr>\n";
    while($row=mysql_fetch_array($rst)) {
        $index++;
        if ($index%2)
           $bgrnd="class=bgvilagos2";
        else
           $bgrnd="bgcolor=white";
        
        $gtitle=$titles["$row[group_id]"];
        $error_desc=$error_codes["$row[error_code]"];
        echo "<tr>
              <td $bgrnd align=left width=7%><span class=szoveg>$index.</span></td>
              <td $bgrnd align=left width=31%><span class=szoveg>$row[error_code] $error_desc</span></td>
              <td $bgrnd align=left width=31%><span class=szoveg>$row[date]</span></td>
              <td $bgrnd align=left width=31%><span class=szoveg>$gtitle</span></td>
              </tr>\n";
    }
    echo "</form>";
    printnavigation();
}

echo "</table>
    </td>
    </tr>
    </table>
    </body>
    </html>\n";

//****[ PrintNavigation ]*****************************************************
function PrintNavigation() {
  global $_MX_var,$group_id,$pagenum,$LastPage,$OnePageLeft,$OnePageRight,$sortm,$filt_demog,$pemail,$hemail;
  global $_MX_var,$pagenum,$maxpages,$first,$word,$maxPerPage,$limitord,$date_type_string;

  $sel_sort[$sortm] = "selected";
  $params="group_id=$group_id&email=$pemail";
  
  echo "
<tr><td colspan=4><table border=0 cellspacing=0 cellpadding=0 width='100%'>  
<tr>
    <td>
    <table border=0 cellspacing=0 cellpadding=0 width='100%'>
	<tr>
    	<td class='formmezo' align='left' width='50%'>
	<table border='0' cellspacing='0' cellpadding='0'>
          <form name=inputs>
          <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='email' value=\"$hemail\">
	  <tr>";
 if ($first>0)
 echo " 
            <td nowrap align='right'><a href='$_MX_var->baseUrl/bounce_detail.php?$params&first=0'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/down2.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/bounce_detail.php?$params&first=$OnePageLeft'><img
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
              <a href='$_MX_var->baseUrl/bounce_detail.php?$params&first=$OnePageRight'><img
                src='$_MX_var->baseUrl/$_MX_var->application_instance/gfx/up1.gif' width='20' height='14' border='0'></a>
              <a href='$_MX_var->baseUrl/bounce_detail.php?$params&first=$LastPage'><img
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
    	<td class='formmezo' align='right' width='50%'>
        <table border='0' cellspacing='0' cellpadding='0'>
          <tr> 
  <form>
  <input type='hidden' name='group_id' value='$group_id'>
          <input type='hidden' name='email' value=\"$hemail\">
            <td nowrap>\n";
            if (!empty($limitord))
              echo "&nbsp;";
            else
              echo "
              <select onChange='JavaScript: this.form.submit();' name=sortm>
                <option value=1 $sel_sort[1]>$word[date_desc]</option>
                <option value=2 $sel_sort[2]>$word[date_asc]</option>
              </select>\n";
            echo "
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
