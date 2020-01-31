<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Baragenda extends Model
{
    public function users()
    {
        return $this->hasOne('App\Models\User','users_info','user_id')->withPivot('lidnummer', 'relatienummer', 'name', 'groups');
    }
}