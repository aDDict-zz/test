<?
//if($active_membership == "affiliate") {
  //include "threadlist_aff_cache.php";
  //die();
//}

mysql_query("set names 'utf8'");

$weare=20;
include "menugen.php";
include "./lang/$language/threadlist.lang";

if (!$sortt)
    $sortt=2;

switch($sortt) {
    case 1: $order = "order by track.date asc"; $order2 = "order by create_date asc"; break;
    case 2: $order = "order by track.date desc"; $order2 = "order by create_date desc"; break;
}

// select count(distinct userid),outid from tracko,users_permission where tracko.outid in (27542,27568) and users_permission.aff=80121 and tracko.userid=users_permission.id group by outid;
       
/*$messids=array();
$aq="select track.message_id,count(distinct tracko.userid)
     from track,tracko,users_$title where
     track.id=tracko.outid and tracko.userid=users_$title.id
     and track.group_id='$group_id' and users_$title.aff='$active_userid' 
     group by track.message_id $order";
//print $aq;
$res=mysql_query($aq);
if ($res && mysql_num_rows($res)) 
    while ($l=mysql_fetch_row($res)) {
        $messids[]=$l[0];
        $affcnts["$l[0]"]=$l[1];
    }

$maxrecords=count($messids);*/

$r = mysql_query("select brutto from affiliate_cache where user_id = {$active_userid} and group_id = {$group_id} order by id desc limit 0,1");
if ($r && mysql_num_rows($r)) {
    $brutto = mysql_result($r,0,0);
}

$r = mysql_query("select count(*) as brutto from messages,user where messages.user_id=user.id and messages.test='no' and group_id={$group_id}");
if ($r && mysql_num_rows($r)) {
    $total = mysql_result($r,0,0);
}

//die("$brutto : $total : $active_userid : $group_id");

if($total > $brutto) {
  manageCache( $group_id, $active_userid, $total, $brutto, $title );

  $r = mysql_query("select brutto from affiliate_cache where user_id = {$active_userid} and group_id = {$group_id} order by id desc limit 0,1");
  if ($r && mysql_num_rows($r)) {
    $brutto = mysql_result($r,0,0);
  }

  $r = mysql_query("select count(*) as brutto from messages,user where messages.user_id=user.id and messages.test='no' and group_id={$group_id}");
  if ($r && mysql_num_rows($r)) {
    $total = mysql_result($r,0,0);
  }

}

function manageCache($group_id, $user_id, $total, $brutto, $title) {
  $diff = $total - $brutto;
  $res = mysql_query("
    select
      messages.*,user.name
      from
        messages,user
    where
      messages.user_id = user.id
    and
      messages.test = 'no'
    and
      group_id = {$group_id}
    order by
      create_date
        desc limit {$brutto},{$diff}
    ");

  if ($res && mysql_num_rows($res)) {
    while($row=mysql_fetch_array($res)) {
      $r = mysql_query("select count(*) as counter from users_{$title} where aff = '{$user_id}' and messagelist like '%,$row[id],%'");
      $counter = 0;
      if ($r && mysql_num_rows($r)) 
        $counter=mysql_result($r,0,0);



      if($counter > 0) {
        
        $name = mysql_escape_string($row['name']);
        $subject = mysql_escape_string($row['subject']);
         
        mysql_query("
          insert
            into
              affiliate_cache
              (name, subject, create_date, counter, group_id, user_id, brutto)
              values
              ('{$name}','{$subject}','{$row['create_date']}','{$counter}','{$group_id}','{$user_id}',{$total})
        ");
      }
      
    }
  }
}

$cacheRes = mysql_query("select count(*) from affiliate_cache where user_id = {$active_userid} and group_id = {$group_id}");
if($cacheRes && mysql_num_rows($cacheRes)) {
  $cachedAll  = mysql_result($cacheRes,0,0);
  $maxrecords = $cachedAll;
}

if($first+$maxPerPage<=$maxrecords) $mHere = $first+$maxPerPage;           
else $mHere = $maxrecords;                                                 
if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                         
PrintHead();

$idlist="0";
for ($i=$first;$i<$first+$maxPerPage && $i<$maxrecords;$i++)
    $idlist.=",$messids[$i]";

/*$res=mysql_query("select messages.*, user.name from messages,user 
                  where messages.user_id=user.id and messages.test='no' 
                  and group_id='$group_id' and messages.id in ($idlist) $order2");*/
$res=mysql_query("
                select
                  *
                from
                  affiliate_cache 
                where
                  user_id = {$active_userid}  
                and
                  group_id={$group_id}
                {$order2} limit $first,$maxPerPage
                ");

if ($res && mysql_num_rows($res)) { 
    $pagenum=(int)($first / $maxPerPage) + 1;
    $maxpages = ceil($maxrecords / $maxPerPage);

    PrintNavigation($maxpages,$pagenum);
    print "
        <form method='post' name='delform'>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='del_messages' value='1'>
        <TR align=middle>
        <TD class=bgkiemelt2 width='25%'><span class='szovegvastag'>$word[t_date]</span></TD>
        <TD class=bgkiemelt2 width='25%'><span class='szovegvastag'>$word[t_sender]</span></TD>
        <TD class=bgkiemelt2 width='35%'><span class='szovegvastag'>$word[t_message]</span></TD>
        <TD class=bgkiemelt2 width='15%'><span class='szovegvastag'>$word[t_copies]</span></TD>
        </TR>\n";
    while($row=mysql_fetch_array($res)) {
        if (empty($row['subject']))
            $subject="[ $word[no_subject] ]";
        else  
            $subject=$row['subject'];
        
        print "<TR>
                   <TD align=middle class='BACKCOLOR' vAlign=top width='18%'>
                   <SPAN class=szoveg>$row[create_date]&nbsp;$testaddon</span></TD>
                   <TD align=middle class='BACKCOLOR' vAlign=top width='16%'>
                   <SPAN class=szoveg>"
                   .nl2br(htmlspecialchars(quoted_printable_decode(decode_mime_string($row["name"])))).
                   "&nbsp;</SPAN></TD>
                   <TD class='BACKCOLOR' vAlign=top width='28%'><SPAN class=szoveg>"
                   .nl2br(htmlspecialchars(quoted_printable_decode(decode_mime_string($subject))));/*."<br>
                   <A href='#' class='vastag'
                   onClick='window.open(\"$_MX_var->baseUrl/message.php?id=$row[id]&group_id=$group_id\", 
                   \"message$row[id]\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=400,height=450\"); return false;'>[$word[plaintext]]</A>\n";
            if ($ismime) {
                print "<A href='#' class='vastag'
                       onClick='window.open(\"$_MX_var->baseUrl/mime.php?message_id=$row[id]&group_id=$group_id\", 
                       \"message$row[id]\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=600,height=550\"); return false;'>[$word[html]]</A>\n";
            }*/
            print "</SPAN></TD>
                   <TD align=middle class='BACKCOLOR' vAlign=top width='10%'>
                   <SPAN class=szoveg>{$row["counter"]}&nbsp;</span></TD>
                   </TR>\n";
        
    }
    print "</form>\n";
    PrintNavigation($maxpages,$pagenum);
} 
else 
    print "<tr>    
           <td align='left' class=COLUMN1>
           <span class='szovegvastag'>$word[no_messages]</span></td>
           </tr>\n";
 
echo"
</TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
";  
  
include "footer.php";
  



?>
