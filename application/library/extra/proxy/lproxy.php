<?php
// the ulta lame variation of proxy
$proxyURL = 'http://192.168.1.200/frontend/service?';
echo file_get_contents($proxyURL.$_SERVER['QUERY_STRING']);
?> 