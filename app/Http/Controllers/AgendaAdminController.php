<?php

namespace App\Http\Controllers;

use App\Helpers\GSCalendar\Event;
use App\Helpers\GSCalendar\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AgendaAdminController extends AgendaController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function edit(Request $request, $id=null)
    {
       # echo('<pre>');print_r(Carbon::now('Europe/Amsterdam'));echo('</pre>');;die;;
        if($request->isMethod('get')){
            $today=Carbon::today('Europe/Amsterdam')->format("Y-m-d");
            $nowHour=Carbon::now('Europe/Amsterdam')->addHour(1)->startOfHour();
            $nowHour2=$nowHour->copy()->addHour(2);
            $resources=Resource::get();
            #echo('<pre>');print_r($resources);echo('</pre>');;die;
            $resourceArray=array();
            foreach($resources as $r)
            {
                $resourceArray[]=array(
                    "email"=>$r['email'],
                    "name"=>$r['name'],
                    "capacity"=>$r['capacity']
                );

            }
            return view('editevent',array(
                'today'=>$today,
                'nowHour'=>$nowHour->translatedFormat("H:i:s"),
                'nowHour2'=>$nowHour2->translatedFormat("H:i:s"),
                'resources'=>$resources
                
                ));
        }
        if($request->isMethod('post')){
           # echo("<pre>");print_r($request->eventNew);echo("</pre>");die;
           
            $calendar=$request->eventNew['agenda'] ==0 ? env('GOOGLE_CALENDAR_ID_PRIVATE') : env('GOOGLE_CALENDAR_ID_PUBLIC');
            $event = new Event; //new obj
            $event->name = $request->eventNew['summary']; //title
            $event->startDateTime = $request->eventNew['allDay'] == 0 ? Carbon::parse($request->eventNew['start']['date'] . " " . $request->eventNew['start']['time']) : Carbon::parse($request->eventNew['start']['date']) ;
            $event->endDateTime = Carbon::parse($request->eventNew['end']['date'] . " " . $request->eventNew['end']['time']);
          
            $event->setDescription($request->eventNew['description']); //description
            $creator = new Google_Service_Calendar_EventCreator(); //creator object
                $creator->setDisplayName(env('GSUITE_CREATOR_NAME')); 
                $creator->setEmail(env('GSUITE_CREATOR_EMAIL'));
            $event->setCreator($creator); //set creator to event
  
            // fixing empty arrays of guests

            $request->eventNew['guests'] = array_filter(array_map(null, $$request->eventNew['guests']));

            // if rooms exist merge with guests

            if (isset($request->eventNew['rooms'])) {
                $request->eventNew['attendees'] = array_merge($request->eventNew['rooms'], $request->eventNew['guests']);
            }
            else {
                $request->eventNew['attendees'] = $request->eventNew['guests'];
            }            
            //add guests
            foreach($request->eventNew['guests'] as $key => $value) {
                $obj_attendee = new Google_Service_Calendar_EventAttendee();
                $obj_attendee->setEmail($value);
                $attendees[] = $obj_attendee;
            }
            if (isset($attendees)) {
                $event->attendees = $attendees;
            }                      
            $event->setLocation($$request->eventNew['location']);
            $event->setGuestsCanModify($request->eventNew['option']['guestsCanModify']);
            $event->setGuestsCanInviteOthers($request->eventNew['option']['guestsCanInviteOthers']);
            $event->setGuestsCanSeeOtherGuests($request->eventNew['option']['guestsCanSeeOtherGuests']);            
            #$event->create($calendarId=$calendar);

            }
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