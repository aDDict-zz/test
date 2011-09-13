<?
include "auth.php";
include "cookie_auth.php";

$gr_sortm=get_cookie('gr_sortm');
$sortm=get_http('sortm','');
if (empty($sortm) && !empty($gr_sortm))
    $sortm=$gr_sortm;
setcookie("gr_sortm",$sortm,time()+30*24*3600);    

$weare=100;
include "menugen.php";
include "./lang/$language/index.lang";
 
$multiid=intval(get_http("multiid",0));
$delmulti=intval(get_http("delmulti",0));

$res=mysql_query("select m.id from multi m inner join multi_members mm where m.id='$delmulti' and m.id=mm.group_id and mm.user_id='$active_userid' and mm.membership='moderator'");
if ($res && mysql_num_rows($res)) {
    $query="delete from multi where id='$delmulti'";
    mysql_query($query);
    logger($query,0,"","delmulti=".$delmulti,"multi");        
    $query="delete from multigroup where multiid='$delmulti'";
    mysql_query($query);
    logger($query,0,"","delmulti=".$delmulti,"multi");        	    
    $query="delete from multi_members where group_id='$delmulti'";
    mysql_query($query);    
}

$addmulti=get_http('addmulti','');
if (!empty($addmulti)) {
    $multi_error="";
    $newtitle=get_http('newtitle','');
    if(!eregi("^[a-z]+[0-9a-z]*$", $newtitle)) 
        $multi_error=$word["multi_title_error"];  
    else {
        $newtitle=strtolower($newtitle);
        $r = mysql_query("SELECT title FROM groups WHERE title='$newtitle'");
        if ($r && mysql_num_rows($r))
            $multi_error=$word["multi_exists_error"];
        $r = mysql_query("SELECT title FROM multi WHERE title='$newtitle'");
        if ($r && mysql_num_rows($r))
            $multi_error=$word["multi_exists_error"];
    }
    if (empty($multi_error)) {
       include "index_multigroup_defaults.php";
       $query="insert into multi (title,create_date,modify_date,owner_id,
                    custom_head,custom_foot,subscribe_subject,subscribe_body,
                    welcome_subject,welcome_message,landing2,landingpage2,
                    validator_page,already_subs,tstamp) 
                    values ('$newtitle',now(),now(),'$active_userid',
                    '$custom_head','$custom_foot','$subscribe_subject','$subscribe_body',
                    '$welcome_subject','$welcome_message','$landing2','$landingpage2',
                    '$validator_page','$already_subs',now())"; 
		mysql_query($query);
        $multiid=mysql_insert_id();
        $subscribe_id = md5("${newtitle}ihaj$multiid");
        mysql_query("update multi set subscribe_id='$subscribe_id' where id='$multiid'");
        $query="insert into multi_members (user_id, group_id, membership, create_date, modify_date, tstamp) 
                    values ($active_userid,$multiid,'moderator',now(),now(),now())";
		mysql_query($query);        
    }
    unset($addmulti);
}
  
if(!$sortm)
    $sortm=1;
switch ($sortm) {
    case 1: $order = "order by title"; break;
    case 2: $order = "order by title desc"; break;
}

$i=array();
$multi_groups="";
$i_multi=0;

$sql="select m.* from multi m inner join multi_members mm where m.id=mm.group_id and mm.user_id='$active_userid' and mm.membership='moderator' $order";
$res = mysql_query($sql);
if($res && mysql_num_rows($res)) {
    while($row=mysql_fetch_array($res)) {
        $multiid=$row["id"];
        $i_multi++;
        if ($i_multi%2)
            $bgrnd="class=bgvilagos2";
        else
            $bgrnd="bgcolor=white";
        $multinum=0;
        $query="select count(*) from multigroup where multiid='$multiid'";
        $cres=mysql_query($query);
        if ($cres && mysql_num_rows($cres)) {
            $k=mysql_fetch_row($cres);
            $multinum=$k[0];
        }
        if (empty($kedvencek) || $row["kedvenc"]=="yes") {
            $multi_groups.="<tr><TD $bgrnd><SPAN class=szovegvastag>&nbsp;$row[title]</SPAN></TD>
                            <td colspan=2 $bgrnd VAlign=center align=middle><span class='szoveg'>$multinum</span></td>
                            <td $bgrnd VAlign=center align=middle>
                            [<a onClick=\"return(confirm('$word[multi_delete_confirm]'))\" href='$_MX_var->baseUrl/multi.php?delmulti=$multiid'>$word[delete]</a>][<a href='$_MX_var->baseUrl/alapadatok_multi.php?multiid=$multiid'>$word[select_group]</a>]</td></tr>\n";
        }
  }
  logger($query,0,"","multiid=$multiid","multigroup");  
}
######################## PRINT

printhead();
PrintNavigation($word["multi_groups"],1);  
echo "<tr>
  <td class=bgkiemelt2>&nbsp;</td>
  <td class=bgkiemelt2 align=center colspan=2>$word[multi_count]</td>
  <td class=bgkiemelt2>&nbsp;</td>        
  </tr>
  $multi_groups
  <form method='post'>
  <tr>
  <td class=bgkiemelt2>&nbsp;</td>
  <td class=bgkiemelt2 align=right colspan=2><span class='szoveg'>
  $word[multi_add]:&nbsp;&nbsp;<input type='text' name='newtitle' class='szoveg'></span></td>
  <td class=bgkiemelt2 align=center><input type='submit' name='addmulti' value='$word[submit3]' class='szoveg'></td>
  </tr>
  </form>\n";
PrintSubFoot();     
  
printfoot();
include "footer.php";

#######################################################
###                  FUNCTIONS               ###
#######################################################

function printhead() {
    global $_MX_var,$multi_error,$cache_stat_text,$word,$set_kedvencek;

    if (!empty($multi_error))
        echo "<br>
              <TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
              <TBODY>
              <TR>
              <TD>
              <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=#ffffff border=0>
              <tr><td><span class='szovegvastag'>$multi_error</span></td></tr>
              </table></td></tr></table>\n";
    echo "<TABLE cellSpacing=0 cellPadding=1 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
          <TBODY>
          <TR>
          <TD>
          <TABLE cellSpacing=1 cellPadding=0 width=\"100%\" bgColor=$_MX_var->main_table_border_color border=0>
          <TBODY>\n";
}

function printfoot() {
    echo "</table>
          </td>
          </tr>
          </table>\n";
}

function PrintNavigation($print_text,$nosubhead=0) {
    global $_MX_var,$sortm,$word;
  
    for ($i=0;$i<10;$i++) {
        $sel_sort[$i] = "";
    }
    $sel_sort[$sortm] = "selected";
    echo "<tr>
          <td colspan=4 bgcolor=$_MX_var->main_table_border_color>
          <table border=0 cellspacing=0 cellpadding=0 width=100% bgcolor=$_MX_var->main_table_border_color>
          <TR>
          <FORM name=inputs method=post>
          <td align=left class=formmezo colspan=2>&nbsp;$print_text</td> 
          <TD noWrap align=right class=formmezo colspan=2>$word[sort_order]&nbsp;
          <select onChange='JavaScript: this.form.submit();' name=sortm>
          <option value=1 $sel_sort[1]>$word[by_groupname_asc]</option>
          <option value=2 $sel_sort[2]>$word[by_groupname_desc]</option>
          </select>
          </span>
          </td>
          </form>
          </tr>
          </table>
          </td>
          </tr>\n";
    if (!$nosubhead)
        PrintSubHead();
}

function PrintEmptyNavigation($print_text,$nosubhead=0) {
    
    echo "<tr>
          <td colspan=4 bgcolor=$_MX_var->main_table_border_color>
          <table border=0 cellspacing=5 cellpadding=0 width=100% bgcolor=$_MX_var->main_table_border_color>
          <TR>
          <td align=left class=formmezo colspan=2>&nbsp;$print_text</td> 
          <TD noWrap align=right class=formmezo colspan=2>&nbsp;
          </td>
          </tr>
          </table>
          </td>
          </tr>\n";
    if (!$nosubhead)
        PrintSubHead();
}

function PrintSubHead() {
    global $_MX_var,$word;

    echo "<TR>
          <TD class=bgkiemelt2 vAlign=center align=middle> &nbsp;$word[groups]&nbsp;</TD>
          <TD class=bgkiemelt2 vAlign=top align=middle colspan=2> &nbsp;$word[messages]&nbsp;</TD>
          <td class=bgkiemelt2 align=center>&nbsp;$word[actions]&nbsp;
          </TR>\n";    
}

function PrintSubFoot() {

    echo "<tr>
          <td colspan=4 bgcolor=white>&nbsp;</td>
          </tr>\n";
}
?>
