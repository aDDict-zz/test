<?php

include('extranet/auth.php');

if (!empty($_POST['name'])) {
    $u = mysql_fetch_array(mysql_query("SELECT password FROM user WHERE email='" . mysql_escape_string($_POST['name']) . "'"));
    if ($u[0]) {
        $body = "A(z) ".$_POST['name']." bejelentkezési címhez tartozó jelszava: ".$u[0]."\n\nÜdvözlettel,\nmaxima.hu\n";
        $subject = "Maxima.hu - jelszóemlékeztető";
        $headers = "From: maxima@maxima.hu\r\n" .
                   "Content-Type: text/plain; charset=iso-utf-8\r\n";
        mail($_POST['name'], $subject, $body, $headers);	
        $success=1;	
    }
    mysql_close();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>maxima - home</title>
<link rel="shortcut icon" href="favicon2.ico" />
<link href="/maxima_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/skin.css">
<script type="text/javascript" src="/js/ajax.js"></script>
</head>
<body>
	<table width="100%" height="100%">
		<tr><td align="center" valign="middle">
	<div style=" background-color: white; width: 270px; border: 1px solid black;">  			
<?php if(!$success){ ?>		
    <form action="" id="remember" method="post">
<fieldset>
		<p>Amennyiben elfelejtette jelszavát kérjük adja meg bejelentkezéséhez használt e-mail címét, melyre elküldjük jelszavát.</p>
    <table id="gyorsreg" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><label for="name">E-mail c&iacute;m:</label></td>
  </tr>
  <tr>
    <td><input type="text" id="name" name="name" /></td>
  </tr>
  <tr>
    <td style="padding:5px 0 5px 0"><div class="gomb_holder">
      <div class="gomb_left"></div>
      <div class="gomb_mid"><a href="#" onclick="document.getElementById('remember').submit();">elküld</a></div>
      <div class="gomb_right"></div>
    </div></td>
  </tr>
</table>
</fieldset>
</form>
<?php }else{ ?> 
	<div style="padding: 40px 0px 40px 0px;">			
	Jelszavát elküldtük a megadott e-mail címre.
	</div>
<?php } ?>	
  	</div>
</td></tr></table>
</body>
</html>
