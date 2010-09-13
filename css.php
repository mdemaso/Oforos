<?
	// The file that gets the changable CSS from the DB
	
	// Spoofs as a CSS filetype
	header("Content-Type: text/css");
	
	// Gets the common library
	require_once('php/commonFunctions.php');
	
	// Prints the contents
	printHTML($GLOBALS['css']);
?>