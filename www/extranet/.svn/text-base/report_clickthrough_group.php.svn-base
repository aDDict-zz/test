<?
include "auth.php";
include "decode.php";
$weare=31;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/statistics.lang";

$group_id=intval($group_id);
$mres = mysql_query("select title,num_of_mess from groups,members where groups.id=members.group_id
                   and groups.id='$group_id' and user_id='$active_userid'
                   and (membership='owner' or membership='moderator' $admin_addq)");
if ($mres && mysql_num_rows($mres))
    $row=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }
$group_title=$row["title"];

if (!$tpl) {
    include "class.FastTemplate.php";
    $tpl=new FastTemplate("./templates");
    $tpl->no_strict();
}
$tplist=array("t_date","group","group_lower","total","ct_all","ct_copies_all","ct_ct_ct",
              "ct_click_first","ct_click_last","t_sender","t_copies","t_message","filter",
              "email_archive");
$tpl->assign_same($tplist);
$tpl->assign("GTITLE",$group_title);
include "report_head.php";
$tpl->define(array( "statistics" => "report_ct_group.tpl"));
$bgstyle1="bgwhite";
$bgstyle2="bggray";
$tpl->define_dynamic("list","statistics");

$mails=0;
$tres2 = mysql_query("select sum(mails) from track where group_id='$group_id'");
if ($tres2 && mysql_num_rows($tres2)) {
    $k2=mysql_fetch_row($tres2);
    $mails=$k2[0];
}      
$clicknum_dist=0;
$tres2 = mysql_query("select count(distinct user_id,message_id) from feedback,trackf where 
                      feedback.id=trackf.feed_id and feedback.group_id='$group_id'");
if ($tres2 && mysql_num_rows($tres2))
    $clicknum_dist=mysql_result($tres2,0,0);
$clicknum=0;
$tres2 = mysql_query("select count(*),max(date),min(date) from feedback,trackf where 
                      feedback.id=trackf.feed_id and feedback.group_id='$group_id'");
if ($tres2 && mysql_num_rows($tres2)) {
    $clicknum=mysql_result($tres2,0,0);
    $click_last=mysql_result($tres2,0,1);
    $click_first=mysql_result($tres2,0,2);
}
$percent=$clicknum_dist==0?" ":" (".number_format($clicknum_dist/$mails*100,2)."%)";

$tpl->assign("MAILS",$mails);
$tpl->assign("CLICKNUM_DIST","$clicknum_dist$percent");
$tpl->assign("CLICK_FIRST",$click_first);
$tpl->assign("CLICK_LAST",$click_last);
$tpl->assign("MTD_STYLE",$bgstyle1);            

$i=0;
$res=mysql_query("select messages.*, user.name from messages,user where messages.user_id=user.id 
                  and group_id='$group_id' order by create_date desc");
if ($res && mysql_num_rows($res)) {
    while($row=mysql_fetch_array($res)) {
        if (empty($row['subject']))
            $subject='[ nincs tema ]'; 
        else  
            $subject=$row['subject'];
        $tres2 = mysql_query("select sum(mails) from track where message_id='$row[id]' and group_id='$group_id'");
        if ($tres2 && mysql_num_rows($tres2)) 
            $mails=mysql_result($tres2,0,0);
        else
            $mails=0;
        if ($active_membership == "owner" || $active_membership == "moderator") { 
            $tres2 = mysql_query("select count(distinct user_id) from feedback,trackf where 
                                  feedback.id=trackf.feed_id and 
                                  feedback.message_id='$row[id]' and feedback.group_id='$group_id'");
            if ($tres2 && mysql_num_rows($tres2))
                $clicknum_dist=mysql_result($tres2,0,0);
            else
                $clicknum_dist=0;
            $clicknum=0;
            $urlnum=0;
            $tres2 = mysql_query("select count(*) from feedback where
                                  message_id='$row[id]' and group_id='$group_id'");
            if ($tres2 && mysql_num_rows($tres2))
                $urlnum=mysql_result($tres2,0,0);
            if ($mails)
                $percent=$clicknum_dist==0?"":number_format($clicknum_dist/$mails*100,2)."%";  
            else
                $percent=" ";
            $ctstats=", $urlnum $word[link], $word[ct]:$clicknum_dist $word[ctp]:$percent";
        }
        else
            $ctstats="";
        $r4=mysql_query("select name from filter where id='$row[filter_id]'");
        if ($r4 && mysql_num_rows($r4)) 
            $t_filter=nl2br(htmlspecialchars(mysql_result($r4,0,0)));
        else
            $t_filter="";
       
        $tpl->assign("CREATE_DATE",$row["create_date"]);
        $tpl->assign("NAME",report_specialchars(quoted_printable_decode(decode_mime_string($row["name"]))));
        $tpl->assign("SUBJECT",report_specialchars(quoted_printable_decode(decode_mime_string($subject))).$ctstats);
        $tpl->assign("MAILNUM",$mails);
        $tpl->assign("FILTER",$t_filter);
        if ($i%2)
            $tpl->assign("TD_STYLE",$bgstyle2);   
        else
            $tpl->assign("TD_STYLE",$bgstyle1);                 
        $tpl->parse("LIST",".list");
        $i++;
    }
} 
else
    $tpl->clear_dynamic("list");

$rep_filename="allct";
include "report_foot.php";

##################################################################

function report_specialchars($str) {

    global $_MX_var,$report;
    if ($report==2) {
        $str=ereg_replace("{","(",$str);
        $str=ereg_replace("}",")",$str);
        return addslashes($str);
    }
    else
        return htmlspecialchars($str);
}
?>
