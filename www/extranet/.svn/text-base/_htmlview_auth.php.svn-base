<?

$preview=get_http("preview","");

if (!empty($preview)) {
    $is_html_view=2;
    if (ereg("^egyperc([0-9]+)$",$preview,$regs)) {
        // Check if the requst comes from the correct IP!
        $message_id=$regs[1];
        $r2=mysql_query("select g.title,m.create_date,g.id,m.implementation,m.subject
                         from messages m,groups g,multigroup mg where m.id='$message_id' and m.test='no' and m.group_id=g.id and g.id=mg.groupid and mg.multiid=3");
        if ($r2 && mysql_num_rows($r2)) {
            $k=mysql_fetch_array($r2);
            $is_html_view=0;
            $group_id=$k["id"];
            $group_name=$k["title"];
            $message_date=$k["create_date"];
            $message_subject=$k["subject"];
            $for_egyperc=1;
        }
    }
    if (ereg("^([0-9a-f]{18,}),(.+)$",$preview,$regs)) {
//print "*<br>";
        $bmd5=$regs[1];
        $bl=strlen($bmd5);
        $md5=substr($bmd5,0,5) . substr($bmd5,$bl-12);
        $mess16=substr($bmd5,5,$bl-17);
        $message_id=base_convert($mess16,16,10);
        $hw_email=mysql_escape_string($regs[2]);
//print "<!--select g.title,m.create_date from messages m,groups g where m.id='$message_id' and m.group_id=g.id<br>-->";
        $r2=mysql_query("select g.title,m.create_date,g.id,m.implementation from messages m,groups g where m.id='$message_id' and m.group_id=g.id");
        if ($r2 && mysql_num_rows($r2)) {
            $k=mysql_fetch_array($r2);
            if ($k["implementaiton"]=="ROBIN") {
                $local_hostname="robin.kirowski.com";
                $unsubscribe_mail_prefix="unsubscribe";
            }
            $group_id=$k["id"];
            $group_name=$k["title"];
            $test_md5=substr(md5("$k[create_date]DombosFest2007$hw_email$message_id"),3,17);
//print "<!--$test_md5 -- $md5 -- $hw_email<br>-->";
            if ($test_md5==$md5) {
//print "<!--<br>select * from users_$k[title] where id='$hw_email' and robinson='no'-->";
                $r2=mysql_query("select * from users_$k[title] where id='$hw_email' and robinson='no'");
                if ($r2 && mysql_num_rows($r2)) {
                    $hw_udat=mysql_fetch_array($r2);
                    $is_html_view=1;
                }
            }
        }
    }
}
if ($is_html_view==2) {
    exit;
}
if ($for_egyperc && in_array($message_id,array(54296,53436,52932,51328,51181))) {
	exit;
}

?>
