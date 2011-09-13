<?
include "auth.php";
include "decode.php";
$weare=20;

include "cookie_auth.php";

if ($active_membership=="affiliate")
exit;


$_MX_popup = 1;
include "menugen.php";


$id=intval($id);
$group_id=intval($group_id);

$mres = mysql_query("select title,num_of_mess,membership from groups,members where groups.id=members.group_id
                     and (membership='owner' or membership='moderator' or membership='client' or membership='support' $admin_addq)
                     and groups.id='$group_id' and user_id='$active_userid'");
if ($mres && mysql_num_rows($mres))
    $rowg=mysql_fetch_array($mres);  
else 
    exit; 

!empty($admin_addq)?$act_memb="moderator":$act_memb=$rowg["membership"];

if ($act_memb=="client") {
    $rcl=mysql_query("select * from message_client where message_id='$id' and user_id='$active_userid'");
    if (!($rcl && mysql_num_rows($rcl)))
        exit;
}


  $res=mysql_query("select * from messages where group_id=$group_id and id='$id'");
  if ($res && mysql_num_rows($res)) {
    $k=mysql_fetch_array($res);
    $subject=nl2br(htmlspecialchars(quoted_printable_decode(decode_mime_string($k["subject"]))));
    $r2 = mysql_query("select addon from mailarchive where id='$id'");
    if ($r2 && mysql_num_rows($r2)) {
        $mime=mysql_fetch_array($r2);
        $ismime = $mime['addon'];
    }
    // in the 'body' it is expected to be the text only version of the message.
    // TODO: fix sender_engine to insert here the right thing (currently it inserts the whole message)
    mysql_query("set names latin1");
    $r2=mysql_query("select body from bodies where id='$id'");
    if ($r2 && mysql_num_rows($r2)) {
        $k2=mysql_fetch_array($r2);
        if ($ismime)
            $dc_body=tag_filter(htmlspecialchars(quoted_printable_decode($k2["body"])));
        else
            $dc_body=tag_filter(htmlspecialchars($k2["body"]));
        $dc_body=eregi_replace("{l}([^{}]*){/l}","\\1",$dc_body);
        echo "<span class='szovegvastag'>$subject</span><br><br>
              <span class='szoveg'>$dc_body</span>\n";
    }
  }

function tag_filter($html_string)
{
  // nobody remembers why this was needed, it messes up some links, just return...
  // maybe these are special tags from LX to display?
		    return nl2br($html_string);
  $letters="[a-zA-Z0-9%_=?&\.~#/-]";
  $URL_letters="($letters*(¸¸¸amp¸¸¸|)$letters*)";
  $patt=array("§&lt;§i",
              "§&gt;§i",
	      "§&amp;§",
	      "§\b(http://".$URL_letters."\.".$URL_letters."(jpg|jpeg|gif|png))\b§i",
	      "§\b(((?<!src=)(http://".$URL_letters."\.".$URL_letters."))(?!>(jpg|jpeg|gif|png)))\b§i",
	      "§\b(mailto:|)(\S+@\S+)\b§i",
	      "§\b(?<!http://)(www\.$URL_letters)\b§i",
	      "§¸¸¸lt¸¸¸§",
	      "§¸¸¸gt¸¸¸§",
	      "§¸¸¸amp¸¸¸§");
  $repl=array("¸¸¸lt¸¸¸",
              "¸¸¸gt¸¸¸",
	      "¸¸¸amp¸¸¸",
	      "<image src=\\1>",
	      "<a href=\\3 target=_blank>\\3</a>",
	      "<a href=mailto:\\2>\\1\\2</a>",
	      "<a href=http://\\1>\\1</a>",
	      "&lt;",
	      "&gt;",
	      "&amp;");
  $html_string=preg_replace($patt,$repl,$html_string);
				$patt=array(
				"§{\s*(f|b)\s*}§i",
				"§{\s*(/\s*f|/\s*b)\s*}§i",
				"§{\s*(a|u)\s*}§i",
				"§{\s*(/\s*a|/\s*u)\s*}§i",
				"§{\s*(i|d)\s*}§i",
				"§{\s*(/\s*i|/\s*d)\s*}§i",
				"§({\s*br\s*}|\n)§i");
				$repl=array("<b>","</b>","<u>","</u>","<i>","</i>","<br>");
				$html_string=preg_replace($patt,$repl,$html_string);
				for ($i=0;$i<3;$i++) {
				  preg_match_all("§(".$repl[0+$i*2].")§i",$html_string,$open_result);
				  preg_match_all("§(".$repl[1+$i*2].")§i",$html_string,$close_result);
				  $o_cnt=count($open_result[1]);
				  $c_cnt=count($close_result[1]);
				  #                       print "*** $o_cnt  ---  $c_cnt ***<br>";
				  while ($o_cnt>$c_cnt++){
				    $html_string.=$repl[1+$i*2];
				  }
				}
		    return $html_string;
		 }
?>

</body>
</html>
