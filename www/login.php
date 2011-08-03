<?php
include('extranet/auth.php');
if(!empty($_GET["loginstatus"])) {
    print "<script>\nparent.location='$_MX_var->baseUrl'\n</script>";
    exit;
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
    <?php if(isset($_GET["loginerror"])): ?>
        <div style="color:#FF8B0E; font-weight:bold; padding: 5px;">
    <?php echo $_GET["loginerror"]; ?>
        </div>	
    <?php endif; ?>
    <form action="<?php echo $_MX_var->baseUrl?>/login.php" name="layerloginform" method="post">
<fieldset>
    <table id="gyorsreg" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><label for="username">E-mail c&iacute;m:</label></td>
  </tr>
  <tr>
    <td><input type="text" name="username" id="username" <?php if(isset($_GET["loginerror"])){ ?>class="input_error"<?php } ?>  onkeypress="if(window.event.keyCode == 13) document.layerloginform.submit();" /></td>
  </tr>
  <tr>
    <td><label for="password">Jelsz&oacute;</label></td>
  </tr>
  <tr>
    <td><input type="password" id="password" name="password" <?php if(isset($_GET["loginerror"])){ ?>class="input_error"<?php } ?>  onkeypress="if(window.event.keyCode == 13) document.layerloginform.submit();" /><input type="hidden" name="public_login_source" value="/login.php"/></td>
  </tr>
  <tr>
    <td style="padding:5px 0 5px 0"><div class="gomb_holder">
      <div class="gomb_left"></div>
      <div class="gomb_mid"><a href="#" onclick="document.layerloginform.submit();">Bel&eacute;p&eacute;s</a></div>
      <div class="gomb_right"></div>
    </div></td>
  </tr>
  <tr>
    <td style=" line-height:20px"><a href="/forget.php" id="forgotten">Elfelejtette a jelszav&aacute;t?</a></td>
  </tr>
</table>
</fieldset>
</form>
  			
  	</div>
</td></tr></table>
</body>
</html>
