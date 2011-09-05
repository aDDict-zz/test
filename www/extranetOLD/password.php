<?




$nored=1;
include "auth.php";
include "cookie_auth.php";  
include "common.php";
include "_chpass.php";
$language=select_lang();
include "./lang/$language/password.lang";

$enter = get_http("enter","");
$pw1 = get_http("pw1","");
$pw2 = get_http("pw2","");
$name = get_http("name","");
$email = get_http("email","");
$oldpw = get_http("oldpw","");

$res=mysql_query("select * from user where id='$active_userid'");
if ($res && mysql_num_rows($res)) {
    $olddata=mysql_fetch_array($res);
}
else {
    exit;
}

$othererror="";
$error="";
if ($_MX_superadmin && $enter=="atad") {
    $super_id=intval($_POST["super_id"]);
    if (in_array($super_id,$_MX_var->possible_superadmin)) {
        mysql_query("update user set superadmin='no' where id='$active_userid'");
        mysql_query("update user set superadmin='yes' where id='$super_id'");
        header("Location: $_MX_var->baseUrl/index.php");
        exit;
    }
    else {
        $error="<font color='red'>$word[superadmin_notchanged]</font>";
    }
    $res=mysql_query("select * from user where id in (" . implode(",",$_MX_var->possible_superadmin) . ") and id!='$active_userid'");
    while ($k=mysql_fetch_array($res)) {
        $superoptions.="<option value='$k[id]'>" . htmlspecialchars("$k[name] ($k[email])") . "</option>";
    }
}
elseif ($enter!="yes" && $enter!="other" && $enter!="atad") {
    $error="$word[enter_pass].";
}
elseif ($enter=="yes") {
    if (empty($pw1))
        $error="<font color='red'>$word[passerr_empty].</font>";
    if ($pw1!=$pw2)
        $error="<font color='red'>$word[passerr_same].</font>";
    if (empty($error)) {
        $_MX_check= new MxPassword($active_userid,$olddata["email"],$pw1);
        $is_accepted=$_MX_check->Check();
        if ($is_accepted!="accepted") {
            $error="<font color='red'>$is_accepted</font>";
        }
    }
    if (empty($error)) {
        $res=mysql_query("select password from user where id='$active_userid'");
        if ($res && mysql_num_rows($res)) {
            $oldpwdb=mysql_result($res,0,0);
            if ($oldpwdb!=$oldpw)
                $error="<font color='red'>$word[passerr_old].</font>";
            elseif ($oldpwdb==$pw1)
                $error="<font color='red'>$word[passerr_sameoldnew].</font>";
            else {
                mysql_query("update user set password='" . mysql_escape_string($pw1) . "',password_modify=now() where id='$active_userid'");
                $error="<font color='green'>$word[pass_changed].</font>";                
                $_MX_check->TrackLast();
                mysql_query("update user set new_pass_state='ok' where id='$active_userid'");                        
                header("Location: $_MX_var->baseUrl/index.php");                  
            }
        }
    }
}
elseif ($enter=="other") {
    $sname=mysql_escape_string($name);
    $semail=mysql_escape_string($email);
    $email=trim($email);
    $othererror="";
    if (empty($name)) {
        $othererror.="<font color='red'>$word[nameerr_empty].</font>";
    }
    $chemail="";
    if ($olddata["email"]!=$email) {
        if (empty($email)) {
            $othererror.="<font color='red'>Rossz email cím</font>";
        }
        elseif (!eregi("^[\+\._a-z0-9-]+@([0-9a-z][0-9a-z-]*\.)+[a-z]{2,4}$", $email)) {
            $othererror.="<font color='red'>Rossz email cím</font>";
        }
        $r5=mysql_query("select id,password from user where email='$semail'");
        if ($r5) {
            if (mysql_num_rows($r5)) {
                $othererror.="<font color='red'>Rossz email cím</font>";
            }
        }
        else {
            exit;
        }
        if (empty($othererror)) {
            $chemail="email='$semail',";
        }
    }
    if (empty($othererror)) {
        mysql_query("update user set ${chemail}name='$sname' where id='$active_userid'");
    }
}

$weare=36;
include "menugen.php";

$res=mysql_query("select * from user where id='$active_userid'");
if ($res && mysql_num_rows($res)) {
    $k=mysql_fetch_array($res);
}

if (!empty($_MX_force_pass_change)) {
    print "<p><b>A továbblépéshez kérjük változtassa meg jelszavát.</b></p><p>&nbsp;</p>";
}

print "<table border='0' cellpadding='0' cellspacing='1' width='100%'>
        <tr>
        <td colspan='2' valign='top' class=formmezo width='100%'>
        <table border='0' cellpadding='2' cellspacing='0'>
        <tr>
        <td class=formmezo>$word[own_data]</td>
        </tr>
        </table>
        </td>
        </tr>
        <form method='post' action='password.php'>
        <input type='hidden' name='enter' value='yes'>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='multiid' value='$multiid'>
        <TR BGCOLOR='#ffffff' ALIGN='left' VALIGN='bottom'> 
        <td align='left' class='bgvilagos2' colspan='2'>
        <span class='szoveg'>
        &nbsp;<B>$word[pass_change]</B>&nbsp;-&nbsp;$error
        </span>
        </td>
        </tr>    
        <TR BGCOLOR='#FFFFFF' ALIGN='right' VALIGN='bottom'> 
        <td align='left' class='bgvilagos2' width=30%>
        <span class='szoveg'>
        $word[oldpass]:&nbsp; 
        </span>
        </td>
        <td align='left' class='bgvilagos2' width=70%>
        <span class='szoveg'>
        &nbsp;<input type='password' name='oldpw'> 
        </span>
        </td>
        </tr>    
        <TR BGCOLOR='#FFFFFF' ALIGN='right' VALIGN='bottom'> 
        <td align='left' class='bgvilagos2' width=30%>
        <span class='szoveg'>
        $word[newpass]:&nbsp; 
        </span>
        </td>
        <td align='left' class='bgvilagos2' width=70%>
        <span class='szoveg'>
        &nbsp;<input type='password' name='pw1'> 
        </span>
        </td>
        </tr>    
        <TR BGCOLOR='#FFFFFF' ALIGN='right' VALIGN='bottom'> 
        <td align='left' class='bgvilagos2' width=30%>
        <span class='szoveg'>
        $word[newpass_again]:&nbsp; 
        </span>
        </td>
        <td align='left' class='bgvilagos2' width=70%>
        <span class='szoveg'>
        &nbsp;<input type='password' name='pw2'> 
        </span>
        </td>
        </tr>    
        <TR BGCOLOR='#FFFFFF' ALIGN='left' VALIGN='bottom'> 
        <td colspan='2' align='center'>
        <input type='submit' name='submit' value='$word[submit3]' class='tovabbgomb'>
        </td>
        </tr>        
        </form>";

if (empty($_MX_force_pass_change)) {
        print "
        <form method='post' action='password.php'>
        <input type='hidden' name='enter' value='other'>
        <TR BGCOLOR='#ffffff' ALIGN='left' VALIGN='bottom'> 
        <td align='left' class='bgvilagos2' colspan='2'>
        <span class='szoveg'>
        &nbsp;<B>$word[other_data]</B>&nbsp;$othererror
        </span>
        </td>
        </tr>    
        <TR BGCOLOR='#FFFFFF' ALIGN='right' VALIGN='bottom'> 
        <td align='left' class='bgvilagos2' width=30%>
        <span class='szoveg'>
        email cím&nbsp; 
        </span>
        </td>
        <td align='left' class='bgvilagos2' width=70%>
        <span class='szoveg'>
        &nbsp;<input type='text' name='email' value='$k[email]' size='40'> 
        </span>
        </td>
        </tr>    
        <TR BGCOLOR='#FFFFFF' ALIGN='right' VALIGN='bottom'> 
        <td align='left' class='bgvilagos2' width=30%>
        <span class='szoveg'>
        $word[name]&nbsp; 
        </span>
        </td>
        <td align='left' class='bgvilagos2' width=70%>
        <span class='szoveg'>
        &nbsp;<input type='text' name='name' value='". htmlspecialchars($k["name"]) . "' size='40'> 
        </span>
        </td>
        </tr>    
        <TR BGCOLOR='#FFFFFF' ALIGN='left' VALIGN='bottom'> 
        <td colspan='2' align='center'>
        <input type='submit' name='submit' value='$word[submit3]' class='tovabbgomb'>
        </td>
        </tr>        
        </form>\n";
}
if ($_MX_superadmin) {
        $superoptions="";
        $res=mysql_query("select * from user where id in (" . implode(",",$_MX_var->possible_superadmin) . ") and id!='$active_userid'");
        while ($k=mysql_fetch_array($res)) {
            $superoptions.="<option value='$k[id]'>" . htmlspecialchars("$k[name] ($k[email])") . "</option>";
        }

        print " <script>
        function mx_supersure() {
            if (!document.superform.super_id.selectedIndex) {
                alert('$word[nosuper]');
                return;
            }
            if (confirm('$word[supersure]')) {
                document.superform.submit(); 
            }
        }
        </script>
        <form method='post' name='superform' action='password.php'> 
        <input type='hidden' name='enter' value='atad'>
        <TR BGCOLOR='#ffffff' ALIGN='left' VALIGN='bottom'> 
        <td align='left' class='bgvilagos2' colspan='2'>
        <span class='szoveg'>
        &nbsp;<B>$word[super_atad]</B>&nbsp;$ataderror
        </span>
        </td>
        </tr>    
        <TR BGCOLOR='#FFFFFF' ALIGN='right' VALIGN='bottom'> 
        <td align='left' class='bgvilagos2' width=30%>
        <span class='szoveg'>
        $word[atad_user]&nbsp; 
        </span>
        </td>
        <td align='left' class='bgvilagos2' width=70%>
        <span class='szoveg'>
        &nbsp;<select name='super_id'><option value='0'> - - - </option>$superoptions</select> 
        </span>
        </td>
        </tr>    
        <TR BGCOLOR='#FFFFFF' ALIGN='left' VALIGN='bottom'> 
        <td colspan='2' align='center'>
        <input type='button' name='sbt' value='$word[submit3]' class='tovabbgomb' onclick='mx_supersure();'>
        </td>
        </tr>        
        </form>\n";
}
print"        </table>\n";

include "footer.php";

?>
