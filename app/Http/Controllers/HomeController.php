<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Shift;
use App\Models\Committee;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!$user=Auth::user()){
            return view('home');
        }
        else {
            $shifts = shift::whereHas('shiftuser', function($q) use ($user)
            {
                $q->where('user_id','=', $user->id); 
            })->with('shifttype.committee')->where('datetime','>=',carbon::today())->get();
            $arr=array();
            foreach($shifts as $s){
                $carbon=Carbon::parse($s->datetime);
                $arr[]=array('carbon'=>$carbon,'date'=>$carbon->format('Ymd'),'data'=>$s);
            }
            #echo('<pre>');print_r($shifts);echo('</pre>');die;
            return view('home',array(
                'user'=>$user,
                'shifts'=>$arr,
				));

        }
    }

}
