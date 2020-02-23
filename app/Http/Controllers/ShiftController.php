<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftType;



use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShiftController extends Controller
{
/**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */

    public function index(Request $request,$page = 0)
    {
        if(!is_numeric($page)) {
            return redirect(route('home'));
        }
        $page = intval($page);
		//get
		if($request->isMethod('get')){
			//date
			$now_r=Carbon::today()->addWeeks($page*2);
			$now_r_start=$this->getDayStartEnd($now_r);
			$now_r2=Carbon::today()->addWeeks($page*2+2);
			$now_r2_end=$this->getDayStartEnd($now_r2,False);
			$dates=$this->generateDateRange($now_r,$now_r2,true);
			try {

                //shifttypes (only common)
                $shifttypes=ShiftType::all()->where('common', '1');

                //show requested date & events
                $shifts = Shift::whereBetween('datetime',array($now_r_start,$now_r2_end))->with('shifttype','shiftuser.info')->get();
                //echo('<pre>');print_r($shifts);
				//create array of dates -> arr[2020-02-14][carbon]=CarbonObj
				$arr=array();
				foreach($dates as $d){
					$data[$d->format('Ymd')]=array('carbon'=>$d);
				}
				//create / fill from object to multidimensional arr
			    foreach($shifts as $s){
					$data[Carbon::parse($s->datetime)->format('Ymd')][$s->shifttype->title]=$s;
			    }

				//prepare view
				return view('shifts', compact('shifttypes', 'page'),array(
				'shifttypes'=>$shifttypes,
				'shifts'=>$data,
				));
			}
			catch(ModelNotFoundException $e){
				//return home
				return redirect(route('home'))->with('error', 'Dienst niet gevonden!');
			}
		}
	}
	private function getDayStartEnd(Carbon $date, $start=true)
	{
		if($start){
			$day =  new Carbon("last Monday $date");
		}
		else{
			$day =  new Carbon("last Sunday $date");
		}
		return $day;

	}
	private function generateDateRange(Carbon $start_date, Carbon $end_date, $firstday=false)
	{
		if($firstday){
			$start_date =  new Carbon("last Monday $start_date");
			$end_date =  new Carbon("last Sunday $end_date");
		}
		$dates = [];

		for($date = $start_date->copy(); $date->lte($end_date); $date->addDay()) {
			$dates[] = new Carbon($date);
		}

		return $dates;
	}


	private function validateDate($date, $format = 'Ymd')
	{
		$d = DateTime::createFromFormat($format, $date);
		// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($format) === $date;
	}

	public function openDate(Request $request,$date = 0)
    {
	if (!$this->validateDate($date)){
		return redirect(route('shifts'));
        }
		//get
		$cdate = Carbon::parse($date)->startOfDay();
        $cdateyd = Carbon::parse($date)->startOfDay()->subDay(1);
		$cdate24=Carbon::parse($date)->addHours(24);
		if($request->isMethod('get')){
			 $shift = Shift::whereBetween('datetime',array($cdate,$cdate24))
             ->orderBy('datetime', 'ASC')
             ->with('shifttype','shiftuser.info')
             ->get();
			 #echo("<pre>");print_r($shift);die;
		//prepare view
			return view('shiftdate', compact('shift'),array(
			'shift'=>$shift,
            'prev'=>$cdateyd->format('Ymd'),
            'today'=>$cdate->format('l d F'),
            'next'=>$cdate24->format('Ymd')
			));

		}
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
        echo("1");
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