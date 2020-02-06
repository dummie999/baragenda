<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
			$userinfo = User::where('id', $id)->firstOrFail();
            return view('profile',['data'=>$userinfo]);
        }

        $request->validate([
            'extra_info' => 'max:191',
        ]);
		
        $user = Auth::user();
        $user->available = $request->get('available');
        $user->extra_info = $request->get('extra_info');
        $user->save();
        return redirect(route('home'))->with('info', 'Instellingen aangepast!');
    }
}
