<?php
	// Contains the calendar section of the site
	
	// Main entry point
	function calendarDotPHP(){
		// Logically chose what to display based on passed variables
		if(isset($_GET['event'])) {
			eventView(getURLVar("event"));
		} else if(isset($_GET['week'])){
			weekView(getURLVar("year"), getURLVar("month"), getURLVar("day"), getLangVar("userId"));
		} else if(isset($_GET['day'])) {
			dayView(getURLVar("year"), getURLVar("month"), getURLVar("day"));
		} else if(isset($_GET['month'])) {
			monthView(getURLVar("year"), getURLVar("month"));
		} else {
			monthView(getLangVar("year"), getLangVar("month"));
		}
	}
	
	// Functions to add or subtract (use a negative number) to dates
	function addMonthToDate($timeStamp, $totalMonths=1){
        $thePHPDate = getdate($timeStamp);
        $thePHPDate['mon'] = $thePHPDate['mon']+$totalMonths;
        $timeStamp = mktime($thePHPDate['hours'], $thePHPDate['minutes'], $thePHPDate['seconds'], $thePHPDate['mon'], $thePHPDate['mday'], $thePHPDate['year']);
        return $timeStamp;
    }
    
    function addDayToDate($timeStamp, $totalDays=1){
        $thePHPDate = getdate($timeStamp);
        $thePHPDate['mday'] = $thePHPDate['mday']+$totalDays;
        $timeStamp = mktime($thePHPDate['hours'], $thePHPDate['minutes'], $thePHPDate['seconds'], $thePHPDate['mon'], $thePHPDate['mday'], $thePHPDate['year']);
        return $timeStamp;
    }

    function addYearToDate($timeStamp, $totalYears=1){
        $thePHPDate = getdate($timeStamp);
        $thePHPDate['year'] = $thePHPDate['year']+$totalYears;
        $timeStamp = mktime($thePHPDate['hours'], $thePHPDate['minutes'], $thePHPDate['seconds'], $thePHPDate['mon'], $thePHPDate['mday'], $thePHPDate['year']);
        return $timeStamp;
    }
	
	// generate the month view of the calendar
	function monthView($year, $month){
		// Set up all the time variables you need
		$timestamp = mktime(0, 0, 0, $month, 1, $year);
		$numberOfDays = date("t", $timestamp);
		$monthName = date("F", $timestamp);
		$monthStart = date("w", $timestamp);
		
		// Calculate the number of calendar cells to make
		$numberOfCells = ceil(($numberOfDays + $monthStart) / 7) * 7; ?>
		<div class="clear">
			<div class="fl"><h3><a href="<? printHTML(fullURL(getLangVar("calendarURL")) . date('Y/n', addMonthToDate($timestamp, -1))); ?>"><</a></h3></div>
			<div class="fr calFix"><h3><a href="<? printHTML(fullURL(getLangVar("calendarURL")) . date('Y/n', addMonthToDate($timestamp))); ?>">></a></h3></div>
			<div class="tc">
				<h1><? printHTML($monthName . ", " . $year); ?></h1>
			</div>
			<div class="tc">
		<?	if(check()){	?>
				<a href="<? printHTML(fullURL("ical.ics")); ?>">Subscribe</a>
		<?	}	?>
			</div>
		</div>
		<div class="span">
			<div class="clear">
				<div class="first">
				</div>
				<div class="fl dayNames">
					<h3>Sunday</h3>
				</div>
				<div class="fl dayNames">
					<h3>Monday</h3>
				</div>
				<div class="fl dayNames">
					<h3>Tuesday</h3>
				</div>
				<div class="fl dayNames">
					<h3>Wednesday</h3>
				</div>
				<div class="fl dayNames">
					<h3>Thursday</h3>
				</div>
				<div class="fl dayNames">
					<h3>Friday</h3>
				</div>
				<div class="fl dayNames">
					<h3>Saturday</h3>
				</div>
			</div>
		<?	for($i = 0; $i < $numberOfCells; $i++){
				if($i % 7 == 0 && $i != $numberOfCells){ ?>
			<div class="clear">
				<div class="first">
				</div>
			<?	} ?>
				<div class="fl oneseventh<? if($i > 6){printHTML(" topLine");}?>">
					<? if($i >= $monthStart && $i < $numberOfDays + $monthStart){ getEventsForCell($year, $month, $i-$monthStart+1);} else { printHTML("&nbsp;");} ?>
				</div>
			<?	if($i % 7 == 0 && $i != 0){ ?>
			</div>
			<?	} ?>
		<?	}
	?>	</div> <?
	}
	
	// Generate the week view of the calendar (used in the user box)
	function weekView($year, $month, $day, $userId){ 
		$timestamp = mktime(0, 0, 0, $month, $day, $year); ?>
		<script type="text/javascript">
			document.getElementById('weekView').innerHTML = "";
		</script>
		<div class="clear span">
			<div class="fl"><h3><a href="<? printHTML(fullURL(getLangVar("weekURL")) . date('Y/n/j', addDayToDate($timestamp, -7))); ?>"><</a></h3></div>
			<div class="fr calFix"><h3><a href="<? printHTML(fullURL(getLangVar("weekURL")) . date('Y/n/j', addDayToDate($timestamp, 7))); ?>">></a></h3></div>
			<div class="clear">
				<div class="first">
				</div>
			<?	for($i = 0; $i < 7; $i++){ ?>
				<div class="fl rightLine oneseventh<? if($i == 0){printHTML(' leftLine');} ?>">
					<div class="tc">
						<h5><?	printHTML(date('l', addDayToDate($timestamp, $i)));	?></h5>
					</div>
					<? getEventsForCell(date('Y', addDayToDate($timestamp, $i)), date('n', addDayToDate($timestamp, $i)), date('j', addDayToDate($timestamp, $i))) ?>
				</div>
			<?	} ?>
			</div>
		</div>
<?	}
	
	// Generate the day view
	function dayView($year, $month, $day){
		$timestamp = mktime(0, 0, 0, $month, $day, $year);
		$monthName = date("F", $timestamp);
		$dayName = date("l", $timestamp); ?>
		<div class="clear">
			<div class="fl"><h3><a href="<? printHTML(fullURL(getLangVar("dayURL")) . date('Y/n/j', addDayToDate($timestamp, -1))); ?>"><</a></h3></div>
			<div class="fr"><h3><a href="<? printHTML(fullURL(getLangVar("dayURL")) . date('Y/n/j', addDayToDate($timestamp))); ?>">></a></h3></div>
			<div class="fl span">
				<? getDaysEvents($year, $month, $day) ?>
			</div>
		</div>
<?	}
	
	// Generate the event view
	function eventView($eventId){ ?>
		<div class="clear">
		<?	$result = mysql_query(getEvent($eventId, "calendarId"));
			
			while ($row = mysql_fetch_assoc($result)) {
				getEventGlobals($row);
				$GLOBALS['adminObjectId'] = $GLOBALS['calendarId']; ?>
				<div>
					<div class="fl cl">
						<h1><? printHTML($GLOBALS['objectTitle'] . " at " . $GLOBALS['location']); ?></h1>
					</div>
					<div class="fr cr">
						<h3><? printHTML(date($GLOBALS['dateTimeFormat'], addDate($GLOBALS['startDateTime'])) . " till " . date($GLOBALS['dateTimeFormat'], addDate($GLOBALS['startDateTime'], $GLOBALS['duration'], "minute"))); ?></h3>
					</div>
					<div class="clear">
						<? printHTML($GLOBALS['objectText']); ?>
					</div>
					<div class="fl cl">
					<?	$result = mysql_query(getUser($GLOBALS['createdId'], "userId"));
						
						$row = mysql_fetch_assoc($result);
						getUserGlobals($row);
					?>	<h4>Created By: <a href="<? printHTML(fullURL(getLangVar("userURL")) . $GLOBALS['userId']); ?>"><? printHTML($GLOBALS['userName']); ?></a></h4>
					</div>
					<div class="fr cr">
						<h4><a href="<? printHTML(fullURL(getLangVar("eventURL") . $GLOBALS['objectId'], "create")); ?>">Sub Event</a> | <a href="<? printHTML(fullURL(getLangVar("messageURL") . $GLOBALS['objectId'], "create")); ?>">Reply</a> | <a href="<? printHTML(fullURL(getLangVar("fileURL") . $GLOBALS['objectId'], "create")); ?>">Attach File</a> | <a href="<? printHTML(fullURL(getLangVar("eventURL") . $GLOBALS['calendarId'], "edit")); ?>">Edit</a></h4>
					</div>
				</div>
		<?	}
			
			// Generate attendance if logged in
			if(check()){
			$getUserResult = mysql_query(getUser(""));	?>
			<div class="fl clear">
				<h2>Attendance</h2>
			</div>
			<div class="subObject">
				<form name="attendanceInput" action="<? printHTML(fullURL("attendance/", "commit")); ?>" method="post">
			<?	$i = 0;
				while ($getUserRow = mysql_fetch_assoc($getUserResult)) {
					getUserGlobals($getUserRow);
					$data = array($GLOBALS['calendarId'], $GLOBALS['userId']);
					$getAttendanceResult = mysql_query(getAttendance($data, "both"));
					$getAttendanceRow = mysql_fetch_assoc($getAttendanceResult);
					getAttendanceGlobals($getAttendanceRow, "ATT") ?>
					<div class="clear">
						<div>
							<h4><a href="<? printHTML(fullURL(getLangVar("userURL") . $GLOBALS['userId'])); ?>"><? printHTML($GLOBALS['userName']); ?></a></h4>
						</div>
						<div class="float: left;">
							<img width="50px" src="<? printHTML($GLOBALS['icon']); ?>">
						</div>
						<div class="fl ar">
							<?	if(isset($GLOBALS['ATTattendanceId'])){	?>
								<label>Present<input id="attended<? printHTML($i); ?>" name="attended<? printHTML($i); ?>" type="radio"<? if($GLOBALS['ATTattended'] == 1){printHTML(' checked="checked"');} ?> value="<? printHTML(editAttendance('1', $GLOBALS['ATTcalendarId'], '0', $GLOBALS['ATTuserId'], $GLOBALS['ATTattendanceId'])); ?>"></input></label><br />
								<label>Absent<input id="attended<? printHTML($i); ?>" name="attended<? printHTML($i); ?>" type="radio"<? if($GLOBALS['ATTattended'] == 0 && $GLOBALS['ATTexcused'] == 0){printHTML(' checked="checked"');} ?> value="<? printHTML(editAttendance('0', $GLOBALS['ATTcalendarId'], '0', $GLOBALS['ATTuserId'], $GLOBALS['ATTattendanceId'])); ?>"></input></label><br />
								<label>Excused<input id="attended<? printHTML($i); ?>" name="attended<? printHTML($i); ?>" type="radio"<? if($GLOBALS['ATTexcused'] == 1){printHTML(' checked="checked"');} ?> value="<? printHTML(editAttendance('0', $GLOBALS['ATTcalendarId'], '1', $GLOBALS['ATTuserId'], $GLOBALS['ATTattendanceId'])); ?>"></input></label>
							<?	}else{	?>
								<label>Present<input id="attended<? printHTML($i); ?>" name="attended<? printHTML($i); ?>" type="radio" value="<? printHTML(createAttendance(1, $GLOBALS['calendarId'], 0, $GLOBALS['userId'])); ?>"></input></label><br />
								<label>Absent<input id="attended<? printHTML($i); ?>" name="attended<? printHTML($i); ?>" type="radio" value="<? printHTML(createAttendance(0, $GLOBALS['calendarId'], 0, $GLOBALS['userId'])); ?>"></input></label><br />
								<label>Excused<input id="attended<? printHTML($i); ?>" name="attended<? printHTML($i); ?>" type="radio" value="<? printHTML(createAttendance(0, $GLOBALS['calendarId'], 1, $GLOBALS['userId'])); ?>"></input></label>
							<?	}	?>
						</div>
					</div>
				<?	$i++;
				}	?>
					<input id="numberOfUsers" name="numberOfUsers" type="hidden" value="<? printHTML($i); ?>"></input>
					<input id="calendarId" name="calendarId" type="hidden" value="<? printHTML($GLOBALS['calendarId']); ?>"></input>
					<input id="sqlType" name="sqlType" type="hidden" value="create"></input>
					<div class="fr clear">
						<input type="submit" value="Submit"></input>
					</div>
				</form>
			</div>
			<?	
			}
			
			// Get related objects if you are viewing one event
			if(isset($_GET['event'])){
				getRelatedObjects($GLOBALS['objectId']);
			}
			?>
		</div>
<?	}
	
	// Get events for display in the calendar and week view
	function getEventsForCell($year, $month, $day){ ?>
		<div class="cellEvent">
			<div class="fl">
				<a href="<? printHTML(fullURL(getLangVar("dayURL")) . $year . "/" . $month . "/" . $day); ?>"><? printHTML($day);?></a>
				<?	if(check()){?>
					<a href="<? printHTML(fullURL(getLangVar("eventURL"), "create") . $year . "/" . $month . "/" . $day); ?>">+</a>
			<?	} ?>
			</div>
		</div>
		<div class="clear cellEvent">
		<?	$result = mysql_query(getEvent("$year$month$day", "startTimeDate"));
			for($i = 0; $row = mysql_fetch_assoc($result); $i++) {
					@extract($row); ?>
				<?	if($i < 3){	?>
					<div class="cellStyle">
						<a href="<? printHTML(fullURL(getLangVar("eventURL")) . $calendarId); ?>"><? printHTML($returnTime . " " . $objectTitle); ?></a>
					</div>
				<?	}else{	?>
					<div>
						<a href="<? printHTML(fullURL(getLangVar("dayURL")) . $year . "/" . $month . "/" . $day); ?>">More</a>
					</div>
				<?	break;
					}	?>
		<?	}	?>
		</div>
<?	}

	// Creates the day view
	function getDaysEvents($year, $month, $day){ ?>
		<div class="cellEvent">
		<?		$timestamp = mktime(0, 0, 0, $month, $day, $year);
				$monthName = date("F", $timestamp);
				$dayName = date("l", $timestamp); ?>
				<div class="tc">
					<h1><? printHTML($dayName . ", " . $monthName . " " . $day . " " . $year); ?></h1>
				</div>
		<?	if(check()){?>
				<div class="fr"><h3><a href="<? printHTML(fullURL(getLangVar("eventURL"), "create") . $year . "/" . $month . "/" . $day); ?>">+</a></h3></div>
		<?	} ?>
		</div>
		<div class="cellEvent">
		<?	$result = mysql_query(getEvent("$year$month$day", "startTimeDate"));
			while ($row = mysql_fetch_assoc($result)) {
					getEventGlobals($row); ?>
					<div>
						<div class="fl cl">
							<h2><a href="<? printHTML(fullURL(getLangVar("eventURL")) . $GLOBALS['calendarId']); ?>"><? printHTML($GLOBALS['objectTitle'] . " at " . $GLOBALS['location']); ?></a></h2>
						</div>
						<div class="fr cr">
							<h3><? printHTML("Starts: " . $GLOBALS['returnTime'] . " Duration: " . $GLOBALS['duration'] . " minutes"); ?></h3>
						</div>
						<div class="clear">
							<? printHTML($GLOBALS['objectText']); ?>
						</div>
						<div class="fl cl">
						<?	$result2 = mysql_query(getUser($GLOBALS['createdId'], "userId"));
							
							$row2 = mysql_fetch_assoc($result2);
							getUserGlobals($row2);
						?>	<h4>Created By: <a href="<? printHTML(fullURL(getLangVar("userURL")) . $GLOBALS['userId']); ?>"><? printHTML($GLOBALS['userName']); ?></a></h4>
						</div>
						<div class="fr cr">
							<h4><a href="<? printHTML(fullURL(getLangVar("eventURL") . $GLOBALS['objectId'], "create")); ?>">Sub Event</a> | <a href="<? printHTML(fullURL(getLangVar("messageURL") . $GLOBALS['objectId'], "create")); ?>">Reply</a> | <a href="<? printHTML(fullURL(getLangVar("fileURL") . $GLOBALS['objectId'], "create")); ?>">Attach File</a></h4>
						</div>
					</div>
		<?	}	?>
		</div>
<?	}
	
	// Generates the event creation form
	// Contains javascript for the YUI calendar
	function eventForm($sqlType){ ?>
		<form name="eventInput" action="<? printHTML(fullURL(getLangVar("eventURL"), "commit")); ?>" method="post">
	<?	objectForm();?>
		<script type="text/javascript">
			function padDigits(n, totalDigits){ 
				n = n.toString(); 
				var pd = ''; 
				if (totalDigits > n.length){ 
					for (i=0; i < (totalDigits-n.length); i++){ 
						pd += '0'; 
					}
				} 
				return pd + n.toString(); 
			}
			YAHOO.util.Event.onDOMReady(function(){
		 
				var Event = YAHOO.util.Event,
					Dom = YAHOO.util.Dom,
					dialog,
					calendar;
		 
				var showBtn = Dom.get("show");
		 
				Event.on(showBtn, "click", function() {
		 
					// Lazy Dialog Creation - Wait to create the Dialog, and setup document click listeners, until the first time the button is clicked.
					if (!dialog) {
		 
						// Hide Calendar if we click anywhere in the document other than the calendar
						Event.on(document, "click", function(e) {
							var el = Event.getTarget(e);
							var dialogEl = dialog.element;
							if (el != dialogEl && !Dom.isAncestor(dialogEl, el) && el != showBtn && !Dom.isAncestor(showBtn, el)) {
								dialog.hide();
							}
						});
		 
						function resetHandler() {
							// Reset the current calendar page to the select date, or 
							// to today if nothing is selected.
							var selDates = calendar.getSelectedDates();
							var resetDate;
				
							if (selDates.length > 0) {
								resetDate = selDates[0];
							} else {
								resetDate = calendar.today;
							}
				
							calendar.cfg.setProperty("pagedate", resetDate);
							calendar.render();
						}
				
						function closeHandler() {
							dialog.hide();
						}
		 
						dialog = new YAHOO.widget.Dialog("container", {
							visible:false,
							context:["show", "tl", "bl"],
							buttons:[ {text:"Reset", handler: resetHandler, isDefault:true}, {text:"Close", handler: closeHandler}],
							draggable:false,
							close:true
						});
						dialog.setHeader('Pick A Date');
						dialog.setBody('<div id="cal"></div>');
						dialog.render(document.body);
		 
						dialog.showEvent.subscribe(function() {
							if (YAHOO.env.ua.ie) {
								// Since we're hiding the table using yui-overlay-hidden, we 
								// want to let the dialog know that the content size has changed, when
								// shown
								dialog.fireEvent("changeContent");
							}
						});
					}
		 
					// Lazy Calendar Creation - Wait to create the Calendar until the first time the button is clicked.
					if (!calendar) {
		 
						calendar = new YAHOO.widget.Calendar("cal", {
							iframe:false,          // Turn iframe off, since container has iframe support.
							hide_blank_weeks:true  // Enable, to demonstrate how we handle changing height, using changeContent
						});
						calendar.render();
		 
						calendar.selectEvent.subscribe(function() {
							if (calendar.getSelectedDates().length > 0) {
		 
								var selDate = calendar.getSelectedDates()[0];
				
								Dom.get("date").value = (selDate.getMonth() + 1) + "/" + selDate.getDate() + "/" + selDate.getFullYear();
								Dom.get("hiddenDateTime").value = selDate.getFullYear() + "-" + padDigits((selDate.getMonth() + 1), 2) + "-" + padDigits(selDate.getDate(), 2);
							} else {
								Dom.get("date").value = "";
							}
							dialog.hide();
						});
		 
						calendar.renderEvent.subscribe(function() {
							// Tell Dialog it's contents have changed, which allows 
							// container to redraw the underlay (for IE6/Safari2)
							dialog.fireEvent("changeContent");
						});
					}
		 
					var seldate = calendar.getSelectedDates();
		 
					if (seldate.length > 0) {
						// Set the pagedate to show the selected date if it exists
						calendar.cfg.setProperty("pagedate", seldate[0]);
						calendar.render();
					}
		 
					dialog.show();
				});
			});
		</script>
		<div>
			<label for="date">Date: </label><input type="text" id="date" name="date" value="<? if(isset($_GET['month'])){printHTML($_GET['month'] . "/" . $_GET['day'] . "/" . $_GET['year']);}else{printHTML($GLOBALS['returnMonth'] . "/" . $GLOBALS['returnDay'] . "/" . $GLOBALS['returnYear']);} ?>"></input><button type="button" id="show" title="Show Calendar"><img src="<? printHTML(fullURL(getLangVar("imageURL"))); ?>calbtn.gif" width="18" height="18" alt="Calendar" ></button>
			<?
				if($GLOBALS['returnTime']){
					$time = explode(" ", $GLOBALS['returnTime']);
					$hourMin = explode(":", $time[0]);
				}
				$unit = "minute";
				if($GLOBALS['duration'] > 59){
					$GLOBALS['duration'] = $GLOBALS['duration'] / 60;
					$unit = "hour";
				}
				if($GLOBALS['duration'] > 23){
					$GLOBALS['duration'] = $GLOBALS['duration'] / 24;
					$unit = "day";
				}
			?>
			<label for="time">Time: </label><input id="hour" name="hour" type="text" maxlength="2" size="2" value="<? printHTML($hourMin[0]); ?>"></input>:<input id="minute" name="minute" type="text" maxlength="2" size="2" value="<? printHTML($hourMin[1]); ?>"></input> 
			<select id="ampm" name="ampm">
				<option value="0"<? if($time[1] == "AM"){printHTML(' selected="selected"');} ?>>AM</option>
				<option value="12"<? if($time[1] == "PM"){printHTML(' selected="selected"');} ?>>PM</option>
			</select>
			<input id="hiddenDateTime" name="hiddenDateTime" type="hidden" value="<? if(isset($_GET['month'])){printHTML($_GET['year'] . "-" . sprintf("%02d", $_GET['month']) . "-" . sprintf("%02d", $_GET['day']));}else{printHTML($GLOBALS['returnYear'] . "-" . sprintf("%02d", $GLOBALS['returnMonth']) . "-" . sprintf("%02d", $GLOBALS['returnDay']));} ?>"></input>
			<label for="duration">Duration: </label><input id="duration" name="duration" type="text" value="<? printHTML($GLOBALS['duration']); ?>"></input>
			<select id="unit" name="unit">
				<option value="1"<? if($unit == "minute"){printHTML(' selected="selected"');} ?>>Minutes</option>
				<option value="60"<? if($unit == "hour"){printHTML(' selected="selected"');} ?>>Hours</option>
				<option value="1440"<? if($unit == "day"){printHTML(' selected="selected"');} ?>>Days</option>
			</select>
			<label for="location">Location: </label><input id="location" name="location" type="text" value="<? printHTML($GLOBALS['location']); ?>"></input>
		</div>
		<div class="fr">
			<input type="submit" value="Submit"></input>
		</div>
			<input id="sqlType" name="sqlType" type="hidden" value="<? printHTML($sqlType); ?>"></input>
			<input id="calendarId" name="calendarId" type="hidden" value="<? printHTML($GLOBALS['calendarId']); ?>"></input>
		</form>
<?	}
	
	// Captures variables and creates a prefilled form for messages
	function eventEdit(){
		$result = mysql_query(getEvent($_GET['event']));
		$GLOBALS['adminObjectId'] = $_GET['event'];
		if($row = mysql_fetch_assoc($result)) {
			getEventGlobals($row);
			eventForm("edit");
		}
	}
	
	// Commits event data to the database, gets forwarding variable and send you to completed event
	function eventCommit(){
		// Get the full 24 hour
		$fullHour = $_POST['hour'] + $_POST['ampm'];
		$duration = $_POST['duration'] * $_POST['unit'];
		if($_POST['sqlType'] == "create") {
			$result = mysql_query(createObject(getLangVar("userId"), $_POST['objectText'], $_POST['parentId'], $_POST['objectTitle']));
			$result = mysql_query(createCalendar($_POST['attendingGroup'], $duration, $_POST['location'], mysql_insert_id(), $_POST['hiddenDateTime'] . " " . sprintf("%02d",$fullHour) . ":" . $_POST['minute'] . ":00", $_POST['calendarId']));
			$forwardId = mysql_insert_id();
		} else if($_POST['sqlType'] == "edit") {
			$result = mysql_query(editObject($_POST['objectText'], $_POST['parentId'], $_POST['objectTitle'], $_POST['objectId']));
			$result = mysql_query(editCalendar($_POST['attendingGroup'], $duration, $_POST['location'], $_POST['objectId'], $_POST['hiddenDateTime'] . " " . $fullHour . ":" . $_POST['minute'] . ":00", $_POST['calendarId']));
			$forwardId = $_POST['calendarId'];
		}
		header('Location:' . fullURL(getLangVar("eventURL") . $forwardId));
	}
	
	// Deletes the event from the database and forwards to the calendar
	function eventDelete(){
		$result = mysql_query(getEvent($_GET['event']));
		if($row = mysql_fetch_assoc($result)) {
			getEventGlobals($row);
			mysql_query(deleteObject($GLOBALS['objectId']));
			mysql_query(deleteCalendar($GLOBALS['eventId']));
		}
		header('Location:' . fullURL(getLangVar("calendarURL")));
	}
	
	// Commit changes to attendance
	function attendanceCommit(){
			for($i = 0; $i < $_POST['numberOfUsers']; $i++){
				$result = mysql_query($_POST['attended' . $i]);
			}
			$forwardId = $_POST['calendarId'];
		header('Location:' . fullURL(getLangVar("eventURL") . $forwardId));
	}
?>