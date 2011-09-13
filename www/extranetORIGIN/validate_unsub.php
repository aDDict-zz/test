<?
include "auth.php";
include "common.php";

$group = get_http("group",0);
$unique_id = get_http("unique_id","");
$action = get_http("action","");

$perlstr="$_MX_var->validator_engine from-php $group validate-unsub-$unique_id 1";

//print $perlstr;
//validate_unsub.php?group=hihetetlen&unique_id=abrakadabra

$message=0;
if ($pp=popen($perlstr,"r")) {
    //print "!!";
    while ($buff=fgets($pp,25000)) { 
        $message.=$buff;
        //print "*";
    }
    pclose($pp);
}
$pl_message=$message;
// perl script should return either 0 or 1.
$message=intval($message);
$message?$res="ok":$res="notok";
$q = mysql_query("select unsublink_$res,header,footer,name from groups where title='$group'");
if ($q && mysql_num_rows($q)) {
   $r = mysql_fetch_row($q);
   print mysql_error();
   // $r[0] = preg_replace("/\{email\}/i",$email,$r[0]); ...?
   $r[0] = preg_replace("/\{group\}/i",$r[3],$r[0]);
   print "$r[1]$r[0]$r[2]";
}
else {
  $message?$message="Ön sikeresen leiratkozott a(z) $group csoportból.":$message="Sikertelen hitelesítés";

$_MX_popup = 1;
include "menugen.php";

  echo "<TABLE cellSpacing=0 cellPadding=0 width='100%' border=0>
      <TBODY>
      <TR>
        <TD vAlign=top width='100%'>
        <br>
          <TABLE cellSpacing=0 cellPadding=1 width='100%' bgColor=$_MX_var->main_table_border_color border=0>
            <TBODY>
            <TR>
              <TD class=formmezo vAlign=center align='left'>&nbsp;Beiratkozás / kiiratkozás hitelesítése</TD>
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

/*if (empty($message))
    $message="Sikertelen hitelesítés";
//print $message;
$message=rawurlencode($message);
header("Location: $_MX_var->baseUrl/validate_land_unsub.php?message=$message&group=$group");  
exit;*/
?>

