<?php
error_reporting(E_ALL);
function list_files($dir, $indent = '') {
	// Get the list of files and directories
	$files = scandir($dir);

	// Loop through the array and print the files and directories
	foreach ($files as $file) {
		if ($file != '.' && $file != '..') {
			echo $indent . $file . '<br>';
			if (is_dir($dir . '/' . $file)) {
				// If the item is a directory, call the function recursively
				list_files($dir . '/' . $file, $indent . '&nbsp;&nbsp;&nbsp;');
			}
		}
	}
}

$dir = '/home/launc19348/public_html/';

// Call the function to print the list of files and directories
list_files($dir, '-');

?>