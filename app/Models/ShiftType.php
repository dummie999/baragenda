<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftType extends Model
{
    public $table = "shift_type";
	public function shift()
    {
        return $this->hasMany(Shift::class);
    }
}
