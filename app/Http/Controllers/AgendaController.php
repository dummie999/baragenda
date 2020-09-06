<?php
namespace App\Http\Controllers;

use App\Helpers\GSCalendar\Event;
use App\Helpers\GSCalendar\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AgendaController extends Controller
{
    /**
     * Creates a range of dates.  |
     * $firstday=true --> mon-sun |
     * $preparray=true --> array[Ymd]=array(carbon=>carbon object,'nameArray'=>array())|
	 * @param \Carbon $start_date
	 * @param \Carbon $end_date
	 * @param \Boolean $firstday
	 * @param \Boolean $prepArray
	 * @param \String $prepArrayFormat
	 * @param \String $nameArray
	 *
	 * @return array
	 */
	private function generateDateRange(Carbon $start_date, Carbon $end_date, $firstday=false,$prepArray=false,$prepArrayFormat='Ymd',$nameArray='events')
	{
        /*
        wed/fri (firstday=true) --> mon-sun
        wed/fri (firstday=false) --> wed/thu/fri
        wed/fri (firstday=true, preparray=true) --> array[date]=array(carbon=>Carbon,... ))

        [20200823] => Array
        (
            [carbon] => Carbon\Carbon Object
                (
                    [date] => 2020-08-23 00:00:00.000000
                    [timezone_type] => 3
                    [timezone] => UTC
                )

            [events] => Array

        */
        //range starting from first day of that week till last day of that week
		if($firstday){
			$start_date =  new Carbon("Monday $start_date");
			$end_date =  new Carbon("Sunday $end_date");
		}
		$dates = []; //empty array init
        //loop over every day
		for($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
			$dates[] =  new Carbon($date); //creat carbon obj
        }
        //prepare the array for a specific format
        if($prepArray){
            $array=array();
            foreach($dates as $k=>$d){
                $array[$d->format($prepArrayFormat)]=array('carbon'=>$d,$nameArray=>array());
            }
            $dates=$array;
        }

		return $dates;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   $date = $request ? Carbon::parse($request->date,"UTC") : Carbon::parse("today","UTC");
        $start = $date->copy()->startOfWeek();
        $end = $start->copy()->endOfWeek();
        $dates = $this->getRangeEvents($start,$end); //get me range of dates between
       # echo("<pre>");print_r($date);echo("</pre>");die;
        return view('agenda',array(
            'events'=>$dates,
            'selectedDate'=>$date
            ));
    }



    /**
     * Get all events
     *
     * @return \Illuminate\Http\Response
     */
    public function getRangeEvents($start,$end,$minimal=False)
    {
        #echo('<pre>');print_r($resourceArray);echo('</pre>');;die;
        $eventsPublic =  Event::get( $startDateTime =$start,  $endDateTime = $end,  $queryParameters = [],  $calendarId = env('GOOGLE_CALENDAR_ID_PUBLIC'));
        $eventsPrivate =  Event::get( $startDateTime =$start,  $endDateTime = $end,  $queryParameters = [],  $calendarId = env('GOOGLE_CALENDAR_ID_PRIVATE'));
        $events = $this->format2view($eventsPublic,array(),$minimal); //format the Obj
        $events = $this->format2view($eventsPrivate,$events,$minimal); //format + append object
        ksort($events,0); //sort on date

       #echo('<pre>');print_r((($events)));echo('</pre>');die;;



        $dates=$this->generateDateRange($start,$end,true,true); // give me an array of dates!!!!

        foreach($events as $date=>$eventList){
            //if startdate is IN this period
            if (array_key_exists($date,$dates)) {
                $dates[$date]['events']=$eventList; //fill the events in the dates
            }
            //go through each event.
            else {
                foreach($eventList as $i=>$event){
                    #print_r(Carbon::parse($event['start']['carbon'])->format('N'));
                    if($event['start']['carbon']->between($start,$end)) {
                        //print_r($event['summary'] . "startdate is in week"); //can never happen due to above
                    }
                    if($event['end']['carbon']->between($start,$end)) {
                        //print_r($event['summary'] . " enddate of period is in this week"); //event of lastweek until somewhere this week
                        $event['shape']['pos_day']=0; //where to start on a all day event
                        $event['shape']['size_day']=$event['shape']['size'] >7 ? 1 :  round((intval(Carbon::parse($event['end']['carbon'])->format('N'))-1)/7,2);  //where to end on a all day event
                        $dates[$start->format('Ymd')]['events'][]=$event; // append events to array with specific format
                    }
                    elseif(reset($dates)['carbon']->between($event['start']['carbon'],$event['end']['carbon'])) {
                        //print_r($event['summary'] . "monday  is in period"); // event of lastweek until somewhere next week
                        $event['shape']['pos_day']=0;
                        $event['shape']['size_day']=1;
                        $dates[$start->format('Ymd')]['events'][]=$event;
                    }
                }
            }


        }
       #echo('<pre>');print_r($dates);echo('</pre>');die;

        /*
        [20200823] => Array
            (
                [carbon] => Carbon\Carbon Object
                    (
                        [date] => 2020-08-23 00:00:00.000000
                        [timezone_type] => 3
                        [timezone] => UTC
                    )

                [events] => Array
                    (
                        [0] => Array
                            (
                                [summary] => Film
                                [calendar] => Interne Agenda
                                [description] =>
                                [created] => Carbon\Carbon Object
                                    (
                                        [date] => 2018-04-16 19:07:58.000000
                                        [timezone_type] => 2
                                        [timezone] => Z
        */
        #echo('<pre>');print_r(($dates));echo('</pre>');


        return $dates;

    }


/**
     * Format the dates.
     * @param array  $eventsArray
     * @return \Illuminate\Http\Response
     */
    private function format2view(object $events, $eventsArray=array(),$minimal=false,$single=false): Array
    {
    /*
    Array
    (
        [20200823] => Array
            (
                [0] => Array
                    (
                        [summary] => Film
                        [calendar] => Interne Agenda
                        [description] =>
                        [created] => Carbon\Carbon Object
                            (
                                [date] => 2018-04-16 19:07:58.000000
                                [timezone_type] => 2
                                [timezone] => Z
                            )



    */
        $events = $single ? array($events) : $events; //single event has no array so create one if single event so loop is possible

        foreach($events as $k =>$event) {
            $carbon=$event->googleEvent->start->date ? Carbon::parse($event->googleEvent->start->date) : Carbon::parse($event->googleEvent->start->dateTime);
            $displayname=$event->googleEvent->organizer->displayName;
            switch($displayname) {
                case env('GSUITE_CAL_PRIV_SUMM'):
                    $calendar=env('GSUITE_CAL_PRIV_SUMM_DISPLAY');
                    $calendarNo=0;
                break;
                case env('GSUITE_CAL_PUBL_SUMM'):
                     $calendar=env('GSUITE_CAL_PUBL_SUMM_DISPLAY');
                     $calendarNo=1;
                break;
                default : $calendar=NULL;$calendarNo=0;
            }
            $attendees['resource']=array();
            $attendees['guest']=array();
            foreach($event->googleEvent->attendees as $k=>$a){
                $a->resource==1 ? $attendees['resource'][] = $a->email : $attendees['guest'][] = $a->email;
            }
            $eventFormat = array(
                'summary'=>$event->googleEvent->summary,
                'calendar'=> $calendar,
                'calendarNo'=>$calendarNo,
                'description'=>$event->googleEvent->description,
                'id'=>$event->googleEvent->id,
                'location'=>$event->googleEvent->location,
                'start'=>array(
                    'dateTime'=>$event->googleEvent->start->dateTime,
                    'date'=>$event->googleEvent->start->date,
                    'carbon'=>$event->googleEvent->start->date ? Carbon::parse($event->googleEvent->start->date) : Carbon::parse($event->googleEvent->start->dateTime)),
                'end'=>array(
                    'dateTime'=>$event->googleEvent->end->dateTime,
                    'date'=>$event->googleEvent->end->date,
                    'carbon'=>$event->googleEvent->end->date ? Carbon::parse($event->googleEvent->end->date) : Carbon::parse($event->googleEvent->end->dateTime)),
                'interval'=>null,
                'attendees'=>$attendees
                );

            if(!$minimal){
                $arr = array(
                    'created'=>Carbon::parse($event->googleEvent->created),
                    'guestsCanInviteOthers'=>$event->googleEvent->guestsCanInviteOthers,
                    'guestsCanModify'=>$event->googleEvent->guestsCanModify,
                    'guestsCanSeeOtherGuests'=>$event->googleEvent->guestsCanSeeOtherGuests,
                    'htmlLink'=>$event->googleEvent->htmlLink,

                    'recurrence'=>$event->googleEvent->recurrence,
                    'recurringEventId'=>$event->googleEvent->recurringEventId,
                    'status'=>$event->googleEvent->status,
                    'updated'=>$event->googleEvent->updated,
                    'creator'=>array(
                        'displayName'=>$event->googleEvent->creator->displayName,
                        'email'=>$event->googleEvent->creator->email),
                    'organizer'=>array(
                        'displayName'=>$event->googleEvent->organizer->displayName,
                        'email'=>$event->googleEvent->organizer->email)
                    );
                    $eventFormat+=$arr;
            }
            //if multiday event (diff datetime >1), then expand array by interval and insert self event. Also ingore default insert by 'continue'
             if(Carbon::parse($eventFormat['end']['carbon'])->diffInDays(Carbon::parse($eventFormat['start']['carbon']))>1) {
                $interval = $this->generateDateRange(Carbon::parse($event->googleEvent->start->date),Carbon::parse($event->googleEvent->end->date),false,false); //create interval
               $eventFormat['interval']=$interval; // this or below... still need to figure out..
/*                 foreach($interval as $i=>$carb){
                    $eventFormat['start']['carbon']=Carbon::parse($carb)->startOfDay(); //replace start time by interval item time
                    $eventFormat['end']['carbon']=Carbon::parse($carb)->addDay()->startOfDay(); //replace start time by interval item time
                    $eventFormat['shape']=array(
                        'pos'=>round(Carbon::parse($eventFormat['start']['carbon'])->startOfDay()->diffInMinutes(Carbon::parse($eventFormat['start']['carbon']))/1440,3),
                        'size'=>round((Carbon::parse($eventFormat['start']['carbon'])->diffInMinutes(Carbon::parse($eventFormat['end']['carbon'])))/1440,3)
                    );
                    $eventsArray[$carb->format('Ymd')][]=$eventFormat; //for every interval insert self
                } */
                //continue; //skip the default insert (when there is  interval)
            }


            //for each event calculate pos/size for display
            $eventFormat['shape']=array(
                'pos'=>round(Carbon::parse($eventFormat['start']['carbon'])->startOfDay()->diffInMinutes(Carbon::parse($eventFormat['start']['carbon']))/1440,3),
                'pos_day'=>round((intval(Carbon::parse($eventFormat['start']['carbon'])->format('N'))-1)/7,2),
                'size'=>round(Carbon::parse($eventFormat['start']['carbon'])->diffInMinutes(Carbon::parse($eventFormat['end']['carbon']))/1440,3));

            $eventFormat['shape']['size_day']=$eventFormat['shape']['size'] >7 ? 1 :  round((intval(Carbon::parse($eventFormat['end']['carbon'])->format('N'))-1)/7,2);



            $eventsArray[$carbon->format('Ymd')][]=$eventFormat;

        }
        #echo("<pre>");print_r($event);die;
        return $eventsArray;
    }
    //function should send less data (this function is for Ajax Post requests) , might be replaced with PHP version of index
    public function getdate(Request $request,$raw=false){
        $request->validate(['s' => 'regex:/^[a-zA-Z0-9 :()+]+$/']);
        switch($request->calendarNo) {
            case 0:
                $cal = env('GOOGLE_CALENDAR_ID_PRIVATE');
            break;
            case 1:
                $cal = env('GOOGLE_CALENDAR_ID_PUBLIC');
            break;
        }

        //echo($request->calendarNo);
        //echo($cal);
        $event = Event::find($request->eventId, $cal); //raw event
        $eventClean = $this->format2view($event, array(),true,true); //clean event
        $eventSimple = reset($eventClean)[0]; //simplified array
        if($raw) { return $event;}
        return response()->json(array('data'=>$eventSimple));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function edit($id=null)
    //{
     //
    //}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
