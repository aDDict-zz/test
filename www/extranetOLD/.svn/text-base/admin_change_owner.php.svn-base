<?
$_MX_superadmin=0;
include "auth.php";
include "cookie_auth.php";  
if (!$_MX_superadmin) {
    exit;
}

$group_id=intval($group_id);
$res=mysql_query("select groups.title,user.email from groups,user 
                  where groups.owner_id=user.id
                  and groups.id='$group_id'");
if ($res && mysql_num_rows($res)) {
    $title=mysql_result($res,0,0);
    $oldemail=mysql_result($res,0,1);
}
else
    exit;

$error="";
$newemail=addslashes($newemail);
if ($newemail) {
    $res=mysql_query("select id from user where email='$newemail'");
    if ($res && mysql_num_rows($res)) {
        $owner_id=mysql_result($res,0,0);
    }
    else {
        $error.="Nem létezik user $newemail email címmel";
    }
    if ($oldemail==$newemail)
        $error.="$newemail már most is a(z) $title csoport tulajdonosa";
    if (empty($error)) {
        mysql_query("update members set membership='moderator',tstamp=now(),modify_date=now() where 
                     group_id='$group_id' and membership='owner'");
        $res=mysql_query("select id from members where user_id='$owner_id' 
                          and group_id='$group_id' and membership!='owner'");
        if ($res && mysql_num_rows($res)) {
            $mid=mysql_result($res,0,0);
            mysql_query("update members set membership='owner',tstamp=now(),modify_date=now() where id='$mid'");
        }
        else {
            mysql_query("insert into members 
                         (user_id,group_id,membership,create_date,modify_date,tstamp)
                         values 
                         ('$owner_id','$group_id','owner',now(),now(),now())");
        }
        mysql_query("update groups set owner_id='$owner_id',tstamp=now() where id='$group_id'");
        echo "
          <script>
           window.close();
           window.opener.location.reload();
          </script>
        ";
        exit;            
    }
}

$_MX_popup = 1;
include "menugen.php";

echo "<table>
      <td class=bgvilagos2 valign='top'><span class=LITTLE>$error&nbsp;</span></td>
      </tr>      
      <form>
      <input type='hidden' name='group_id' value='$group_id'>
      <tr>    
      <td class=bgvilagos2 valign='top'><span class=LITTLE>A(z) $title csoport eddigi tulajdonosa:&nbsp;$oldemail</span></td>
      </tr>      
      <tr>    
      <td class=bgvilagos2 valign='top'><span class=LITTLE>Az új tulajdonos:&nbsp;
      <input type='text' name='newemail'><br>[az eddigi tulajdonost moderátorrá fokozza le]</span></td>
      </tr>
      <tr>    
      <td class=bgvilagos2 valign='top'><span class=LITTLE>
      <input type='submit' name='submit' value='ok'></span></td>
      </tr>
      </form>
      </table>
      </body>
      </html>\n";
?>
