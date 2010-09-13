<?php
	// Bring in all the required HTML generating functions
	require_once('htmlHead.php');
	require_once('header.php');
	require_once('navigation.php');
	require_once('footer.php');
	require_once('contentCreator.php');
	require_once('controlPanel.php');
	
	// Generates HTML based on which function is called
	printHTML(getLangVar("documentType"), "\n");?>
<html>
	<head>
		<? htmlHeadDotPHP(); ?>
	</head>
	<body class="yui-skin-sam">
		<div id="bodyContainer" class="siteMinWidth">
			<div id="header">
				<? headerDotPHP(); ?>
			</div>
			<div class="siteMargins">
				<? contentCreatorDotPHP(); ?>
			</div>
			<div id="footer" class="bottomBuffer">
				<? footerDotPHP(); ?>
			</div>
		</div> <? 
		if(adminCheck()){
			require_once('adminPanel.php');?>
			<div class="adminBar">
				Admin Options: <? adminPanelDotPHP(); ?>
			</div>
	<? 	}	?>
	</body>
</html>