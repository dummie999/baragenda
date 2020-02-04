<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
	public function getShiftType(){
		return $this->hasMany('App\Models\ShiftType');
    }
	public function getUser(){
		return $this->hasMany('App\Models\ShiftUser');
    }
}
