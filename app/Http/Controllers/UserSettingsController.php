<?php

namespace App\Http\Controllers;

use App\Models\User as User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function changeSettings(Request $request){
        if(Auth::user()->service_user){
            return redirect(route('home'));
        }

        if($request->isMethod('get')){
            $info = User::find(1)->info;
            return view('profile',array('info' => $info));
        }

        $request->validate([
            'extra_info' => 'max:191',
        ]);

        $user = Auth::user();
		$info = User::find(1)->info;
		$info->available=$request->input('available')[0] ? 1 : 0;
		$info->extra_info=$request->input('extra_info');
		$info->save();
        return redirect(route('home'))->with('info', 'Instellingen aangepast!');
    }
}
