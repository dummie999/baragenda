<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Shift as Shift;
use App\Models\ShiftType as ShiftType;

class ShiftAdminController extends ShiftController
{

    public function __construct(){
        $this->middleware('auth');
    }



    public function admin(Request $request,$page = 0)
    {
		if(Auth::user()->info->admin>0) {
			if(!is_numeric($page)) {
				return redirect(route('home'));
			}
			$page = intval($page);
			//get
			if($request->isMethod('get')){
				//date
				$now_r=Carbon::today()->addWeeks($page);
				$now_r_start=$this->getDayStartEnd($now_r);
				$now_r1=Carbon::today()->addWeeks($page+1);
				$now_r1_end=$this->getDayStartEnd($now_r1,False);
				$dates=$this->generateDateRange($now_r,$now_r1,true);
				try {

					//shifttypes (only common)
					$whereMatch=['enabled'=> '1'];
					$shifttypes=ShiftType::Where($whereMatch)->get();

					//show requested date & events
					$shifts = Shift::withTrashed()->whereBetween('datetime',array($now_r_start,$now_r1_end))->with('shifttype','shiftuser.info')->get();
					//echo('<pre>');print_r($shifts);
					//create array of dates -> arr[2020-02-14][carbon]=CarbonObj
					$arr=array();
					foreach($dates as $d){
						$data[$d->format('Ymd')]=array('carbon'=>$d);
					}
					//create / fill from object to multidimensional arr
					foreach($shifts as $s){
						if ($s->deleted_at==null) {
							$data[Carbon::parse($s->datetime)->format('Ymd')][$s->shifttype->title]=True;
						}
						else {
							$data[Carbon::parse($s->datetime)->format('Ymd')][$s->shifttype->title]=False;
						}
					}
					#echo('<pre>');print_r($data);echo('</pre>');die;;				
					//prepare view
					return view('shiftmanagement', compact('shifttypes', 'page'),array(
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
		//post

	    #echo("<pre>");print_r($request->all()); die;

		if ($request->has('input_shifttype')) {


			//generate new shifts in daterange
			$shifttypes=ShiftType::all();
			$user = Auth::user();
			$range=$this->generateDateRange(Carbon::parse($request->dt1), Carbon::parse($request->dt2),false);
			#echo('<pre>');print_r($range);echo('</pre>');die;;
			/*
			Array
			(
				[0] => Carbon\Carbon Object
					(
						[date] => 2020-08-03 00:00:00.000000
						[timezone_type] => 3
						[timezone] => UTC
					)

				[1] => Carbon\Carbon Object
					(
						[date] => 2020-08-04 00:00:00.000000
						[timezone_type] => 3
						[timezone] => UTC
					)
			*/	
			foreach($range as $d){
				$date=$d->format('Y-m-d');
				$shift = Shift::whereBetween('datetime',array($d,Carbon::parse($d)->addHours(24)))->with('shifttype')->get(); //multiple shifts on one day
				foreach($request->input_shifttype as $k=>$v){
					$st = $shifttypes->find($k);
					
					#echo('<pre>');print_r($shift);echo('</pre>');die;
					$st_default= Carbon::parse($st->default_datetime)->format('H:i:s');
					$st_default_end= Carbon::parse($st->default_datetime_end)->format('H:i:s');
					if(!$shift->contains('shifttype', $st)){
						$obj = new Shift;
						$obj->shift_type_id=$k;
						$obj->title=null;
						$obj->datetime=Carbon::parse("$date $st_default");
						$obj->datetime_end=Carbon::parse("$date $st_default_end");
						$obj->updated_by=$user->id;
						$obj->save();
						}
				}
			} 
			return redirect(route('shifts.admin'))->with('info', 'Shifts aangepast!');
		}

		if ($request->has('del_shift_type')) {
			$r=$request->del_shift_type;
			$date=array_keys($r)[0];
			$datec=Carbon::createFromFormat("Ymd", $date);
			$shift_type_id=$r[$date];
			$shift = Shift::whereBetween('datetime',array(Carbon::parse($datec),Carbon::parse($datec)->addHours(24)))->where('shift_type_id',$shift_type_id)->first();
			$shift->delete();
			#echo("<pre>");print_r($shift); die;
			
			return redirect(route('shifts.admin'))->with('info', 'Shifts aangepast!');
		}
		
	}




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        if($request->isMethod('get')){
            $shifttypes=ShiftType::all();
            $shifts=Shift::with('shifttype')->get();
            return view('shiftmanagement', compact('shifttypes','shifts'));
        }
        //if it is a post

        return redirect(route('shiftmanagement.settings'))->with('info', 'Instellingen aangepast!');
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
