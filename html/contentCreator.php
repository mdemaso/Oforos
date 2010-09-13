<?php
	// Directs to the correct HTML containing file
	function contentCreatorDotPHP(){
		if(isset($_GET['login'])){
			require_once('login.php');
			loginDotPHP();
		}else if(isset($_GET['create']) || isset($_GET['edit']) || isset($_GET['commit']) || isset($_GET['delete'])){
			require_once('forms.php');
			formsDotPHP();
		} else if(isset($_GET['calendar']) || isset($_GET['event'])){
			require_once('calendar.php');
			calendarDotPHP();
		} else if(isset($_GET['files']) || isset($_GET['file'])){
			require_once('file.php');
			fileDotPHP();
		} else if(isset($_GET['contacts']) || isset($_GET['user']) || isset($_GET['group'])){
			require_once('user.php');
			userDotPHP();
		} else {
			require_once('message.php');
			messageDotPHP();
		}
	}
?>