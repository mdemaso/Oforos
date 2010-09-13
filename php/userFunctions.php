<?php
	// This page controls and checks login info
	
	// Checks to see if user is indeed logged in
	function check() {
		if(!isset($_SESSION['userId']) || (trim($_SESSION['userId']) == '')) {
			return false;
		} else {
			return true;
			
		}
	}
	
	// Logs the user in or fails
	function login() {
		$email = $_POST['email']; 
		$password = md5($_POST['password']);
			
		$data = array($email, $password);
		
		$getUserResultLogin = mysql_query(getUser($data, "login"));
		
		if(mysql_num_rows($getUserResultLogin) == 1){ 
			while($getUserRowLogin = mysql_fetch_assoc($getUserResultLogin)){
				getUserGlobals($getUserRowLogin, "LOGIN");
				
				$data = array($GLOBALS['LOGINuserId'], $GLOBALS['adminGroup']);
				
				$getUserGroupResult = mysql_query(getUserGroup($data, "both"));
				if(mysql_num_rows($getUserGroupResult) == 1){
					$_SESSION['admin'] = 1;
				}
				
				$_SESSION['userId'] = $GLOBALS['LOGINuserId'];
				$_SESSION['userName'] = $GLOBALS['LOGINuserName'];
				$_SESSION['userEmail'] = $GLOBALS['LOGINuserEmail'];
				$_SESSION['icon'] = $GLOBALS['LOGINicon'];
			}
		}
		
		if(check()) {
			setLangVar("userId", $_SESSION['userId']);
			setLangVar("userName", $_SESSION['userName']);
			setLangVar("userEmail", $_SESSION['userEmail']);
			setLangVar("icon", $_SESSION['icon']);
		} else {
			setLangVar("alert", "Login Failed!");
		}
	}
	
	// Logs the user out
	function logout() {
		unset($_SESSION['userId']);
		unset($_SESSION['userName']);
		unset($_SESSION['userEmail']);
		unset($_SESSION['icon']);
		
		session_destroy();
	}
?>