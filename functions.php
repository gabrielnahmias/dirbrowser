<?php

define("DATE_FMT", "F jS, Y \\a\\t g:i A");

// this next function proved to be a resource whore AND not entirely relevant (e.g.,
// it's recursive so it goes into each subdirectory, not giving information specifically
// for the current folder.  takes FOREVER on C: haha.

/*
function dir_info($path) {
	
	$totalsize = 0;
	$totalcount = 0;
	$dircount = 0;
	
	if ( $handle = opendir ($path) ) {
	
		while ( false !== ($file = readdir($handle) ) ) {
		  
		  $nextpath = $path . '/' . $file;
		  
		  if ( $file != '.' && $file != '..' && !is_link($nextpath) ) {
			
			if ( is_dir($nextpath) ) {
			  
			  $dircount++;
			  
			  $result = dir_info($nextpath);
			  
			  $totalsize += $result['size'];
			  $totalcount += $result['files'];
			  $dircount += $result['dirs'];
			
			} elseif ( is_file($nextpath) ) {
			  
			  $totalsize += filesize ($nextpath);  
			  $totalcount++;
			
			}
			
		  }
		  
		}
		
	}
	
	closedir ($handle);
	
	$total['size'] = format_size($totalsize);
	$total['files'] = $totalcount;
	$total['dirs'] = $dircount;
	
	return $total;
	
}*/

function deldir($dir, $deldir = true) {
	
	// simple function to delete all files in given directory,
	// allowing the deletion of the folder
		
	$current_dir = opendir($dir);

	while ( $entryname = readdir($current_dir) ) {
		
		if ( is_dir("$dir/$entryname") and ( $entryname != "." and $entryname!=".." ) )
			deldir("${dir}/${entryname}");
		elseif ( $entryname != "." and $entryname!=".." )
			unlink("${dir}/${entryname}");
		
	}
	
	closedir($current_dir);
	
	if ($deldir)
		rmdir( ${dir} );

}

function format_size($size) {
	
	// simple presentation assistant
	
	$digits = 2;
	
	if ( $size < 1024 )
		return "$size bytes";
	
	elseif( $size < ( 1024 * 1024 ) ) {
		
		$size = round( ( $size / 1024 ), $digits);
		
		return "$size KB";
	
	} elseif( $size < ( 1024 * 1024 * 1024 ) ) {
	
		$size = round( ( $size / ( 1024 * 1024 ) ), $digits);
	
		return "$size MB";
	
	} else {
	
		$size = round( ( $size / ( 1024 * 1024 * 1024) ), $digits);
	
		return "$size GB";
	
	}
	
}

function random_color() {
	
	//mt_srand( (double) microtime() * 1000000 );
	
	$c = '';
	
	while(strlen($c)<6)
		$c .= sprintf("%02X", mt_rand(0, 200));
	
	return $c;
	
}

function sort_dir_by_type($filepath, $dir=".") {
	
	// make this have an extra argument that dictates what sorting to do.
	// also, i dont think $dir is necessary but ill have to look into why i used it.
	
	$dir = str_replace("\\", "/", $dir);
	
	$dirs   = glob($filepath.'/*', GLOB_ONLYDIR);
	$files  = glob($filepath.'/*');
	
	natcasesort($files);
	natcasesort($dirs); // if i don't do this, capitalization will affect the sorting.  just try commenting out this call.
	
	$all    = array_unique( array_merge($dirs, $files) );
	$filter = array($filepath.'/Thumbs.db', $filepath.'/..', $filepath.'/.');
		
	foreach ($all as $path) {
		
		$path= str_replace("./", "/", $path);
		
		@$final[] = $dir.$path;
	
	}
	
	return array_diff($final, $filter);

}

//print "<font color=\"" . random_color() . "\">TEST!!</font>";

?>