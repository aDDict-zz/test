<?php /* Smarty version 2.6.6, created on 2010-06-29 07:02:58
         compiled from forgott_password.html */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php echo '
<style type="text/css">
body{	
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:11px;
}
</style>
'; ?>

</head>

<body>
<h3>Elfelejtett jelsz&oacute;</h3>

	<p><strong>E-mail:</strong> <?php echo $this->_tpl_vars['data']['email']; ?>
</p>
	<p><strong>Jelsz&oacute;:</strong> <?php echo $this->_tpl_vars['data']['password']; ?>
</p>

</body>
</html>