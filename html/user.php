<?
	// Displays all users or a user based on the URL
	
	// Main entry point
	function userDotPHP(){
		if(isset($_GET['user'])){
			$result = mysql_query(getUser($_GET['user']));
		}else{
			$result = mysql_query(getUser(""));
		}
		while ($row = mysql_fetch_assoc($result)) {
			getUserGlobals($row);
			if(isset($_GET['user'])){
				$GLOBALS['adminObjectId'] = $GLOBALS['userId'];
			}	?>
			<div style="clear">
				<div>
			<?	if(isset($_GET['user'])){ ?>
					<h1><? printHTML($GLOBALS['userName']); ?></h1>
			<?	}else{ ?>
					<h2><a href="<? printHTML(fullURL(getLangVar("userURL") . $GLOBALS['userId'])); ?>"><? printHTML($GLOBALS['userName']); ?></a></h2>
			<?	}	?>
				</div>
				<div>
					<img width="50px" src="<? printHTML($GLOBALS['icon']); ?>">
				</div>
				<div>
					Email: <? printHTML($GLOBALS['userEmail']); ?>
				</div>
			<?	$result2 = mysql_query(getUserData($GLOBALS['userId'], "userId"));
				while($row2 = mysql_fetch_assoc($result2)) {
					getUserDataGlobals($row2);
					$userDataCount = $userDataCount . $GLOBALS['dataId'] . ",";	?>
				<div>
					<? printHTML($GLOBALS['dataName']); ?>: <? printHTML($GLOBALS['data']); ?>
				</div>
			<?	}	?>
			</div>
	<?	}
	}
	
	// Generates the form for creating or edting users
	function userForm($sqlType){ 
		if($GLOBALS['icon'] == "" || $_GET['create'] == "user"){
			$GLOBALS['icon'] = fullURL("img/defaultPic.png");
		}?>
		<form name="eventInput" action="<? printHTML(fullURL(getLangVar("userURL"), "commit")); ?>" method="post">
			<div class="clear">
				<div class="userEditName fl">
					<label for="userEmail fl">Email: </label>
				</div>
				<div class="userEditField fr">
					<input id="userEmail" name="userEmail" type="text" value="<? printHTML($GLOBALS['userEmail']); ?>"></input>
				</div>
				<div class="userEditName fl">
					<label for="userPassword">Password: </label>
				</div>
				<div class="userEditField fr">
					<input id="userPassword" name="userPassword" type="password"></input>
				</div>
				<div class="userEditName fl">
					<label for="userName">Name: </label>
				</div>
				<div class="userEditField fr">
					<input id="userName" name="userName" type="text" value="<? printHTML($GLOBALS['userName']); ?>"></input>
				</div>
				<div class="userEditName fl">
					<label for="icon">Icon (URL): </label>
				</div>
				<div class="userEditField fr">
					<input id="icon" name="icon" type="text" value="<? printHTML($GLOBALS['icon']); ?>"></input>
				</div>
			<?	// Get user data if any
				$getDataResult = mysql_query(getData());
				$userDataCount = 0;
				while($getDataRow = mysql_fetch_assoc($getDataResult)) {
					getDataGlobals($getDataRow);
					$data = array($GLOBALS['userId'], $GLOBALS['dataId']);
					$getUserDataResult = mysql_query(getUserData($data, "both"));
					if(@$getUserDataRow = mysql_fetch_assoc($getUserDataResult)) {
						getUserDataGlobals($getUserDataRow);
					}
					if($GLOBALS['data'] == ""){
						$userDataQueryType = "create";
					} else {
						$userDataQueryType = "edit";
					}?>
				<div class="userEditName fl">
					<label for="<? printHTML("userData" . $userDataCount); ?>"><? printHTML($GLOBALS['dataName']); ?>: </label>
				</div>
				<div class="userEditField fr">
					<input id="<? printHTML("userData" . $userDataCount); ?>" name="<? printHTML("userData" . $userDataCount); ?>" type="text" value="<? printHTML($GLOBALS['data']); ?>"></input>
					<input id="<? printHTML("userDataId" . $userDataCount); ?>" name="<? printHTML("userDataId" . $userDataCount); ?>" type="hidden" value="<? printHTML($GLOBALS['dataId']); ?>"></input>
					<input id="<? printHTML("userDataQueryType" . $userDataCount); ?>" name="<? printHTML("userDataQueryType" . $userDataCount); ?>" type="hidden" value="<? printHTML($userDataQueryType); ?>"></input>
				</div>
			<?	$GLOBALS['data'] = "";
				$userDataCount++;
				}
				?>
				<div class="clear fr">
					<input type="submit" value="Submit"></input>
				</div>
				<input id="sqlType" name="sqlType" type="hidden" value="<? printHTML($sqlType); ?>"></input>
				<input id="userDataCount" name="userDataCount" type="hidden" value="<? printHTML($userDataCount); ?>"></input>
				<input id="userId" name="userId" type="hidden" value="<? printHTML($GLOBALS['userId']); ?>"></input>
				<input id="userPasswordHidden" name="userPasswordHidden" type="hidden" value="<? printHTML($GLOBALS['userPassword']); ?>"></input>
			</div>
		</form>
<?	}
	
	// Captures variables and creates a prefilled form for users
	function userEdit(){
		$result = mysql_query(getUser($_GET['user']));
		$GLOBALS['adminObjectId'] = $_GET['user'];
		if($row = mysql_fetch_assoc($result)) {
			getUserGlobals($row);
			userForm("edit");
		}
	}
	
	// Changes the value of the deleted column to `1`, keeps user for legacy links
	function userDelete(){
		$result = mysql_query(getUser($_GET['user']));
		if($row = mysql_fetch_assoc($result)) {
			getUserGlobals($row);
			mysql_query(deleteUser($GLOBALS['userId']));
		}
		header('Location:' . fullURL(""));
	}
	
	// Commits changes to the database
	function userCommit(){
		if($_POST['sqlType'] == "create") {
			$result = mysql_query(createUser($_POST['icon'], $_POST['userEmail'], $_POST['userName'], md5($_POST['userPassword'])));
			$userId = mysql_insert_id();
			// Commits user data to the database
			for($i = 0; $i < $_POST['userDataCount']; $i++){
				if($_POST['userDataQueryType' . $i] == "create"){
					$result = mysql_query(createUserData($_POST['userData' . $i], $_POST['userDataId' . $i], $userId));
				} else {
					$result = mysql_query(editUserData($_POST['userData' . $i], $_POST['userDataId' . $i], $userId));
				}
			}
			$forwardId = $userId;
		} else if($_POST['sqlType'] == "edit") {
			// Decides whether or not a change to the password was made
			if($_POST['userPassword'] == ""){
				$password = $_POST['userPasswordHidden'];
			}else{
				$password = md5($_POST['userPassword']);
			}
			$result = mysql_query(editUser($_POST['icon'], $_POST['userEmail'], $_POST['userName'], $password, $_POST['userId']));
			// Commits user data to the database
			for($i = 0; $i < $_POST['userDataCount']; $i++){
				// Decides on UPDATE or INSERT for user data based on whether it exsited
				if($_POST['userDataQueryType' . $i] == "create"){
					$result = mysql_query(createUserData($_POST['userData' . $i], $_POST['userDataId' . $i], $_POST['userId']));
				} else {
					$result = mysql_query(editUserData($_POST['userData' . $i], $_POST['userDataId' . $i], $_POST['userId']));
				}
			}
			$forwardId = $_POST['userId'];
		}
		header('Location:' . fullURL(getLangVar("userURL") . $forwardId));
	}
?>