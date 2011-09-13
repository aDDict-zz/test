<?
  include "auth.php";
  include "cookie_auth.php";  
  include "common.php";
  $group_id = slasher($_GET["group_id"]);
  $mres = mysql_query("select title,num_of_mess,membership from groups,members 
                       where groups.id=members.group_id and groups.id='$group_id' 
                       and (membership='owner' or membership='moderator' or membership='admin'
                            or membership='client' or membership='affiliate' or membership='support')
                            and user_id='$active_userid'");
  if ($mres && mysql_num_rows($mres))
     $rowg=mysql_fetch_array($mres);  
  else {
     header("Location: index.php"); exit; }
  
  $active_membership=$rowg["membership"];
  $title=$rowg["title"];
  
  $weare=28;
  include "menugen.php";
include "footer.php";

$r2 = mysql_query("select count(*) from users_$title where validated='yes' and robinson='no' and bounced='no'");
if ($r2 && mysql_num_rows($r2))
    $rmem=mysql_result($r2,0,0);


exit;

  include "./lang/$language/mygroups_main.lang";
  
  $clicknum=0;
  $sum_mails=0;
  
  if ($active_membership=="client") {
      $cres=mysql_query("select count(*) from messages,message_client where 
                         messages.id=message_client.message_id and messages.group_id='$group_id'
                         and message_client.user_id='$active_userid' and messages.test!='yes'");
      if ($cres && mysql_num_rows($cres)) 
          $num_of_mess=mysql_result($cres,0,0);
      $res=mysql_query("select sum(mails) from messages,track,message_client 
                        where messages.id=track.message_id 
                        and track.message_id=message_client.message_id
                        and message_client.user_id='$active_userid' and messages.group_id='$group_id'
                        and messages.test!='yes'");
      if ($res && mysql_num_rows($res)) 
          $sum_mails=mysql_result($res,0,0);
      $res=mysql_query("select count(distinct trackf.user_id,feedback.message_id) 
                        from message_client,feedback,trackf
                        where message_client.message_id=feedback.message_id and feedback.id=trackf.feed_id
                        and message_client.user_id='$active_userid' and feedback.group_id='$group_id'");
      if ($res && mysql_num_rows($res)) 
          $clicknum=mysql_result($res,0,0);
      if ($sum_mails)
          $percent=$clicknum==0?"":"&nbsp;".number_format($clicknum/$sum_mails*100,2)."%";  
      else
          $percent="&nbsp;";
  }
  else {
      $num_of_mess=$rowg["num_of_mess"];
      $res=mysql_query("select sum(mails) from messages,track where 
                        messages.id=track.message_id and messages.test!='yes' and
                        messages.group_id='$group_id'");
      if ($res && mysql_num_rows($res)) 
          $sum_mails=mysql_result($res,0,0);
      $res=mysql_query("select count(distinct user_id,message_id) from feedback,trackf where 
                        feedback.id=trackf.feed_id and feedback.group_id='$group_id'");
      if ($res && mysql_num_rows($res)) 
          $clicknum=mysql_result($res,0,0);
      if ($sum_mails)
          $percent=$clicknum==0?"":"&nbsp;".number_format($clicknum/$sum_mails*100,2)."%";  
      else
          $percent="&nbsp;";
      $res=mysql_query("select count(*) from users_$title where robinson='yes' and validated='yes'");
      if ($res && mysql_num_rows($res)) 
          $robinson=mysql_result($res,0,0);
      $res=mysql_query("select count(*) from users_$title where bounced='yes' and validated='yes'");
      if ($res && mysql_num_rows($res)) 
          $bounced=mysql_result($res,0,0);
      /*$res=mysql_query("select count(*) from users_$title where validated='no'");
      if ($res && mysql_num_rows($res)) 
          $not_validated=mysql_result($res,0,0);*/
      # number of unsuccesfull validations now (2002-03) must be redefined. An unsuccessful
      # subscribe, coming from trusted affiliate, may be important, too, even if user is
      # already subscribed. This, and fact that with new subscribe system there will be no 
      # more users with flag validated='no' are the reason why not to use users_*.validated.
      # It is important to see that now we are calculating 'unsuccessful subscribes' rather
      # than 'not validated members'. The another important thing is that 'unsuccessful subscribes'
      # should not be unique (by user), if the same user has 2 (or more) unsuccessful subscribes,
      # both subscribes may carry important data!
      $unsuccesful_subscribes=0;
      $res=mysql_query("select count(*) from validation where action='sub' and validated='no'
                        and group_id='$group_id'");
      if ($res && mysql_num_rows($res))
          $unsuccesful_subscribes+=mysql_result($res,0,0);
      # the following query is really disgusting, but multivalidation.groups coloumn was
      # obviously not designed for such query. If there will be problems with speed the
      # subscribe scripts should be slightly changed.
      $res=mysql_query("select count(*) from multivalidation where action='sub' and validated='no'
                        and (concat(' ',groups,' ') regexp '[[:space:]]$title"."[[:space:]|\\|]' 
                        or concat(',',groups,',') like '%,$group_id,%')");
      if ($res && mysql_num_rows($res))
          $unsuccesful_subscribes+=mysql_result($res,0,0);
      
      $res=mysql_query("select count(*) from users_$title where robinson='no' and bounced='no'
                        and validated='yes' and aff='$active_userid'");
      if ($res && mysql_num_rows($res)) 
          $aff_rmem=mysql_result($res,0,0);
      $res=mysql_query("select count(*) from users_$title where robinson='yes'
                        and validated='yes' and aff='$active_userid'");
      if ($res && mysql_num_rows($res)) 
          $aff_robinson=mysql_result($res,0,0);
      $res=mysql_query("select count(distinct user_id,message_id) from feedback,trackf,users_$title where 
                        feedback.id=trackf.feed_id and feedback.group_id='$group_id'
                        and trackf.user_id=users_$title.id and users_$title.aff='$active_userid'");
      if ($res && mysql_num_rows($res)) 
          $aff_clicknum=mysql_result($res,0,0);
      if ($aff_sum_mails)
          $aff_percent=$aff_clicknum==0?"":"&nbsp;".number_format($aff_clicknum/$aff_sum_mails*100,2)."%";  
      else
          $aff_percent="&nbsp;";
  }

print "
<table width=65% border=0 cellspacing=1 cellpadding=0 align='center'>
  <tr>
    <td colspan=3 width='100%'>
      <table width=100% border=0 cellspacing=0 cellpadding=2>
        <tr>
          <td class=formmezo width='100%'>&nbsp;&nbsp;$rowg[title] $word[group_lower]</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width=100% align=left class=bgvilagos2>
    <span class='szoveg'>\n";
if ($active_membership=="client")
    print "
    $word[num_of_memb]: $rmem<br>
    $word[num_of_mess]: $num_of_mess<br>
    $word[mails_total]: $sum_mails<br>
    $word[ct]: $percent<br>\n";
if ($active_membership!="affiliate" && $active_membership!="client")
    print "
    <b>$word[whole_group]</b><br>
    $word[num_of_memb]: $rmem<br>
    $word[unsuccessful_subscribes]: $unsuccesful_subscribes<br>
    $word[bounced]: $bounced<br>
    $word[unsubbed]: $robinson<br>
    $word[num_of_mess]: $num_of_mess<br>
    $word[mails_total]: $sum_mails<br>
    $word[ct]: $percent<br>\n";
if ($active_membership=="affiliate")
    print "
    <br><b>$word[affs]</b><br>
    $word[num_of_memb]: $aff_rmem<br>
    $word[unsubbed]: $aff_robinson<br>\n";
    //$word[not_validated]: $aff_not_validated<br>
print "
    </span>
    </td>
  </tr>
</table>
";	  
  
?>
