<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeNoteUser extends Model
{
	public function getUser(){
		return $this->hasMany('App\Models\User');
    }
	public function getCommittee(){
		return $this->hasMany('App\Models\Committee');
    }
}
