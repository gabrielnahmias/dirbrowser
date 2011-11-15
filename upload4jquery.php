<?php

$dir = $_POST['dir'];

$temp = $_FILES['ufile']['tmp_name'];
$name = $_FILES['ufile']['name'];
$error = $_FILES['ufile']['error'];

$header = "index.php?d=$dir";

print "<script>";

if ( move_uploaded_file($temp, "$dir/".basename($name) ) )
	print "";
else
	print "alert('You need to select a file.');";

print "window.location.href='$header';";

print "</script>";

//header($header);

?>