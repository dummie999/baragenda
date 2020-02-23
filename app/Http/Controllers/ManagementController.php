<?php

namespace App\Http\Controllers;

use App\Models\ShiftType as ShiftType;
use App\Models\Committee as Committee;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
class ManagementController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }
     public function newRow(Request $request){
        $st = new ShiftType;
        $st->save();
        #changeSettings();
        #return view('management', compact('shifttypes','committees'));
    }
    public function changeSettings(Request $request){
        if($request->isMethod('get')){
            $shifttypes=ShiftType::with('committee','user.info')->get();
            $committees=Committee::get();
            #echo('<pre>');print_r($shifttypes);die;
            return view('management', compact('shifttypes','committees'));
        }

        $request->validate([
            'extra_info' => 'max:191',
        ]);

        $user = Auth::user();
        foreach($request->input()['id'] as $k =>$v) {
            $common = isset($request->input('common')[$k]) ? 1 : 0;
            $st = ShiftType::updateOrCreate(
            ['id'=>$v],
            [
            'common' =>$common,
            'committee_id' => $request->input('committee_id')[$k],
            'title' => $request->input('title')[$k],
            'description' => $request->input('description')[$k],
            'updated_by' => $user->id
            ]);
        }
        return redirect(route('management'))->with('info', 'Instellingen aangepast!');
    }
}
