<?php
	// This page controls and authenticates admins
	
	// Checks user for admin group
	function adminCheck(){
		if($_SESSION['admin'] == 1){
			return true;
		}else{
			return false;
		}
	}
?>