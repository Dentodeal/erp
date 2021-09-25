<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    
    public function state()
    {
        return $this->belongsTo('App\State','state_id');
    }
}
