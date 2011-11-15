<?php

// the following is kinda convoluted, no?  all this does is strips the query string from
// the request so I can accurately test what script is being accessed, and change the footer
// class accordingly.

$file = basename( str_replace( "?" . $_SERVER['QUERY_STRING'], "", $_SERVER['REQUEST_URI'] ) );

?>
<hr class="footer<?php if ( $file == "file.php" ) print 2; ?>">
<span id="footer">Copyright &copy; 2011 by Gabriel Nahmias</span>

</body>

</html>