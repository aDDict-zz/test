<?
include "auth.php";
include "common.php";

$group = get_http("group",0);
$unique_id = get_http("unique_id","");
$action = get_http("action","");

$perlstr=escapeshellcmd("$_MX_var->validator_engine from-php $group validate-sub-$unique_id 1");

// print "$perlstr<br>";

if ($pp=popen($perlstr,"r")) {
    //print "!!";
    while ($buff=fgets($pp,25000)) { 
        $message.=$buff;
        //print "*";
    }
    pclose($pp);
}

if (empty($message)) {
    $message="Sikertelen hitelesítés";
}

//print $message;

$validator_page="";
$groupname="";
$header="";
$footer="";
$res=mysql_query("select id,validator_page,name,header,footer from multi where title='$group'");
if ($res && mysql_num_rows($res)) {
    $id=mysql_result($res,0,0);
    $validator_page=mysql_result($res,0,1);
    $groupname=mysql_result($res,0,2);
    $header=mysql_result($res,0,3);
    $footer=mysql_result($res,0,4);
    $r2=mysql_query("select groups from multivalidation where unique_id='$unique_id' and group_id='$id'");
    if ($r2 && mysql_num_rows($r2)) {
        $titlelist = explode("\n",mysql_result($r2,0,0)); 
        $gnames=array();
        foreach ($titlelist as $thist) {
            $qthist=mysql_escape_string($thist);
            $r3 = mysql_query("select name from groups where title='$qthist'"); 
            if ($r3 && mysql_num_rows($r3)) {
                $gnames[]=mysql_result($r3,0,0);
            }
            else {
                $gnames[]=$thist;
            }
        }
        $ignames=implode("<br>",$gnames);
        $validator_page=str_replace("{groups}",$ignames,$validator_page);
        $validator_page=str_replace("{subgroups}",$ignames,$validator_page);
        $validator_page=str_replace("{GROUPS}",$ignames,$validator_page);
        $validator_page=str_replace("{SUBGROUPS}",$ignames,$validator_page);
    }
}
$res=mysql_query("select id,validator_page,name,header,footer from groups where title='$group'");
if ($res && mysql_num_rows($res)) {
    $id=mysql_result($res,0,0);
    $validator_page=mysql_result($res,0,1);
    $groupname=mysql_result($res,0,2);
    $header=mysql_result($res,0,3);
    $footer=mysql_result($res,0,4);
}

$message=rawurldecode($message);

if (!empty($validator_page)) {
 $validator_page=str_replace("{MESSAGE}",$message,$validator_page);
 $validator_page=str_replace("{GROUP}",$groupname,$validator_page);
 $validator_page=str_replace("{group}",$groupname,$validator_page);
 echo "$header$validator_page$footer";
}
else {
 
  echo "  
<html>
<head>
<title>$_MX_var->application_instance_name</title>
<meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\'>
</head>
<body>  
 <TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
  <TBODY>
  <TR>
    <TD vAlign=top width='100%'>
	<br>
      <TABLE cellSpacing=0 cellPadding=1 width='100%' bgColor=$_MX_var->main_table_border_color border=0>
        <TBODY>
        <TR>
          <TD class=formmezo vAlign=center align='left'>&nbsp;$word[VAL_SUBUNSUB]</TD>
        <TR>
          <TD class=formmezo>
            <TABLE cellSpacing=0 cellPadding=0 width='100%' bgColor=#ffffff 
            border=0>
              <TBODY>
              <TR>
                <TD class=bgvilagos2 align=center><br><span class=szoveg>$message</span>
		</TD>
              </TR>
	      </TBODY>
	    </TABLE>
	   </TD>
	 </TR>
	 </TBODY>
       </TABLE></TD></TR></TBODY></TABLE></body></html>
";                 

}

?>

