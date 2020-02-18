<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{

	public $table = "shifts";

    public function updated_by(){
		return $this->belongsTo('App\Models\User','updated_by');
    }

    public function shifttype(){
		return $this->belongsTo('App\Models\ShiftType','shift_type_id');
    }
	public function shiftuser(){
		return $this->belongsToMany('App\Models\User')->using('App\Models\ShiftUser');
    }
}
