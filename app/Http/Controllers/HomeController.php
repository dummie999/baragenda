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
            $userid=$user->id;
            #echo('<pre>');print_r($user->id);echo('</pre>');
            $shifts = shift::with('shifttype.committee')->where('datetime','>=',carbon::today())->with(array('shiftuser' => function($query) use ($user)
            {
                $query->where('user_id', $user->id); 
            }))->get();
            $arr=array();
            foreach($shifts as $s){
                $arr[]=array('date'=>Carbon::parse($s->datetime)->format('Ymd'),'data'=>$s);
            }
            #echo('<pre>');print_r($shifts);echo('</pre>');die;
            return view('home',array(
                'user'=>$user->find(1),
                'shifts'=>$arr,
				));

        }
    }

}
