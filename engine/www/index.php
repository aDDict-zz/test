<?php

require_once( "../config/config.php" );
require_once( CLASSES . "class.main.php" );

$main = new main();

echo $main->page->content;

