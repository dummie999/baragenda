<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagementController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    public function changeSettings(Request $request){
        return view('management');
    }
}
