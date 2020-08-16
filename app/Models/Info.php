<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
           'objectGUID','lidnummer','relatienummer','name','email','available','autofill_name','extra_info','groups','admin'
        ];

    public $table = "infos";
    /**
     * Get the user that belongs to userinfo.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
