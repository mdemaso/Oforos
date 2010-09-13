<?php
	// This file contains functions that many pages could use
	
	// Get helper files
	require_once('lang/english(us).php');
	require_once('sqlFunctions.php');
	require_once('userFunctions.php');
	require_once('adminFunctions.php');
	
	// Establish database connection
	connectToDatabase();
	
	// Get environment variables
	$getEnvironmentResult = mysql_query(getEnvironment());
	if($getEnvironmentRow = mysql_fetch_assoc($getEnvironmentResult)) {
		getEnvironmentGlobals($getEnvironmentRow);
	}
	
	// Takes a string and replaces the spaces with dashes, also can do the reverse
	function spacesToDashes($string, $reverse = false) {
		
	}
	
	// Make full URLs
	function fullURL($pageURL, $mode = "") {
		if($mode != ""){
			$mode = $mode . "/";
		}
		return $GLOBALS['langArray']['siteURL'] . $mode . $pageURL;
	}
	
	// Print to HTML Response
	function printHTML($string, $append = ""){
		echo $string . $append;
	}
	
	// Retrieve data from Lang Array
	function getLangVar($variable){
		return $GLOBALS['langArray'][$variable];
	}
	
	// Retrieve data from URL
	function getURLVar($variable){
		return $_GET[$variable];
	}
	
	// Set data in Lang Array
	function setLangVar($variable, $value = ""){
		$GLOBALS['langArray'][$variable] = $value;
	}
	
	// Check if variable is set
	function varIsSet($variable){
		if($variable != NULL){
			return true;
		} else {
			return false;
		}
	}
	
	// Creates an rss feed of the sites content
	// FOR FUTURE USE //
	function rssFeed() {
		
		header("Content-type: text/xml");
		
		$data = "SELECT p.id AS id, p.text_title AS title, p.text_body AS body, p.text_tags AS keywords, (SELECT COUNT(*) FROM `comments`
			WHERE `id_post` = p.id) AS int_comments, DATE_FORMAT(p.time_updated, '%a, %d %b %Y %T') AS updated, a.id AS user_id, a.text_name AS user_name";
		
		$sql = " FROM `posts` AS p JOIN `authors` AS a ON p.id_author = a.id
			WHERE int_mode = '1'
			ORDER BY  `time_created` DESC";
			
		
		//This checks to see if there is a page number. If not, it will set it to page 1 
		if (!(isset($page))) { 
			$page = 1; 
		} 
		
		$sql = $data . $sql;
		$result = mysql_query($sql);

		if (!$result) {
			$tags['message'] = "Error, error in the database!";
		} else {
			if (mysql_num_rows($result) == 0) {
				$tags['message'] = "Error, returned no result!";
			} else {
				while ($row = mysql_fetch_assoc($result)) {
					@extract($row);
					$body = htmlentities($body);
					$tags['content'] = $tags['content'] . "\n" .
						"<item>" . "\n" .
						"	<title>$title</title>" . "\n" .
						"	<link>" . $tags['home_url'] . "post/$id</link>" . "\n" .
						"	<description>" . "\n" .
						"		$body" . "\n" .
								htmlentities("<br>comments: <a href='" . $tags['home_url'] . "post/$id'>$int_comments</a> share: <a href='http://digg.com/submit?url=" . urlencode($tags['home_url'] . "post/$id") . "&title=" . urlencode($title) . "&bodytext=" . substr(urlencode($body), 0, 250) . "' target='_blank'><img src='" . $tags['home_url'] . "img/digg.gif' title='Digg it'></a> | <a href='http://www.stumbleupon.com/submit?url=" . urlencode($tags['home_url']) . "post/$id" . "&title=" . urlencode($title) . "' target='_blank'><img src='" . $tags['home_url'] . "img/stumbleit.png' title='StumbleUpon'></a> | <a href='http://facebook.com/sharer.php?u=" . urlencode($tags['home_url'] . "post/$id") . "&t=" . urlencode($title) . "' target='_blank'><img src='" . $tags['home_url'] . "img/facebook.gif' title='Share on facebook'></a> | <a href='http://twitter.com/home?status=" . urlencode($title) . "+" . urlencode($tags['home_url'] . "post/$id") . "&source=tvolved' target='_blank'><img src='" . $tags['home_url'] . "img/twitter.png' title='Tweet it'></a> | <a href='http://www.reddit.com/submit?url=" . urlencode($tags['home_url'] . "post/$id") . "&title=" . urlencode($title) . "' target='_blank'><img src='" . $tags['home_url'] . "img/reddit.png' title='Submit to Reddit'></a>" . "\n" . " by: <a href='" . $tags['home_url'] . "user/$user_id'>$user_name</a>") . "\n" .
						"	</description>" . "\n" .
						"	<pubDate>$updated EST</pubDate>" . "\n" .
						"	<author>$user_name</author>" . "\n" .
						"	<comments>" . $tags['home_url'] . "post/$id" . "</comments>" . "\n" .
						"	<guid>" . $tags['home_url'] . "post/$id" . "</guid>" . "\n" .
						"	<source>" . $tags['home_url'] . "rss/" . "</source>" . "\n" .
						"	<category>$keywords</category>" . "\n" .
						"</item>" .  "\n";
				}
				
				mysql_free_result($result);
			}
		}
	}
	
	// Convert date
	function dateConvert($dateIn){
		return str_replace(" ", "T", $dateIn);
	}
	
	// Add and convert dates
	function addDate($givendate, $add = 0, $to = "day") {
		$cd = strtotime(dateConvert($givendate));
		$hour = date('H', $cd);
		$minute = date('i', $cd);
		$second = date('s', $cd);
		$month = date('m', $cd);
		$day = date('d', $cd);
		$year = date('Y', $cd);
		
		if($to == "day"){
			$day = $day + $add;
		}else if($to == "month"){
			$month = $month + $add;
		}else if($to == "year"){
			$year = $year + $add;
		}else if($to == "second"){
			$second = $second + $add;
		}else if($to == "minute"){
			$minute = $minute + $add;
		}else if($to == "hour"){
			$hour = $hour + $add;
		}
		
		return mktime($hour, $minute, $second, $month, $day, $year);
	}
	
	// Get all children Objects and print them
	function getRelatedObjects($relatedObjectId){
		$result = mysql_query(getMessage($relatedObjectId, "parentId")); ?>
		<div style="float: left; clear: both;">
			<h2>Replies</h2>
		</div>
	<?	while ($row = mysql_fetch_assoc($result)) {
			getMessageGlobals($row); ?>
			<div style="padding-left: 100px; padding-right: 50px; clear: both;">
				<div style="float: left;">
					<h3><a href="<? printHTML(fullURL(getLangVar("messageURL") . $GLOBALS['messageId'])); ?>"><? printHTML($GLOBALS['objectTitle']); ?></a></h3>
				</div>
				<div style="float: right;">
				<?	$result2 = mysql_query(getUser($GLOBALS['createdId'], "userId"));
					
					$row2 = mysql_fetch_assoc($result2);
					getUserGlobals($row2);
				?>	<h4>Created By: <a href="<? printHTML(fullURL(getLangVar("userURL")) . $GLOBALS['userId']); ?>"><? printHTML($GLOBALS['userName']); ?></a></h4>
				</div>
				<div style="clear: both;">
					<? printHTML($GLOBALS['objectText']); ?>
				</div>
				<div style="float: left;">
					<h5>Created: <? printHTML(date($GLOBALS['dateTimeFormat'], addDate($GLOBALS['createdOn']))); ?></h5>
				</div>
				<div style="float: right;">
					<h5><a href="<? printHTML(fullURL(getLangVar("eventURL") . $GLOBALS['objectId'], "create")); ?>">Sub Event</a> | <a href="<? printHTML(fullURL(getLangVar("messageURL") . $GLOBALS['objectId'], "create")); ?>">Reply</a> | <a href="<? printHTML(fullURL(getLangVar("fileURL") . $GLOBALS['objectId'], "create")); ?>">Attach File</a></h5>
				</div>
			</div>
	<?	}
		
		$result = mysql_query(getEvent($relatedObjectId, "parentId")); ?>
		<div style="float: left; clear: both;">
			<h2>Sub Events</h2>
		</div>
	<?	while ($row = mysql_fetch_assoc($result)) {
			getEventGlobals($row); ?>
			<div style="padding-left: 100px; padding-right: 50px; clear: both;">
				<div style="float: left;">
					<h3><a href="<? printHTML(fullURL(getLangVar("eventURL") . $GLOBALS['calendarId'])); ?>"><? printHTML($GLOBALS['objectTitle'] . " at " . $GLOBALS['location']); ?></a></h3>
				</div>
				<div style="float: right; clear: right;">
					<h4><? printHTML(date($GLOBALS['dateTimeFormat'], addDate($GLOBALS['startDateTime'])) . " till " . date($GLOBALS['dateTimeFormat'], addDate($GLOBALS['startDateTime'], $GLOBALS['duration'], "minute"))); ?></h4>
				</div>
				<div style="clear: both">
					<? printHTML($GLOBALS['objectText']); ?>
				</div>
				<div style="float: left; clear: left;">
				<?	$result = mysql_query(getUser($GLOBALS['createdId'], "userId"));
					
					$row = mysql_fetch_assoc($result);
					getUserGlobals($row);
				?>	<h5>Created By: <a href="<? printHTML(fullURL(getLangVar("userURL")) . $GLOBALS['userId']); ?>"><? printHTML($GLOBALS['userName']); ?></a></h5>
				</div>
				<div style="float: right; clear: right;">
					<h5><a href="<? printHTML(fullURL(getLangVar("eventURL") . $GLOBALS['objectId'], "create")); ?>">Sub Event</a> | <a href="<? printHTML(fullURL(getLangVar("messageURL") . $GLOBALS['objectId'], "create")); ?>">Reply</a> | <a href="<? printHTML(fullURL(getLangVar("fileURL") . $GLOBALS['objectId'], "create")); ?>">Attach File</a></h5>
				</div>
			</div>
	<?	}
		
		$result = mysql_query(getFile($relatedObjectId, "parentId")); ?>
		<div style="float: left; clear: both;">
			<h2>Attached Files</h2>
		</div>
	<?	while ($row = mysql_fetch_assoc($result)) {
			getFileGlobals($row); ?>
			<div style="padding-left: 100px; padding-right: 50px; clear: both;">
				<div style="float: left;">
					<h3><a href="<? printHTML(fullURL(getLangVar("fileURL") . $GLOBALS['fileId'])); ?>"><? printHTML($GLOBALS['objectTitle']); ?></a></h3>
				</div>
				<div style="float: right;">
				<?	$result2 = mysql_query(getUser($GLOBALS['createdId'], "userId"));
					
					$row2 = mysql_fetch_assoc($result2);
					getUserGlobals($row2);
				?>	<h4>Created By: <a href="<? printHTML(fullURL(getLangVar("userURL")) . $GLOBALS['userId']); ?>"><? printHTML($GLOBALS['userName']); ?></a></h4>
				</div>
				<div style="clear: both;">
					<? printHTML($GLOBALS['objectText']); ?>
				</div>
				<div style="float: left;">
					<h5>Created: <? printHTML(date($GLOBALS['dateTimeFormat'], addDate($GLOBALS['createdOn']))); ?></h5>
				</div>
				<div style="float: right;">
					<h5><a href="<? printHTML(fullURL(getLangVar("eventURL") . $GLOBALS['objectId'], "create")); ?>">Sub Event</a> | <a href="<? printHTML(fullURL(getLangVar("messageURL") . $GLOBALS['objectId'], "create")); ?>">Reply</a> | <a href="<? printHTML(fullURL(getLangVar("fileURL") . $GLOBALS['objectId'], "create")); ?>">Attach File</a></h5>
				</div>
			</div>
	<?	}
	}
?>