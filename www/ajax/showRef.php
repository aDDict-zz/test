<?php
include "../include/_config.php"; 
mysql_connect("$_db_change_host","$_dbuser","$_dbpass");
mysql_select_db("$_dbname");
mysql_query("SET NAMES 'UTF8'");

$r=mysql_fetch_array(mysql_query("SELECT * FROM refs WHERE r_url='".$_GET['url']."'"));
mysql_close();

echo '
<div class="content_right_header"><h4>'.stripslashes($r['r_title']).'</h4></div>
<div class="content_right_ref">
  	<div class="content_ref_holder">
    	<center><img src="/images/references/'.$r['r_bpicture'].'" alt="" /></center>
  	</div>
</div>
<div class="content_right_text">
    <p>'.nl2br(stripslashes($r['r_text'])).'</p>
</div>
';


?>
