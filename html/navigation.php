<?php
	// Generates HTML for navigaton
	
	// Generates the header navigation bar
	function headerNavigation() {?>
		<div id="headerNavigation" class="tc">
			<h2><a href="<?php printHTML(fullURL(getLangVar("threadsURL"))); ?>"><?php printHTML(getLangVar("threads")); ?></a>
			<a href="<?php printHTML(fullURL(getLangVar("calendarURL"))); ?>"><?php printHTML(getLangVar("calendar")); ?></a>
			<a href="<?php printHTML(fullURL(getLangVar("filesURL"))); ?>"><?php printHTML(getLangVar("files")); ?></a>
			<a href="<?php printHTML(fullURL(getLangVar("contactsURL"))); ?>"><?php printHTML(getLangVar("contacts")); ?></a></h2>
		</div>
<?php
	}
	
	// Generates the bottom navigation bar
	function footerNavigation() {?>
		<div id="footerNavigation" class="clear tc">
			<h4><a href="<?php printHTML(fullURL(getLangVar("threadsURL"))); ?>"><?php printHTML(getLangVar("threads")); ?></a>
			<a href="<?php printHTML(fullURL(getLangVar("calendarURL"))); ?>"><?php printHTML(getLangVar("calendar")); ?></a>
			<a href="<?php printHTML(fullURL(getLangVar("filesURL"))); ?>"><?php printHTML(getLangVar("files")); ?></a>
			<a href="<?php printHTML(fullURL(getLangVar("contactsURL"))); ?>"><?php printHTML(getLangVar("contacts")); ?></a></h4>
		</div>
<?php
	}
	
	// Generates the Site Map
	// FUTURE USE //
	function siteMap() {
	
	}
?>