<?
include "auth.php";
$weare=4;
include "decode.php";
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/members_demog_info.lang";  
include "./lang/$language/members.lang";
include "_filter.php";

$_MX_popup = 1;
include "menugen.php";

  $user_id = get_http('user_id','');
  $user_id=intval($user_id);
  $filt_demog = get_http('filt_demog','');
  $filt_demog=intval($filt_demog);
  $group_id=intval($group_id);
  $multiid=intval($multiid);
  $mres = mysql_query("select groups.id,title,num_of_mess,unique_col from groups,members where groups.id=members.group_id
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
 
  $scope="";

  $ru=mysql_query("select * from users_$title where id='$user_id'");
  $rf=mysql_query("select * from filter where id='$filt_demog' and group_id='$group_id'");
  if ($ru && mysql_num_rows($ru)) {
      $user_data=mysql_fetch_array($ru);
      $scope="user";
  }
  elseif ($rf && mysql_num_rows($rf)) {
      $filter_data=mysql_fetch_array($rf);
      $scope="filter";
  }
  else {
      print "$word[no_such_user]";
      exit;
  }

  if ($scope == "user") {
      echo "<span class='szovegvastag'>$word[scope_user]:</span><span class='szoveg'> " . $user_data["ui_$rowg[unique_col]"] . "<br><br></span>";
      $ra=mysql_query("select * from user where id='$user_data[aff]'");
      if ($ra && mysql_num_rows($ra)) {
          $z=mysql_fetch_array($ra);
          echo "<span class='szovegvastag'>$word[affiliate]:</span><span class='szoveg'> $z[email] ($z[name])<br><br></span>";
      }
      echo "<span class='szovegvastag'>$word[tstamp]:</span><span class='szoveg'> $user_data[data_changed]<br><br></span>";
  }
  if ($scope == "filter") {
      $_MX_filter = new MxFilter($rowg,1);
      $_MX_filter->GetParams();
      $_MX_filter->params["show_user_list"] = "no";
      $_MX_filter->GetSql();
      if (empty($_MX_filter->update_query)) {
        exit;
      }
      echo "<span class='szovegvastag'>$word[scope_filter]:</span><span class='szoveg'>$filter_data[name]<br><br>$_MX_filter->stat_text<br><br></span>";
  }

  echo "
        <TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <TR>
        <TD>
        <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
        <tr>
        <td class='bgkiemelt2' align=left width='43%'><span class=szovegvastag>$word[dt_variable]</span></td>
        <td class='bgkiemelt2' align=left width='43%'><span class=szovegvastag>$word[dt_value]</span></td>
        <td class='bgkiemelt2' align=left width='14%'><span class=szovegvastag>&nbsp;</span></td>
        </tr>
        ";
  $index=0;
  $rq=mysql_query("select demog.question,demog.variable_type,demog.variable_name,demog.id 
                   from demog,vip_demog where demog.id=vip_demog.demog_id and $vipinfo order by demog.question");
  if ($rq && mysql_num_rows($rq)) {
     while ($res=mysql_fetch_array($rq)) {
        if ($index%2)
           $bgrnd="class=bgvilagos2";
        else
           $bgrnd="bgcolor=white";
        $index++;
        $chdemog="";
        echo "<tr><td $bgrnd><span class='szoveg'>$res[question]: </span></td><td $bgrnd>";
        $dvals[]=$res["id"];
        if ($vch["$res[id]"]=="yes") 
            $chdemog="&nbsp;<a href='members_demog_info_change.php?group_id=$group_id&demog_id=$res[id]&user_id=$user_id&filt_demog=$filt_demog'>$word[change]</a>";
        $value="";
        if ($scope == "user") {
            if ($res["variable_type"]=="enum" || $res["variable_type"]=="matrix") {
               $enumvalues=explode(",",$user_data["ui_$res[variable_name]"]);
               while (list($key,$val)=each($enumvalues)) {
                   if ($res["variable_type"]=="enum") { 
                       $option_id=intval($val);
                       $options=$option_id; 
                   } 
                   else {
                       $msplit=explode("m",$val);
                       if (count($msplit)) {
                        $option_id=intval($msplit[1]);
                        $subvar_id=intval($msplit[0]);
                        $options="$option_id,$subvar_id"; 
                       }
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
            else {
               $value=$user_data["ui_$res[variable_name]"];
            }
            $value = nl2br(htmlspecialchars($value));
        }
        elseif ($scope == "filter") {
            $value = "<a target='_blank' href='mygroups14.php?group_id=$group_id&filt_demog=$filt_demog&demog_id=$res[id]'>$word[md_statistics]</a>";
        }
		echo "<span class=szoveg>$value</td><td $bgrnd>&nbsp;";
        if ($res["variable_type"] != "matrix" && !($scope!="user" && $res["variable_name"]==$rowg["unique_col"])) {
		    echo $chdemog;
        }
		echo "</span></td></tr>\n";
	 }
  }
  
?>
</table>
</td>
</tr>
</table>
</body>
</html>
