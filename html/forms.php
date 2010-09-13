<?
	// Contains all of the creating, editing, and deleting of objects and users
	
	// Main entry point, decides which functions to call based on URL
	function formsDotPHP(){
		// Display dropdown if logged in, else print out register header
		if(check()){
			createaDropdown($_GET['create']); 
		}else{ ?>
			<div class="clear">
				<h1>Register</h1>
			</div>
	<?	}
		
		// Main login, got to the correct function based on URL
		// Most check for a logged in user, some check for admin rights
		
		// Check to see if it is a user creation
		if($_GET['create'] == "user" || $_GET['commit'] == "user"){
			require_once('user.php');
			if($_GET['create'] == "user") {
				userForm("create");
			}else if($_GET['commit'] == "user") {
				userCommit();
			}
		}else{
			// Checks to see if user is logged in
			if(check()){
				if($_GET['create'] == "message" || $_GET['commit'] == "message" || $_GET['edit'] == "message" || $_GET['delete'] == "message"){
					require_once('message.php');
					if($_GET['create'] == "message") {
						messageForm("create");
					}else if($_GET['commit'] == "message") {
						messageCommit();
					}else if($_GET['edit'] == "message") {
						messageEdit();
					}else if($_GET['delete'] == "message") {
						messageDelete();
					}
				}else if($_GET['create'] == "event" || $_GET['commit'] == "event" || $_GET['edit'] == "event" || $_GET['delete'] == "event" || $_GET['create'] == "attendance" || $_GET['commit'] == "attendance"){
					require_once('calendar.php');
					if($_GET['create'] == "event") {
						eventForm("create");
					}else if($_GET['commit'] == "event") {
						eventCommit();
					}else if($_GET['edit'] == "event") {
						eventEdit();
					}else if($_GET['delete'] == "event") {
						eventDelete();
					}else if($_GET['create'] == "attendance") {
						attendanceForm("create");
					}else if($_GET['commit'] == "attendance") {
						attendanceCommit();
					}
				}else if($_GET['create'] == "file" || $_GET['commit'] == "file" || $_GET['edit'] == "file" || $_GET['delete'] == "file"){
					require_once('file.php');
					if($_GET['create'] == "file") {
						fileForm("create");
					}else if($_GET['commit'] == "file") {
						fileCommit();
					}else if($_GET['edit'] == "file") {
						fileEdit();
					}else if($_GET['delete'] == "file") {
						fileDelete();
					}
				}else if($_GET['edit'] == "user") {
					if($_GET['user'] == $_SESSION['userId'] || adminCheck()){
						require_once('user.php');
						userEdit();
					}
				}else{
					if(adminCheck()){
						if($_GET['create'] == "userData") {
							require_once('user.php');
							userDataForm("create");
						}else if($_GET['create'] == "group") {
							require_once('user.php');
							groupForm("create");
						}else if($_GET['edit'] == "environment") {
							environmentForm();
						}else if($_GET['commit'] == "environment") {
							environmentCommit();
						}else if($_GET['delete'] == "user") {
							require_once('user.php');
							userDelete();
						}
					}else{
					
					}
				}
			}else{
				header('Location:' . fullURL("login/"));
			}
		}
	}
	
	// Creates the object drop down, includes user in an admin
	function createaDropdown($selected = ""){ ?>
		<div class="clear fl">
			<label for="createa">Create a </label>
			<select id="createa" name="createa" onchange="location.href = '<? printHTML(fullURL("", "create")); ?>' + document.getElementById('createa').value">
				<option value="">...</option>
				<option value="message" <? if($selected == "message"){ printHTML('selected="selected"'); } ?>>Message</option>
				<option value="event" <? if($selected == "event"){ printHTML('selected="selected"'); } ?>>Event</option>
				<option value="file" <? if($selected == "file"){ printHTML('selected="selected"'); } ?>>File</option>
				<? if(adminCheck()){ ?>
				<option value="user" <? if($selected == "user"){ printHTML('selected="selected"'); } ?>>User</option>
				<? } ?>
			</select>
		</div>
<?	}
	
	// Creates the form for the base class object
	// Contains javascript for the YUI editor
	function objectForm(){ ?>
		<div class="fr">
			<label for="parentId">Parent: </label>
				<select id="parentId" name="parentId">
					<option value="">Select One</option>
				<?	$result = mysql_query(getObject("", ""));
				
					while ($row = mysql_fetch_assoc($result)) {
						@extract($row);?>
					<option value="<? printHTML($objectId); ?>" <? if($GLOBALS['parentId'] == $objectId){ printHTML('selected="selected"'); } ?>><? printHTML($objectTitle); ?></option> <?	
					} ?>
				</select>
			<label for="objectTitle">Title: </label><input id="objectTitle" name="objectTitle" type="text" size="100" value="<? printHTML($GLOBALS['objectTitle']); ?>" onsubmit="validate_required(this,'Form not completed!'")"></input>
		</div>
		<div class="clear">
			<textarea id="objectText" name="objectText"><? printHTML($GLOBALS['objectText']); ?></textarea>
			<script>
				(function() {
					var Dom = YAHOO.util.Dom,
						Event = YAHOO.util.Event;
					
					var myConfig = {
						titlebar: 'Body', 
						height: '300px',
						handleSubmit: true,
						width: '100%',
						animate: true,
						autoHeight: true
					};
					
					var myEditor = new YAHOO.widget.Editor('objectText', myConfig);
					myEditor.render();
				 
				})();
			</script>
		</div>
		<input id="createdId" name="createdId" type="hidden" value="<? if(isset($GLOBALS['createdId'])){printHTML($GLOBALS['createdId']);}else{printHTML(getLangVar("userId"));} ?>"></input>
		<input id="objectId" name="objectId" type="hidden" value="<? printHTML($GLOBALS['objectId']); ?>"></input>
<?	}
	
	// Generate form for environment variables
	function environmentForm(){?>
		<div class="clear">
			<div class="tc">
				<h1>Evironment Variables</h1>
			</div>
			<form name="evironmentEdit" action="<? printHTML(fullURL("environment/", "commit")); ?>" method="post">
				<div class="userEditName fl clear">
					<label>Site Name:</label>
				</div>
				<div class="userEditField fr">
					<input name="siteName" id="siteName" type="text" value="<? printHTML($GLOBALS['siteName']); ?>"></input>
				</div>
				<div class="userEditName fl clear" style="height: 200px; padding-bottom: 10px;">
					<label>Site Description:</label>
				</div>
				<div class="userEditField fr" style="height: 200px; padding-bottom: 10px;">
					<textarea name="siteDescription" id="siteDescription" style="width: 100%; height: 200px;"><? printHTML($GLOBALS['siteDescription']); ?></textarea>
				</div>
				<div class="userEditName fl clear">
					<label for="adminGroup">Admin Group: </label>
				</div>
				<div class="userEditField fr">
					<select id="adminGroup" name="adminGroup">
						<option value="">Select One</option>
					<?	$result = mysql_query(getGroup(""));
						
						while ($row = mysql_fetch_assoc($result)) {
							getGroupGlobals($row); ?>
						<option value="<? printHTML($GLOBALS['groupId']); ?>"<? if($GLOBALS['groupId'] == $GLOBALS['adminGroup']){printHTML(' selected="selected"');} ?>><? printHTML($GLOBALS['groupName']); ?></option> <?	
						}	?>
					</select>
				</div>
				<div class="userEditName fl clear">
					<label>Time Offset:</label>
				</div>
				<div class="userEditField fr">
					<input name="timeOffset" id="timeOffset" type="text" value="<? printHTML($GLOBALS['timeOffset']); ?>"></input>
				</div>
				<div class="userEditName fl clear">
					<label>Date Time Format:</label>
				</div>
				<div class="userEditField fr">
					<input name="dateTimeFormat" id="dateTimeFormat" type="text" value="<? printHTML($GLOBALS['dateTimeFormat']); ?>"></input>
				</div>
				<div class="userEditName fl clear" style="height: 400px; padding-bottom: 10px;">
					<label>CSS:</label>
				</div>
				<div class="userEditField fr" style="height: 400px; padding-bottom: 10px;">
					<textarea name="css" id="css" style="width: 100%; height: 400px;"><? printHTML($GLOBALS['css']); ?></textarea>
				</div>
				<div class="clear fr">
					<input id="sqlType" name="sqlType" type="hidden" value="edit"></input>
					<input type="submit" value="Submit"></input>
				</div>
			</form>
		</div>
<?	}
	
	// Commit enivronment variables to the database and redirect back to editing them
	function environmentCommit(){
		if($_POST['sqlType'] == "edit") {
			$result = mysql_query(editEnvironment($_POST['siteName'], $_POST['siteDescription'], $_POST['adminGroup'], $_POST['css'], $_POST['timeOffset'], $_POST['dateTimeFormat'], 1));
		}
		header('Location:' . fullURL("environment", "edit"));
	}
?>