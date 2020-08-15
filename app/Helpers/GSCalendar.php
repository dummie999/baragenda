

















	


functions.php






 /**
 * Returns all events of shared calendar separated by nextpagetoken
 *
 * @return array
 */
/*function getEventList($calendarId,$optParams,$service)
{
	$calendar=$service->calendars->get($calendarId);
 	$eventList = array();
	$eventList[$calendar->getSummary() ] = $service->events->listEvents($calendar->getId() , $optParams);
 	foreach($eventList[$calendar->getSummary()]->getItems() as $event) {
			$childEventId = $event->getId();
			$eventArray[]=array(
			'id' => $event->getId(),
			'parentId' => getEventType($calendarId,$childEventId,$service), //get parentId if available
			'summary'=>$event->getSummary(),
			'description'=>$event->getDescription(),
			'end'=>getCorrectDatetime($event,'end'),
			'start'=>getCorrectDatetime($event,'start'), 
			'eventType'=>getEventType($calendar->getId(),$event->getId(),$service,'type') //get type
			
			);
		}  
	
	
	return $eventArray;
	
	
/* 	OLD:
	foreach($calendarSharedList as $calendarItem) { // next page token?
	$eventCalendarSharedList[$calendarItem->getSummary() ] = $service->events->listEvents($calendarItem->getId() , $optParams);
	$optParams['pageToken'] = $eventCalendarSharedList[$calendarItem->getSummary() ]->getNextPageToken();
	
	print_r($optParams);
	#die();
	#while (true) {
		foreach($eventCalendarSharedList[$calendarItem->getSummary() ]->getItems() as $event) {
			echo $event->getSummary();
		}

		/* $pageToken = $eventCalendarSharedList[$calendarItem->getSummary() ]->getNextPageToken();
		if ($pageToken) {
			$optParams['pageToken'] = $pageToken;
			echo ("nextpage <br />"); */

			// print_r($optParams);  //no next page token????
			//print_r($eventCalendarSharedList);

/* 		}
		else {
			break;
		} 
	#}
} 

pagetoken!
$events = $service->events->listEvents('primary');

while(true) {
  foreach ($events->getItems() as $event) {
    echo $event->getSummary();
  }
  $pageToken = $events->getNextPageToken();
  if ($pageToken) {
    $optParams = array('pageToken' => $pageToken);
    $events = $service->events->listEvents('primary', $optParams);
  } else {
    break;
  }
}




*/

 
 
 
 
 
 


 


 /**
 * prepare event and returns eventdata
 *
 * @return 
 */
/*function prepareCreateEvent($eventNew)
{  
 
 
	$eventNew['start']['datetime'] = date_format(date_create($eventNew['start']['date'] . " " . $eventNew['start']['time']) , DATE_RFC3339);
	$eventNew['end']['datetime'] = date_format(date_create($eventNew['end']['date'] . " " . $eventNew['end']['time']) , DATE_RFC3339);
	if ($eventNew['recurring']['active'] == 'true') {
		$rrule_array=([
		//'DTSTART' => $eventNew['start']['datetime'], //datetime as start date of rrule NOT ALLOWED BY GOOGLE CALEDNAR
		'interval' => $eventNew['recurring']['interval'], //every X freq
		'freq' => $eventNew['recurring']['period'], //"yearly" "monthly" "weekly" "daily" required
		'WKST'	=> 'MO' ]);	//week starts when?		
		
		//dag repeat every # DAG ends NEVER/ON/AFTER
		//week repeat every # WEEK on MO TU WE ends NEVER/ON/AFTER
		//month repeat every # MONTH on 17th or 3rd TUESDAY ends NEVER/ON/AFTER
		//month repeat every # YEAR  ends NEVER/ON/AFTER
		switch ($eventNew['recurring']['period']) {
			case "daily":
				break;
				
			case "weekly":
				$rrule_array['byday'] = $eventNew['recurring']['week']['day']; //on MO/TU/WE
				break;
				
			case "monthly":
				if($eventNew['recurring']['month']['type']=='dayNo'){
					//automatic
				}
				if($eventNew['recurring']['month']['type']=='dayName'){
					$rrule_array['byday'] = substr(date("D",strtotime($eventNew['start']['datetime'])),0,2); 
					$rrule_array['BYSETPOS'] = $eventNew['recurring']['month']['dayAmount']; //nth
				}
				break;
			case "yearly":
				break;
		}

		
		//END 
		if($eventNew['recurring']['end']['type']=='until'){
			$rrule_array['until'] = $eventNew['recurring']['end']['value']['until']; //on date yyyy-mm-dd
		}
		if($eventNew['recurring']['end']['type']=='count'){
			$rrule_array['count'] = $eventNew['recurring']['end']['value']['count']; //after # times
		}
		//else NEVER
		
		
		
		$rrule = new RRule\RRule($rrule_array);

/* 		//test rrule
		echo("------------------------------------------------------------<br>");
		print_r($rrule_array);
		echo($rrule . "<br><br>");
		echo($rrule->humanReadable() . "<br><br>");
		foreach ( $rrule as $occurrence ) {
			echo $occurrence->format('r'),"\n";
		}  
		echo("------------------------------------------------------------<br>");
		//end test 
		unset($rrule_array);
		$eventNew['recurring']['rrule']=$rrule->rfcString();
		
	}
	else {
		unset($eventNew["recurring"]);
	}
	
	// reminders
	if($eventNew['reminder']['type']!=null && $eventNew['reminder']['period']!=null){
		$eventNew['reminder']['no']*=$eventNew['reminder']['period'];
		unset($eventNew['reminder']['period']);
	}

	// fixing empty arrays of guests

	$eventNew['guests'] = array_filter(array_map(null, $eventNew['guests']));

	// if rooms exist merge with guests

	if (isset($eventNew['rooms'])) {
		$eventNew['attendees'] = array_merge($eventNew['rooms'], $eventNew['guests']);
	}
	else {
		$eventNew['attendees'] = $eventNew['guests'];
	}

	unset($eventNew['agenda'], $eventNew['rooms'], $eventNew['guests']);
 
 return $eventNew;
} 
 
 
 
 
 
 
 
 

 /**
 * Create event and returns data
 *
 * @return 
 */
/*function createEvent($eventData,$service)
{ 
 
	$event = new Google_Service_Calendar_Event();
		#attachement class <-
	
		#attendees
	foreach($eventData['attendees'] as $key => $value) {
		$obj_attendee = new Google_Service_Calendar_EventAttendee();
		$obj_attendee->setEmail($value);
		$attendees[] = $obj_attendee;
	}

	if (isset($attendees)) {
		$event->attendees = $attendees;
	}
	#$event->setColorId("Grape"); NOT WORKING!
		#Creator = netcie
		$creator = new Google_Service_Calendar_EventCreator();
		$creator->setDisplayName(CREATOR_NAME);
		$creator->setEmail(CREATOR_EMAIL);
	$event->setCreator($creator);
	$event->setDescription($eventData['description']);
		#END
		$end = new Google_Service_Calendar_EventDateTime();
		if ($eventData['allDay'] == "true"){
			$end->setDate($eventData['end']['date']); // date only => whole day event
			}
		if ($eventData['allDay'] == "false"){
			$end->setDateTime($eventData['end']['datetime']); // combine time & date
		}
		$end->setTimeZone(TIMEZONE_DEFAULT);
	$event->setEnd($end);
	$event->setGuestsCanModify($eventData['option']['guestsCanModify']);
	$event->setGuestsCanInviteOthers($eventData['option']['guestsCanInviteOthers']);
	$event->setGuestsCanSeeOtherGuests($eventData['option']['guestsCanSeeOtherGuests']);
	$event->setLocation($eventData['location']);
	#$event->setLocked(false);
		#Organizer = baragenda

	if (isset($eventData['recurring']['rrule'])) {
		$event->setRecurrence(array('RRULE:' . $eventData['recurring']['rrule']));
	}
		
/* 	
		#reminder
	$reminders = new Google_Service_Calendar_EventReminders();
		$reminders->setUseDefault("false");  
		$reminders->setOverrides(array(
			array(
				"method" => $eventData['reminder']['type'],
				"minutes" => $eventData['reminder']['no']
			)
		));	
	$event->setReminders($reminders);  */
	
		#start
/*		$start = new Google_Service_Calendar_EventDateTime();
		if ($eventData['allDay'] == "true"){
			$start->setDate($eventData['start']['date']); // date only => whole day event
			}
		if ($eventData['allDay'] == "false"){
			$start->setDateTime($eventData['start']['datetime']); // combine time & date
		}
		$start->setTimeZone(TIMEZONE_DEFAULT);
	$event->setStart($start);
	
	$event->setSummary($eventData['summary']);


		#insert event in calendar
	$event = $service->events->insert($eventData['calendar'], $event);
	printf('Event created: %s\n', $event->htmlLink);
	echo ("edit event / delete");
	print_r($event); 

}


 /**
 * Modify event and fill form / array
 *
 * @return 
 */
/*function modifyEvent($calendarId,$eventId,$service)
{ 	
	$parentId=getEventType($calendarId,$eventId,$service); //get parentId if available	
	$parentEventObject=$service->events->get($calendarId,$parentId); //get object with all data of parent event.
	
	$eventObject=$service->events->get($calendarId,$eventId); //get object with all data of event.
	$eventData=array(
		'created'=>$eventObject->getCreated(),
		'creator'=>$eventObject->getCreator()->getEmail(),
		'description'=>$eventObject->getDescription(),
		'eventType'=>getEventType($calendarId,$eventId,$service,'type'), //get type
		'guestscaninviteothers'=>$eventObject->getGuestsCanInviteOthers(),
		'guestscanmodify'=>$eventObject->getGuestsCanModify(),
		'guestscanseeotherguests'=>$eventObject->getGuestsCanSeeOtherGuests(),
		'id'=>$eventObject->getId(),
		'location'=>$eventObject->getLocation(),
		'organizer'=>$eventObject->getOrganizer()->getEmail(),
		'recurringeventid'=>$eventObject->getRecurringEventId(),
		'sequence'=>$eventObject->getSequence(),
		'summary'=>$eventObject->getSummary()
	);
	$eventData['end']=getCorrectDatetime($eventObject,'end');
	$eventData['start']=getCorrectDatetime($eventObject,'start');
	
	if (!empty($parentEventObject->getRecurrence())) {
		$rrule = new RRule\RRule($parentEventObject->getRecurrence()[0]); //create rrule object 
		$eventData['recurrence']=array(
			'rrule'=>$service->events->get($calendarId,$parentId)->getRecurrence()[0],
			'array'=>$rrule->getRule(),
			'occur'=>$rrule->getOccurrences());
	}
	else {
	$eventData['recurrence']=false;
	}

	
	$reminders=$eventObject->getReminders();
	foreach($reminders->getOverrides() as $reminder){
		$eventData['reminders'][]=array(
			'method'=>$reminder->getMethod(),
			'minutes'=>$reminder->getMinutes());
	}
	
	$attendeeList=$eventObject->getAttendees();
	foreach($attendeeList as $attendee) {
		$eventData['attendees'][]=array(
			'displayName'=>$attendee->getDisplayName(),
			'email'=>$attendee->getEmail(),
			'responseStatus'=>$attendee->getResponseStatus());
	}
	
	$attachementList=$eventObject->getAttachments();
	foreach($attachementList as $attachment) {
		$eventData['attachments'][]=array(
			'title'=>$attachment->getTitle(),
			'url'=>$attachment->getFileUrl(),
			'fileid'=>$attachment->getFileId());
	}	
	unset($rrule);
	ksort($eventData);
	
	return $eventData;
}

 /**
 * getEventType
 *
 * @return eventid of parent (if available)
 */
/*function getEventType($calendarId,$eventId,$service,$returnValue='id')
{ 
	$eventObject=$service->events->get($calendarId,$eventId);
	$recurringEventId=$eventObject->getRecurringEventId();
	$recurrence=$eventObject->getRecurrence();
		
	if(!$recurrence){ //single event or single event of recurring.
		if(!$recurringEventId){ //single event
			$eventType=array(
			'type'=>'single',
			'id'=>$eventId);
		}
		else{ //single event of recurring (child)
			$eventType=array(
			'type'=>'recurring(child)',
			'id'=>$recurringEventId);	
		}
	}
	else { //recurring event (parent)
		$eventType=array(
			'type'=>'recurring(parent)',
			'id'=>$eventId);	
	}
	return $eventType[$returnValue];
		
}



 /**
 * getDate(Time)
 *
 * @return string datetime or date
 */
/*function getCorrectDatetime($eventObject,$type)
{ 
	if((($eventObject->getEnd()->getDate())=='')&&(($eventObject->getStart()->getDate())=='')){
		if($type=='start'){
			$data=$eventObject->getStart()->getDatetime();
		}
		if($type=='end'){
			$data=$eventObject->getEnd()->getDatetime();
		}
	} 
	else {
		if($type=='start'){
			$data=$eventObject->getStart()->getDate();
		}
		if($type=='end'){
			$data=$eventObject->getEnd()->getDate();
		}
	}
	return $data;
}



 /**
 * Delete event, show buttons (this, this/following, all) delete
 *
 * @return success
 */
/*function getDeleteEventId($calendarId,$eventId,$service)
{ 	
	$event=$service->events->get($calendarId,$eventId);
	$parentId=getEventType($calendarId,$eventId,$service); //get parentId if available	
	$type=getEventType($calendarId,$eventId,$service, 'type'); //get type if available	
	switch ($type) {
			case "single":
				$deleteId=array(
					'single'=>$eventId
					);
				break;			
			case "recurring(child)":
				$childEvents=getChildEventId($calendarId,$parentId,$service,getCorrectDatetime($event,'start')); //get all 
				$deleteId=array(
					'single'=>array($eventId),
					'future'=>$childEvents,
					'all'=>array($parentId)
					);			
				break;			

	}
	return $deleteId;	
}


 /**
 * Delete event, show buttons (this, this/following, all) delete
 *
 * @return success
 */
/*function deleteEvent($calendarId,$eventIds,$service)
{ 
	foreach($eventIds as $eventId) {
		$service->events->delete($calendarId, $eventId);
	}
	return true;
}




 /**
 * Delete event, show buttons (this, this/following, all) delete
 *
 * @return success

/*function getChildEventId($calendarId,$parentId,$service,$dateTimeFilter=null)
{ 
	$childEvents=array();
	$eventInstance = $service->events->instances($calendarId,$parentId);
	foreach($eventInstance->getItems() as $event) {
		$start=getCorrectDatetime($event,'start');
		if($dateTimeFilter){
			if(date_create($start)<date_create($dateTimeFilter)){
				continue;
			}
		}
		
		$childEvents[]=/* array(
			'id'=> $event->getId() ,
			'start'=>$start
		); 
	}
	return $childEvents;
}








 /**
 * get Freebusy data
 *
 * @return array dataset
 */
/*function getFreeBusyData($timeMin,$interval,$resources,$service,$filter='dateTime', $adminService)
{

  $freebusyItems = [];
  $freebusy = new \Google_Service_Calendar_FreeBusyRequest();
	$timeMin = new \DateTime($timeMin);
	$timeMax = date_add(new \DateTime('now'),date_interval_create_from_date_string($interval));
  $freebusy->setTimeMin($timeMin->format(\DateTime::ATOM));
  $freebusy->setTimeMax($timeMax->format(\DateTime::ATOM));
  $freebusy->setTimeZone(TIMEZONE_DEFAULT);
  foreach($resources as $resource) {
	$freebusyItem = new \Google_Service_Calendar_FreeBusyRequestItem();
    $freebusyItem->setId($resource['email']);
    $freebusy->setItems(array($freebusyItem));
    $freebusyItems[] = $freebusyItem;
  }
  $freebusy->setItems($freebusyItems);
  $freebusyResponse = $service->freebusy->query($freebusy);
  ;
	
	foreach($freebusyResponse->getCalendars() as $calendar=>$calendarData)
	{	
		$resourceId = getResourceIdFromEmail($calendar,$resources);
		$timePeriods = $calendarData->getBusy();
		if($timePeriods)
		{
			foreach($timePeriods as $timePeriod) {
				$freeBusyResponseBusyData[]=array(
					'start'=>$timePeriod->getStart(),
					'end'=>$timePeriod->getEnd());
			}
		}
		else {$freeBusyResponseBusyData=null;}
		$freeBusyResponseData[]=array(
		'name'=>$adminService->resources_calendars->get('my_customer',$resourceId)->getResourceName(),
		'busy'=>$freeBusyResponseBusyData);
	}
	
	return($freeBusyResponseData);

}


 /**
 * get resource id from resource email. 
 *
 * @return string id of resource oubject 
 
function getResourceIdFromEmail($email,$resources)
{
	if (preg_match_all('$(@resource.calendar.google.com)$', $email)) {
		foreach($resources as $resource) {
			if($resource['email'] == $email){
				return $resource['id'];
			}
		}
	}	
	
	
}





 /**
 * get the optional parameters for request of data (default is set)
 *
 * @return array optParams 
 */
/*function getOptParams($maxResults=30, $orderBy='startTime',$singleEvents=True,$timeMin="last monday",$timeMax="7days",$q="")
{ 

$optParams = array(
	'maxResults' => $maxResults,
	'orderBy' => $orderBy,
	'singleEvents' => $singleEvents, //expand recurring events into instances and only return single one-off events / False
	//'pageToken	' => '' ,
	'timeMin' => date_format(date_create($timeMin),DATE_RFC3339),
	'timeMax' => date_format(date_add(date_create($timeMin),date_interval_create_from_date_string($timeMax)),DATE_RFC3339),
	'q' => $q //free text search that match these termin in any field

);

return $optParams;

}


/**
 * format title & description data based on dataset.
 *
 * @return array 
 */
/*function formatEventTitleDescription($category="other", $group="", $persons=array(), $title, $description, $creator)
{
	$data['title'] ="";
	$now= (new \DateTime('now'))->format(DateTime::ATOM);
	$data['description']="";
	switch ($category) {
			case "shift":
			$formatPersons="";
			foreach($persons as $person) {
				$formatPersons .= $person . "<br/>"; //clean format list of persons
			}
				$data['title']			.=	"Shift: " . $title; //Shift: Happenbar
				$data['description']	.=	"Namen: <br />" . $formatPersons . "<br /> " . $description . "<br /><br /><i>Aangemaakt door:</i><br /> " .  $creator . " op " . $now;
				break;
			case "event":
				$data['title']			.=	"Activiteit: " . $title; //Activiteit: Halloweenfeest
				$data['description']	.=	$description . "<br /><br /><i>Aangemaakt door:</i><br /> " .  $creator . " op " . $now;
				break;
			case "meeting":
				$data['title']			.=	"Vergadering: " . $group . " " . $title; //Vergadering: Acie brainstorm
				$data['description']	.=	$description . "<br /><br /><i>Aangemaakt door:</i><br /> " .  $creator . " op " . $now;
			break;
			case "other":
				$data['title']			.=	$title; //ik ben een mooie titel
				$data['description']	.=	$description . "<br /><br /><i>Aangemaakt door:</i><br /> " .  $creator . " op " . $now;
	
				break;				
	}
	
	return $data;
}
/**
 * format Calendar based on options
 *
 * @return build calendar 
 */
/* 

function draw_calendar($m,$y){
    $date=strtotime("01-".$m."-".$y); 
    $day=date('d',$date); 
    $month=date('m',$date); 
    $year=date('Y',$date);
    $first_day=mktime(0,0,0,$month, 1,$year); 
    $title=date('F',$first_day); 
    $day_of_week=date('D',$first_day); 
    $blankCount=array("Sun","Mon","Tue","Wed","Thu","Fri","Sat"); 
    $blank=array_search($day_of_week,$blankCount); 
    $days_in_month=cal_days_in_month(0,$month,$year); 
    $CalenderTable="<table border=1 width=294>"; 
    $CalenderTable.="<thead><th><<</th><th colspan=5><span 
class='Title'>$title</span><span>$year<span></th><th>>></th></thead>";
    $CalenderTable.="<tr><td>Su</td><td>Mo</td><td>Tu</td><td>We</td><td>Th</td><td>Fr</td><td>Sa</td></tr>"; 
    $day_count=1; 
    $CalenderTable.="<tr>"; 
    while ( $blank > 0 ) {
        $CalenderTable.="<td class='past blank'></td>"; 
        $blank--; 
        $day_count++; 
    }				
    $day_num=1; 
    while ( $day_num <= $days_in_month ) { 
        $CalenderTable.="<td>$day_num</td>"; 
        $day_num++; 
        $day_count++; 
        if ($day_count > 7) { 
            $CalenderTable.="</tr><tr>"; 
            $day_count=1;
        }							
    } 						
    while ( $day_count >1 && $day_count <=7 ) { 
        $CalenderTable.="<td class='future blank'> </td>"; 
        $day_count++;
    } 							
    $CalenderTable.="</tr></table>"; 
    return $CalenderTable; 
}

 */

 ?>