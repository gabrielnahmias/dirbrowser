<?php

include_once "version.php";
include_once "../browser.php";

if ( isset($_GET['f']) )
	$file = $_GET['f'];	// gotta get these things before anything really
else
	$file = "index.php";

$dir = substr($file, 0, strrpos($file, "\\") );

$br = new Browser;

?><html>
<head>
<title>File Viewer <?php print "v".VER.": ".basename($file); ?></title>

<?php if ( $br->Platform == "iPhone" ): ?>
<link rel="apple-touch-icon" href="img/touchfav.png" />
<?php endif; ?>
<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="cb/colorbox.css" rel="stylesheet" type="text/css" />
<link href="img/file.ico" rel="shortcut icon" /><?php
css_add(); ?>

<?php if ( $br->Platform == "iPhone" ): ?>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
<?php endif; ?>

<script src="js/jquery-1.4.4.min.js"></script>
<script src="js/jquery.highlight-3.js"></script>
<script src="cb/jquery.colorbox.min.js"></script>

<script language="javascript">

$(document).ready( function() {
	//$("body").click(function(){alert("clicked")});
	/* the following is a fun idea i played with to get a niftier look
	   for the line numbers but i decided its too unreliable
	
	$(".ln:first").css("border-top", "2px solid #aaa");
	$(".ln:eq(1)").css("margin-left", "-62px");
	$(".ln:last").css("border-bottom", "2px solid #aaa");
	
	*/
	
	$("#save").hide();
	
	$(window).keypress( function(event) {
		
		if ( $("#textbox").css("display") == "none" ) {
			
			// This allows Ctrl+E to edit the file.
			
			if ( !(event.which == 101 && event.ctrlKey) )
				return true;
			
			$("#edit").click();
			
			event.preventDefault();
			
			return false;
			
		}
		
	} );
	
	// ADD A CLOSE FILE FUNCTION BUTTON!!!!!!!!!!!
	
	$(window).keypress( function(event) {
		
		if ( $("#textbox").css("display") == "none" ) {
			
			// This allows Ctrl+G to go to a specific line number.
			
			if ( !(event.which == 103 && event.ctrlKey) )
				return true;
			
			$("#goto").click();
			
			event.preventDefault();
			
			return false;
			
		}
		
	} );
	
	$(window).keypress( function(event) {
		
		if ( $("#textbox").css("display") != "none" ) {
			
			// This allows Ctrl+S to save the file.
			
			if ( !(event.which == 115 && event.ctrlKey) && !(event.which == 19) )
				return true;
			
			$("#save").click();
			
			event.preventDefault();
			
			return false;
			
		}
		
	} );
	
	$("#edit").click( function() {
		
		$.get(
			
			"do.php?action=read",
			
			{ f: "<?php print str_replace("\\", "\\\\", $file); ?>" },
			
			function(data) {
				
				$("#edit").hide();
				
				$("#goto").hide();

				$("#output").hide();
				
				$("#textbox").show().text(data).focus();
				
				$("#status").html("Now Editing");
				
				$("#save").show();
				
			}
			
		)
		
	} );
	
	$("#goto").click( function() {
		
		ln = prompt("Enter line number to go to:", "");
		
		if (ln != '' && ln != null) {
			
			url = "<?php print basename(__FILE__) . "?" . str_replace("\\", "\\\\", $_SERVER['QUERY_STRING'] ); ?>#" + ln;
			
			if ( $( ".ln:eq(" + (ln-1) + ")" ).length != 0 ) {	// check if the element exists
				
				location.href = url;
				
				$(".ln").css("background", "");
				//$(".line").css("background", "");
				
				$(".ln:eq(" + (ln) + ")").css("background", "yellow");
				//$(".line:eq(" + (ln - 1) + ")").css("background", "yellow");
				
			}
			
		}
		
	} );
	
	$("#output").dblclick( function() {
		
		$("#edit").click();
		
	} );
	
	$("#save").click( function() {
		
		$("#textbox").hide();
		
		$("#save").hide();
				
		$("#status").html("Now Viewing");
		
		old = $("#output").html();
		
		$("#output").show().html("Loading file...");		
		
		$.get(
			
			"do.php?action=read",
			
			{ f: "<?php print str_replace("\\", "\\\\", $file); ?>" },
			
			function(contents) {
				
				if ( $("#textbox").val() != contents ) {	// if the textbox remains unchanged, don't bother saving the file 
					
					$.post(
						
						"do.php?action=save",
						
						{ f: "<?php print str_replace("\\", "\\\\", $file); ?>",
						  c: $("#textbox").val() },
						
						function(data) {
							
							$.get(
								
								"output_file.php",
								
								{ f: "<?php print str_replace("\\", "\\\\", $file); ?>",
								  d: "<?php print str_replace("\\", "\\\\", $dir); ?>" },
								
								function(data) {
									
									$("#output").html(data).show();
									
									$("#edit").show();
									
									$("#goto").show();
									
								}
								
							)
						
						}
						
					)
					
				} else {
					
					$("#output").html(old).show();
					
					$("#edit").show();
					
					$("#goto").show();
					
				}
				
			}
			  
		)
		
	} );
	
	$("#textbox").blur( function() {
		
		// I'm not so sure I like this anymore.  Even though it's reminiscent of many
		// existing services' methods of saving input, it disallows focusing on ANYTHING
		// else without saving the file.
		
		//$("#save").click();
		
	} );

} );

</script>

</head>

<body<?php if ($br->Name == "iPhone") { ?> onorientationchange="updateOrientation();"<? } ?>>

<?php

if ( !file_exists($file) ):
	die("<body onload=\"javascript:$.colorbox({html:'bad file!',title:'error'});\">");
else:
	
	if ( isset( $_GET['fld'] ) && !empty( $_GET['fld'] ) )
		$folder = $_GET['fld'];
	else
		$folder = dirname($dir);

		//$folder = dirname($_SERVER['REQUEST_URI']);
		//$folder = str_replace("./\\", "", dirname($_SERVER['REQUEST_URI']) );

	$fn = basename($file);
	
//print $folder;

	print "<div id=\"top\"><b><span id=\"status\">Now Viewing</span> <a href=\"$fn\" title=\"Link to File on Server\"><i>$file</i></a></b> ";
	
	// begiiiiiin TOOLBAR!  yayy!
	
	print "<a href=\"index.php?d=$dir&fld=$folder\"><div id=\"up\" title=\"Go Back to Directory\">&nbsp;&nbsp;&nbsp;&nbsp;</div></a> ";
	print "<div id=\"toolbar\"><button id=\"edit\" title=\"Edit File (Ctrl+E)\">&nbsp;&nbsp;&nbsp;</button>";
	print "<button id=\"save\" title=\"Save File (Ctrl+S)\">&nbsp;&nbsp;&nbsp;</button>";
	print " <button id=\"goto\" title=\"Goto Line Number (Ctrl+G)\">&nbsp;&nbsp;&nbsp;</button></div></div>";

	print "<hr class=\"top2\">";
	
	print "<div id=\"output\">";
	
	include "output_file.php";
	
	print "</div>";

?>

<textarea id="textbox"></textarea>

<?php

	include_once "footer.php";

endif;

?>