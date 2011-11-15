<?php

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

?>