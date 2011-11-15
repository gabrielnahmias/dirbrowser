<div style="font-size: 14pt; margin: 10px 0 10px 0;">

<center><?php

$drives = "abcdefghijklmnopqrstuvxwyz";

for ($i = 0; $i < strlen($drives) - 1; $i++) {
	
	$drive = strtoupper( $drives[$i] ) . ":";
	
	if ( is_dir($drive) )
		print "<a href=\"index.php?d=$drive\">$drive</a><br />";
	
}

?></center>

</div>