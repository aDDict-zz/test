<?php
    include_once('inc/db_prop.inc.php');
	$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
	$db = mysql_select_db($data_base, $connection) or die(mysql_error());
	mysql_query("Set names 'UTF8'") or die(mysql_error());

    $res=mysql_query("show tables;");
    if($res)
      echo "<? \$ok=\"OK\"; ?>";

      $s=disk_free_space($_HI_var->dbtest_dir);
      echo "<? \$free=\"$s\"; ?>";

      mysql_close();

    ?>

