<?php

namespace App\Http\Controllers;

use App\Models\ShiftType as ShiftType;
use App\Models\Committee as Committee;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
class ManagementController extends Controller
{
    //you need to be admin
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('isSuperAdmin');
    }
    //if button new row has been pressed
     public function newRow(Request $request){
        $user = Auth::user();
        $st = new ShiftType;
        $st->title="_NO_NAME";
        $st->updated_by=$user->id;
        $st->save();
        return redirect(route('management.settings'))->with('info', 'Nieuw diensttype toegevoegd!');
    }
    //if button del row has been pressed
    public function delRow(request $request, ShiftType $shifttype){
        $shifttype->delete();
        return redirect(route('management.settings'))->with('info', 'Diensttype verwijderd!');
    }
    //if page has been saved
    public function changeSettings(Request $request){
        if(Auth::user()->info->admin!=1) { // you admin sir?
            return redirect(route('home')); 
        }
        //get request , when you show the page
        if($request->isMethod('get')){
            $shifttypes=ShiftType::with('committee','user.info')->get();
            $committees=Committee::get();
            return view('management', compact('shifttypes','committees'));
        }

        //post request when you want to cahnge somehting
        $user = Auth::user();
        foreach($request->input()['id'] as $k =>$v) {
            $common = isset($request->input('common')[$k]) ? 1 : 0; //is it a commons hift
            $enabled = isset($request->input('enabled')[$k]) ? 1 : 0; //is it enabled in overview
			$st_default= Carbon::parse($request->default_datetime[$k]); //default start time
			$st_default_end= Carbon::parse($request->default_datetime_end[$k]); //default end time
            $st = ShiftType::updateOrCreate( //shifttype
            ['id'=>$v],
            [
            'enabled' =>$enabled,
            'common' =>$common,
            'committee_id' => $request->input('committee_id')[$k],
            'title' => $request->input('title')[$k],
            'description' => $request->input('description')[$k],
            'default_datetime' => Carbon::parse("$st_default"),
            'default_datetime_end' =>Carbon::parse("$st_default_end"),
            'updated_by' => $user->id
            ]);
        }
        return redirect(route('management.settings'))->with('info', 'Instellingen aangepast!');
    }
}
