<?php

$dir = $_GET['d'];
$from = $dir . $_GET['f'];
$to = $dir . $_GET['t'];

// i need to add this existence validation checking on new file, new directory, and copy
// then add the proper javascript on index.php

if ( file_exists($to) ) {
	
	if ( isset($_GET['o'] ) )	// overwrite setting
		rename($from, $to);
	else
		print "exists";
	
} else
	rename($from, $to);

?>