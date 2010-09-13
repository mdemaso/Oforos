<?php
	// Contains the header for each page, also has the user panel
	
	// Gets the header and includes user panel if user is logged in
	function headerDotPHP() { ?>
		<div class="logoArea" onMouseOver="this.style.cursor = 'pointer'" onClick="document.location = '<? printHTML(getLangVar("siteURL")); ?>'">
		<?	if(check()){	?>	
			<div class="fr recentMessages">
				<div>
					<h4><a href="<? printHTML(fullURL(getLangVar("threadsURL"))); ?>">Recent Messages</a></h4>
				</div>
			<?	$result = mysql_query(getMessage(""));
				for($i = 0; $row = mysql_fetch_assoc($result); $i++) {
					@extract($row);
					if($i < 6){?>
				<div class="cellStyle"><a href="<? printHTML(fullURL(getLangVar("messageURL") . $messageId)); ?>"><? printHTML( date("g:i A", addDate($createdOn, $GLOBALS['timeOffset'], "hour")) . " " . $objectTitle); ?></a></div>
				<?	}
			}	?>
			</div>
			<div class="fr userLinks">
				<div><? printHTML(getLangVar("userName")); ?></div>
				<div><img width="50px" src="<? printHTML(getLangVar("icon")); ?>"></div>
				<div><a href="<? printHTML(fullURL(getLangVar("userURL"), "edit") . getLangVar("userId")); ?>"><? printHTML(getLangVar("edit")); ?></a></div>
				<div><a href="<? printHTML(fullURL(getLangVar(""), "create")); ?>"><? printHTML(getLangVar("compose")); ?></a></div>
				<div><a href="<? printHTML(fullURL(getLangVar("loginURL"), "exit")); ?>"><? printHTML(getLangVar("logout")); ?></a></div>
			</div>
		<?	}	?>
		</div>
		<div class="clear">
			<? headerNavigation(); ?>
		</div>
		<div class="siteMargins">
			<? controlPanelDotPHP(); ?>
		</div>
<?	}	?>