<?php

namespace App\Http\Controllers;

use Google_Service_Calendar_EventCreator;
use App\Helpers\GSCalendar\Event;
use App\Helpers\GSCalendar\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AgendaAdminController extends AgendaController
{

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
     * Create or edit a Calenda Event
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
       # echo('<pre>');print_r(Carbon::now('Europe/Amsterdam'));echo('</pre>');;die;;
       // when opneining this page
        if($request->isMethod('get')){
            $type = $_GET==null ? "create" : "edit";
            if($type == "edit"){
                try {$event = ($this->getdate($request)->original)['data']; }
                catch (Exception $e){ }
            }

            #echo('<pre>');print_r($event);echo('</pre>');

            $today=Carbon::today('Europe/Amsterdam')->format("Y-m-d"); //date today
            $nowHour=Carbon::now('Europe/Amsterdam')->addHour(1)->startOfHour(); //next hour rounded down to hour, 7:05->8:00
            $nowHour2=$nowHour->copy()->addHour(2); // copy object + add 2hr
            $resources=Resource::get(); //get all Calendar Resources (rooms)
            #echo('<pre>');print_r($resources);echo('</pre>');;die;
            $resourceArray=array(); //new array
            foreach($resources as $r) //loop over rooms
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
                'resources'=>$resources,
                'event'=>$event ?? null,
                'type'=>$type

                ));
        }
        //when posting the form
        if($request->isMethod('post')){

            $calendar=$request->eventNew['agenda'] ==0 ? env('GOOGLE_CALENDAR_ID_PRIVATE') : env('GOOGLE_CALENDAR_ID_PUBLIC'); //which calendar
            $creator = new Google_Service_Calendar_EventCreator(); //creator object
                $creator->setDisplayName(env('GSUITE_CREATOR_NAME'));
                $creator->setEmail(env('GSUITE_CREATOR_EMAIL'));


            # echo("<pre>");print_r($request->eventNew);echo("</pre>");die;
            $eventNew = $request->eventNew;

            // fixing empty arrays of guests
            $eventNew['guests'] = array_filter(array_map(null, $eventNew['guests']));
            if (isset($eventNew['guests'])) {
                foreach($eventNew['guests'] as $k=>$a){ //loop over attendees
                    $eventNew['guests'][$k]=array('email'=>$a);
                }
            }
            // if rooms exist merge with guests
            if (isset($eventNew['rooms'])) {
                foreach($eventNew['rooms'] as $k=>$a){ //loop over attendees
                    $eventNew['rooms'][$k]=array('email'=>$a,'resource'=>true);
                }
                $eventNew['attendees'] = array_merge($eventNew['rooms'], $eventNew['guests']);
            }
            else {
                $eventNew['attendees'] = $eventNew['guests'];
            }



            if($eventNew['allDay'] == 0){
                $start = array('startDateTime' => Carbon::parse($eventNew['start']['date'] . " " . $eventNew['start']['time'],'Europe/Amsterdam') );
                $end   = array('endDateTime' => Carbon::parse($eventNew['end']['date'] . " " . $eventNew['end']['time'],'Europe/Amsterdam') );
            }
            else {
                $start = array('startDate' =>Carbon::parse($eventNew['start']['date'],'Europe/Amsterdam'));
                $end   = array('endDate' => Carbon::parse($eventNew['end']['date'],'Europe/Amsterdam'));
                }


            $eventData = array(
                'name' => $eventNew['summary'], //title
                'description' => $eventNew['description'], //description
                'creator'=>$creator, //set creator to event
                'location' => $eventNew['location'],
                'GuestsCanModify' => $eventNew['option']['guestsCanModify'],
                'guestsCanInviteOthers' => $eventNew['option']['guestsCanInviteOthers'],
                'GuestsCanSeeOtherGuests' => $eventNew['option']['guestsCanSeeOtherGuests'],
                'attendees' => $eventNew['attendees'],

            );
            if($eventNew['reminder']['type']){
                $reminder = array('reminders' => array(
                    'useDefault' => FALSE,
                    'overrides' => array(
                        array('method' =>  $eventNew['reminder']['type'], 'minutes' => $eventNew['reminder']['no'] * $eventNew['reminder']['period'])
                    )
                ));
                $eventData+=$reminder;
            }

            $eventData+=$start;
            $eventData+=$end;

            #echo('<pre>');print_r($eventData);echo('</pre>');;
            #echo('<pre>');print_r($eventNew);echo('</pre>');;
            if($request->type == 'edit'){
                try {$event = ($this->getdate($request,$raw=true)); }
                catch (Exception $e){ }
                $event->update($eventData);

            }
            if($request->type == 'create'){
                $event = new Event;
                $event->create($eventData,$calendarId=$calendar);
            }

            return redirect(route('agenda'))->with('info', 'Agenda item aangemaakt');
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
    public function destroy(Request $request)
    {
        switch($request->calendarNo) {
            case 0:
                $cal = env('GOOGLE_CALENDAR_ID_PRIVATE');
            break;
            case 1:
                $cal = env('GOOGLE_CALENDAR_ID_PUBLIC');
            break;
        }
        $event = Event::find($request->eventId, $cal); //raw event
        $event->delete($request->eventId,$cal);
        return redirect(route('agenda'))->with('info', 'Agenda item verwijderd');
    }
}
