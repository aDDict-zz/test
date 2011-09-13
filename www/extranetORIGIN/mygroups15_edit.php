<?
include "auth.php";
$sgweare=19;
$weare=19;
if (get_http('id','')) $subweare=191;
else $subweare=192;
include "cookie_auth.php";  
include "common.php";
$language=select_lang();
include "./lang/$language/filter.lang";

$group_id=intval(get_http("group_id",0));
$enter=get_http("enter",0);
$name=get_http("name",0);
$clear_filt_cache=get_http("clear_filt_cache","");
$id=intval(get_http("id",0));
$mres = mysql_query("select title from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator')
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }
$title=$rowg["title"];
     
$res=mysql_query("select id,name,cache_num,query_text,to_days(cache_date) as cddn,to_days(now()) as ndn 
                  from filter where id='$id'");
logger($q,$group_id,"","filter_id=$id","filter");                  
if ($res && mysql_num_rows($res)) {
    $k=mysql_fetch_array($res);
    $what=$word["vf_edit"];
    $new=0;
    $filter_cache_num=$k["cache_num"];
    $filter_cache_diff=($k["cddn"]+$_MX_var->filter_cache_expire)-$k["ndn"]; 
    #so if this is>=0, cached data is not yet expired. See common.php for more.
    $filter_cache_age=$k["ndn"]-$k["cddn"];   
    if ($clear_filt_cache=="yes") { #force recalculate
        $name=$k["name"];
        $filt_query=$k["query_text"];      
        $enter=1;
    }
}
else {
    $what=$word["vf_addnew"];
    $new=1;
    $filter_cache_diff=-1;
}

if ($k["ftype"]=="wizard" && !$force_advanced) {
    header("location: filter_wizard.php?group_id=$group_id&id=$id");
    exit;
}
elseif ($force_advanced) {
	$q="update filter set ftype='advanced' where id='$id'";
    mysql_query($q);
	logger($q,$group_id,"","filter_id=$id","filter");
}
$group_part=" (vip_demog.group_id='$group_id') ";

$iid=0;
if ($enter) {
   if (!ereg("^[a-z][a-z0-9_]*$",$name))
      $error.="$word[vf_vnerror]<br>";
   else {
      $r2=mysql_query("select id from filter where name='$name' and id<>'$id' and group_id='$group_id'");
      if ($r2 && mysql_num_rows($r2))
         $error.="$word[vf_vnxerror]<br>";
   }
  if ($clear_filt_cache!="yes") { #force recalculate
       $filt_query=get_http("filt_query","");
   }
   $query_text=slasher($filt_query,1);
   $filt_query=slasher($filt_query,0);
   if (empty($filt_query)) {
       $error.="$word[vf_empty].<br>";
   }
   if (empty($error)) {
      # if the query text is not changed and the cache is valid use the cache value,
      # delete cache otherwise
      if (!($filter_cache_diff>=0 && $filt_query==$k["query_text"] && $clear_filt_cache!="yes" && $name==$k["name"])) {
          $filter_cache_diff=-1;
          if ($new) {
          	$query="insert into filter (name,query_text,group_id,ftype,tstamp) values
                  ('$name','$query_text','$group_id','advanced',now())";                  
             mysql_query($query);
             $id=mysql_insert_id();
             $iid=$id;
			 logger($query,$group_id,"","","filter");
             //header("Location: mygroups15_edit.php?group_id=$group_id&id=$id"); exit();
          }          
          else {
          	 $q="update filter set name='$name',query_text='$query_text',
                          cache_date='2000-01-01 00:00:00', tstamp=now() where id='$id'";
             mysql_query($q);
			 logger($q,$group_id,"","","filter");                          
		  }
          unset($filtres);
          $filter_error="filter_ok";         
          if ($pp=popen("$_MX_var->filter_engine $id","r")) {
              while ($buff=fgets($pp,25000)) {
                  $filtres.=$buff;
              }
              pclose($pp);
          }
          $filtarr = explode("\n",$filtres);
          if (trim($filtarr[0]) == "filter_ok") {
              $filter_qpart=trim($filtarr[1]);
              $limitord=trim($filtarr[2]);
              $limitnum=trim($filtarr[3]);
              $syntax_error=trim($filtarr[4]);
              $syntax_error_text=trim($filtarr[5]);
          }
          else {
              $filter_error="$word[filt_engine_error]: $filtarr[0]";
          }
          if ($syntax_error==1) {
              $filter_error="$word[filt_syntax_error]: $syntax_error_text";
          }      
          if ($filter_error != "filter_ok")
             $error.="$filter_error<br>";
          else {
              $qq="select count(*) from users_$title where validated='yes' and robinson='no' 
                   and ($filter_qpart)";
              // echo nl2br(htmlspecialchars("select count(*) from users_$title where validated='yes' and robinson='no' and ($filter_qpart)"))." -- $limitord -- $limitnum";
              $res=mysql_query($qq);
              if ($res && mysql_num_rows($res)) 
                  $users=mysql_result($res,0,0);
              else
                  $users=0;
              if (!empty($limitord) && $users>$limitnum) 
                  $users=$limitnum;      
              mysql_query("update filter set cache_num='$users',cache_date=now() where id='$id'");
              $error.="$word[total_of] $users $word[satisfies].<br>";
          }
      }
   }
   $k["name"]=$name;
   $k["query_text"]=$filt_query;      
}

if ($filter_cache_diff>=0)
    $error.="$word[total_of] $filter_cache_num $word[satisfies].<br>
             [$word[from_cache] ($filter_cache_age $word[days_old])</span> 
             <a href=mygroups15_edit.php?group_id=$group_id&id=$id&clear_filt_cache=yes>
             $word[cache_refresh]</a><span class='szovegvastag'>]<br>";

$save_k_=$k;

include "menugen.php";

$k=$save_k_;
$filt_query=$k["query_text"];

if (!empty($error)) {
    $error="<br>$error";
}

$rs=mysql_query("select id,name from demog_group where group_id='$group_id' order by name");
if ($rs && mysql_num_rows($rs)) {
    while ($ks=mysql_fetch_array($rs)) {
        $dgroups.="<a href='#' onclick='set_ff($ks[id])'>".htmlspecialchars($ks["name"])."</a> ";
    }
}

echo "
<script language='JavaScript'>
NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
ver4 = (NS4 || IE4) ? 1 : 0;
function storeCaret (textEl) {
if (textEl.createTextRange) 
 textEl.caretPos = document.selection.createRange().duplicate();
}
function addBody (text) {
textEl=document.filtform.filt_query;
//alert (textEl);
if (IE4) {
if (textEl.createTextRange && textEl.caretPos) {
 var caretPos = textEl.caretPos;
 caretPos.text =
   caretPos.text.charAt(caretPos.text.length - 1) == ' ' ?
     text + ' ' : text;
}
else
 textEl.value+=text;
  textEl.focus();
} else {
  textEl.value+=text;
  textEl.focus();
}
}
function set_ff(fflt) {
   fframe.location='mygroups15_edit_frame.php?group_id=$group_id&vgparam='+fflt; 
}
//-->
</script>

<TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
  <TR>
    <TD class=MENUBORDER width='100%'>
      <TABLE cellSpacing=1 cellPadding=1 width='100%' border=0>
<tr>
<td colspan='2' class=bgkiemelt2 valign='top' align='left'><span class='szovegvastag'>
$what$error</span></td>
</tr>      
<form method=post name='filtform' action='mygroups15_edit.php'>
<input type='hidden' name='enter' value='1'>
<input type='hidden' name='group_id' value='$group_id'>
<input type='hidden' name='id' value='".(!empty($iid)?$iid:$id)."'>
<tr><td class='bggray' width='33%'><span class='szoveg'>$word[vf_name]:&nbsp;<input style='width:200px;' type='text' name='name' value=".htmlspecialchars($k["name"])."></span></td>
<td class='bggray' width='67%' align='right'><span class='szoveg'>$dgroups<a href='#' onclick='set_ff(0)'>$word[ft_common]</a> <a href='#' onclick='set_ff(-1)'>$word[ft_special]</a></span></td></tr>
<TR><TD colspan='2' class=BACKCOLOR ALIGN='left'><iframe src='mygroups15_edit_frame.php?group_id=$group_id&vgparam=0' width='963' height='150' MARGINWIDTH=0 MARGINHEIGHT=0 HSPACE=0 VSPACE=0 FRAMEBORDER=0 SCROLLING=yes name='fframe' id='fframe'></iframe></td></tr>
<TR>
<TD colspan='2' class='bggray' ALIGN='left'>
<SPAN CLASS='szoveg'><input type='button'  name='left_par' value=' ( ' onClick=\"addBody(' (')\"><input type='button'  name='right_par' value=' ) ' onClick=\"addBody(') ')\"><input type='button'  name='and' value=' $word[vf_and] ' onClick=\"addBody(' and ')\"><input type='button'  name='or' value=' $word[vf_or] ' onClick=\"addBody(' or ')\"><input type='button'  name='and' value=' $word[vf_not] ' onClick=\"addBody(' not ')\"><input type='button'  name='less' value=' < ' onClick=\"addBody(' < ')\"><input type='button'  name='greater' value=' > ' onClick=\"addBody(' > ')\"><input type='button'  name='equal' value=' = ' onClick=\"addBody(' = ')\"><input type='button'  name='like' value=' $word[vf_like] ' onClick=\"addBody(' like ')\"><BR>
<TEXTAREA name='filt_query' wrap=virtual ONSELECT=\"storeCaret(this);\" ONCLICK=\"storeCaret(this);\" ONKEYUP=\"storeCaret(this);\" style='width:963px;height:150px;'>$filt_query</TEXTAREA>
</SPAN>
</TD>
</TR>
<tr>
<td colspan='2' class=BACKCOLOR valign='top' align='center'><span class='szoveg'>
<input type='submit' name='submit' value='$word[submit3]'>&nbsp;<input type='button' name='visszag' value='$word[vf_back]' onclick=\"location='mygroups15.php?group_id=$group_id'\">
</span></td>
</tr>
</form>
</table>
</td>
</tr>
</table>
<script>
hei=parseInt(document.body.clientHeight)-310;
//alert(hei);
if (hei<50) { hei=50; }
var br=document.getElementById('fframe'); br.style.height=hei+'px';
</script>
\n";

include "footer.php";
?>
