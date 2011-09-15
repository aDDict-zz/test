#!/usr/bin/php
<?php
	set_time_limit(0);

	include_once('../www/inc/db_prop.inc.php');
    include_once('../www/inc/_variables.php');
    $_HI_var = new HirekVar();
$connection = mysql_connect($host, $user, $psw) or die(mysql_error());
$db = mysql_select_db($data_base, $connection) or die(mysql_error());
mysql_query("Set names 'UTF8'") or die(mysql_error());
$sqls = array();
$sql = "select distinct year(date_add) y, month(date_add) m from ct_news";
$r = mysql_query($sql)  or die(mysql_error());
while ($k = mysql_fetch_assoc($r)) {
	if ($k["y"] && $k["m"]) {
		$mt = "ct_news_$k[y]_$k[m]";
		$sql = "create table if not exists $mt like ct_news";
		$sqls[] = $sql;
		$sql = "update ct_news set packed = 'yes' where year(date_add) = $k[y] and month(date_add) = $k[m]";
		$sqls[] = $sql;
		$sql = "insert into $mt select * from ct_news where year(date_add) = $k[y] and month(date_add) = $k[m] and packed = 'yes'";
		$sqls[] = $sql;
		$sql = "delete from ct_news where year(date_add) = $k[y] and month(date_add) = $k[m] and packed = 'yes' and date_add(date_add, interval 2 month) < now() ";
		$sqls[] = $sql;
	}
}
foreach($sqls as $sql) {
	print "$sql\n";
	mysql_query($sql);
}
	

?>
