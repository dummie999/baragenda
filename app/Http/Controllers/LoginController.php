<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login(Request $request){
        if($request->isMethod('get'))
            return view('login');

        if(App::environment('local', 'dev')){
            $user=User::where('username',$request->username)->first();
            #print_r($user); die;
            Auth::login($user, true);
            return redirect(route('home'));
        }

        // Do LDAP login stuff
    }

    public function logout(){
        Auth::logout();
        return redirect(route('home'));
    }

}
