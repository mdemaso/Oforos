<?php
	// This files contains the HTML header information for each file
	// Includes CSS and JS files and generates other data from the Lang Array and from
	// the environment variables
	function htmlHeadDotPHP() { ?>
		<title><? printHTML($GLOBALS['siteName']); ?></title>
		<meta name="description" content="<? printHTML($GLOBALS['siteDescription']); ?>" />
		<meta name="keywords" content="<? printHTML(getLangVar("keywords")); ?>" />
		<meta name="author" content="<? printHTML(getLangVar("author")); ?>" />
		<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1" />
	<!-- Combo-handled YUI CSS files: --> 
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/combo?2.8.1/build/reset-fonts-grids/reset-fonts-grids.css&2.8.1/build/base/base-min.css"> 
	<!-- Combo-handled YUI CSS files: --> 
		<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/combo?2.8.1/build/assets/skins/sam/skin.css"> 
	<!-- Combo-handled YUI JS files: --> 
		<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.8.1/build/yahoo-dom-event/yahoo-dom-event.js&2.8.1/build/element/element-min.js&2.8.1/build/dragdrop/dragdrop-min.js&2.8.1/build/container/container-min.js&2.8.1/build/menu/menu-min.js&2.8.1/build/button/button-min.js&2.8.1/build/calendar/calendar-min.js&2.8.1/build/editor/editor-min.js"></script>
	<!-- Oforos Style -->
		<link rel="stylesheet" type="text/css" href="<? printHTML(getLangVar("siteURL")); ?>css/default.css">
		<link rel="stylesheet" type="text/css" href="<? printHTML(getLangVar("siteURL")); ?>css.php">
		
<?	} ?>