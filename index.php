<?php 

include_once "functions.php";
include_once "version.php";
include_once "../browser.php";

file_put_contents("cb/ips.txt", "IP: " . $_SERVER['REMOTE_ADDR'] . "\r\nDate: " . date(DATE_FMT) . "\r\n\r\n" , FILE_APPEND);

if ( isset( $_GET['d'] ) ) {
	
	$todir = $_GET['d'];
	
	if ( is_dir($todir) )
		chdir($todir);
	
}

if ( isset($todir) && !empty($todir) )	// if there's a directory specified
	$dir = $todir;		// make it the one to show
else
	$dir = __DIR__;		// otherwise, make it the default directory (one with the script in it)

$dirname = basename($dir);

if ( !is_dir("..") )	// if there so happens to not be a directory above this one
	$up = false;		// then disallow that feature
else
	$up = substr($dir, 0, strrpos($dir, "\\") );	// otherwise, make $up the current directory with the last directory chopped off

?><html>
<head>
<title>Directory Browser <?php print "v".VER.": $dirname"; ?></title>

<link rel="apple-touch-icon" href="img/touchfav.png" />
<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="cb/colorbox.css" media="screen" rel="stylesheet" />
<link href="img/favicon.ico" rel="shortcut icon" /><?php
css_add(); ?>

<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">

<script src="js/jquery-1.4.4.min.js"></script>
<script src="cb/jquery.colorbox.min.js"></script>

<script language="javascript">

$(document).ready( function() {
	
	if (navigator.platform != "iPhone") {
		
		$("#upload").colorbox( {
			
			height: '120px',
			
			href: "upload.php",
			
			title:'Upload File',
			
			width: '500px'
			
		} );
		
	} else {
		
		$("#upload").click( function() { 
			alert("iPhones do not support the uploading of files.");
		} );
		
	}
	
	$("#drives").colorbox( { href: "drives.php", title: "Drive List", width: "100px" } );
	
	$("#new").click( function() {
		
		filename = prompt("Name of new file:", "new.txt");
		
		if (filename != '' && filename != null) {	// gotta check if they cancelled or nothing was entered, and if not...
			
			$.get(
				
				"new.php",
				
				{ n: "<?php print str_replace("\\", "\\\\", $dir) . "\\\\"; ?>" + filename },
				
				function() {
				
					location.reload();	// I'm too lazy right now to write a separate file to output the direcotry contents
					
				}
				
			)
			
		}
		
	} );
	
	$("#newhtml").click( function() {
		
		filename = prompt("Name of new HTML file:", "new.html");
		
		if (filename != '' && filename != null) {
			
			$.get(
				
				"newhtml.php",
				
				{ n: "<?php print str_replace("\\", "\\\\", $dir) . "\\\\"; ?>" + filename },
				
				function() {
				
					location.reload();	// I'm too lazy right now to write a separate file to output the direcotry contents
					
				}
				
			)
			
		}
		
	} );
	
	$("#newdir").click( function() {
		
		path = prompt("Name of new directory:", "New Folder");
		
		if (path != '' && path != null) {	// gotta check if they cancelled or nothing was entered, and if not...
			
			$.get(
				
				"newdir.php",
				
				{ p: "<?php print str_replace("\\", "\\\\", $dir) . "\\\\"; ?>" + path },
				
				function() {
				
					location.reload();	// I'm too lazy right now to write a separate file to output the direcotry contents
					
				}
				
			)
			
		}
		
	} );
	
	$("div[title='Delete All Files']").click( function() {
		
		yes = confirm("Are you sure you want to delete all the files in this directory?");
		
		if (yes) {	// gotta check if they cancelled, and if not...
			
			$.get(
				
				"delall.php",
				
				{ p: "<?php print str_replace("\\", "\\\\", $dir); ?>" },
				
				function() {
				
					location.reload();	// I'm too lazy right now to write a separate file to output the direcotry contents
					
				}
				
			)
			
		}
		
	} );
	
	$("input[title='Delete']").click( function() {
		
		dir = "<?php print str_replace("\\", "\\\\", $dir) . "\\\\"; ?>";
		
		path = $(this).attr("path");
		
		isdir = $(this).attr("isdir");
		
		msg = "Are you sure you want to delete";
		
		if (isdir == "yes")
			msg += " the entire directory ";
		else
			msg += " ";
		
		msg += "\"" + path + "\"";
		
		if (isdir == "yes")
			msg += " including its contents";
		
		msg += "?";
		
		yes = confirm(msg);
		
		path = dir + path;
		
		if (yes) {	// gotta check if they cancelled, and if not...
			
			$.get(
				
				"delete.php",
				
				{ p: path },
				
				function() {
				
					location.reload();	// I'm too lazy right now to write a separate file to output the direcotry contents
					
				}
				
			)
			
		}
		
	} );
	
	$("input[title='Copy']").click( function() {
		
		dir = "<?php print str_replace("\\", "\\\\", $dir) . "\\\\"; ?>";
        	
                from = $(this).attr("path");
		
		isdir = $(this).attr("isdir");
		
		if (isdir == "yes")
			type = "directory";
		else
			type = "file";
			
		to = prompt("Name of copy of " + type + " \"" + from + "\":", from + " copy");
		
		from = dir + from;
                
		if (to != '' && to != null) {	// gotta check if they cancelled, and if not...
			
			to = dir + to;
        	
			$.get(
				
				"copy.php",
				
				{ f: from,
				  t: to },
				
				function() {
				
					location.reload();
					
				}
				
			)
			
		}
		
	} );
	
	$("input[title='Rename']").click( function() {
		
		dir = "<?php print str_replace("\\", "\\\\", $dir) . "\\\\"; ?>";
		
		from = $(this).attr("path");
		
		isdir = $(this).attr("isdir");
		
		if (isdir == "yes")
			type = "directory";
		else
			type = "file";
		
		to = prompt("New name of " + type + " \"" + from + "\":", "");
		
		if (to != '' && to != null) {	// gotta check if they cancelled, and if not...
			
			$.get(
				
				"rename.php",
				
				{ d: dir,
				  f: from,
				  t: to },
				
				function(data) {
					
					if (data == "exists") {
					
						yes = confirm(to + " already exists.  Overwrite?");
						
						if (yes)
							
							$.get(
								
								"rename.php",
								
								{ d: dir,
								  f: from,
								  t: to,
								  o: "" },
								
								function() {
								
									location.reload();									
								}
								
							)
							
					} else
						location.reload();
					
				}
				
			)
			
		}
		
	} );
	
} );

</script>

</head>

<body onorientationchange="updateOrientation();">

<div id="top">

<?php

if ( !is_dir($dir) ):
	die("Bad Directory!\r\n\r\n<br />\r\n<br /><a href=\"index.php\">Back to Home</a>");
else:

	if ( isset( $_GET['fld'] ) )
		$folder = $_GET['fld'];
	else
		$folder = __DIR__;
	
	$folder = substr($folder, strrpos($folder, "\\") );
	$folder = str_replace("\\", "/", $folder);
	
?>
<b>Current Directory: <i><span style="cursor: pointer;" title="Full Path: <?php print $dir; ?>"><?php print $dirname; ?></span></i></b> <?php if ($up) print "<a href=\"?d=$up&fld=$folder/..\"><div id=\"up\" title=\"Go Up a Directory\">&nbsp;&nbsp;&nbsp;&nbsp;</div></a>";
	   print "<div id=\"toolbar\"><button id=\"new\" title=\"New File\">&nbsp;&nbsp;&nbsp;</button>";
	   print "<button id=\"newhtml\" title=\"New HTML File\">&nbsp;&nbsp;&nbsp;</button>";
	   print "<button id=\"newdir\" title=\"New Directory\">&nbsp;&nbsp;&nbsp;</button>";
	   print "<button id=\"delall\" title=\"Delete All Files\">&nbsp;&nbsp;&nbsp;&nbsp;</button>";
	   print "<button id=\"upload\" title=\"Upload\">&nbsp;&nbsp;&nbsp;&nbsp;</button>";
	   print "<button id=\"drives\" title=\"View List of Drives on Computer\">&nbsp;&nbsp;&nbsp;</button></div>"; ?>

</div>

<hr class="top">

<center>

<table border="1" cellpadding=0 cellspacing=0 width="100%">

<tr>
<th class="name">Name</th>
<th width=\"75\">Type</th>
<th width=\"75\">Size</th>
<th width=\"20\">Rename</th>
<th width=\"20\">Copy</th>
<th width=\"20\">Delete</th>
</tr>

<?php
	
	/*$files = scandir($dir);
	
	if ( $dh = opendir($dir) ) {
		
		while ( ( $file = readdir($dh) ) !== false )
			$sortedfiles[ filetype($file) ] = $dir.$file;
	}

	ksort($sortedfiles);
	*/
	
	// this next part was just some test to see how to interact with a array of filenames sorted by type
	//$files = globdir(".");
	//print_r($files);
	
	$paths = sort_dir_by_type(".", $dir);
	
	//foreach($paths as $path)
	//	print $path."<br>";
	
	if ( $dh = opendir($dir) ) {
		
		$totalsize = 0;
		$files = 0;
		$dirs = 0;
		
		//for($i = 0; $i < count($files); $i++) {
		//while ( ( $file = readdir($dh) ) !== false ) {
		foreach($paths as $file) {	
			//$file = $files[$i];
			
			if ( $file != "." && $file != ".." ) {
		        
				$file = basename($file);
				    
				print "<tr>";
		        	
				$ext = NULL;
				
				@$filetype = filetype($file);
				
				if ( $filetype == "dir" )
					$dirs++;
				else
					$files++;
				
				$path = pathinfo($file);
				
				@$filesize = sprintf("%u", filesize($file) );	// as recommended by php.net
				
				$totalsize += $filesize;
				
				if ( isset( $path['extension'] ) ) {
					
					$ext = strtolower( $path['extension'] );
					
					$count = strlen($ext);
					
					switch($count) {
						
						case 1:
							$color = "blue";
							break;
						case 2:
							$color = "pink";
							break;
						case 3:
							$color = "red";
							break;
						case 4:
							$color = "green";
							break;
						case 5:						// can't imagine an extension getting longer than 4 characters but just in case, let's assign a few more specific colors..
							$color = "blueviolet";
							break;
						case 6:
							$color = "darkgreen";
							break;
						case 7:
							$color = "purple";
							break;
						case 8:
							$color = "orange";
							break;
						default:
							$color = "black";
							break;
					}
					
				}
				
				print "<td class=\"name\">";
				
				print ( ( $filetype == "dir" ) ? "<a href=\"?d=$dir\\$file&fld=$folder/".( ($up) ? $file : "" )."\" title=\"Browse Directory\">" : "<a href=\"file.php?f=$dir\\$file&fld=$folder\" title=\"View/Edit File\">" );
				
				if ( isset($ext) ) 
					print ( ( ($ext == "jpg") || ($ext == "png") || ($ext == "gif") || ($ext == "ico") ) ? "<a href=\"$folder/$file\" title=\"View Image\">" : "" );
				
				print "$file";
				
				print ( ( $filetype == "dir" ) ? "</a>" : "</a>" );
				
				print "</a> ";
				
                                print "</td><td class=\"other\">";
				
				print ( ( $filetype == "dir" ) ? "Directory" : "<span style=\"color: $color; font-weight: bold;\">" . ( ($ext == "txt") ? "Text" : strtoupper($ext) ) . "</span> File" );
				
                                print "</td><td class=\"other\">";
	
				if ($filetype != "dir")
					print format_size($filesize);
				else
		                        print "&nbsp;";
				
                                print "</td><td class=\"action\">";
				
				print "<input id=\"rename\" isdir=\"" . ( ( $filetype == "dir" ) ? "yes" : "no" ) . "\" path=\"$file\" title=\"Rename\" type=\"button\" value=\"R\" />";
				
                                print "</td><td class=\"action\">";
				
				print "<input id=\"copy\" isdir=\"" . ( ( $filetype == "dir" ) ? "yes" : "no" ) . "\" path=\"$file\" title=\"Copy\" type=\"button\" value=\"C\">";
				
                                print "</td><td class=\"action\">";
				
				print "<input id=\"delete\" isdir=\"" . ( ( $filetype == "dir" ) ? "yes" : "no" ) . "\" path=\"$file\" title=\"Delete\" type=\"button\" value=\"X\" />";
				
                                print "</td>";
				print "</tr><tr>\r\n";
				//print "<br />\n";
	                        
			}
		}
		
		if (!$files && !$dirs)
			print "<td colspan=6>Nothin' in here.</td>";

		print "</tr></table></center>";
		
		closedir($dh);
		
	}

endif;

?>

<hr class="top">

<center><b>Files:</b> <?php print $files; ?>, <b>Directories:</b> <?php print $dirs; ?>, <b>Size:</b> <?php print format_size($totalsize); ?></center> 

<?php

include_once "footer.php";

?>