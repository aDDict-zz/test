<?php
	session_start();
	
	$_SESSION['logged'] = '';
	session_destroy();
	setcookie('Hirek', '', time()-(60*60*24*30));
	
	header("Location: index.php");
	exit;
?>
