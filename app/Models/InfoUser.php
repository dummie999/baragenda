<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoUser extends Model
{
    public $table = "users_infos";
    /**
     * Get the user that belongs to userinfo.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
