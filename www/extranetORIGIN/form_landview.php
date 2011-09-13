<?
include "auth.php";
include "decode.php";
$weare=34;
include "cookie_auth.php";
include "common.php";
$language=select_lang();
include "./lang/$language/form.lang";
include "./lang/$language/dategen.lang";

$mres = mysql_query("select title,num_of_mess,membership 
                     from groups,members where groups.id=members.group_id
                     and groups.id='$group_id' and (membership='owner' or membership='moderator' $admin_addq)
                     and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else {
    exit; 
}

$form_id=get_http("form_id",0);
$lpview=get_http("lpview","");
$elid=get_http("elid",0);
if ($elid)
    $res=mysql_query("select * from form_endlink where id='$elid'");
else
    $res=mysql_query("select * from form where id='$form_id' and group_id='$group_id'");
if ($res && mysql_num_rows($res))
    $formdata=mysql_fetch_array($res);
else
    exit;
if ($lpview=="landing_page") {
    print(str_replace("{TITLE}",$formdata["title"],$formdata["landing_page"]));
}
elseif ($lpview=="intro_page") {
    print "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'></head><body>\n";
    print(str_replace("{TITLE}",$formdata["title"],$formdata["intro_page"]));
    print "</body></html>\n";
}
elseif ($lpview=="filled_out_page") {
    print "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'></head><body>\n";
    print(str_replace("{TITLE}",$formdata["title"],$formdata["filled_out_page"]));
    print "</body></html>\n";
}
elseif ($lpview=="quitted_page") {
    print "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'></head><body>\n";
    print(str_replace("{TITLE}",$formdata["title"],$formdata["quitted_page"]));
    print "</body></html>\n";
}
elseif ($lpview=="invalid_cid") {
    print "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'></head><body>\n";
    print(str_replace("{TITLE}",$formdata["title"],$formdata["invalid_cid"]));
    print "</body></html>\n";
}
else {
    if ($elid) $html=$formdata["html"]; else $html=$formdata["landing_page_inactive"];
    print(str_replace("{TITLE}",$formdata["title"],$html));
}

?>
