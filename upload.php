<html>
<head>
<title></title>

<?php

if ( isset($_GET['d']) )
	$dir = $_GET['d'];
else
	$dir = str_replace("\\", "/", __DIR__) ;

?>

<script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript">

$(document).ready(function() {

	/*$("userFrm").submit( function() {
		
		var ufileValue = $("input[name='ufile']").val();
		
		if ( !ufileValue[0] ) {
		
			$('#msg').hide();
			$('#error').hide().slideDown().html("Please select a file.");
		
			return false;
		
		}
	
		return true;
	
	} ); */

} ); 

</script>

</head>

<center>

<br /><form action="upload4jquery.php" enctype="multipart/form-data" method="post" style="margin-bottom: 20px">

<input id="ufile" name="ufile" type="file">
<input style="margin-left: 10px;" type="submit" value="Upload">
<input id="dir" name="dir" type="hidden" value="<?php print $dir; ?>">

<img id="loading" src="img/load.gif" style="margin-left: 20px; visibility: hidden" />

</form>

<br><div id="msg" style="display: none; width: 300px"></div>
<div id="error" style="display: none; width: 300px"></div>

</center>

</html>