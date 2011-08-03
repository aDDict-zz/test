<?
#--------------------------------------
#-- MAIN ------------------------------
#--------------------------------------
function mess_main() {

global $_MX_var,$group_id,$error,$id,$word;

    $rcl=mysql_query("select user.id from user,message_client where
                      user.id=message_client.user_id and message_client.message_id='$id'");
    if ($rcl && mysql_num_rows($rcl)) 
        while ($kc=mysql_fetch_array($rcl))
            $clientlist["$kc[id]"]=1;

    echo "<table width=300 cellpadding=0 cellspacing=0 border=0>
          <form method=post>
          <input type='hidden' name='action' value='enter'>
          <input type='hidden' name='id' value='$id'>
          <input type='hidden' name='group_id' value='$group_id'>
          <tr>
          <td><span class='szoveg'>$error&nbsp;</span></td>
          </tr>
          <tr>
          <td valign='top'>\n";
    $res=mysql_query("select subject from messages where id='$id'");
    if ($res && mysql_num_rows($res)) {
        $subject=mysql_result($res,0,0);
        if (strstr('UTF',$string)) $string = utf8_decode($string);
        echo "<span class='szoveg'>$word[cl_title1] \"$subject\"$word[cl_title2]:</span>";
    }
    $res=mysql_query("select user.id,user.email,user.name from user,members 
                      where user.id=members.user_id and group_id='$group_id'
                      and membership='client'");          
    if ($res && mysql_num_rows($res)) { 
        while ($k=mysql_fetch_array($res)) {
            if ($clientlist["$k[id]"])
                $checked="checked";
            else
                $checked="";
            echo "<br>
            <input type='checkbox' name='client[$k[id]]' value='1' $checked>
            <span class='szoveg'>$k[email] ($k[name])</span>\n";        
        }
    }
    else
        echo "<br><span class='szoveg'>$word[no_clients]</span>";
            
    echo "</td>
          </tr>
          <tr>
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
function mess_enter() {

global $_MX_var,$group_id,$error,$id,$client;

$client=$_POST["client"];
mysql_query("delete from message_client where message_id='$id'");
if (is_array($client))
    while (list($client_id,$val)=each($client)) {
        $client_id=intval($client_id);
        if ($client_id)
            mysql_query("insert into message_client (user_id,message_id,group_id,tstamp)
                         values ('$client_id','$id','$group_id',now())");
    }

if (empty($error)) {
   echo "
     <script>
      window.close();
      //window.opener.location.reload();
     </script>
   ";
   exit;
}
else
  admin_main();
}

#--------------------------------------
#-- MAIN ------------------------------
#--------------------------------------

include "auth.php";
include "decode.php";
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/threadlist.lang";


$_MX_popup = 1;
include "menugen.php";


  $group_id=intval($group_id);
  $multiid=intval($multiid);
  $mres = mysql_query("select title,membership from groups,members where groups.id=members.group_id
                       and groups.id='$group_id' and (membership='owner' or membership='moderator')
                       and user_id='$active_userid'");
  if ($mres && mysql_num_rows($mres))
      $rowg=mysql_fetch_array($mres);  
  else
      exit; 
  $title=$rowg["title"];

$action=get_http('action','main');
if (!$action)
  $action="main";

eval("mess_$action();");

?>

    </body>
</html>
