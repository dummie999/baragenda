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
        $user = Auth::user();
        $st = new ShiftType;
        $st->title="_NO_NAME";
        $st->updated_by=$user->id;
        $st->save();
        return redirect(route('management.settings'))->with('info', 'Nieuw diensttype toegevoegd!');
    }
    public function delRow(request $request, ShiftType $shifttype){
        $shifttype->delete();
        return redirect(route('management.settings'))->with('info', 'Diensttype verwijderd!');
    }

    public function changeSettings(Request $request){
        if($request->isMethod('get')){
            $shifttypes=ShiftType::with('committee','user.info')->get();
            $committees=Committee::get();
            return view('management', compact('shifttypes','committees'));
        }
        $user = Auth::user();
        foreach($request->input()['id'] as $k =>$v) {
            $common = isset($request->input('common')[$k]) ? 1 : 0;
            $enabled = isset($request->input('enabled')[$k]) ? 1 : 0;
            $st = ShiftType::updateOrCreate(
            ['id'=>$v],
            [
            'enabled' =>$enabled,
            'common' =>$common,
            'committee_id' => $request->input('committee_id')[$k],
            'title' => $request->input('title')[$k],
            'description' => $request->input('description')[$k],
            'updated_by' => $user->id
            ]);
        }
        return redirect(route('management.settings'))->with('info', 'Instellingen aangepast!');
    }
}
