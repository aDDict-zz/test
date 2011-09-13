<?
$_MX_superadmin=0;
include "auth.php";
$weare = 202;
if (get_http('chuser_id','')) $subweare = 2021;
else $subweare = 2022;
include "cookie_auth.php";  
if (!$_MX_superadmin) {
    exit;
}
include "menugen.php";

$chuser_id=intval(get_http('chuser_id',''));
$res=mysql_query("select * from user where id='$chuser_id'");
if ($res && mysql_num_rows($res)) {
    $udata=mysql_fetch_array($res);
    $titleissue="Felhasználó adatainak módosítása";
    $chuserissue="<tr><td colspan='2'><span class='szoveg'>Az alábbi két mezőt csak abban az esetben kell kitölteni ha meg szeretné változtatni a felhasznéló jelszavát.</span></td></tr>";
}
else {
    $chuser_id=0;
    $titleissue="Új felhasználó hozzáadása";
    $chuserissue="";
}

printhead();

$email=get_http('email','');
$name=get_http('name','');
$replyto=get_http('replyto','');
$sms_validate=get_http('sms_validate','');
$sms_send=get_http('sms_send','no');
$change_variables=get_http('change_variables','no');
$pass1=get_http('pass1','');
$pass2=get_http('pass2','');
$enter=get_http('enter','no');

$semail=slasher($email);
$hemail=slasher($email,-1);
$sreplyto=slasher($replyto);
$hreplyto=slasher($replyto,-1);
$ssms_validate=slasher($sms_validate);
$hsms_validate=slasher($sms_validate,-1);
if ($sms_send=="yes") {
    $sschecked=" checked";
}
else {
    $sms_send="no";
    $sschecked="";
}
if ($change_variables=="yes") {
    $cvchecked=" checked";
}
else {
    $change_variables="no";
    $cvchecked="";
}

if ($enter=="yes") {
    $finished=1;
    $r5=mysql_query("select id from user where email='$semail' and id!='$chuser_id'");
    if ($r5 && mysql_num_rows($r5)) {
        echo "<tr><td><span class='szovegvastag'> Hiba! Már létezik felhasználó ilyen e-mail címmel.
              </span></td></tr>"; 
        $finished=0;
    }
    if (!eregi("^[\+\._a-z0-9-]+@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+[a-z]{2}[mtgvu]?$", $email)) {
        echo "<tr><td><span class='szovegvastag'> Hibás email cím: $hemail</span></td></tr>"; 
        $finished=0;
    }
    if(!trim($pass1) && !$chuser_id) {
        echo "<tr><td><span class='szovegvastag'>Nem adta meg a jelszavat.</span></td></tr>"; 
        $finished=0;
    }
    if($pass1 != $pass2) {
        echo "<tr><td><span class='szovegvastag'>A jelszó és megerősítése nem egyezik meg.</span></td></tr>";
        $finished=0;
    }
    if(!trim($name)) {
        echo "<tr><td><span class='szovegvastag'>Nem adta meg a nevet.</span></td></tr>"; 
        $finished=0;
    }
    if (!empty($replyto) && !eregi("^[\+\._a-z0-9-]+@([0-9a-z][0-9a-z-]*\.)+[a-z]{2,4}$", $replyto)) {
        echo "<tr><td><span class='szovegvastag'> Hibás reply-to cím: $hreplyto</span></td></tr>"; 
        $finished=0;
    }
    if (!empty($sms_validate) && !eregi("^[\+\._a-z0-9-]+@([0-9a-z][0-9a-z-]*\.)+[a-z]{2,4}$", $sms_validate)) {
        echo "<tr><td><span class='szovegvastag'> Hibás sms hitelesítő email cím: $hsms_validate</span></td></tr>"; 
        $finished=0;
    }
    $spass1=slasher($pass1);
    $sname=slasher($name);
    $hpass1=slasher($pass1,-1);
    $hname=slasher($name,-1);
}
elseif ($chuser_id) {
    $hname=htmlspecialchars($udata["name"]);
    $hemail=htmlspecialchars($udata["email"]);
    $hreplyto=htmlspecialchars($udata["replyto"]);
    $hsms_validate=htmlspecialchars($udata["sms_validate"]);
    $sms_send=$udata["sms_send"];
    $change_variables=$udata["change_variables"];
}

if ($enter=="yes" && $finished==1) {
    if ($chuser_id) {
        $pupart="";
        if (trim($pass1)) {
            $pupart=",password='$spass1',password_modify=from_days(to_days(now())-24),new_pass_state='redirect'";
        }
        $res=mysql_query("update user set name='$sname',replyto='$sreplyto',email='$semail',
                          sms_send='$sms_send',
                          change_variables='$change_variables',
                          sms_validate='$ssms_validate',
                          username='$semail'$pupart where id='$chuser_id'");
        if ($res) {
            $msg="A felhasználó módosított adatai: $hemail,$hname,$hreplyto id: $chuser_id, sms: $sms_send minden változó állíthatásága: $change_variables $hsms_validate.<br>";
        }
        else {
            $msg="Az felhasználó adatainak módosítása sikertelen: ".mysql_error();
        }
    }
    else {
        $res=mysql_query("insert into user (password,name,username,email,tstamp,replyto,sms_send,change_variables,sms_validate,password_modify)
                          values ('$spass1','$sname','$semail','$semail',now(),'$sreplyto','$sms_send','$change_variables',
                                  '$ssms_validate',from_days(to_days(now())-24))");
        if ($res) {
            $id=mysql_insert_id();
            $msg="Új felhasználó: $hemail,$hname,$hreplyto id: $id, sms: $sms_send minden változó állíthatásága: $change_variables $hsms_validate.<br>";
        }
        else {
            $msg="Az új felhasználó hozzáadása sikertelen: ".mysql_error();
        }
    }
    echo "<tr>
        <td>
        $msg
        <a href='admin_newuser.php'>Új felhasználó hozzáadása</a><br>
        <a href='admin_user.php'>Felhasználók listája</a><br>
        </td>
        </tr>\n";
}
else {
    if ($sms_send=="yes") {
        $sschecked=" checked";
    }
    else {
        $sms_send="no";
        $sschecked="";
    }
    if ($change_variables=="yes") {
        $cvchecked=" checked";
    }
    else {
        $change_variables="no";
        $cvchecked="";
    }
    echo "<form method=post>
        <input type='hidden' name='enter' value='yes'>
        <input type='hidden' name='chuser_id' value='$chuser_id'>
        <tr>
        <td valign='top'><span class='szoveg'>&nbsp;&nbsp;Email cím&nbsp;</span></td>
        <td valign='top'><input class=formframe type='text' name='email' value=\"$hemail\"></td>
        </tr>
        <tr>
        <td valign='top'><span class='szoveg'>&nbsp;&nbsp;Név&nbsp;</span></td>
        <td valign='top'><input class=formframe type='text' name='name' value=\"$hname\"></td>
        </tr>        
        $chuserissue
        <tr>
        <td valign='top'><span class='szoveg'>&nbsp;&nbsp;Jelszó</span></td>
        <td valign='top'><input class=formframe type='password' name='pass1'></td>
        </tr>
        <tr>
        <td valign='top'><span class='szoveg'>&nbsp;&nbsp;Jelszó mégegyszer</span></td>
        <td valign='top'><input class=formframe type='password' name='pass2'></td>
        </tr>
        <tr>
        <td valign='top'><span class='szoveg'>&nbsp;&nbsp;Reply-to cím&nbsp;</span></td>
        <td valign='top'><input class=formframe type='text' name='replyto' value=\"$hreplyto\"></td>
        </tr>        
        <tr>
        <td valign='top'><span class='szoveg'>&nbsp;&nbsp;Küldhet sms-t&nbsp;</span></td>
        <td valign='top'><input type='checkbox'$sschecked name='sms_send' value=\"yes\"></td>
        </tr>        
        <tr>
        <td valign='top'><span class='szoveg'>&nbsp;&nbsp;sms hitelesítő email cím&nbsp;</span></td>
        <td valign='top'><input class=formframe type='text' name='sms_validate' value=\"$hsms_validate\"></td>
        </tr>        
        <tr>
        <td valign='top'><span class='szoveg'>&nbsp;&nbsp;Minden változó állíthatásága:&nbsp;</span></td>
        <td valign='top'><input type='checkbox'$cvchecked name='change_variables' value=\"yes\"></td>
        </tr>        
        <tr>
        <td align=center colspan=2>
        <input type=submit class='tovabbgomb' value='Mehet'>
        </td>
        </tr>		
        </form>\n";
}

echo "</table>\n";
include "footer.php";

function printhead() {

global $_MX_var,$titleissue,$word;

print "
<table border='0' cellpadding='2' cellspacing='0' width='100%'>
<tr>
<td class=formmezo>$word[admin_user] &gt; $titleissue</td>
</tr>
</table>
<table border=0 cellpadding=2 cellspacing=0 width=\"60%\">
";
}
?>
