<?
#--------------------------------------
#-- MAIN ------------------------------
#--------------------------------------
function admin_main() {

global $_MX_var,$group_id,$error,$user_id,$active_membership,$email,$name,$membership,$word;
global $_MX_var,$admindata,$admin_pages;

    $client_stattypes=array();
    $admin_pages=array();
    $r=mysql_query("select stattype_id from stattype_user where user_id='$user_id' and group_id='$group_id'");
    if ($r && mysql_num_rows($r)) {
        while ($k=mysql_fetch_array($r)) {
            $client_stattypes[]=$k["stattype_id"];
        }
    }
    $hemail=htmlspecialchars($admindata["email"]);

    echo "<table width=100% cellpadding=0 cellspacing=0 border=0>
            <tr>
            <td valign='top'><span class='szovegvastag'>$hemail $word[client_pages_title]</span></td>
            </tr>
            <tr>
            <td valign='top'><span class='szoveg'>$word[client_pages_desc]</span></td>
            </tr>
          </table>
          <table width=100% cellpadding=0 cellspacing=0 border=0>
            <form method=post>
            <input type='hidden' name='action' value='enter'>
            <input type='hidden' name='user_id' value='$user_id'>
            <input type='hidden' name='group_id' value='$group_id'>
            <tr>
            <td valign='top' width='75%'><span class='szoveg'>&nbsp;$word[t_page]&nbsp;</span></td>
            <td valign='top' width='25%'><span class='szoveg'>&nbsp;</span></td>
            </tr>\n";
    $res=mysql_query("select * from stattype order by sortorder");
    if ($res && mysql_num_rows($res)) {
        $prev_pgid=-1;
        while ($k=mysql_fetch_array($res)) {
            in_array($k["id"],$client_stattypes)?$checked="checked":$checked="";
            $k["parent_id"]?$sstuff="&nbsp;&nbsp;&nbsp;":$sstuff="";
            $k["parent_id"]?$bgc="":$bgc=" bgcolor='#eeeeee'";
            print "<tr>
                <td valign='top' width='75%' $bgc><span class='szoveg'>$sstuff".$word["stattype$k[id]"]."&nbsp;</span></td>
                <td valign='top' width='25%' $bgc><input class=formframe type='checkbox' name='setapage[$k[id]]' value=1 $checked></td>
                </tr>\n";
        }
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
#-- ENTER -----------------------------
#--------------------------------------
function admin_enter() {

global $_MX_var,$group_id,$error,$user_id,$active_membership,$email,$name,$membership,$word;
global $_MX_var,$admindata,$admin_pages,$setapage;

    $setapage=$_POST["setapage"];
    $res=mysql_query("select * from stattype");
    if ($res && mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            if (isset($setapage["$k[id]"])) {
                $r=mysql_query("select * from stattype_user where stattype_id='$k[id]' and user_id='$user_id' and group_id='$group_id'");
                if (!($r && mysql_num_rows($r))) {
                    mysql_query("insert into stattype_user (stattype_id,user_id,group_id) values ('$k[id]','$user_id','$group_id')");
                }
            }
            else {
                mysql_query("delete from stattype_user where stattype_id='$k[id]' and user_id='$user_id' and group_id='$group_id'");
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
else
  admin_main();
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
include "./lang/$language/menugen.lang";


$_MX_popup = 1;
include "menugen.php";


$user_id=intval(get_http("user_id",0));
$group_id=intval(get_http("group_id",0));
$multiid=intval(get_http("multiid",0));
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
}

  $mres = mysql_query("select title,membership from groups,members where groups.id=members.group_id
                       and groups.id='$group_id' and membership='client' and user_id='$user_id'");
  if ($mres && mysql_num_rows($mres))
      $clientdata=mysql_fetch_array($mres);  
  else
      exit; 

  $mres=mysql_query("select * from user where id='$user_id'");
  if ($mres && mysql_num_rows($mres))
      $admindata=mysql_fetch_array($mres);  
  else
      exit; 

  $title=$rowg["title"];
  $active_membership=$rowg["membership"];

$action=get_http("action","");

if (!$action)
  $action="main";

eval("admin_$action();");

echo "</body>
    </html>\n";

?>
