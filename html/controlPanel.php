<?
	// The user control panel for the website
	
	// Checks to see if the user is logged in or not and displays
	// the right content
	function controlPanelDotPHP(){
		if(check()){
			require_once('calendar.php')?>
			<div id="weekView" class="">
				<?	weekView(getLangVar("year"), getLangVar("month"), getLangVar("date"), getLangVar("userId")); ?>
			</div>
	<?	} else {	?>
			<div class="bottomLine">
				<a href="<?php printHTML(fullURL(getLangVar("loginURL"))); ?>"><?php printHTML(getLangVar("login")); ?></a> | <a href="<? printHTML(fullURL(getLangVar("userURL"), "create")); ?>">Register</a>
			</div>
	<?	}
	}
?>