<?
  include "auth.php";
    
  $group=addslashes($group);
  $validator_page="";
  $res=mysql_query("select validator_page_unsub from multi where title='$group'");
  if ($res && mysql_num_rows($res))
     $validator_page=mysql_result($res,0,0);
  $res=mysql_query("select id,validator_page_unsub from groups where title='$group'");
  if ($res && mysql_num_rows($res)) {
     $id=mysql_result($res,0,0);
     $validator_page=mysql_result($res,0,1);
  }
  

  $message=rawurldecode($message);

  if (!empty($validator_page)) {
     $validator_page=str_replace("{MESSAGE}",$message,$validator_page);
     echo $validator_page;
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
