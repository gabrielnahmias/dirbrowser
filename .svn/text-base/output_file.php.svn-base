<?php

function blank_border($last = false) {
	
	// adds something of a margin to the top and bottom so dey ain't so damn close
	// to da edge, mane.
	
	$line = "<span class=\"ln\">&nbsp;</span>";
	
	$line = $line . ( (!$last) ? "<br />" : "" );
	
	print $line;
	
}

if ( !isset($file) )	// if this thing's being called from javascript, PHP ain't gonna help us with variables
	$file = $_GET['f'];
	
if ( !isset($dir) )
	$dir = $_GET['d'];

$content = file_get_contents($file);

if ( empty($content) ) {
	
	print "This file's empty.";
	
	goto bottom;
	
}

ini_set('highlight.comment', '#FF9900;'); // make comments more like Dreamweaver's

$lines = highlight_file($file, true);

$lines = explode("<br />", $lines);

// ok, so maybe a little bit of explaining is in order for the next bit of code..
// what this does is replaces any instance of a PHP filename that contains at least one character
// (which is what the {1,} business is about in the pattern) with a link to view the file.

$lines = preg_replace("/[a-zA-Z0-9\._-]{1,}\.(php|html|css)/", "<a href=\"file.php?f=$dir\\\\$0\" title=\"View this File with File Viewer\">$0</a>", $lines);

$no = 1;

blank_border();

foreach ($lines as $line) {
	
	print "<span class=\"ln\"><a name=\"$no\">$no</a></span>$line<br />";
	
	$no++;
	
}

blank_border(true);

bottom:

?>