<?


#--------------------------------------
#-- MAIN ------------------------------
#--------------------------------------
function admin_main() {

global $_MX_var,$group_id,$error,$user_id,$active_membership,$email,$name,$membership,$word;
global $_MX_var,$hemail,$hname,$hreplyto,$replyto,$_MX_superadmin,$user_password,$_MX_rights;
    if ($user_id) {
        $res=mysql_query("select membership,email,trusted_affiliate,password,replyto,name,change_variables,sms_send,sms_validate
                          from user,members where
                          user.id=members.user_id
                          and group_id='$group_id' and user_id='$user_id'");
	    logger($query,$group_id,"","user_id=$user_id","users,members");                          
        if ($res && mysql_num_rows($res)) {
            $membership=mysql_result($res,0,0);
            $hemail=htmlspecialchars(mysql_result($res,0,1));
            $trusted_affiliate=mysql_result($res,0,2);
            $hpassword=htmlspecialchars(mysql_result($res,0,3));
            $hreplyto=htmlspecialchars(mysql_result($res,0,4));
            $hname=htmlspecialchars(mysql_result($res,0,5));
            $change_variables=mysql_result($res,0,6);
            $sms_send=mysql_result($res,0,7);
            $hsms_validate=htmlspecialchars(mysql_result($res,0,8));
        }
    }
    if ($trusted_affiliate=="yes") {
        $tac="checked";
    }
    else {
        $trusted_affiliate="no";
        $tac="";
    }
    if ($change_variables=="yes") {
        $cvc="checked";
    }
    else {
        $change_variables="no";
        $cvc="";
    }
    if ($sms_send=="yes") {
        $sss="checked";
    }
    else {
        $sms_send="no";
        $sss="";
    }
    if ($_REQUEST["membership"]!=null) 
		$sel[$_REQUEST["membership"]]="selected";
	else
        $sel[$membership]="selected";

    $plusstat="";
    $plusset="";
    $parms="group_id=$group_id";
    if ($membership=="client" && mx_can_i_modify_membership($membership)) {
        $plusset="<a href='#' onClick='window.open(\"client_stats.php?$parms&user_id=$user_id\", \"clnp$user_id\", \"width=500,height=500,scrollbars=yes,resizable=yes\"); return false;'>$word[client_pages]</a>&nbsp;";
    }
    if ($membership=="admin" && mx_can_i_modify_membership($membership)) {
        $plusset="<a href='#' onClick='window.open(\"moderator_pages.php?$parms&user_id=$user_id\", \"moderp$user_id\", \"width=500,height=500,scrollbars=yes,resizable=yes\"); return false;'>$word[mod_pages]</a>&nbsp;";
    }
    foreach ($_MX_rights as $right) {
        if (mx_can_i_modify_membership($right)) {  // allithatok-e be ilyen jogosultsagot
            $plusstat .= "<option $sel[$right] value='$right'>$word[$right]</option>\n";
        }
    }
    echo "<table width=400 cellpadding=0 cellspacing=0 border=0>
            <form method=post>
            <input type='hidden' name='action' value='enter'>
            <input type='hidden' name='user_id' value='$user_id'>
            <input type='hidden' name='group_id' value='$group_id'>
            <tr>
            <td colspan='2'><span class='adminwarn'>$error&nbsp;</span></td>
            </tr>
            <tr>
            <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[t_kind]&nbsp;</span></td>
            <td valign='top'><select name=membership>
            <option value=''> - - - </option>
            $plusstat
            </select>&nbsp;$plusset<br>
            <input type='checkbox' $tac name='trusted_affiliate' value='yes'>&nbsp;$word[trusted_affiliate]</td>
            </tr>\n";

    if ($user_id) {
        $modify_data=mx_can_i_modify_data($membership,$user_id);
        if ($modify_data) {
            // ha meg nincs jelszava a felhasznalonak, kerni kell (ez akkor lehetseges ha sender volt es ezert meg nem volt jelszava)
            echo "<tr>
                <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[t_email]</span></td>
                <td valign='top'><input class=formframe type='text' name='email' value=\"$hemail\"></td>
                </tr>\n";
            echo "<tr>
                <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[t_name]</span></td>
                <td valign='top'><input class=formframe type='text' name='name' value=\"$hname\"></td>
                </tr>\n";
            if (empty($hpassword)) {
                echo "<tr>
                    <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[password]</span></td>
                    <td valign='top'><input class=formframe type='password' name='pass1'></td>
                    </tr>\n";
                echo "<tr>
                    <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[password_again]</span></td>
                    <td valign='top'><input class=formframe type='password' name='pass2'></td>
                    </tr>\n";
            }
            elseif ($membership=="client" || $membership=="affiliate") {
                echo "<tr>
                    <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[password]</span></td>
                    <td valign='top'><input class=formframe type='text' name='pass1' value=\"$hpassword\"></td>
                    </tr>\n";
            }
            echo "<tr>
                <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[replyto]</span></td>
                <td valign='top'><input class=formframe type='text' name='replyto' value=\"$hreplyto\"></td>
                </tr>\n";
            if ($membership=="moderator") {
                echo "<tr>
                    <td valign='top' colspan=2><span class='szoveg'>&nbsp;&nbsp;<input type='checkbox' $sss name='sms_send' value='yes'>&nbsp;Küldhet sms-t</span></td>
                    </tr>\n";
                echo "<tr>
                    <td valign='top'><span class='szoveg'>&nbsp;&nbsp;sms hitelesítő email cím</span></td>
                    <td valign='top'><input class=formframe type='text' name='sms_validate' value=\"$hsms_validate\"></td>
                    </tr>\n";
                echo "<tr>
                    <td valign='top' colspan=2><span class='szoveg'>&nbsp;&nbsp;<input type='checkbox' $cvc name='change_variables' value='yes'>&nbsp;Minden változó állíthatásága</span></td>
                    </tr>\n";
            }
        }
        else {
            echo "<tr>
                <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[t_email]&nbsp;</span></td>
                <td valign='top'><span class='szovegvastag'>$hemail</span></td>
                </tr>\n";
        }
    }
    else {
        echo "<tr>
            <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[t_email]&nbsp;</span></td>
            <td valign='top'><input class=formframe type='text' name='email' value=\"$hemail\"></td>
            </tr>
            <tr>
            <td colspan='2'><span class='szoveg'>$word[explain]</span></td>
            </tr>
            <tr>
            <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[t_name]&nbsp;</span></td>
            <td valign='top'><input class=formframe type='text' name='name' value=\"$hname\"></td>
            </tr>        
            <tr>
            <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[password]</span></td>
            <td valign='top'><input class=formframe type='password' name='pass1'></td>
            </tr>
            <tr>
            <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[password_again]</span></td>
            <td valign='top'><input class=formframe type='password' name='pass2'></td>
            </tr>
            <tr>
            <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[replyto]</span></td>
            <td valign='top'><input class=formframe name='replyto' value=\"$hreplyto\"></td>
            </tr>
            ";
    }

    echo "<tr>
        <td align=center colspan=2>
        <input type=submit class='tovabbgomb' value='$word[go]'>
        </td>
        </tr>		
        </form>
        </table>\n";

}

#--------------------------------------
#-- ENTER -------------------------------
#--------------------------------------
function admin_enter() {

global $_MX_var,$group_id,$error,$user_id,$active_membership,$email,$name,$membership,$pass1,$pass2,$word,$trusted_affiliate;
global $_MX_var,$hemail,$hname,$replyto,$hreplyto,$_MX_superadmin,$user_superadmin,$_MX_rights,$change_variables,$sms_send,$sms_validate;

    $hemail=slasher($email,-1);
    $hname=slasher($name,-1);
    $email=slasher($email);
    $name=slasher($name);
    $membership=slasher($membership);
    $pass1=slasher(trim($pass1));
    $pass2=slasher($pass2);
    $hreplyto=slasher($replyto,-1);
    $replyto=slasher($replyto);
    $sms_validate=slasher($sms_validate);

    $old_membership="";
    $res=mysql_query("select membership from members where user_id='$user_id' and group_id='$group_id'");
    logger($query,$group_id,"","user_id=$user_id","members");
    if ($res && mysql_num_rows($res))
        $old_membership=mysql_result($res,0,0);
    $old_password="";
    $res=mysql_query("select password,email from user where id='$user_id'");
    if ($res && mysql_num_rows($res)) {
        $old_password=mysql_result($res,0,0);
        $old_email=mysql_result($res,0,1);
    }
    $modify_data=mx_can_i_modify_data($old_membership,$user_id);

    if ($trusted_affiliate!="yes" && $trusted_affiliate!="no")
        $trusted_affiliate="no";

    if (!in_array($membership,$_MX_rights)) {
        $error=$word["right_type_error"];
    }
    elseif (mx_can_i_modify_membership($membership)) {  // allithatok-e be ilyen jogosultsagot
        if ($user_id) {
            $sets=array();
            if (mx_can_i_modify_membership($old_membership)) {  // megvaltoztathatom-e az eredetit
                if ($modify_data) {
                    // ha meg nincs jelszava a felhasznalonak, kerni kell (ez akkor lehetseges ha sender volt es ezert meg nem volt jelszava)
                    // egyebkent csak a client es affiliate usereknel kertuk.
                    if ($old_membership=="client" || $old_membership=="affiliate" || empty($old_password)) {
                        if (empty($pass1) && $membership!="sender") {
                            $error.="$word[pass_error]<br>"; 
                        }
                        elseif (empty($old_password) && $pass1 != $pass2 && $membership!="sender") {
                            $error.="$word[pass_eq_error]<br>";
                        }
                        else {
                            $sets[]="password='$pass1'";
                            if ($old_password!=$pass1) {
                                $sets[]="password_modify=from_days(to_days(now())-24)";
                            }
                        }
                    }
                    if (!empty($replyto)) {
                        if (!eregi("^[\+\._a-z0-9-]+@([0-9a-z][0-9a-z-]*\.)+[a-z]{2,4}$", $replyto)) {
                            $error.="$word[replyto_error] $hreplyto<br>"; 
                        }
                        else {
                            $sets[]="replyto='$replyto'";
                        }
                    }
                    if (!empty($name)) {
                        $sets[]="name='$name'";
                    }
                    if (!empty($email) && $email!=$old_email) {
                        if (!eregi("^[\+\._a-z0-9-]+@([0-9a-z][0-9a-z-]*\.)+[a-z]{2,4}$", $email)) {
                            $emailcherror.="$word[email_error] $hemail<br>"; 
                        }
                        $r5=mysql_query("select id,password from user where email='$email'");
                        if ($r5 && mysql_num_rows($r5)) {
                            $emailcherror.="Már létezik ilyen email cím.<br>";
                        }
                        if (empty($emailcherror)) {
                            $sets[]="email='$email'";
                        }
                        else {
                            $error.=$emailcherror;
                        }
                    }
                    if ($membership==$old_membership && $membership=="moderator") {
                        if ($change_variables!="yes") {
                            $change_variables="no";
                        }
                        $sets[]="change_variables='$change_variables'";
                        if ($sms_send!="yes") {
                            $sms_send="no";
                        }
                        $sets[]="sms_send='$sms_send'";
                        $sets[]="sms_validate='$sms_validate'";
                    }
                }
                if (empty($error)) {
                    if (count($sets)) {
                        $q="update user set " . implode(",",$sets) . " where id='$user_id'";
                        logger($q,$group_id,"","user_id=$user_id","user");
                        mysql_query($q);
                    }
                    if ($old_membership!=$membership) {
                        // if the membership is changed, there is no need for perm tables, these should be cleaned up.
                        $query="delete from stattype_user where user_id='$user_id' and group_id='$group_id'";
                        mysql_query($query);
                        logger($query,$group_id,"","user_id=$user_id","stattype");                        
                        $query="delete from page_user where user_id='$user_id' and group_id='$group_id'";
                        mysql_query($query);
                        logger($query,$group_id,"","user_id=$user_id","page_user");
                    }
                    $q="update members set membership='$membership',tstamp=now(),trusted_affiliate='$trusted_affiliate'
                        where user_id='$user_id' and group_id='$group_id'";
                    mysql_query($q);
                    logger($q,$group_id,"","user_id=$user_id","members");
                }
            }                     
        }
        else {
            $r5=mysql_query("select id,password from user where email='$email'");
            if ($r5 && mysql_num_rows($r5)) {
                $newid=mysql_result($r5,0,0);
                $oldpass=mysql_result($r5,0,1);
                if(!trim($oldpass)) {
                    if (!trim($pass1) && $membership!="sender") {
                        $error.="$word[pass_error]<br>"; 
                    }
                    if ($pass1 != $pass2 && $membership!="sender") {
                        $error.="$word[pass_eq_error]<br>";
                    }
                    if (empty($error)) {
                    	$q="update user set password='$pass1',replyto='$replyto' where id='$newid'";
                        mysql_query($q);
	                    logger($q,$group_id,"","user_id=$user_id","user");                        
                    }
                }
                $r6=mysql_query("select * from members where group_id='$group_id' and user_id='$newid'");
                if (!($r6 && mysql_num_rows($r6))) {
                    if (empty($error)) {
                    	$query="insert into members (user_id,group_id,membership,create_date,modify_date,tstamp,trusted_affiliate) values ('$newid','$group_id','$membership',now(),now(), now(),'$trusted_affiliate')";
                        mysql_query($query);
	                    logger($query,$group_id,"","","members");                                     
                    }
                }
            }
            else {
                if (!eregi("^[\+\._a-z0-9-]+@([0-9a-z][0-9a-z-]*\.)+[a-z]{2,4}$", $email)) 
                    $error.="$word[email_error] $hemail<br>"; 
                if (!empty($replyto) && !eregi("^[\+\._a-z0-9-]+@([0-9a-z][0-9a-z-]*\.)+[a-z]{2,4}$", $replyto)) 
                    $error.="$word[replyto_error] $hreplyto<br>"; 
                if(!trim($pass1) && $membership!="sender") 
                    $error.="$word[pass_error]<br>"; 
                if($pass1 != $pass2 && $membership!="sender") 
                    $error.="$word[pass_eq_error]<br>";
                if(!trim($name)) 
                    $error.="$word[name_error]<br>";
                if (empty($error)) {
                    $res=mysql_query("insert into user (password,name,username,email,tstamp,replyto,password_modify)
                                      values ('$pass1','$name','$email','$email',now(),'$replyto',from_days(to_days(now())-24))");
                    if ($res) {
                        $newid=mysql_insert_id();
                        $query="insert into members 
                                     (user_id,group_id,membership,create_date,modify_date,tstamp,trusted_affiliate)
                                     values ('$newid','$group_id','$membership',now(),now(),now(),'$trusted_affiliate')";
                        mysql_query($query);
	                    logger($query,$group_id,"","","members");                                     
                    }                
                }
            }
        }
    }
    if (empty($error)) {
        echo "
         <script>
          window.close();
          window.opener.location.reload();
         </script>
        ";
        exit;
    }
    else {
        admin_main();
    }
}

#--------------------------------------
#-- MAIN ------------------------------
#--------------------------------------

include "auth.php";
include "decode.php";
$weare=5;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/moderators.lang";

$membership = get_http("membership", "");
$trusted_affiliate= get_http("trusted_affiliate", "");
$action = get_http("action", "");
$group_id = get_http("group_id", 0);
$user_id = get_http("user_id", 0);
$email = get_http("email", "");
$replyto = get_http("replyto", "");
$name = get_http("name", "");
$sms_send = get_http("sms_send", "");
$pass1= get_http("pass1", "");
$pass2= get_http("pass2", "");
$change_variables= get_http("change_variables", "");


$_MX_popup = 1;
include "menugen.php";


$user_id=intval($user_id);
$group_id=intval($group_id);
$multiid=intval($multiid);
if ($_MX_superadmin) {
    $mres = mysql_query("select title from groups where id='$group_id'");
    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else {
        header("Location: $_MX_var->baseUrl/index.php"); exit; }
    $title=$rowg["title"];
    $active_membership="";
}
else {
  $mres = mysql_query("select title,membership from groups,members where groups.id=members.group_id
                       and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                       and user_id='$active_userid'");
  if ($mres && mysql_num_rows($mres))
      $rowg=mysql_fetch_array($mres);  
  else
      exit; 
  $title=$rowg["title"];
  $active_membership=$rowg["membership"];
}

if (!$action)
  $action="main";

eval("admin_$action();");

echo "</body>
    </html>\n";

?>
