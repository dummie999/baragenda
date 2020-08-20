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
		if($firstday){
			$start_date =  new Carbon("Monday $start_date"); 
			$end_date =  new Carbon("Sunday $end_date");
		}
		$dates = [];

		for($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
			$dates[] =  new Carbon($date);
        }
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
    public function index()
    {
        $today=Carbon::parse("this week monday");
        $sunday=Carbon::parse("next sunday");
        $eventsPublic =  Event::get( $startDateTime =$today,  $endDateTime = $sunday,  $queryParameters = [],  $calendarId = env('GOOGLE_CALENDAR_ID_PUBLIC'));
        $eventsPrivate =  Event::get( $startDateTime =$today,  $endDateTime = $sunday,  $queryParameters = [],  $calendarId = env('GOOGLE_CALENDAR_ID_PRIVATE'));
        $events = $this->format2view($eventsPublic);
        $events = $this->format2view($eventsPrivate,$events);
        
        
        #$res =  Resource::get();
        #$echo('<pre>');print_r($eventsPrivate[0]);echo('</pre>');
        ksort($events,0);
        //echo('<pre>');print_r($events);echo('</pre>');die;+
       
        $dates=$this->generateDateRange($today,$sunday,true,true); // give me an array of dates!!!!
        #echo('<pre>');print_r($dates);echo('</pre>');die;
        foreach($events as $k=>$e){
            $dates[$k]['events']=$e;
        }

      
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
        #echo('<pre>');print_r(($events));echo('</pre>');die;;
       

        return view('agenda',array(
            'events'=>$dates
            ));

    }


/**
     * Format the dates.
     * @param array  $eventsArray
     * @return \Illuminate\Http\Response
     */
    private function format2view(object $events, $eventsArray=array()): Array
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


        foreach($events as $k =>$event) {
            $carbon=$event->googleEvent->start->date ? Carbon::parse($event->googleEvent->start->date) : Carbon::parse($event->googleEvent->start->dateTime);
            $displayname=$event->googleEvent->organizer->displayName;
            switch($displayname) {
                case env('GSUITE_CAL_PRIV_SUMM'): 
                    $calendar='Interne Agenda'; 
                break;
                case env('GSUITE_CAL_PUBL_SUMM'):
                     $calendar='Openbare Agenda'; 
                break;
                default : $calendar=NULL;
            }
            $eventsArray[$carbon->format('Ymd')][]=array(
                'summary'=>$event->googleEvent->summary,
                'calendar'=> $calendar,
                'description'=>$event->googleEvent->description,
                'created'=>Carbon::parse($event->googleEvent->created),
                'guestsCanInviteOthers'=>$event->googleEvent->guestsCanInviteOthers,
                'guestsCanModify'=>$event->googleEvent->guestsCanModify,
                'guestsCanSeeOtherGuests'=>$event->googleEvent->guestsCanSeeOtherGuests,
                'htmlLink'=>$event->googleEvent->htmlLink,
                'id'=>$event->googleEvent->id,
                'location'=>$event->googleEvent->location,
                'recurrence'=>$event->googleEvent->recurrence,
                'recurringEventId'=>$event->googleEvent->recurringEventId,
                'status'=>$event->googleEvent->status,
                'updated'=>$event->googleEvent->updated,
                'creator'=>array(
                    'displayName'=>$event->googleEvent->creator->displayName,
                    'email'=>$event->googleEvent->creator->email),
                'organizer'=>array(
                    'displayName'=>$event->googleEvent->organizer->displayName,
                    'email'=>$event->googleEvent->organizer->email),
                'start'=>array(
                    'dateTime'=>$event->googleEvent->start->dateTime,
                    'date'=>$event->googleEvent->start->date,
                    'carbon'=>$event->googleEvent->start->date ? Carbon::parse($event->googleEvent->start->date) : Carbon::parse($event->googleEvent->start->dateTime)),
                'end'=>array(
                    'dateTime'=>$event->googleEvent->end->dateTime,
                    'date'=>$event->googleEvent->end->date,
                    'carbon'=>$event->googleEvent->end->date ? Carbon::parse($event->googleEvent->end->date) : Carbon::parse($event->googleEvent->end->dateTime)),
                );
        }
        return $eventsArray;
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
    //public function edit($id)
    public function edit()
    {
        return view('editevent');
    }

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