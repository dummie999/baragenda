<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
	public function getUser(){
		return $this->hasMany('App\Models\User');
    }
	public function getLocation(){
		return $this->hasMany('App\Models\Location');
    }
	public function getShift(){
		return $this->hasMany('App\Models\Shift');
    }
	public function getCommittee(){
		return $this->hasMany('App\Models\Committee');
    }
}
