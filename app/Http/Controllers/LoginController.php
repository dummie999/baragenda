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
            Auth::login(User::find('DEMO'), true);
            return redirect(route('home'));
        }

        // Do LDAP login stuff
    }

    public function logout(){
        Auth::logout();
        return redirect(route('home'));
    }
}