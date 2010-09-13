<?php
	// This file get the contents of the `calendar` table and generates
	// a ICal document for exporting
	
	// Spoof file type
	header("Content-type: text/calendar");
	
	// Get common library
	require_once('php/commonFunctions.php');
	
	// Begin calendar
	$stringData = "BEGIN:VCALENDAR\n"
		."VERSION:2.0\n"
		."PRODID:-//" . $GLOBALS['siteName'] . "//NONSGML v1.0//EN\n"
		."X-WR-CALNAME:" . $GLOBALS['siteName'] . "\n"
		."X-WR-CALDESC:" . $GLOBALS['siteDescription'] . "\n"
		."BEGIN:VTIMEZONE\n"
		."TZID:US/Eastern\n"
		."LAST-MODIFIED:" . date("Ymd\THis") . "\n"
		."BEGIN:STANDARD\n"
		."DTSTART:19671029T020000\n"
		."RRULE:FREQ=YEARLY;BYDAY=1SU;BYMONTH=11\n"
		."TZOFFSETFROM:-0400\n"
		."TZOFFSETTO:-0500\n"
		."TZNAME:EST\n"
		."END:STANDARD\n"
		."BEGIN:DAYLIGHT\n"
		."DTSTART:19870405T020000\n"
		."RRULE:FREQ=YEARLY;BYDAY=2SU;BYMONTH=3\n"
		."TZOFFSETFROM:-0500\n"
		."TZOFFSETTO:-0400\n"
		."TZNAME:EDT\n"
		."END:DAYLIGHT\n"
		."END:VTIMEZONE\n";
	
	// Get all events
	$result = mysql_query(getEvent(""));
	
	// Step through events
	while($row = mysql_fetch_array($result)) {
		// Bring variables into $GLOBAL
		getEventGlobals($row);
		
		// Format dateTime for iCal
		$startDateTime = str_replace(" ", "T", $GLOBALS['startDateTime']);
		$startDateTime = str_replace(":", "", $startDateTime);
		$startDateTime = str_replace("-", "", $startDateTime);
		
		// Format dateTime for iCal
		$endDateTime = date("Y-m-d H:i:s", addDate($GLOBALS['startDateTime'], $GLOBALS['duration'], "minute"));
		$endDateTime = str_replace(" ", "T", $endDateTime);
		$endDateTime = str_replace(":", "", $endDateTime);
		$endDateTime = str_replace("-", "", $endDateTime);
		
		// Output the event
		$stringData = $stringData . "BEGIN:VEVENT\n"
			. "UID:" . ($GLOBALS['returnYear'] + ($startDateTime * $endDateTime)) . "\n"
			. "DTSTART;TZID=US/Eastern:" . $startDateTime . "\n"
			. "DTEND;TZID=US/Eastern:" . $endDateTime . "\n"
			. "LOCATION:" . $GLOBALS['location'] . "\n"
			. "SUMMARY:" . $GLOBALS['objectTitle'] . "\n"
			. "DESCRIPTION:" . $GLOBALS['objectText'] . "\n"
			. "END:VEVENT\n";
	}
	
	// End the calendar
	$stringData = $stringData . "END:VCALENDAR";
	
	// Print the file
	printHTML($stringData);
?>