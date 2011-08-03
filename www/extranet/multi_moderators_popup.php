<?
#--------------------------------------
#-- MAIN ------------------------------
#--------------------------------------
function admin_main() {

global $_MX_var,$multiid,$error,$user_id,$active_membership,$email,$name,$membership,$word;
global $_MX_var,$hemail,$hname,$hreplyto,$replyto,$_MX_superadmin,$user_password,$_MX_rights;



         echo "<table width=400 cellpadding=0 cellspacing=0 border=0>
            <form method=post>
            <input type='hidden' name='action' value='enter'>
            <input type='hidden' name='user_id' value='$user_id'>
            <input type='hidden' name='multiid' value='$multiid'>
            <tr>
            <td colspan='2'><span class='adminwarn'>$error&nbsp;</span></td>
            </tr>\n";


        echo "<tr>
            <td valign='top'><span class='szoveg'>&nbsp;&nbsp;$word[t_email]&nbsp;</span></td>
            <td valign='top'><input class=formframe type='text' name='email' value=\"$hemail\"></td>
            </tr>";

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

global $_MX_var,$multiid,$error,$user_id,$active_membership,$email,$name,$membership,$pass1,$pass2,$word,$trusted_affiliate;
global $_MX_var,$hemail,$hname,$replyto,$hreplyto,$_MX_superadmin,$user_superadmin,$_MX_rights,$change_variables,$sms_send,$sms_validate;

    $hemail=slasher($email,-1);
    $email=slasher($email);


        $r5=mysql_query("select id,password from user where email='$email'");
            if ($r5 && mysql_num_rows($r5)) {
                $newid=mysql_result($r5,0,0);
                $r6=mysql_query("select * from multi_members where group_id='$multiid' and user_id='$newid'");
                if (!($r6 && mysql_num_rows($r6))) {
                    if (empty($error)) {
                    	$query="insert into multi_members (user_id,group_id,membership,create_date,modify_date,tstamp,trusted_affiliate) values ('$newid','$multiid','moderator',now(),now(), now(),'')";
                        mysql_query($query);
	                    logger($query,$multiid,"","","members");                                     
                    }
                }
            }
            else {
                    $error.="$word[no_email_error]<br>";
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


$_MX_popup = 1;
include "menugen.php";


$user_id=intval(get_http("user_id",""));
$multiid=intval(get_http("multiid",""));
$multiid=intval(get_http("multiid",""));
if ($_MX_superadmin) {
    $mres = mysql_query("select title from multi where id='$multiid'");
    if ($mres && mysql_num_rows($mres))
        $rowg=mysql_fetch_array($mres);  
    else {
        header("Location: $_MX_var->baseUrl/index.php"); exit; }
    $title=$rowg["title"];
    $active_membership="";
}
else {
    $mres = mysql_query("select multi.* from multi,multi_members where multi.id=multi_members.group_id
                         and multi.id='$multiid' and user_id='$active_userid' 
                         and membership='moderator'");
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
