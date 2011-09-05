<?
#--------------------------------------
#-- MAIN ------------------------------
#--------------------------------------
function admin_main() {

global $_MX_var,$group_id,$error,$user_id,$active_membership,$email,$name,$membership,$word;
global $_MX_var,$admindata,$admin_pages;

    $admin_pages=array();
    $r=mysql_query("select page_id from page_user where user_id='$user_id' and group_id='$group_id'");
    if ($r && mysql_num_rows($r)) {
        while ($k=mysql_fetch_array($r)) {
            $admin_pages[]=$k["page_id"];
        }
    }
    $hemail=htmlspecialchars($admindata["email"]);

    echo "<table width=100% cellpadding=0 cellspacing=0 border=0>
            <tr>
            <td valign='top'><span class='szovegvastag'>$hemail $word[mod_pages_title]</span></td>
            </tr>
            <tr>
            <td valign='top'><span class='szoveg'>$word[mod_pages_desc]</span></td>
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
    $res=mysql_query("select p.id as pid,pg.id as pgid,p.readonly from page p,pagegroup pg where pg.id=p.pagegroup 
                      and p.test='no' and p.type='protected' and p.multi='no'
                      order by pg.sortorder,p.sortorder");
    if ($res && mysql_num_rows($res)) {
        $prev_pgid=-1;
        while ($k=mysql_fetch_array($res)) {
            $warnsa=$warnsb="";
            if ($k["readonly"]=="no") {
                $warnsa="<span class='adminwarn'>";
                $warnsb="</span>";
            }
            if ($k["pgid"]!=$prev_pgid) {
                print "<tr>
                <td valign='top' colspan='2' bgcolor='#eeeeee'><span class='szovegvastag'>&nbsp;".$word["menu_main$k[pgid]"]."&nbsp;</span></td>
                </tr>\n";
            }
            in_array($k["pid"],$admin_pages)?$checked="checked":$checked="";
            print "<tr>
                <td valign='top' width='75%'><span class='szoveg'>$warnsa&nbsp;".$word["menu_$k[pid]"]."&nbsp;$warnsb</span></td>
                <td valign='top' width='25%'><input class=formframe type='checkbox' name='setapage[$k[pid]]' value=1 $checked></td>
                </tr>\n";
            $prev_pgid=$k["pgid"];
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
    
    if (!isset($setapage)) {
        $setapage = $_POST["setapage"];
        foreach ($setapage as $k=>$v) {
            $setapage[$k] = slasher($v);
        }
    }

    $res=mysql_query("select p.id as pid from page p");
    if ($res && mysql_num_rows($res)) {
        while ($k=mysql_fetch_array($res)) {
            if (isset($setapage["$k[pid]"])) {
//print "isset$k[pid]***";
                $r=mysql_query("select * from page_user where page_id='$k[pid]' and user_id='$user_id' and group_id='$group_id'");
                if (!($r && mysql_num_rows($r))) {
                    mysql_query("insert into page_user (page_id,user_id,group_id) values ('$k[pid]','$user_id','$group_id')");
//print "insert$k[pid]***";
                }
            }
            else {
                mysql_query("delete from page_user where page_id='$k[pid]' and user_id='$user_id' and group_id='$group_id'");
//print "delete$k[pid]***";
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

include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/moderators.lang";
include "./lang/$language/menugen.lang";

if (!isset($user_id)) { $user_id = get_http("user_id",0); }
if (!isset($group_id)) { $group_id = get_http("group_id",0); }
if (!isset($multiid)) { $multiid = get_http("multiid",0); }
if (!isset($action)) { $action = get_http("action",'main'); }


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
                       and groups.id='$group_id' and (membership='owner' or membership='moderator')
                       and user_id='$active_userid'");
  if ($mres && mysql_num_rows($mres))
      $rowg=mysql_fetch_array($mres);  
  else
      exit; 
}

  $mres = mysql_query("select title,membership from groups,members where groups.id=members.group_id
                       and groups.id='$group_id' and membership='admin' and user_id='$user_id'");
  if ($mres && mysql_num_rows($mres))
      $admindata=mysql_fetch_array($mres);  
  else
      exit; 

  $mres=mysql_query("select * from user where id='$user_id'");
  if ($mres && mysql_num_rows($mres))
      $admindata=mysql_fetch_array($mres);  
  else
      exit; 

  $title=$rowg["title"];
  $active_membership=$rowg["membership"];

if (!$action)
  $action="main";

eval("admin_$action();");

echo "</body>
    </html>\n";

?>
