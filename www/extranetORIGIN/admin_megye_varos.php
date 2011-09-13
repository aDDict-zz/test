<?
foreach ($_POST as $var=>$val) {
    $$var = $val;
}
foreach ($_GET as $var=>$val) {
    $$var = $val;
}
if (empty($maxPerPage) && isset($admin_perpage)) 
  $maxPerPage=$admin_perpage_mv;

if (empty($mv_suid) && isset($admin_mv_suid)) 
  $mv_suid=$admin_mv_suid;

if (empty($mv_showall) && isset($admin_mv_showall)) 
  $mv_showall=$admin_mv_showall;

if (empty($filt_mv) && isset($admin_filt_mv) && !isset($filt_clear)) 
  $filt_mv=$admin_filt_mv;

$mv_suid=abs(intval($mv_suid));
if (!$mv_suid)
    $mv_suid=1;

$maxPerPage=intval($maxPerPage);
if ($maxPerPage<1) 
    $maxPerPage=15;
if ($maxPerPage>50) 
    $maxPerPage=50;
if (!($mv_showall=="yes" || $mv_showall=="no"))
    $mv_showall="no";

setcookie("admin_perpage_mv",$maxPerPage,time()+30*24*3600);
setcookie("admin_mv_suid",$mv_suid,time()+30*24*3600);
setcookie("admin_mv_showall",$mv_showall,time()+30*24*3600);

if (get_magic_quotes_gpc())
    $filt_mv=stripslashes($filt_mv);
$sfilt_mv=addslashes($sfilt_mv);
    
if (isset($filt_clear)) {
  setcookie("admin_filt_mv");
  $filt_mv="";
}
else
  setcookie("admin_filt_mv",$filt_mv,time()+30*24*3600);

$_MX_superadmin=0;
include "auth.php";
$weare = 201;
$subweare = 2011;
include "cookie_auth.php";  
if (!$_MX_superadmin) {
    exit;
}
include "menugen.php";

$group_id=addslashes($group_id);
$res=mysql_query("select id,title from groups where id='$group_id'");
if ($res && mysql_num_rows($res)) {
    $k=mysql_fetch_array($res);
    $title=$k["title"];
    $group_id=$k["id"];
}
else
    exit;
        
PrintHead();

$r7=mysql_query("select * from megye order by nev");
if ($r7 && mysql_num_rows($r7)) {
    while ($z=mysql_fetch_array($r7)) {
        $m_enum_id["$z[id]"]=$z["enum_id"];
        $m_rnev["$z[enum_id]"]=$z["nev"];
    }
}
$m_rnev["536"]="Külföld";

$sfilt_mv=addslashes($filt_mv);
if ($sfilt_mv)
    $filtpart=" and (ui_email like '%$sfilt_mv%' or ui_varos like '%$sfilt_mv%')";
else
    $filtpart="";

print "<CENTER>
       <table border=0 cellpadding=0 cellspacing=0 width='100%'>
       <form method='post' action='admin_megye_varosu.php'>
       <input type='hidden' name='group_id' value='$group_id'>
       <tr>    
       <td class=LEFTTITLE valign='top'>Email</td>
       <td class=LEFTTITLE valign='top'>Irszám</td>
       <td class=LEFTTITLE valign='top'>Város</td>  
       <td class=LEFTTITLE valign='top'>Megye</td>
       </tr>\n";

$r2=mysql_query("select * from users_$title where id>='$mv_suid' $filtpart
                 and (length(ui_ir)>0 or length(ui_varos)>0 or length(ui_megye)>0)
                 order by id");
if ($r2 && mysql_num_rows($r2)) {
    while ($m=mysql_fetch_array($r2)) {
        $varos=$m["ui_varos"];
        $megye=$m["ui_megye"];
        $ir=$m["ui_ir"];
        $db_enum_id="";
        $db_telepnev="";
        $r1=mysql_query("select megye_id from ir where id='$ir'");
        if ($r1 && mysql_num_rows($r1)) {
            $mid=mysql_result($r1,0,0);
            $db_enum_id=$m_enum_id[$mid];
        }
        $r1=mysql_query("select telep.telepules from telep,ir where telep.id=ir.telep_id and ir.id='$ir'");
        if ($r1 && mysql_num_rows($r1)) {
            $db_telepnev=mysql_result($r1,0,0);
        }
        $verr=0;
        $merr=0;
        if (strtolower($varos)!=strtolower($db_telepnev) && !($megye==",536,")) { #ignore kulfold
            $verr=1;
            $r4=mysql_query("select telepules from telep where ir='$ir'"); 
            if ($r4 && mysql_num_rows($r4)) {
                while ($k4=mysql_fetch_array($r4)) {
                    $kistelep=$k4["telepules"];
                    if (strtolower($kistelep)==strtolower($varos))
                        $verr=0;
                }
            }
        }
        if (",$db_enum_id,"!=$megye && !($db_enum_id==243 && $megye==",230," && $ir>999 && $ir<2000) && !($megye==",536,"))
            $merr=1;
        $ishow=1;
        $comment="";
        $r3=mysql_query("select comment from admin_mv_ignored where user_id='$m[id]' and group_id='$group_id'");
        if ($r3 && mysql_num_rows($r3)) {
            if ($mv_showall!="yes")
                $ishow=0;
            else
                $comment=htmlspecialchars(mysql_result($r3,0,0));
        }
        if (($verr || $merr) && $ishow) {
            $hvaros=htmlspecialchars($varos);
            $hir=htmlspecialchars($ir);
            if ($verr) 
                $verr=htmlspecialchars($db_telepnev);
            else
                $verr=$hvaros;
            $hid=intval(str_replace(",","",$megye));
            $megyenev=$m_rnev[$hid];
            if ($merr) {
                if ($ir>999 && $ir<2000)
                    $merr=230;
                else
                    $merr=$db_enum_id;
            }
            else
                $merr=$hid;
            $mdd="";
            reset($m_rnev);
            while (list($xm_id,$xm_nev)=each($m_rnev)) {
                $xm_id==$merr?$xsel="selected":$xsel="";
                $mdd.="<option value='$xm_id' $xsel>$xm_nev</option>";
            }
            $mv_user_id=$m["id"];
            $col==2?$col=1:$col=2;
            print "<tr>
                   <td class=COLUMN$col><span class=LITTLE>$m[ui_email]&nbsp;</span></td>
                   <td class=COLUMN$col><span class=LITTLE>$hir&nbsp;</span></td>
                   <td class=COLUMN$col><span class=LITTLE>$hvaros&nbsp;</span></td>
                   <td class=COLUMN$col><span class=LITTLE>$megyenev&nbsp;</span></td>
                   </tr>
                   <tr>
                   <td class=COLUMN$col><span class=LITTLE><input type=radio name='mv_radio[$mv_user_id]' checked value='change'> Változtatás</span></td>
                   <td class=COLUMN$col><span class=LITTLE><input type='text' name=mv_ir[$mv_user_id] value=\"$hir\"></span></td>
                   <td class=COLUMN$col><span class=LITTLE><input type='text' name=mv_varos[$mv_user_id] value=\"$verr\"></span></td>
                   <td class=COLUMN$col><span class=LITTLE><select name='mv_megye[$mv_user_id]'><option value=0> -- </option>$mdd</select></span></td>
                   </tr>
                   <tr>
                   <td class=COLUMN$col colspan='4'><span class=LITTLE><input type=radio name='mv_radio[$mv_user_id]' value='ignore'> Megjelöl (nem változtat, de nem is jelenik meg ezen a listán), megjegyzés: <input type='text' size='36' name=mv_comment[$mv_user_id] value=\"$comment\"></span></td>
                   </tr>\n";
            $i++;
        }
        if ($i==$maxPerPage)
            break;
    }
}

$col==2?$col=1:$col=2;
print "<tr>
       <td class=COLUMN$col colspan='4' align='center'><span class=LITTLE>&nbsp;</span></td>
       </tr>
       <tr>
       <td class=COLUMN$col colspan='4' align='center'><span class=LITTLE><input type='submit' name='sbmt' value='Mentés'></span></td>
       </tr>
       <tr>
       <td class=COLUMN$col colspan='4' align='center'><span class=LITTLE>&nbsp;</span></td>
       </tr>
       </form>
       </table>
       \n";

include "footer.php";

//****[ PrintHead ]***********************************************************
function PrintHead() {

global $_MX_var,$filt_mv, $mv_suid, $mv_showall, $title, $group_id, $word;

$mv_showall=="yes"?$allsel="selected":$allsel="";

$hfilt_mv=htmlspecialchars($filt_mv);

print "<table border='0' cellpadding='2' cellspacing='0' width='100%'><tr>
       <td colspan='2' class=formmezo>$word[admin_statistic] &gt; Irszám/megye/város admin</td>
       </tr>
       <tr align='left'>    
       <td class=bgvilagos2 valign='top' align='left'>$title csoport</td>
       <td class=bgvilagos2 valign='top' align='right'>&nbsp;</td>
       </tr>
       <tr>
       <form method='post'>
       <input type='hidden' name='group_id' value='$group_id'>
       <td>
       <span class='szovegvastag'>Szûrõ:&nbsp;</span> 
       <span class='szoveg'><input type='text' name='filt_mv' value=\"$hfilt_mv\">
       <input type=submit value='Mehet'>
       <input type=submit value='Szûrõ törlése' name='filt_clear'>
       </span>
       </td>
       </form>
       <form method='post'>
       <input type='hidden' name='group_id' value='$group_id'>
       <td>
       <span class='szovegvastag'>Keresés user_id=<input type='text' name='mv_suid' value='$mv_suid'>-tõl
       <input type=submit value='Mehet'>
       </span>
       </td>
       </form>
       </tr>
       <form method='post'>
       <input type='hidden' name='group_id' value='$group_id'>
       <tr>
       <td align=left colspan=2>
       <select onChange='JavaScript: this.form.submit();' name='mv_showall'>
       <option value='no'>Nem megjelölt hibák</option>
       <option $allsel value='yes'>Minden hiba</option>
       </select>&nbsp;
       </td>
       </tr>
       </form>
       </table>\n";
}
?>
