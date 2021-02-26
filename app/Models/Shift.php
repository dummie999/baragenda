<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
  use SoftDeletes;
	public $table = "shifts";

    public function updated_by(){
		return $this->belongsTo('App\Models\User','updated_by');
    }

    public function shifttype(){
		return $this->belongsTo('App\Models\ShiftType','shift_type_id');
    }
 	  public function shiftuser(){
         //shift->shift_user->user->info
        #SELECT * FROM `shifts`
        #INNER JOIN shift_user ON shifts.id=shift_user.shift_id
        #INNER JOIN users ON shift_user.user_id=users.id
        #INNER JOIN infos ON infos.user_id=users.id;
		return $this->belongsToMany(User::class,'shift_user');
    }
}
