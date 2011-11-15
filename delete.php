<?php

include_once "functions.php";

$path = $_GET['p'];

if ( is_dir($path) )
	deldir($path);
else
	unlink($path);

?>