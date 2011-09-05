<?

//include "main.php";

include "auth.php";
$weare=20;
include "cookie_auth.php";
include "decode.php";

if (!$tpl) {
    include "class.FastTemplate.php";
    $tpl=new FastTemplate("./templates");
    $tpl->no_strict();
}
$tpl->define( array( "dategen" => "form_dategen_wf.tpl"));

$min_start_daynum=730851; // 2001-01-01
$max_end_daynum=744364; // 2037-12-31

$nowyear=date('Y');
$dropdown_year=2001;
$nav_id="messages";

$tpl->assign("FORM_ACTION","subs_stat.php");
$tpl->assign("HIDDEN_PARTS","<input type='hidden' name='type' value='$type'><input type='hidden' name='group_id' value='$group_id'><input type='hidden' name='aff' value='$aff'>");
$tpl->assign("AFF_HIDDEN_PARTS","<input type='hidden' name='type' value='$type'><input type='hidden' name='group_id' value='$group_id'>");
$tpl->assign("GROUP_ID",$group_id);
include "dategen.php";
$tpl->parse("DATEGEN","dategen");

$dategen=$tpl->fetch("DATEGEN");

$pagenum=get_http("pagenum","");
$first=get_http("first","");
$Wsortt=get_cookie('Wsortt');
$Wtperpage=get_cookie('Wtperpage');
$Amallgroups=get_cookie("Amallgroups");
$sortt = (isset($_GET['sortt']) || empty($Wsortt)) ? get_http('sortt',4) : $Wsortt;
$maxPerPage = (isset($_GET['maxPerPage']) || empty($Wtperpage)) ? get_http('maxPerPage',5) : $Wtperpage;
$allgroups = (isset($_GET['allgroups']) || empty($Amallgroups)) ? get_http('allgroups',0) : $Amallgroups;
$antiallgroups=$allgroups?0:1;
setcookie("Wsortt",$sortt,time()+30*24*3600);
setcookie("Wtperpage",$maxPerPage,time()+30*24*3600);
setcookie("Amallgroups",$allgroups,time()+30*24*3600);

$maxPerPage=intval($maxPerPage);
if($maxPerPage<1) $maxPerPage = 5;
$pagenum=intval($pagenum);
if($pagenum<1) $pagenum=1;
if(!strlen($first) && $pagenum) $first=($pagenum-1)*$maxPerPage;
if(!$first) $first = 0;

/* 

Turned this option off, this needs to be done more carefully, especially for the cases when a user has mixed, client and moderator rights. - tbjanos

$gqpart="";
if ($allgroups) {
    if (!$_MX_superadmin) {
        $allmy=array();
        $res=$_MX_var->sql("select distinct group_id from members where user_id='$active_userid' 
                          and membership in ('moderator','owner','client')");
        while ($k=mysql_fetch_array($res)) {
            $allmy[]=$k["group_id"];
        }
        $gqpart=" and groups.id in (". implode(",",$allmy) . ")";
    }
}
else {
    $gqpart=" and groups.id='$group_id'";
}
        <TD class='bgkiemelt2 trborderblue' width='130'><span class=szovegvastag>$word[group]</span><span class='szoveg'> (<input type='checkbox' onclick=\"location='threadlist.php?group_id=$group_id&allgroups=$antiallgroups';\"$agch>$word[st_allgroups])</span><br><span class='szovegvastag'>$word[t_date]$hdradd</span></TD>
*/

$gqpart=" and groups.id='$group_id'";

$mquery = "select title,num_of_mess,membership from groups,members 
                     where groups.id=members.group_id $gqpart 
                     and (membership='owner' or membership='moderator' or membership='client' or membership='support' $admin_addq)
                     and user_id='$active_userid'";
$mres = $_MX_var->sql($mquery);
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: index.php?no_group=1"); exit; }

$title=$rowg["title"];
!empty($admin_addq)?$active_membership="moderator":$active_membership=$rowg["membership"];

/* the user is authenticated, it can be client or one of (admin (=moderator rights),moderator,support,owner)
   action that can be taken only by moderator/owner:
      - delete messages
      - choose clients for individual messages
   ct and special stats ay be seen by moderor,owner or by clients that are permitted to do so (their stats on their messages). */
  
include "menugen.php";
include "./lang/$language/threadlist.lang";
logger("",$group_id,"","","messages");	
$del_messages=get_http("del_messages","");
if (($active_membership == "owner" || $active_membership=="moderator") && $del_messages && is_array($_POST["delmess"])) {
    while (list($delmess_id,$val)=each($_POST["delmess"])) {
        $delmess_id=intval($delmess_id);
        $res=$_MX_var->sql("select id from messages where group_id='$group_id' and id='$delmess_id'");
        if ($res && mysql_num_rows($res)) {
        	$query="delete from messages where id='$delmess_id'";
            $_MX_var->sql($query);
            logger($query,$group_id,"","delete message id=$delmess_id","messages");
            $_MX_var->sql("delete from track where message_id='$delmess_id'");
            $_MX_var->sql("delete from feedback where message_id='$delmess_id'");
            $_MX_var->sql("delete from bodies where id='$delmess_id'");
            $_MX_var->sql("delete from mailarchive where id='$delmess_id'");
            $_MX_var->sql("update groups set num_of_mess=num_of_mess-1,tstamp=now() where id='$group_id'");
        }
    }
}

$show_send_logs=0;
// everybody can see the logs.
//if (in_array($active_userid,array(59446,80079))) {
    $show_send_logs=1;
//}
$reqlist="";
if (!$sortt) {
    $sortt=4;
}
switch($sortt) {
    case 1: $order = "order by subject asc"; break;
    case 2: $order = "order by subject desc"; break;
    case 3: $order = "order by create_date asc"; break;
    case 4: $order = "order by create_date desc"; break;
}

$wh="";
$dwh="";
$ctg=get_http("ctg","");
$cts=get_http("cts","");
$subs=get_http("subs","");
$clients=get_http("clients","");
$filters=get_http("filters","");
$filtersh=get_http("filtersh","");
$message_category_id=intval(get_http("message_category_id",""));
$selected = "";
if ($message_category_id == -1) {
    $selected = " selected";
    $selected_message_category_id = 0;
}
$message_category_options = "<option value='-1' $selected>alap</option>";
$message_categories = array();
$res = mysql_query("select * from message_category where group_id=$group_id order by name");
while ($k = mysql_fetch_array($res)) {
    $selected="";
    if ($k["id"] == $message_category_id) {
        $selected = " selected";
        $selected_message_category_id = $message_category_id;
    }
    $message_categories["$k[id]"] = $k["name"];
    $message_category_options .= "<option value='$k[id]' $selected>$k[name]</option>";
}
if ($ctg) $wh.=" and ctp>$ctg";
if ($cts) $wh.=" and ctp<$cts";
if ($subs) $wh.=" and subject like '%$subs%'";
if ($clients) $wh.=" and clients like '%$clients%'";
if ($filtersh) $wh.=" and filters like '%$filtersh%'";
if ($message_category_id && isset($selected_message_category_id)) $dwh.=" and message_category_id=$selected_message_category_id";
if ($start_daynum) $dwh.= " and (to_days(create_date)>='$start_daynum')";
if ($end_daynum) $dwh.= " and (to_days(create_date)<='$end_daynum')";

$mids=array();
$sql="select message_id from message_search where 1 ";
if ($wh || in_array($sortt,array(5,6))) {
    $sql.=" $wh $dwh $order";
} 
if ($sortt==5) {
    $sql.=" order by ct desc";
} elseif ($sortt==6) {
    $sql.=" order by ct asc";
}

//echo $sql."<br/>";

if ($wh!="" || in_array($sortt,array(5,6))) {
    $r2=$_MX_var->sql($sql);
    if ($r2 && mysql_num_rows($r2)) {
        while ($z=mysql_fetch_array($r2)) {
            $mids[]=$z["message_id"];
        }
    }
}

// client messages and permissions.
$stattypes=array();
if ($active_membership=="client") {
    $res=$_MX_var->sql("select count(*) from messages,message_client where 
                         messages.id=message_client.message_id and messages.group_id='$group_id'
                         and message_client.user_id='$active_userid'");
    $r2=$_MX_var->sql("select st.id,st.parent_id from stattype st,stattype_user su where 
                     su.user_id='$active_userid' and su.group_id='$group_id' and st.id=su.stattype_id");
    if ($r2 && mysql_num_rows($r2)) {
        while ($z=mysql_fetch_array($r2)) {
            $stattypes[]=$z["id"];
        }
    }
    if ($res && mysql_num_rows($res))
        $maxrecords=mysql_result($res,0,0);
    else
        $maxrecords=0;    
}
else {
    $maxrecords=count($mids);
    if (!$maxrecords && !$wh) {
        $res=$_MX_var->sql("select count(*) from messages where group_id='$group_id' $dwh");
        if ($res && mysql_num_rows($res))
            $maxrecords=mysql_result($res,0,0);    
    }
}

if($first+$maxPerPage<=$maxrecords) $mHere = $first+$maxPerPage;           
else $mHere = $maxrecords;                                                 
if($first>$maxrecords) $first = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;                                         
PrintHead();

if ($maxrecords) {
    $hdradd="";
    if ($active_membership=="client") {
        $res=$_MX_var->sql("select messages.* from message_client,messages 
                          where message_client.user_id='$active_userid' and messages.group_id='$group_id'
                          and messages.id=message_client.message_id $order limit $first,$maxPerPage");
    }
    else {                 
        if (count($mids)) {
            $res=$_MX_var->sql("select messages.* from messages where group_id='$group_id' and id in(".implode(",",$mids).") order by field(id,".implode(",",$mids).") limit $first,$maxPerPage");
//            print("select messages.* from messages where group_id='$group_id' and id in(".implode(",",$mids).") order by field(id,".implode(",",$mids).") limit $first,$maxPerPage");
        } 
        else {
            $res=$_MX_var->sql("select messages.* from messages where group_id='$group_id' $dwh $order limit $first,$maxPerPage");
//            print("select messages.* from messages where group_id='$group_id' $dwh $order limit $first,$maxPerPage");
        }
        if ($active_membership == "owner" || $active_membership=="moderator") {
            $hdradd=" // $word[clients]";
        }
    }
    //if ($active_membership != 'client') { $bouncedadd="<br>($word[bounced])\n"; }

    $messlist="0";
    $messids=array();
    $subjects=array();
    $filters=array();
    $user_groups=array();
    $dates=array();
    $send_dates=array();
    $tests=array();
    $message_category_ids=array();
    $filtlist="0";
    $uglist="0";
    $tlb_finished=array();
    $schedule_date=array();
    $status=array();
    if ($res && mysql_num_rows($res)) { 
        while ($z=mysql_fetch_array($res)) {
            $messlist.=",$z[id]";
            $messids[]=$z["id"];
            $subjects["$z[id]"]=$z["subject"];
            $filters["$z[id]"]=intval($z["filter_id"]);
            $user_groups["$z[id]"]=intval($z["user_group_id"]);
            $dates["$z[id]"]=$z["create_date"];
            $send_dates["$z[id]"]=$z["send_date"];
            if ($z["tlb_finished_date"]!="2000-01-01 00:00:00" && $rowg["membership"]=="moderator") {
                $dates["$z[id]"] = "Küldés kezdete:<br>$z[create_date]<br>Küldés befejezése:<br>$z[tlb_finished_date]";
            }
            $tests["$z[id]"]=$z["test"];
            $message_category_ids["$z[id]"]=$z["message_category_id"];
            $schedule_date["$z[id]"]=$z["schedule_date"];
            $status["$z[id]"]=$z["status"];
            $tlb_finished["$z[id]"]=$z["tlb_finished"];
            $filtlist.=",".intval($z["filter_id"]);
            $uglist.=",".intval($z["user_group_id"]);
        }
    }

    // go through the stat types and query for those which user has permission to see.
    if ($active_membership == "owner" || $active_membership=="moderator")    
        $permtoall=1;
    else
        $permtoall=0;

    if ($permtoall==1 || in_array(1,$stattypes)) {
        $ctnums=array();
        $ctnums_nounique=array();
        $urlnums=array();
        $tres2 = $_MX_var->sql("select count(distinct t.user_id),count(distinct f.id),f.message_id,count(t.id) 
                              from feedback f,trackf t where 
                              f.message_id in ($messlist) and f.group_id='$group_id'
                              and f.id=t.feed_id group by f.message_id");
        if ($tres2 && mysql_num_rows($tres2)) {
            while ($l=mysql_fetch_row($tres2)) {
                $ctnums["$l[2]"]=$l[0];
                $urlnums["$l[2]"]=$l[1];
                $ctnums_nounique["$l[2]"]=$l[3];
            }
        }
    }
    if ($permtoall==1 || in_array(4,$stattypes)) {
        $opnums=array();
        $tres2=$_MX_var->sql("select count(distinct v.user_id),v.message_id from track1x1 v where 
                           v.message_id in ($messlist) and v.group_id='$group_id'   
                           group by v.message_id");
        // incorrect result may be given if the user_id does not exist in users_* table.
        // this is the correct thing in theory, but very slow in practice.
        /*$tres2=$_MX_var->sql("select count(distinct v.user_id),v.message_id from track1x1 v,users_$title u where 
                           v.message_id in ($messlist) and v.group_id='$group_id'   
                           and v.user_id=u.id group by v.message_id");*/
        if ($tres2 && mysql_num_rows($tres2)) {
            while ($l=mysql_fetch_row($tres2)) {
                $opnums["$l[1]"]=$l[0];
            }
        }
    }
    if ($permtoall==1 || in_array(7,$stattypes)) {
        $bonums=array();
        $bounce_cats=array(
            array("Kézbesíthetetlen","(error_code like '5__' or error_code like '6')"),
            array("Felhasználói hibából átmenetileg nem kézbesíthetõ (pl. postaláda megtelt)","error_code not like '4__' and error_code not like '5__' and error_code not like '6'"),
            array("Technikai hiba miatt átmenetileg nem kézbesíthetõ (pl. kapcsolati probléma)","error_code like '4__'"),
        );
        for ($bi=0;$bi<count($bounce_cats);$bi++) {
            $tres2 = $_MX_var->sql("select count(distinct email),message_id from $_MX_var->bounce_database.bounced_back where 
                                  message_id in ($messlist) and group_id='$group_id'
                                  and ". $bounce_cats[$bi][1] ."
                                  and email not in ('postmaster@maxima.hu','krisztina@egyperces.hu','') group by message_id");
            if ($tres2 && mysql_num_rows($tres2)) {
                while ($l=mysql_fetch_row($tres2)) {
                    $bonums["$l[1]"][$bi]=$l[0];
                }
            }
        }
    }
    if ($permtoall==1 || in_array(9,$stattypes)) {
        $unsnums=array();
        $tres2=$_MX_var->sql("select count(distinct v.user_id),v.message_id from validation v where 
                           v.message_id in ($messlist) and v.group_id='$group_id' and v.action='unsub'  
                           and v.validated='yes' group by v.message_id");
        // incorrect result may be given if the user_id does not exist in users_* table.
        // this is the correct thing in theory, but very slow in practice.
        /*$tres2=$_MX_var->sql("select count(distinct v.user_id),v.message_id from validation v,users_$title u where 
                           v.message_id in ($messlist) and v.group_id='$group_id' and v.action='unsub'  
                           and v.validated='yes' and v.user_id=u.id group by v.message_id");*/
        if ($tres2 && mysql_num_rows($tres2)) {
            while ($l=mysql_fetch_row($tres2)) {
                $unsnums["$l[1]"]=$l[0];
            }
        }
    }
    $mailsnum=array();
    $tres2 = $_MX_var->sql("select sum(mails),message_id from track where 
                          message_id in ($messlist) and group_id='$group_id' group by message_id");
    if ($tres2 && mysql_num_rows($tres2)) {
        while ($l=mysql_fetch_row($tres2)) {
            $mailsnum["$l[1]"]=$l[0];
        }
    }
    $mimes=array();
    $tres2 = $_MX_var->sql("select id from mailarchive where id in ($messlist)");
    if ($tres2 && mysql_num_rows($tres2)) {
        while ($l=mysql_fetch_row($tres2)) {
            $mimes["$l[0]"]=1;
        }
    }
    $filtnames=array();
    $tres2 = $_MX_var->sql("select id,name from filter where id in ($filtlist)");
    if ($tres2 && mysql_num_rows($tres2)) {
        while ($l=mysql_fetch_row($tres2)) {
            $filtnames["$l[0]"]=$l[1];
        }
    }
    $ugnames=array();
    $tres2 = $_MX_var->sql("select id,name from user_group where id in ($uglist)");
    if ($tres2 && mysql_num_rows($tres2)) {
        while ($l=mysql_fetch_row($tres2)) {
            $ugnames["$l[0]"]=$l[1];
        }
    }

    $pagenum=(int)($first / $maxPerPage) + 1;
    $maxpages = ceil($maxrecords / $maxPerPage);
    PrintNavigation($maxpages,$pagenum);
    $agch=$allgroups?" checked":"";
    print "
        <form method='post' name='delform'>
        <input type='hidden' name='group_id' value='$group_id'>
        <input type='hidden' name='del_messages' value='1'>
        <TR align=middle>
        <TD class='bgkiemelt2 trborderblue' width='130'><span class='szovegvastag'>$word[t_date]$hdradd</span></TD>
        <TD class='bgkiemelt2 trborderblue'><span class='szovegvastag'>$word[t_message]</span></TD>\n";
    if ($show_send_logs) {
        print "<TD class='bgkiemelt2 trborderblue'><span class='szovegvastag'>$word[t_sending]</span></TD>\n";
    }
    print "<TD class='bgkiemelt2 trborderblue'><span class='szovegvastag'>$word[t_copies]</span></TD>
        <TD class='bgkiemelt2 trborderblue'><span class='szovegvastag'>$word[t_filter]</span></TD>\n";
        
    if ($active_membership == "owner" || $active_membership=="moderator")
        print "<TD class='bgkiemelt2 trborderblue'><span class='szovegvastag'>&nbsp;<a onClick=\"return(confirm('$word[sure_delete]'))\" href='javascript:document.delform.submit();'>$word[delete]</a></span></TD>\n";
    print "</TR>\n";
    $counter = 0;
    while (list($message_id,$subject)=each($subjects)) {
        if (empty($subject))
            $subject="[ $word[no_subject] ]"; 
        if ($tests["$message_id"]=="yes") {
            $testaddon="<br><b>[$word[test_mail]]</b>";
            $tdclass="bgcolor='ddeeee'";
        }
        else {
            $testaddon="";
            $tdclass="class='BACKCOLOR'";
        }
        $cataddon = "";
        if (isset($message_categories["$message_category_ids[$message_id]"])) {
            $cataddon="<br>[" . $message_categories["$message_category_ids[$message_id]"] . "]";
            $tdclass="bgcolor='ddeeee'";
        }

        if ($counter%2)
            $bgrnd="class='bgvilagos2 trborder trborderblue'";
        else
            $bgrnd="bgcolor=white class='trborder trborderblue'";

        $counter++;

        print "<TR>
               <TD align=middle vAlign=top width='130' $bgrnd>
               <SPAN class=szoveg>$dates[$message_id]&nbsp;$testaddon";
        if ($active_membership == "owner" || $active_membership=="moderator") {
            print "<br><A href='#' class='vastag' onClick='window.open(\"$_MX_var->baseUrl/message_client.php?id=$message_id&group_id=$group_id\", \"mc$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=350,height=350\"); return false;'>$word[clients]</a>\n";
        }   // column 1 end

        print "</SPAN></TD>
               <TD $bgrnd vAlign=top><SPAN class=szoveg>".nl2br(htmlspecialchars(quoted_printable_decode(decode_mime_string($subject))))."$cataddon<br>
               <A href='#' class='vastag' onClick='window.open(\"$_MX_var->baseUrl/message.php?id=$message_id&group_id=$group_id\", \"message$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=400,height=450\"); return false;'>[$word[plaintext]]</A>\n";
        if ($mimes["$message_id"]) {
            print "<A href='#' class='vastag' onClick='window.open(\"$_MX_var->baseUrl/message_source.php?message_id=$message_id&group_id=$group_id\", \"message_source$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=600,height=550\"); return false;'>[$word[source]]</A>
                   <A href='#' class='vastag' onClick='window.open(\"$_MX_var->baseUrl/mime.php?message_id=$message_id&group_id=$group_id\", \"message$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=600,height=550\"); return false;'>[$word[mime_rendered]]</A>\n";
        }
        $mails=intval($mailsnum["$message_id"]);
	$qqr = $_MX_var->sql("select tlb_count from messages where id='$message_id'");
	if ($qqr and mysql_num_rows($qqr))
	{
		$realmails = mysql_result($qqr,0,0);
	} 
	else
	{
		$realmails = 0;
	}
        $ctstats="";
        if ($permtoall==1 || in_array(1,$stattypes)) {
            $ctstats="<br>";
            $clicknum=intval($ctnums_nounique["$message_id"]);
            $clicknum_dist=intval($ctnums["$message_id"]);
            if ($mails)
                $percent=$clicknum==0?"":"&nbsp;".number_format($clicknum/$mails*100,2)."%";  
            else
                $percent="&nbsp;";
            $urls=intval($urlnums[$message_id]);
            $ctstats.="<SPAN class=szoveg>$urls $word[link], ";
            if ($permtoall==1 || in_array(2,$stattypes))
                $ctstats.="<A href='clickthrough.php?group_id=$group_id&message_id=$message_id'>";
            $ctstats.="összes $word[ct]: $clicknum&nbsp;&nbsp;&nbsp;$word[ctp]: $percent";
            if ($permtoall==1 || in_array(2,$stattypes)) {
                $ctstats.="</A>";
                if ($active_membership=="moderator") $ctstats.="&nbsp;&nbsp;<A href='quick_stat.php?group_id=$group_id&message_id=$message_id'>$word[quick_stat]</A>";
            }                
            if (($permtoall==1 || in_array(3,$stattypes)) && $clicknum)
                $ctstats.="&nbsp;<A href='#' class='vastag' onClick='window.open(\"clickthrough_url.php?group_id=$group_id&message_id=$message_id\", \"mtcu$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=420,height=500\"); return false;'>$word[user_list]</a>";
        }
        print "$ctstats</SPAN></TD>"; // column 2 end
        if ($show_send_logs) {
            print "<td $bgrnd align='center' valign='center' id='reqdiv$message_id'>";
            if ($status["$message_id"]=="scheduled") {
                print "<span class='szoveg'>Idozitett kikuldes: $schedule_date[$message_id]</span>";
            }
            elseif ($tlb_finished["$message_id"]=="yes") {
                print "<span class='szoveg'>$word[send_ended]</span>";
            }
            else {
                $reqlist.="log_item_send ('$title',$message_id,$message_id);\n";
            }
            print "</td>\n";
        }
        print "<TD align=middle $bgrnd vAlign=top><SPAN class=szoveg>$realmails / <font color='#999999'>$mails</font>\n";
        $t1x1="";
        if ($permtoall==1 || in_array(4,$stattypes)) {
            $t1x1="";
            /*
            $t1x1="<br>";
            if ($permtoall==1 || in_array(5,$stattypes))
                $t1x1.="<A href='clickthrough.php?group_id=$group_id&message_id=$message_id'>";
            $t1x1.="$word[user_open]: ". intval($opnums[$message_id]);
            if ($permtoall==1 || in_array(5,$stattypes))
                $t1x1.="</A>";
            */
            if (($permtoall==1 || in_array(6,$stattypes)) && $opnums["$message_id"])
                $t1x1.="&nbsp;<A href='#' class='vastag' onClick='window.open(\"message_opened.php?group_id=$group_id&message_id=$message_id\", \"mtcz$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=420,height=500\"); return false;'>$word[user_list]</a>";
        }
        $bback="";
        if ($permtoall==1 || in_array(7,$stattypes)) {
            $bounce_parts=array();
            $bototal=0;
            for ($bi=0;$bi<count($bounce_cats);$bi++) {
                $bounce_parts[]="<span style='cursor:pointer;' title='". $bounce_cats[$bi][0] ."'>". intval($bonums["$message_id"][$bi]) ."</span>";
                $bototal+=$bonums["$message_id"][$bi];
            }
            $bosummary=implode(" / ",$bounce_parts);
            if ($bototal && ($permtoall==1 || in_array(8,$stattypes))) {
                $bosummary .= mx_sender6_bounce($message_id,strtotime($send_dates["$message_id"]));
                $bback="<br>$bosummary $word[bounced]&nbsp;<A href='#' class='vastag' onClick='window.open(\"$_MX_var->baseUrl/message_bounced.php?message_id=$message_id&group_id=$group_id\", \"mbou$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=420,height=500\"); return false;'>$word[user_list]</a>";
            }
            else {
                $bback="<br>[".$bosummary."&nbsp;$word[bounced]]";
            }
        }
        $unsubbed="";
        if ($permtoall==1 || in_array(9,$stattypes)) {
            if ($unsnums["$message_id"] && ($permtoall==1 || in_array(10,$stattypes)))
                $unsubbed="<br>$unsnums[$message_id]&nbsp;$word[unsubbed]&nbsp;<A href='#' class='vastag' onClick='window.open(\"$_MX_var->baseUrl/message_unsub.php?message_id=$message_id&group_id=$group_id\", \"musu$message_id\", \"resizable=yes,toolbar=no,location=no,directories=no,status=no,scrollbars=yes,width=420,height=500\"); return false;'>$word[user_list]</a>";
            else 
                $unsubbed="<br>[".intval($unsnums["$message_id"])."&nbsp;$word[unsubbed]]";
        }
        print "$t1x1$bback$unsubbed&nbsp;</span></TD>\n"; // column 3 end

        $t_filter="";
        if ($filters["$message_id"])    
            $t_filter="$word[filterd]: ".nl2br(htmlspecialchars($filtnames["$filters[$message_id]"]));
        if ($user_groups["$message_id"])    
            $t_filter="$word[user_group]: ".nl2br(htmlspecialchars($ugnames["$user_groups[$message_id]"]));
        print "<TD align=middle $bgrnd vAlign=top><SPAN class=szoveg>$t_filter&nbsp;</span></TD>\n"; // column 4 end

        if ($active_membership == "owner" || $active_membership=="moderator")
            print "<td $bgrnd align='center'><input type='checkbox' name='delmess[$message_id]' value='1'></span></TD>\n"; //column 5 end

        print "</TR>\n";
    }
    print "</form>\n";
    PrintNavigation($maxpages,$pagenum);
} 
else {
    PrintNavigation($maxpages,$pagenum);    
    print "<tr>    
           <td align='left' class=COLUMN1>
           <span class='szovegvastag'>$word[no_messages]</span></td>
           </tr>\n";
}
echo"
</TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<script>
$reqlist
</script>
";  
  
include "footer.php";
  

//****[ PrintHead ]***********************************************************
function PrintHead() {

    echo "
          <script src='xmlreq2.js' type='text/javascript'></script>
          <TABLE border=0 cellPadding=0 cellSpacing=0 width='100%'>
          <TBODY>
          <TR>
          <TD class=formmezo>
          <TABLE border=0 cellPadding=3 cellSpacing=1 width='100%'>
          <TBODY>";
}


//****[ PrintNavigation ]*****************************************************
function PrintNavigation($maxpages, $pagenum) {

    global $_MX_var,$word,$group_id,$sortt,$maxPerPage,$maxrecords,$first,$dategen,$ctg,$cts,$subs,$filtersh,$clients,$active_membership,$message_category_options;

    $LastPage = (ceil($maxrecords / $maxPerPage)-1) * $maxPerPage;
    $OnePageLeft = $first - $maxPerPage; if($OnePageLeft<1) $OnePageLeft = 0;
    $OnePageRight = $maxPerPage + $first; if($OnePageRight>$LastPage) $OnePageRight = $LastPage;
    $sel_sort[$sortt] = "selecteD"; 
    $params="group_id=$group_id";

    echo '
    <tr>
        <td colspan="6">
            <form name="inputs">
                <table border="0" cellspacing="1" style="border-spacing:2px;" width="100%">';
        if ($active_membership!="client") {
            echo '
                        <tr>
                            <td class="formmezo tal" width="20%">
                                CT &gt;<input type="text" name="ctg" value="'.$ctg.'" size="3" maxlength="3" /> &
                                CT &lt;<input type="text" name="cts" value="'.$cts.'" size="3" maxlength="3" />
                            </td>
                            <td class="formmezo tar" width="40%">
                                &nbsp;&nbsp;'.$word['subj_search'].': <input type="text" name="subs" value="'.$subs.'" style="width:150px;" />
                            </td>
                            <td class="formmezo tar" width="40%">
                                &nbsp;&nbsp;'.$word['client_search'].': <input type="text" name="clients" value="'.$clients.'" style="width:150px;" /> 
                            </td>
                        </tr>';
            echo '
                        <tr>
                            <td class="formmezo tal" width="20%">
                                &nbsp;
                            </td>
                            <td class="formmezo tar" width="40%">                                
                                &nbsp;&nbsp;'.$word['message_category_search'].': <select style="width:150px;" name="message_category_id"/><option value=0>---</option>' .  $message_category_options . '</select>
                            </td>
                            <td class="formmezo tar" width="40%">                                
                                &nbsp;&nbsp;'.$word['filter_search'].': <input type="text" name="filtersh" value="'.$filtersh.'" style="width:150px;" />
                            </td>
                        </tr>';
        }
        echo '
                        <tr>
                            <td>
                                <table width="100%" style="border-spacing:2px;">
                                    <tr>
                                        <td class="tal">
                                            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                <input type="hidden" name="group_id" value="'.$group_id.'" />
                                                <tr>'."\n";
    if ($first>0) {
        echo '                                      <td nowrap align="right">
                                                        <a href="'.$_MX_var->baseUrl.'/threadlist.php?'.$params.'&first=0"><img src="'.$_MX_var->baseUrl.'/'.$_MX_var->application_instance.'/gfx/down2.gif" width="20" height="14" border="0"></a>
                                                        <a href="'.$_MX_var->baseUrl.'/threadlist.php?'.$params.'&first='.$OnePageLeft.'"><img src="'.$_MX_var->baseUrl.'/'.$_MX_var->application_instance.'/gfx/down1.gif" width="20" height="14" border="0"></a>
                                                    </td>'."\n";
    } else {
        echo '                                      <td nowrap align="right">&nbsp;&nbsp;
                                                        <img src="'.$_MX_var->baseUrl.'/'.$_MX_var->application_instance.'/gfx/down2_dead.gif" width="20" height="14" border="0" />
                                                        <img src="'.$_MX_var->baseUrl.'/'.$_MX_var->application_instance.'/gfx/down1_dead.gif" width="20" height="14" border="0" />
                                                    </td>'."\n";
    }
    echo '                                          <td nowrap align="right"> 
                                                        <input type="text" name="pagenum" size="3" maxlength="3" value="'.$pagenum.'">
                                                    </td>
                                                    <td nowrap class="formmezo">&nbsp;/ '.$maxpages.'</td>'."\n";
    if ($first<$LastPage) {
        echo '                                      <td nowrap align="right"> &nbsp;
                                                        <a href="'.$_MX_var->baseUrl.'/threadlist.php?'.$params.'&first='.$OnePageRight.'"><img src="'.$_MX_var->baseUrl.'/'.$_MX_var->application_instance.'/gfx/up1.gif" width="20" height="14" border="0" /></a>
                                                        <a href="'.$_MX_var->baseUrl.'/threadlist.php?'.$params.'&first='.$LastPage.'"><img src="'.$_MX_var->baseUrl.'/'.$_MX_var->application_instance.'/gfx/up2.gif" width="20" height="14" border="0" /></a>&nbsp;
                                                    </td>'."\n";
    } else {
        echo '                                      <td nowrap align="right">&nbsp;&nbsp;
                                                        <img src="'.$_MX_var->baseUrl.'/'.$_MX_var->application_instance.'/gfx/up1_dead.gif" width="20" height="14" border="0" />
                                                        <img src="'.$_MX_var->baseUrl.'/'.$_MX_var->application_instance.'/gfx/up2_dead.gif" width="20" height="14" border="0" />
                                                    </td>'."\n";
    }
        echo '
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td colspan="2">
                                <table width="100%">
                                    <tr>
                                        <td>
                                            <table border="0" cellspacing="0" cellpadding="0">
                                                <tr> 
                                                    <input type="hidden" name="group_id" value="'.$group_id.'">
                                                    <td nowrap class="formmezo">'.$word['sort_order'].': </td>
                                                    <td nowrap>
                                                        <span class="szoveg"> 
                                                            <select onChange="JavaScript: this.form.submit();" name="sortt">
                                                                <option value="1" '.$sel_sort[1].'>'.$word['subject_asc'].'</option>
                                                                <option value="2" '.$sel_sort[2].'>'.$word['subject_desc'].'</option>
                                                                <option value="3" '.$sel_sort[3].'>'.$word['date_asc'].'</option>
                                                                <option value="4" '.$sel_sort[4].'>'.$word['date_desc'].'</option>';
                                                        if ($active_membership!="client") {        
                                                          echo '<option value="5" '.$sel_sort[5].'>'.$word['CT_desc'].'</option>        
                                                                <option value="6" '.$sel_sort[6].'>'.$word['CT_asc'].'</option>';
                                                        }
                                                        echo '
                                                            </select>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>                                        
                                        </td>
                                        <input type="hidden" name="group_id" value="'.$group_id.'" />
                                        <td nowrap class="formmezo" align="center">'.$word['view'].':</td>
                                        <td nowrap align="center"> 
                                            <input type="text" name="maxPerPage" value="'.$maxPerPage.'" size="3" maxlength="3">
                                        </td>
                                        <td nowrap class="formmezo" align="left"> '.$word['mess_page'].'</td>
                                        <td nowrap>'.$dategen.'</td>
                                        <td>
                                            <input type="submit" name="go" value="'.$word['go'].'" />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </form>
        </tr>'."\n";
}

function mx_sender6_bounce($message_id,$time) {

    global $active_useremail,$_MX_var;

    // IMPORTANT check, change very carefully, we don't show these logs to anybody!
    if (!preg_match("/@(hirekmedia\.hu|manufaktura\.rs)$/i",$active_useremail)) {
        return "";
    }
    $stats = array("new"=>0,"sent"=>0,"bounce_other"=>0,"bounce_baduser"=>0,"bounce_unroutable"=>0);
    $tablename = "message_" . date("o", $time) . "_" . preg_replace("/^0/","",date("W", $time));
    if ($res = $_MX_var->sql("select count(*) cnt,status from $tablename where message_id=$message_id and message_type='m' group by status","bounce")) {
        while ($k = mysql_fetch_array($res)) {
            $stats["$k[status]"] = $k["cnt"];
        }
        return "{" . "$stats[bounce_baduser]/$stats[bounce_unroutable]/$stats[bounce_other]" . "}";
    }
    return "";
}
?>
