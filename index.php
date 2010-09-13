<?php
	// This is the main entry point of the site.
	// It handles all requests made to the server and fetches needed files
	
	// This function is to clean the sent file to the smallest, still working
	// size possible by removing extra whitespace
	function callback($buffer){
		$whiteSpace = array(" ", "\t");
		return (str_replace($whiteSpace, " ", $buffer));
	}
	
	// Starts the buffered output of the website.
	// This lets me edit header data late into the file giving me the
	// option to redirect to other pages or change data types.
	ob_start("callback");
	
	// Starts the session for client interaction
	session_start();
	
	// Gets a long list of common functions use around the site
	require_once('php/commonFunctions.php');
	
	// Gets the main HTML generating file
	require_once('html/index.php');
	
	// Flushes the buffer and delivers content to the user
	ob_end_flush();
?>