<?
include "auth.php";
$weare=24;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "sender_gen.php";

$group_id=get_http("group_id",0);
$base_id=get_http("base_id",0);
$mail_format=get_http("mail_format","");
$framepart=get_http("framepart","");

$mres = mysql_query("select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    header("Location: $_MX_var->baseUrl/index.php"); exit; }

include "./lang/$language/sender.lang";

$base_id=intval($base_id);
$r2=mysql_query("select * from sender_base where group_id='$group_id' and id='$base_id'");
if ($r2 && mysql_num_rows($r2)) {
    $brow=mysql_fetch_array($r2);
}
else {
    exit;
}

print "<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
</head>
";

$mail_formats = array("html","html_source","plain","mime");
if (!in_array($mail_format,$mail_formats)) {
    $mail_format=$mail_formats[0];
}

if ($framepart=="top") {
    print "<body bgcolor='#FFFFFF' onload='focus();' style='border:1px $_MX_var->main_table_border_color solid;'> 
           <table border='0' cellpadding='2' cellspacing='0' style='width:100%'>
           <tr><td class='bgkiemelt2'><span class='szovegvastag'>$word[base_preview]:". htmlspecialchars($brow["name"]) ."</span>
                </td></tr><tr><td>\n";
    foreach ($mail_formats as $mf) {
        $mf==$mail_format?$sel="checked":$sel="";
        print "<input type='radio' name='mail_format' $sel onclick=\"parent.show.location='sender_base_preview.php?group_id=$group_id&base_id=$base_id&framepart=show&mail_format=$mf';\">$mf&nbsp;&nbsp;";
    }
    print "</td></tr></table></body>";
}
elseif ($framepart=="show") {
    $out="";
    $rmf=$mail_format;
    if ($mail_format=="html_source") {
        $rmf="html";
    }
    ereg("^(.*/)[^/]+$",$_MX_var->sender_engine,$regs);
    chdir($regs[1]);
    if ($pp=popen("$_MX_var->sender_engine base-$base_id format-$rmf output-pipe","r")) {
        while ($buff=fgets($pp,25000)) {
            $out.=$buff;
        }
        pclose($pp);
    }
    else {
        $out="Sender engine error";
    }
    if ($mail_format=="html") {
        print $out;
    }
    else {
        print nl2br(htmlspecialchars($out));
    }
}
else {
    print "<frameset rows='60,*' frameborder='0' framespacing='0' bgcolor='#FFFFFF'>
           <frame src='sender_base_preview.php?group_id=$group_id&base_id=$base_id&framepart=top&mail_format=$mail_format' frameborder='0' scrolling='no' name='top' id='top' noresize bgcolor='#ffffff'></frame>
           <frame src='sender_base_preview.php?group_id=$group_id&base_id=$base_id&framepart=show&mail_format=$mail_format' frameborder='0' name='show' id='show' noresize bgcolor='#ffffff'></frame>
           </frameset>";
}
?>
</body>
</html>
