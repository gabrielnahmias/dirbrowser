<?php

include_once "functions.php";

@$path = $_GET['p'];

if ( isset($path) ) {

	if ( is_dir($path) )
		deldir($path, false);

} else
	print "Nothing to delete."

?>