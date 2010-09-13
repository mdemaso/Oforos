<?
	// Generates the message view based on URL parameters
	
	// Main entry point
	function messageDotPHP(){ ?>
	<?	if(isset($_GET['message'])){
			$result = mysql_query(getMessage($_GET['message']));
		}else{
			$result = mysql_query(getMessage(""));
		}
		while ($row = mysql_fetch_assoc($result)) {
			getMessageGlobals($row);
			if(isset($_GET['message'])){
				$GLOBALS['adminObjectId'] = $GLOBALS['messageId'];
			}	?>
			<div class="clear">
				<div class="fl">
				<?	if(isset($_GET['message'])){ ?>
						<h1><? printHTML($GLOBALS['objectTitle']); ?></h1>
				<?	}else{ ?>
						<h2><a href="<? printHTML(fullURL(getLangVar("messageURL") . $GLOBALS['messageId'])); ?>"><? printHTML($GLOBALS['objectTitle']); ?></a></h2>
				<?	} ?>
				</div>
				<div class="fr">
				<?	$result2 = mysql_query(getUser($GLOBALS['createdId'], "userId"));
					
					$row2 = mysql_fetch_assoc($result2);
					getUserGlobals($row2);
				?>	<h3>Created By: <a href="<? printHTML(fullURL(getLangVar("userURL")) . $GLOBALS['userId']); ?>"><? printHTML($GLOBALS['userName']); ?></a></h3>
				</div>
				<div class="clear">
					<? printHTML($GLOBALS['objectText']); ?>
				</div>
				<div class="fl">
					<h4>Created: <? printHTML(date($GLOBALS['dateTimeFormat'], addDate($GLOBALS['createdOn'], $GLOBALS['timeOffset'], "hour"))); ?></h4>
				</div>
				<div class="fr">
					<h4><a href="<? printHTML(fullURL(getLangVar("eventURL") . $GLOBALS['objectId'], "create")); ?>">Sub Event</a> | <a href="<? printHTML(fullURL(getLangVar("messageURL") . $GLOBALS['objectId'], "create")); ?>">Reply</a> | <a href="<? printHTML(fullURL(getLangVar("fileURL") . $GLOBALS['objectId'], "create")); ?>">Attach File</a> | <a href="<? printHTML(fullURL(getLangVar("messageURL") . $GLOBALS['messageId'], "edit")); ?>">Edit</a></h4>
				</div>
			</div>
	<?	}
		
		// Gets related objects
		if(isset($_GET['message'])){
			getRelatedObjects($GLOBALS['objectId']);
		}
	}
	
	// Generates the forms for a message
	function messageForm($sqlType){ ?>
		<form name="eventInput" action="<? printHTML(fullURL(getLangVar("messageURL"), "commit")); ?>" method="post">
	<?	objectForm();?>
		<div class="fr">
			<input type="submit" value="Submit"></input>
		</div>
		<input id="sqlType" name="sqlType" type="hidden" value="<? printHTML($sqlType); ?>"></input>
		<input id="messageId" name="messageId" type="hidden" value="<? printHTML($GLOBALS['messageId']); ?>"></input>
		</form>
<?	}
	
	// Captures variables and creates a prefilled form for messages
	function messageEdit(){
		$result = mysql_query(getMessage($_GET['message']));
		$GLOBALS['adminObjectId'] = $_GET['message'];
		if($row = mysql_fetch_assoc($result)) {
			getMessageGlobals($row);
			messageForm("edit");
		}
	}
	
	// Runs SQL to delete messages, redirects to the main list
	function messageDelete(){
		$result = mysql_query(getMessage($_GET['message']));
		if($row = mysql_fetch_assoc($result)) {
			getMessageGlobals($row);
			mysql_query(deleteObject($GLOBALS['objectId']));
			mysql_query(deleteMessage($GLOBALS['messageId']));
		}
		header('Location:' . fullURL(getLangVar("threadsURL")));
	}
	
	// Runs the SQL to create or edit a message entry, also captures the forwarding id
	// and sends you to the new entry upon completion
	function messageCommit(){
		if($_POST['sqlType'] == "create") {
			$result = mysql_query(createObject(getLangVar("userId"), $_POST['objectText'], $_POST['parentId'], $_POST['objectTitle']));
			$result = mysql_query(createMessage(mysql_insert_id(), $_POST['privateId']));
			$forwardId = mysql_insert_id();
		} else if($_POST['sqlType'] == "edit") {
			$result = mysql_query(editObject($_POST['objectText'], $_POST['parentId'], $_POST['objectTitle'], $_POST['objectId']));
			$result = mysql_query(editMessage($GLOBALS['objectId'], $_POST['privateId'], $_POST['messageId']));
			$forwardId = $_POST['messageId'];
		}
		header('Location:' . fullURL(getLangVar("messageURL") . $forwardId));
	}
?>