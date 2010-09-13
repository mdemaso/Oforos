<?php
	// This file contains database connections and SQL statments as well
	// as functions for moving variables to the $GLOBAL space
	
	// Connects to the database
	function connectToDatabase() {
		$link = mysql_connect('oforos.db.3250439.hostedresource.com', 'oforos', 'Greek01');
		
		if (!$link) {
			setLangVar("alert", "Not connected : " . mysql_error());
		}

		// make blog the current db
		$db_selected = mysql_select_db('oforos', $link);
		
		if (!$db_selected) {
			setLangVar("alert", "Database error: " . mysql_error());
		}
		
	}
	
	// Cleans the SQL query if data comes from a user
	function cleanSQLVar($variable) {
		$variable = stripslashes($variable);
		$variable = mysql_real_escape_string($variable);
		return $variable;
	}
	
	// All of the SQL statements follow as well as the $GLOBAL functions
	function getObject($data, $by = "objectId"){
		$data = cleanSQLVar($data);
		if($by == "objectId"){
			$lastLine = "WHERE `objectId` =$data";
		}
		if($data == ""){
			$lastLine = ";";
		}
		return "SELECT  `createdId` ,  `createdOn` ,  `objectId` ,  `objectText` ,  `parentId` ,  `objectTitle` "
		. "FROM  `object`"
		. $lastLine;
	}
	
	function getObjectGlobals($row, $prefix = ""){
		@extract($row);
		$GLOBALS[$prefix . 'createdId'] = $createdId;
		$GLOBALS[$prefix . 'createdOn'] = $createdOn;
		$GLOBALS[$prefix . 'objectId'] = $objectId;
		$GLOBALS[$prefix . 'objectText'] = $objectText;
		$GLOBALS[$prefix . 'parentId'] = $parentId;
		$GLOBALS[$prefix . 'objectTitle'] = $objectTitle;
	}
	
	function getMessage($data, $by = "messageId"){
		$data = cleanSQLVar($data);
		if($by == "objectId"){
			$lastLine = "AND  `o`.`objectId` = '$data' ";
		}else if($by == "createdId"){
			$lastLine = "AND  `o`.`createdId` = '$data' ";
		}else if($by == "parentId"){
			$lastLine = "AND  `o`.`parentId` = '$data' ";
		}else if($by == "messageId"){
			$lastLine = "AND  `m`.`messageId` = '$data' ";
		}else if($by == "privateOwner"){
			$lastLine = "AND  `m`.`privateOwner` = '$data' ";
		}
		if($data == ""){
			$lastLine = "";
		}
		return "SELECT  `createdId` ,  `createdOn` ,  `o`.`objectId` ,  `objectText` ,  `parentId` ,  `objectTitle` ,  `messageId` ,  `privateOwner`, `createdOn`, DAY(`createdOn`) AS returnDay, DAYNAME(`createdOn`) AS returnDayName, MONTHNAME(`createdOn`) AS returnMonthName, MONTH(`createdOn`) AS returnMonth, YEAR(`createdOn`) AS returnYear, DATE_FORMAT(`createdOn`, '%l:%i %p') AS returnTime "
		. "FROM  `object` AS  `o` "
		. "JOIN  `message` AS  `m` "
		. "WHERE  `o`.`objectId` =  `m`.`objectId` "
		. $lastLine
		. "ORDER BY `createdOn` DESC;";
	}
	
	function getMessageGlobals($row, $prefix = ""){
		@extract($row);
		$GLOBALS[$prefix . 'createdId'] = $createdId;
		$GLOBALS[$prefix . 'createdOn'] = $createdOn;
		$GLOBALS[$prefix . 'objectId'] = $objectId;
		$GLOBALS[$prefix . 'objectText'] = $objectText;
		$GLOBALS[$prefix . 'parentId'] = $parentId;
		$GLOBALS[$prefix . 'objectTitle'] = $objectTitle;
		$GLOBALS[$prefix . 'messageId'] = $messageId;
		$GLOBALS[$prefix . 'privateOwner'] = $privateOwner;
		$GLOBALS[$prefix . 'createdOn'] = $createdOn;
		$GLOBALS[$prefix . 'returnDay'] = $returnDay;
		$GLOBALS[$prefix . 'returnDayName'] = $returnDayName;
		$GLOBALS[$prefix . 'returnMonth'] = $returnMonth;
		$GLOBALS[$prefix . 'returnMonthName'] = $returnMonthName;
		$GLOBALS[$prefix . 'returnYear'] = $returnYear;
		$GLOBALS[$prefix . 'returnTime'] = $returnTime;
	}
	
	function getEvent($data, $by = "calendarId"){
		$data = cleanSQLVar($data);
		if($by == "objectId"){
			$lastLine = "AND  `o`.`objectId` =$data";
		}else if($by == "createdId"){
			$lastLine = "AND  `o`.`createdId` =$data";
		}else if($by == "parentId"){
			$lastLine = "AND  `o`.`parentId` =$data";
		}else if($by == "attendingGroup"){
			$lastLine = "AND  `c`.`attendingGroup` =$data";
		}else if($by == "calendarId"){
			$lastLine = "AND  `c`.`calendarId` =$data";
		}else if($by == "startTimeDate"){
			$lastLine = "AND  DATE_FORMAT(`c`.`startDateTime`, '%Y%c%e') = $data";
		}
		if($data == ""){
			$lastLine = "";
		}
		return "SELECT  `createdId` ,  `createdOn` ,  `o`.`objectId` ,  `objectText` ,  `parentId` ,  `objectTitle` ,  `attendingGroup` ,  `calendarId` ,  `duration` ,  `location` , `startDateTime`, DAY(`startDateTime`) AS returnDay, DAYNAME(`startDateTime`) AS returnDayName, MONTH(`startDateTime`) AS returnMonth, MONTHNAME(`startDateTime`) AS returnMonthName, YEAR(`startDateTime`) AS returnYear, DATE_FORMAT(`startDateTime`, '%l:%i %p') AS returnTime "
		. "FROM  `object` AS  `o` "
		. "JOIN  `calendar` AS  `c` "
		. "WHERE  `o`.`objectId` =  `c`.`objectId` "
		. $lastLine . " "
		. "ORDER BY `startDateTime` ASC;";
	}
	
	function getEventGlobals($row, $prefix = ""){
		@extract($row);
		$GLOBALS[$prefix . 'createdId'] = $createdId;
		$GLOBALS[$prefix . 'createdOn'] = $createdOn;
		$GLOBALS[$prefix . 'objectId'] = $objectId;
		$GLOBALS[$prefix . 'objectText'] = $objectText;
		$GLOBALS[$prefix . 'parentId'] = $parentId;
		$GLOBALS[$prefix . 'objectTitle'] = $objectTitle;
		$GLOBALS[$prefix . 'attendingGroup'] = $attendingGroup;
		$GLOBALS[$prefix . 'calendarId'] = $calendarId;
		$GLOBALS[$prefix . 'duration'] = $duration;
		$GLOBALS[$prefix . 'location'] = $location;
		$GLOBALS[$prefix . 'startDateTime'] = $startDateTime;
		$GLOBALS[$prefix . 'returnDay'] = $returnDay;
		$GLOBALS[$prefix . 'returnDayName'] = $returnDayName;
		$GLOBALS[$prefix . 'returnMonth'] = $returnMonth;
		$GLOBALS[$prefix . 'returnMonthName'] = $returnMonthName;
		$GLOBALS[$prefix . 'returnYear'] = $returnYear;
		$GLOBALS[$prefix . 'returnTime'] = $returnTime;
	}
	
	function getFile($data, $by = "fileId"){
		$data = cleanSQLVar($data);
		if($by == "objectId"){
			$lastLine = "AND  `o`.`objectId` = $data ";
		}else if($by == "createdId"){
			$lastLine = "AND  `o`.`createdId` = $data ";
		}else if($by == "parentId"){
			$lastLine = "AND  `o`.`parentId` = $data ";
		}else if($by == "fileId"){
			$lastLine = "AND  `f`.`fileId` = $data ";
		}
		if($data == ""){
			$lastLine = "";
		}
		return "SELECT  `createdId` ,  `createdOn` ,  `o`.`objectId` ,  `objectText` ,  `parentId` ,  `objectTitle` ,  `fileId` ,  `filePath` ,  `fileSize` , `fileType`, `createdOn`, DAY(`createdOn`) AS returnDay, DAYNAME(`createdOn`) AS returnDayName, MONTH(`createdOn`) AS returnMonth, MONTHNAME(`createdOn`) AS returnMonthName, YEAR(`createdOn`) AS returnYear, DATE_FORMAT(`createdOn`, '%l:%i %p') AS returnTime "
		. "FROM  `object` AS  `o` "
		. "JOIN  `file` AS  `f` "
		. "WHERE  `o`.`objectId` =  `f`.`objectId` "
		. $lastLine
		. "ORDER BY `createdOn` DESC;";
	}
	
	function getFileGlobals($row, $prefix = ""){
		@extract($row);
		$GLOBALS[$prefix . 'createdId'] = $createdId;
		$GLOBALS[$prefix . 'createdOn'] = $createdOn;
		$GLOBALS[$prefix . 'objectId'] = $objectId;
		$GLOBALS[$prefix . 'objectText'] = $objectText;
		$GLOBALS[$prefix . 'parentId'] = $parentId;
		$GLOBALS[$prefix . 'objectTitle'] = $objectTitle;
		$GLOBALS[$prefix . 'fileId'] = $fileId;
		$GLOBALS[$prefix . 'filePath'] = $filePath;
		$GLOBALS[$prefix . 'fileSize'] = $fileSize;
		$GLOBALS[$prefix . 'fileType'] = $fileType;
		$GLOBALS[$prefix . 'createdOn'] = $createdOn;
		$GLOBALS[$prefix . 'returnDay'] = $returnDay;
		$GLOBALS[$prefix . 'returnDayName'] = $returnDayName;
		$GLOBALS[$prefix . 'returnMonth'] = $returnMonth;
		$GLOBALS[$prefix . 'returnMonthName'] = $returnMonthName;
		$GLOBALS[$prefix . 'returnYear'] = $returnYear;
		$GLOBALS[$prefix . 'returnTime'] = $returnTime;
	}
	
	function getUser($data, $by = "userId"){
		if($by == "userId"){
			$data = cleanSQLVar($data);
			$lastLine = "WHERE  `userId` =$data;";
		}else if($by == "login"){
			$data[0] = cleanSQLVar($data[0]);
			$data[1] = cleanSQLVar($data[1]);
			$lastLine = "WHERE userEmail =  '" . $data[0] . "' " . "\n"
			. "AND userPassword =  '" . $data[1] . "';";
		}
		if($data == ""){
			$lastLine = "WHERE `deleted` = 0;";
		}

		return "SELECT  `icon`, `userEmail` ,  `userId` ,  `userName` ,  `userPassword` "
		. "FROM  `user` AS  `u` "
		. $lastLine;
	}
	
	function getUserGlobals($row, $prefix = ""){
		@extract($row);
		$GLOBALS[$prefix . 'icon'] = $icon;
		$GLOBALS[$prefix . 'userEmail'] = $userEmail;
		$GLOBALS[$prefix . 'userId'] = $userId;
		$GLOBALS[$prefix . 'userName'] = $userName;
		$GLOBALS[$prefix . 'userPassword'] = $userPassword;
	}
	
	function getAttendance($data, $by = "calendarId"){
		if($by == "calendarId"){
			$lastLine = "WHERE  `calendarId` = $data;";
		}else if($by == "userId"){
			$lastLine = "WHERE  `userId` = $data;";
		}else if($by == "both"){
			$lastLine = "WHERE  `calendarId` = " . $data[0] . " "
			. "AND  `userId` = " . $data[1] . ";";
		}
		return "SELECT  `attendanceId` ,  `attended` ,  `calendarId` ,  `excused` ,  `userId` "
		. "FROM  `attendance` AS  `a` "
		. $lastLine;
	}
	
	function getAttendanceGlobals($row, $prefix = ""){
		@extract($row);
		$GLOBALS[$prefix . 'attendanceId'] = $attendanceId;
		$GLOBALS[$prefix . 'attended'] = $attended;
		$GLOBALS[$prefix . 'calendarId'] = $calendarId;
		$GLOBALS[$prefix . 'excused'] = $excused;
		$GLOBALS[$prefix . 'userId'] = $userId;
	}
	
	function getUserData($data, $by = "userId"){
		if($by == "userId"){
			$data = cleanSQLVar($data);
			$lastLine = "AND  `ud`.`userId` = $data ";
		}else if($by == "both"){
			$data[0] = cleanSQLVar($data[0]);
			$data[1] = cleanSQLVar($data[1]);
			$lastLine = "AND  `ud`.`userId` = " . $data[0] . " "
			. "AND  `ud`.`dataId` = " . $data[1] . " ";
		}
		if(!check()){
			$lastLine = $lastLine . "AND  `d`.`private` = 0";
		}
		return "SELECT  `data` ,  `ud`.`dataId` ,  `userId` ,  `userDataId` ,  `dataName` ,  `dataType` ,  `private` "
		. "FROM  `userData` AS  `ud` "
		. "JOIN  `data` AS  `d` "
		. "WHERE  `ud`.`dataId` =  `d`.`dataId` "
		. $lastLine . ";";
	}
	
	function getUserDataGlobals($row, $prefix = ""){
		@extract($row);
		$GLOBALS[$prefix . 'data'] = $data;
		$GLOBALS[$prefix . 'dataId'] = $dataId;
		$GLOBALS[$prefix . 'userId'] = $userId;
		$GLOBALS[$prefix . 'userDataId'] = $userDataId;
		$GLOBALS[$prefix . 'dataName'] = $dataName;
		$GLOBALS[$prefix . 'dataType'] = $dataType;
		$GLOBALS[$prefix . 'private'] = $private;
	}
	
	function getData(){
		return "SELECT  `dataId` ,  `dataName` ,  `dataType` ,  `private` "
		. "FROM  `data`;";
	}
	
	function getDataGlobals($row, $prefix = ""){
		@extract($row);
		$GLOBALS[$prefix . 'dataId'] = $dataId;
		$GLOBALS[$prefix . 'dataName'] = $dataName;
		$GLOBALS[$prefix . 'dataType'] = $dataType;
		$GLOBALS[$prefix . 'private'] = $private;
	}
	
	function getGroup($data, $by = "groupId"){
		if($by == "groupId"){
			$lastLine = "WHERE `groupId` = $data;";
		}
		if($data == ""){
			$lastLine = ";";
		}
		return "SELECT  `groupDescription` ,  `groupId` ,  `groupName` "
		. "FROM  `group` "
		. $lastLine;
	}
	
	function getGroupGlobals($row, $prefix = ""){
		@extract($row);
		$GLOBALS[$prefix . 'groupDescription'] = $groupDescription;
		$GLOBALS[$prefix . 'groupId'] = $groupId;
		$GLOBALS[$prefix . 'groupName'] = $groupName;
	}
	
	function getUserGroup($data, $by = "groupId"){
		if($by == "groupId"){
			$lastLine = "WHERE `groupId` = $data;";
		}else if($by == "userId"){
			$lastLine = "WHERE `userId` = $data;";
		}else if($by == "both"){
			$lastLine = "WHERE `userId` = " . $data[0] . " "
			. "AND `groupId` = " . $data[1] . ";";
		}
		if($data == ""){
			$lastLine = ";";
		}
		return "SELECT  `groupId` ,  `userGroupId` ,  `userId` "
		. "FROM  `userGroup` "
		. $lastLine;
	}
	
	function getUserGroupGlobals($row, $prefix = ""){
		@extract($row);
		$GLOBALS[$prefix . 'groupId'] = $groupId;
		$GLOBALS[$prefix . 'userGroupId'] = $userGroupId;
		$GLOBALS[$prefix . 'userId'] = $userId;
	}
	
	function createObject($userId, $objectText, $parentId, $objectTitle){
		$userId = cleanSQLVar($userId);
		$objectText = cleanSQLVar($objectText);
		$parentId = cleanSQLVar($parentId);
		$objectTitle = cleanSQLVar($objectTitle);
		return "INSERT INTO `object` ("
		. "`createdId`, "
		. "`createdOn`, "
		. "`objectId`, "
		. "`objectText`, "
		. "`parentId`, "
		. "`objectTitle`"
		. ")"
		. "VALUES ("
		. "'$userId', "
		. "NOW(), "
		. "NULL, "
		. "'$objectText', "
		. "'$parentId', "
		. "'$objectTitle'"
		. ");";
	}
	
	function editObject($objectText, $parentId, $objectTitle, $objectId){
		$objectText = cleanSQLVar($objectText);
		$parentId = cleanSQLVar($parentId);
		$objectTitle = cleanSQLVar($objectTitle);
		$objectId = cleanSQLVar($objectId);
		return "UPDATE `object` "
		. "SET `objectText` =  '$objectText', "
		. "`parentId` =  '$parentId', "
		. "`objectTitle` =  '$objectTitle' "
		. "WHERE `objectId` = $objectId "
		. "LIMIT 1;";
	}
		
	function deleteObject($objectId){
		$objectId = cleanSQLVar($objectId);
		return "DELETE FROM `object`"
		. "WHERE `objectId` = $objectId "
		. "LIMIT 1;";
	}
	
	function createMessage($objectId, $privateOwner){
		$objectId = cleanSQLVar($objectId);
		$privateOwner = cleanSQLVar($privateOwner);
		return "INSERT INTO `message` ("
		. "`messageId`, "
		. "`objectId`, "
		. "`privateOwner`"
		. ")"
		. "VALUES ("
		. "NULL, "
		. "'$objectId', "
		. "'$privateOwner'"
		. ");";
	}
	
	function editMessage($objectId, $privateOwner, $messageId){
		$objectId = cleanSQLVar($objectId);
		$privateOwner = cleanSQLVar($privateOwner);
		$messageId = cleanSQLVar($messageId);
		return "UPDATE `message` "
		. "SET `objectId` =  '$objectId', "
		. "`privateOwner` =  '$privateOwner' "
		. "WHERE `messageId` = $messageId "
		. "LIMIT 1;";
	}
		
	function deleteMessage($messageId){
		$messageId = cleanSQLVar($messageId);
		return "DELETE FROM `message` "
		. "WHERE `messageId` = $messageId "
		. "LIMIT 1;";
	}
		
	function createCalendar($attendingGroup, $duration, $location, $objectId, $startDateTime){
		$attendingGroup = cleanSQLVar($attendingGroup);
		$duration = cleanSQLVar($duration);
		$location = cleanSQLVar($location);
		$objectId = cleanSQLVar($objectId);
		$startDateTime = cleanSQLVar($startDateTime);
		return "INSERT INTO `calendar` ("
		. "`attendingGroup`, "
		. "`calendarId`, "
		. "`duration`, "
		. "`location`, "
		. "`objectId`, "
		. "`startDateTime`"
		. ")"
		. "VALUES ("
		. "'$attendingGroup', "
		. "NULL, "
		. "'$duration', "
		. "'$location', "
		. "'$objectId', "
		. "'$startDateTime'"
		. ");";
	}
		
	function editCalendar($attendingGroup, $duration, $location, $objectId, $startDateTime, $calendarId){
		$attendingGroup = cleanSQLVar($attendingGroup);
		$duration = cleanSQLVar($duration);
		$location = cleanSQLVar($location);
		$objectId = cleanSQLVar($objectId);
		$startDateTime = cleanSQLVar($startDateTime);
		$calendarId = cleanSQLVar($calendarId);
		return "UPDATE `calendar` "
		. "SET `attendingGroup` = '$attendingGroup', "
		. "`duration` = '$duration', "
		. "`location` =  '$location', "
		. "`objectId` =  '$objectId', "
		. "`startDateTime` =  '$startDateTime' "
		. "WHERE `calendarId` = $calendarId "
		. "LIMIT 1;";
	}
		
	function deleteCalendar($calendarId){
		$calendarId = cleanSQLVar($calendarId);
		return "DELETE FROM `calendar` "
		. "WHERE `calendarId` = $calendarId "
		. "LIMIT 1;";
	}
		
	function createData($dataName, $dataType, $private){
		$dataName = cleanSQLVar($dataName);
		$dataType = cleanSQLVar($dataType);
		$private = cleanSQLVar($private);
		return "INSERT INTO `data` ("
		. "`dataId`, "
		. "`dataName`, "
		. "`dataType`, "
		. "`private`"
		. ")"
		. "VALUES ("
		. "NULL, "
		. "'$dataName', "
		. "'$dataType', "
		. "'$private'"
		. ");";
	}
		
	function editData($dataName, $dataType, $private, $dataId){
		$dataName = cleanSQLVar($dataName);
		$dataType = cleanSQLVar($dataType);
		$private = cleanSQLVar($private);
		$dataId = cleanSQLVar($dataId);
		return "UPDATE `data` "
		. "SET `dataName` =  '$dataName', "
		. "`dataType` =  '$dataType', "
		. "`private` =  '$private' "
		. "WHERE `dataId` = $dataId "
		. "LIMIT 1;";
	}
		
	function deleteData($dataId){
		$dataId = cleanSQLVar($dataId);
		return "DELETE FROM `data` "
		. "WHERE `dataId` = $dataId "
		. "LIMIT 1;";
	}
		
	function createAttendance($attended, $calendarId, $excused, $userId){
		$attended = cleanSQLVar($attended);
		$calendarId = cleanSQLVar($calendarId);
		$excused = cleanSQLVar($excused);
		$userId = cleanSQLVar($userId);
		return "INSERT INTO `attendance` ("
		. "`attendanceId`, "
		. "`attended`, "
		. "`calendarId`, "
		. "`excused`, "
		. "`userId`"
		. ")"
		. "VALUES ("
		. "NULL , "
		. "$attended, "
		. "$calendarId, "
		. "$excused, "
		. "$userId"
		. ");";
	}
		
	function editAttendance($attended, $calendarId, $excused, $userId, $attendanceId){
		$attended = cleanSQLVar($attended);
		$calendarId = cleanSQLVar($calendarId);
		$excused = cleanSQLVar($excused);
		$userId = cleanSQLVar($userId);
		$attendanceId = cleanSQLVar($attendanceId);
		return "UPDATE `attendance` "
		. "SET `attended` = $attended, "
		. "`calendarId` = $calendarId, "
		. "`excused` = $excused, "
		. "`userId` = $userId "
		. "WHERE `attendanceId` = $attendanceId "
		. "LIMIT 1;";
	}
		
	function deleteAttendance($attendanceId){
		$attendanceId = cleanSQLVar($attendanceId);
		return "DELETE FROM `attendance` "
		. "WHERE `attendanceId` = $attendanceId "
		. "LIMIT 1;";
	}
		
	function createFile($filePath, $fileSize, $fileType, $objectId){
		$filePath = cleanSQLVar($filePath);
		$fileSize = cleanSQLVar($fileSize);
		$fileType = cleanSQLVar($fileType);
		$objectId = cleanSQLVar($objectId);
		return "INSERT INTO `file` ("
		. "`fileId`, "
		. "`filePath`, "
		. "`fileSize`, "
		. "`fileType`, "
		. "`objectId`"
		. ")"
		. "VALUES ("
		. "NULL, "
		. "'$filePath', "
		. "'$fileSize', "
		. "'$fileType', "
		. "'$objectId'"
		. ");";
	}
		
	function editFile($filePath, $fileSize, $fileType, $objectId, $fileId){
		$filePath = cleanSQLVar($filePath);
		$fileSize = cleanSQLVar($fileSize);
		$fileType = cleanSQLVar($fileType);
		$objectId = cleanSQLVar($objectId);
		$fileId = cleanSQLVar($fileId);
		return "UPDATE `file` "
		. "SET `filePath` =  '$filePath', "
		. "`fileSize` =  '$fileSize', "
		. "`fileType` =  '$fileType', "
		. "`objectId` =  '$objectId' "
		. "WHERE `fileId` = $fileId "
		. "LIMIT 1;";
	}
		
	function deleteFile($fileId){
		$fileId = cleanSQLVar($fileId);
		return "DELETE FROM `file` "
		. "WHERE `fileId` = $fileId "
		. "LIMIT 1;";
	}
		
	function createGroup($groupDescription, $groupName){
		$groupDescription = cleanSQLVar($groupDescription);
		$groupName = cleanSQLVar($groupName);
		return "INSERT INTO `group` ("
		. "`groupDescription`, "
		. "`groupId`, "
		. "`groupName`"
		. ")"
		. "VALUES ("
		. "'$groupDescription', "
		. "NULL, "
		. "'$groupName'"
		. ");";
	}
	
	function editGroup($groupDescription, $groupName, $groupId){
		$groupDescription = cleanSQLVar($groupDescription);
		$groupName = cleanSQLVar($groupName);
		$groupId = cleanSQLVar($groupId);
		return "UPDATE `group` "
		. "SET `groupDescription` =  '$groupDescription',"
		. "`groupName` =  '$groupName' "
		. "WHERE `groupId` = $groupId "
		. "LIMIT 1 ";
	}
	
	function deleteGroup($groupId){
		$groupId = cleanSQLVar($groupId);
		return "DELETE FROM `group` "
		. "WHERE `groupId` = $groupId "
		. "LIMIT 1;";
	}
	
	function createUser($icon, $userEmail, $userName, $userPassword){
		$icon = cleanSQLVar($icon);
		$userEmail = cleanSQLVar($userEmail);
		$userName = cleanSQLVar($userName);
		$userPassword = cleanSQLVar($userPassword);
		return "INSERT INTO `user` ("
		. "`icon`, "
		. "`userEmail`, "
		. "`userId`, "
		. "`userName`, "
		. "`userPassword`"
		. ")"
		. "VALUES ("
		. "'$icon', "
		. "'$userEmail', "
		. "NULL, "
		. "'$userName', "
		. "'$userPassword'"
		. ");";
	}
	
	function editUser($icon, $userEmail, $userName, $userPassword, $userId){
		$icon = cleanSQLVar($icon);
		$userEmail = cleanSQLVar($userEmail);
		$userName = cleanSQLVar($userName);
		$userPassword = cleanSQLVar($userPassword);
		$userId = cleanSQLVar($userId);
		return "UPDATE `user` "
		. "SET `icon` =  '$icon', "
		. "`userEmail` =  '$userEmail', "
		. "`userName` =  '$userName', "
		. "`userPassword` =  '$userPassword' "
		. "WHERE `userId` = $userId "
		. "LIMIT 1;";
	}
	
	function deleteUser($userId){
		$userId = cleanSQLVar($userId);
		return "UPDATE `user` "
		. "SET `deleted` =  '1' "
		. "WHERE `userId` = $userId "
		. "LIMIT 1;";
	}
	
	function createUserData($data, $dataId, $userId){
		$data = cleanSQLVar($data);
		$dataId = cleanSQLVar($dataId);
		$userId = cleanSQLVar($userId);
		return "INSERT INTO `userData` ("
		. "`data`, "
		. "`dataId`, "
		. "`userId`, "
		. "`userDataId` "
		. ")"
		. "VALUES ("
		. "'$data', "
		. "'$dataId', "
		. "'$userId', "
		. "NULL"
		. ");";
	}
	
	function editUserData($data, $dataId, $userId){
		$data = cleanSQLVar($data);
		$dataId = cleanSQLVar($dataId);
		$userId = cleanSQLVar($userId);
		return "UPDATE `userData` "
		. "SET `data` =  $data "
		. "WHERE `dataId` = $dataId "
		. "AND `userId` = $userId "
		. "LIMIT 1;";
	}
	
	function createUserGroup($groupId, $leader, $userId){
		$groupId = cleanSQLVar($groupId);
		$leader = cleanSQLVar($leader);
		$userId = cleanSQLVar($userId);
		return "INSERT INTO `userGroup` ("
		. "`groupId`, "
		. "`leader`, "
		. "`userId`, "
		. "`userGroupId`"
		. ")"
		. "VALUES ("
		. "'$groupId', "
		. "'$leader', "
		. "'$userId', "
		. "NULL"
		. ");";
	}
	
	function editUserGroup($groupId, $leader, $userId, $userGroupId){
		$groupId = cleanSQLVar($groupId);
		$leader = cleanSQLVar($leader);
		$userId = cleanSQLVar($userId);
		$userGroupId = cleanSQLVar($userGroupId);
		return "UPDATE `userGroup` "
		. "SET `groupId` =  '$groupId', "
		. "`leader` = '$leader', "
		. "`userId` =  '$userId' "
		. "WHERE `userGroupId` = $userGroupId "
		. "LIMIT 1 ;";
	}
	
	function deleteUserGroup($userGroupId){
		$userGroupId = cleanSQLVar($userGroupId);
		return "DELETE FROM `userGroup` "
		. "WHERE `userGroupId` = $userGroupId "
		. "LIMIT 1;";
	}
	
	function getEnvironment(){
		return "SELECT  `siteName` ,  `siteDescription` ,  `adminGroup` ,  `css` ,  `timeOffset` ,  `dateTimeFormat` "
		. "FROM  `environment` "
		. "LIMIT 1;";
	}
	
	function getEnvironmentGlobals($row, $prefix = ""){
		@extract($row);
		$GLOBALS[$prefix . 'siteName'] = $siteName;
		$GLOBALS[$prefix . 'siteDescription'] = $siteDescription;
		$GLOBALS[$prefix . 'adminGroup'] = $adminGroup;
		$GLOBALS[$prefix . 'css'] = $css;
		$GLOBALS[$prefix . 'timeOffset'] = $timeOffset;
		$GLOBALS[$prefix . 'dateTimeFormat'] = $dateTimeFormat;
	}
	
	function editEnvironment($siteName, $siteDescription, $adminGroup, $css, $timeOffset, $dateTimeFormat, $environmentId){
		$siteName = cleanSQLVar($siteName);
		$siteDescription = cleanSQLVar($siteDescription);
		$adminGroup = cleanSQLVar($adminGroup);
		$css = cleanSQLVar($css);
		$timeOffset = cleanSQLVar($timeOffset);
		$dateTimeFormat = cleanSQLVar($dateTimeFormat);
		$environmentId = cleanSQLVar($environmentId);
		return "UPDATE `environment` "
		. "SET `siteName` =  '$siteName', "
		. "`siteDescription` =  '$siteDescription', "
		. "`adminGroup` =  '$adminGroup', "
		. "`css` =  '$css', "
		. "`timeOffset` =  '$timeOffset', "
		. "`dateTimeFormat` =  '$dateTimeFormat' "
		. "WHERE `environmentId` = $environmentId LIMIT 1 ;";
	}
?>