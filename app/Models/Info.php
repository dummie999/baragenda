<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    public $table = "infos";
    /**
     * Get the user that belongs to userinfo.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
