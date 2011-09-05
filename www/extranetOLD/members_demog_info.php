<?
include "auth.php";
$weare=4;
include "decode.php";
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/members_demog_info.lang";  


$_MX_popup = 1;
include "menugen.php";


  $user_id = get_http('user_id','');
  $user_id=intval($user_id);
  $group_id=intval($group_id);
  $multiid=intval($multiid);
  $mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                       and groups.id='$group_id' and 
                       (membership='owner' or membership='moderator' or membership='support' $admin_addq)
                       and user_id='$active_userid'");
  if ($mres && mysql_num_rows($mres))
      $rowg=mysql_fetch_array($mres);  
  else
      exit; 
  $title=$rowg["title"];

  $vipinfo=" vip_demog.group_id='$group_id' ";

  $rc=mysql_query("select demog_id,changeable from vip_demog where $vipinfo");
  if ($rc && mysql_num_rows($rc)) {
    while ($k=mysql_fetch_array($rc)) {
       $vch["$k[demog_id]"]="yes";
    }
  }
  $dvals=array();
 
  $ru=mysql_query("select * from users_$title where id='$user_id'");
  logger($query,$group_id,"","user_id=$user_id","users_");
  if ($ru && mysql_num_rows($ru)) 
      $ku=mysql_fetch_array($ru);
  else {
      print "$word[no_such_user]";
      exit;
  }

  $ra=mysql_query("select * from user where id='$ku[aff]'");
  if ($ra && mysql_num_rows($ra)) {
    $z=mysql_fetch_array($ra);
    echo "<span class='szovegvastag'>$word[affiliate]:</span><span class='szoveg'> $z[email] ($z[name])<br><br></span>";
  }
  echo "<span class='szovegvastag'>$word[tstamp]:</span><span class='szoveg'> $ku[tstamp]<br><br></span>";
  
  $rq=mysql_query("select demog.question,demog.variable_type,demog.variable_name,demog.id 
                   from demog,vip_demog where demog.id=vip_demog.demog_id and $vipinfo order by vip_demog.ordernum,demog.id");
  if ($rq && mysql_num_rows($rq)) {
     while ($res=mysql_fetch_array($rq)) {
        $chdemog="";
        echo "<span class='szovegvastag'>$res[question]: </span>";
        $dvals[]=$res["id"];
        if ($vch["$res[id]"]=="yes"){
            if(isset($_GET["users"]))
              $massDemog = "&users={$_GET['users']}";
            $chdemog="&nbsp;<a href='members_demog_info_change.php?group_id=$group_id{$massDemog}&demog_id=$res[id]&user_id=$user_id'>$word[change]</a>";
        }
        $value="";
	    if ($res["variable_type"]=="enum" || $res["variable_type"]=="matrix") {
           $enumvalues=explode(",",$ku["ui_$res[variable_name]"]);
           while (list($key,$val)=each($enumvalues)) {
               if ($res["variable_type"]=="enum") { 
                   $option_id=intval($val);
                   $options=$option_id; 
               } 
               else {
                   $msplit=explode("m",$val);
                   $option_id=intval($msplit[1]);
                   $subvar_id=intval($msplit[0]);
                   $options="$option_id,$subvar_id"; 
               }  
               if ($option_id) {
		           $r2=mysql_query("select enum_option,id from demog_enumvals where id in ($options)");
		           if ($r2 && mysql_num_rows($r2)) {
                       if (!empty($value)) {
                           $value.="; ";
                       }
                       if ($res["variable_type"]=="enum") { 
		                   $value.=mysql_result($r2,0,0);
                       } 
                       elseif (mysql_num_rows($r2)==2) {
                           if (mysql_result($r2,0,1)==$option_id) {
                               $value.=mysql_result($r2,1,0)."=".mysql_result($r2,0,0);
                           } 
                           else {
                               $value.=mysql_result($r2,0,0)."=".mysql_result($r2,1,0);
                           } 
                       } 
                   }
               }
           }
        }
		else
		   $value=$ku["ui_$res[variable_name]"];
		echo "<span class=szoveg>".nl2br(htmlspecialchars($value))."$chdemog<br></span>\n";
	 }
  }
  
$emptyvals="";
if (is_array($vch) && is_array($dvals)) {
    reset($vch);
    while (list($key,$val)=each($vch)) {
        $found=0;
        reset($dvals);
        while (list($dvkey,$dvvals)=each($dvals)) {
            if ($key==$dvvals) {
                $found=1;
                break;
            }
        }
        if (!$found) {
            if (!empty($emptyvals))
                $emptyvals.=",";
            $emptyvals.=$key;
        }
    }
} 

if (!empty($emptyvals)) {
  $rq=mysql_query("select * from demog where demog.id in ($emptyvals) order by demog.id");
  if ($rq && mysql_num_rows($rq)) {
     while ($res=mysql_fetch_array($rq)) {
        $chdemog="";
	    echo "<span class='szovegvastag'>$res[question]: </span>";
        if ($vch["$res[id]"]=="yes") 
            $chdemog="&nbsp;<a href='members_demog_info_change.php?group_id=$group_id&demog_id=$res[id]&user_id=$user_id'>$word[change]</a>";
		echo "<span class=szoveg>$chdemog<br></span>";
	 }
  }
}

?>
</body>
</html>
