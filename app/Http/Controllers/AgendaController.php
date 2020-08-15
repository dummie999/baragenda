<?php
namespace App\Http\Controllers;

use App\Helpers\GSCalendar\Event;
use App\Helpers\GSCalendar\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today=Carbon::parse("today");
        $nextweeksunday=Carbon::parse("next month");
        $eventsPublic =  Event::get( $startDateTime =$today,  $endDateTime = $nextweeksunday,  $queryParameters = [],  $calendarId = env('GOOGLE_CALENDAR_ID_PUBLIC'));
        $eventsPrivate =  Event::get( $startDateTime =$today,  $endDateTime = $nextweeksunday,  $queryParameters = [],  $calendarId = env('GOOGLE_CALENDAR_ID_PRIVATE'));
        #$res =  Resource::get();
        #echo('<pre>');print_r($eventsPrivate);echo('</pre>');
        return view('agenda',array(
            'eventsPublic'=>$eventsPublic,
            'eventsPrivate'=>$eventsPrivate,
            ));

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
    public function edit($id)
    {
        //
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