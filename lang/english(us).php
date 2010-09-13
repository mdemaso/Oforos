<?php
	// This file has the $GLOBAL language array on it
	
	// Gets all the URL, POST, and COOKIE variables
	import_request_variables('gpc');
	
	// Set up the langArray
	$GLOBALS['langArray'] = array(
		'title'			=>	"Oforos. Organization for organizations.",
		'h1'			=>	"",
		'alert'			=>	"",
		'description'	=>	"",
		'keywords'		=>	"",
		'author'		=>	"Michael DeMaso (mdemaso@gmail.com); Matthew Massery ()",
		'documentType'	=>	"<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>",
		'siteURL'		=>	"http://oforos.tvolved.com/",
		'edit'			=>	"Edit",
		'editURL'		=>	"edit/",
		'homeURL'		=>	"home/",
		'home'			=>	"home",
		'loginURL'		=>	"login/",
		'login'			=>	"Login",
		'logout'		=>	"Logout",
		'controlPanel'	=>	"Control Panel",
		'threadsURL'	=>	"messages/",
		'threads'		=>	"messages",
		'messageURL'	=>	"message/",
		'compose'		=>	"Compose",
		'calendarURL'	=>	"calendar/",
		'calendar'		=>	"calendar",
		'filesURL'		=>	"files/",
		'files'			=>	"files",
		'fileURL'		=>	"file/",
		'contactsURL'	=>	"contacts/",
		'contacts'		=>	"contacts",
		'aboutURL'		=>	"message/1",
		'eventURL'		=>	"event/",
		'userURL'		=>	"user/",
		'user'			=>	"User",
		'weekURL'		=>	"week/",
		'dayURL'		=>	"day/",
		'imageURL'		=>	"img/",
		'about'			=>	"about",
		'email'			=>	"Email Address: ",
		'password'		=>	"Password: ",
		'admin'			=>	$_SESSION['admin'],
		'userId'		=>	$_SESSION['userId'],
		'userName'		=>	$_SESSION['userName'],
		'userEmail'		=>	$_SESSION['userEmail'],
		'icon'			=>	$_SESSION['icon'],
		'dayWord'		=> date("l"),
		'date'			=> date("j"),
		'month'			=> date("n"),
		'year'			=> date("Y"),
		'time'			=> date("G:i:s"),
		'timezone'		=> date("T")
	);
	
?>