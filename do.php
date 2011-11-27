<?php

require_once "functions.php";

$action = $_GET['action'];

switch($action) {
	
	case 'copy':	
		
		@$from = $_GET['f'];
		@$to = $_GET['t'];
		
		if ( isset($from, $to) )
			copy($from, $to);
			
		break;
	
	case 'delall':
		
		@$path = $_GET['p'];
		
		if ( isset($path) ) {
		
			if ( is_dir($path) )
				deldir($path, false);
		
		} else
			print "Nothing to delete.";
		
		break;
	
	case 'delete':
		
		$path = $_GET['p'];
		
		if ( is_dir($path) )
			deldir($path);
		else
			unlink($path);
		
		break;
	
	case 'new':
		
		file_put_contents($_GET['n'], "");
		
		break;
	
	case 'newdir':
		
		mkdir( $_GET['p'] );
		
		break;
	
	case 'newhtml':
		
		$html = 
		'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\r\n".
		'<html xmlns="http://www.w3.org/1999/xhtml">'."\r\n".
		'<head>'."\r\n".
		'<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'."\r\n".
		'<title>New HTML Document</title>'."\r\n".
		'</head>'."\r\n\r\n".
		'<body>'."\r\n\r\n".
		'</body>'."\r\n\r\n".
		'</html>';
		
		file_put_contents($_GET['n'], $html);
		
		break;
	
	case 'read':
		
		print file_get_contents( $_GET['f'] );	// probably the most complicated thing I've ever written.  WHOA.
		
		break;
		
	case 'rename':
		
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
		
		break;
	
	case 'save':
		
		$file = $_POST['f'];
		
		$content = $_POST['c'];
		
		file_put_contents($file, $content);
		
		break;
	
}

?>