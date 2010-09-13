<?
	// Displays the file list or the individual file
	
	// Main entry point
	function fileDotPHP(){
		// Gets the file or files based on URL
		if(isset($_GET['file'])){
			$result = mysql_query(getFile($_GET['file']));
		}else{
			$result = mysql_query(getFile(""));
		}
		
		// Displays what was retrieved
		while ($row = mysql_fetch_assoc($result)) {
			getFileGlobals($row);
			if(isset($_GET['file'])){
				$GLOBALS['adminObjectId'] = $GLOBALS['fileId'];
			}	?>
			<div class="clear">
				<div class="fl">
				<?	if(isset($_GET['file'])){ ?>
						<h1><? printHTML($GLOBALS['objectTitle']); ?></h1>
				<?	}else{ ?>
						<h2><a href="<? printHTML(fullURL(getLangVar("fileURL") . $GLOBALS['fileId'])); ?>"><? printHTML($GLOBALS['objectTitle']); ?></a></h2>
				<?	} ?>
				</div>
				<div class="fr">
				<?	$result2 = mysql_query(getUser($GLOBALS['createdId'], "userId"));
					
					$row2 = mysql_fetch_assoc($result2);
					getUserGlobals($row2);
				?>	<h3>Created By: <a href="<? printHTML(fullURL(getLangVar("userURL")) . $GLOBALS['userId']); ?>"><? printHTML($GLOBALS['userName']); ?></a></h3>
				</div>
				<div class="cl">
					<? printHTML($GLOBALS['objectText']); ?>
				</div>
			<?	if(isset($_GET['file'])){ ?>
				<div>
					<h3><a href="<? printHTML($GLOBALS['filePath']); ?>">Download</a></h3>
				</div>
			<?	}	?>
				<div class="fl">
					<h4>Created: <? printHTML(date($GLOBALS['dateTimeFormat'], addDate($GLOBALS['createdOn'], $GLOBALS['timeOffset'], "hour"))); ?></h4>
				</div>
				<div class="fr">
					<h4><a href="<? printHTML(fullURL(getLangVar("eventURL") . $GLOBALS['objectId'], "create")); ?>">Sub Event</a> | <a href="<? printHTML(fullURL(getLangVar("messageURL") . $GLOBALS['objectId'], "create")); ?>">Reply</a> | <a href="<? printHTML(fullURL(getLangVar("fileURL") . $GLOBALS['objectId'], "create")); ?>">Attach File</a> | <a href="<? printHTML(fullURL(getLangVar("fileURL") . $GLOBALS['fileId'], "edit")); ?>">Edit</a></h4>
				</div>
			</div>
	<?	}
		
		// Gets related objects
		if(isset($_GET['file'])){
			getRelatedObjects($GLOBALS['objectId']);
		}
	}
	
	// Generates the form for creating or editing files
	function fileForm($sqlType){ ?>
		<form name="eventInput" action="<? printHTML(fullURL(getLangVar("fileURL"), "commit")); ?>" method="post" enctype="multipart/form-data">
	<?	objectForm(); ?>
			<div>
				<label for="file">Filename:</label><input type="file" name="file" id="file" />
			</div>
			<div class="fr">
				<input type="submit" value="Submit"></input>
			</div>
			<input id="sqlType" name="sqlType" type="hidden" value="<? printHTML($sqlType); ?>"></input>
			<input id="fileId" name="fileId" type="hidden" value="<? printHTML($GLOBALS['fileId']); ?>"></input>
		</form>
<?	}
	
	// Captures variables and creates a prefilled form for files
	function fileEdit(){
		$result = mysql_query(getFile($_GET['file']));
		$GLOBALS['adminObjectId'] = $_GET['file'];
		if($row = mysql_fetch_assoc($result)) {
			getFileGlobals($row);
			fileForm("edit");
		}
	}
	
	// Commits the file entry to the database and sends the file to the server and forwards you to the file
	function fileCommit(){
		// Target folder, make sure it is 777
		$target = "uploads/";
		
		// Appends the file name
		$target = $target . basename( $_FILES['file']['name']);
		
		// Moves the file to target
		move_uploaded_file($_FILES['file']['tmp_name'], $target);
		if($_POST['sqlType'] == "create") {
			$result = mysql_query(createObject(getLangVar("userId"), $_POST['objectText'], $_POST['parentId'], $_POST['objectTitle']));
			$result = mysql_query(createFile(fullURL($_FILES["file"]["name"], "uploads"), $_FILES["file"]["size"], $_FILES["file"]["type"], mysql_insert_id()));
			$forwardId = mysql_insert_id();
		} else if($_POST['sqlType'] == "edit") {
			$result = mysql_query(editObject($_POST['objectText'], $_POST['parentId'], $_POST['objectTitle'], $_POST['objectId']));
			$result = mysql_query(editFile());
			$forwardId = $_POST['fileId'];
		}
		header('Location:' . fullURL(getLangVar("fileURL") . $forwardId));
	}
	
	// Removes the file from the database, not the server for linking
	function fileDelete(){
		$result = mysql_query(getFile($_GET['file']));
		if($row = mysql_fetch_assoc($result)) {
			getFileGlobals($row);
			mysql_query(deleteObject($GLOBALS['objectId']));
			mysql_query(deleteFile($GLOBALS['fileId']));
			die();
		}
		header('Location:' . fullURL(getLangVar("filesURL")));
	}
?>