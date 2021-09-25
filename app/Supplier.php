<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    public function addresses(){
        return $this->morphMany('App\Address','addressable');
    }

    public function emails()
    {
        return $this->morphTomany('App\Email','emailable');
    }

    public function purchases()
    {
        return $this->hasMany('App\Purchase','supplier_id');
    }

}
