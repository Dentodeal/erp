<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $guarded = [];
    public function addressable()
    {
        return $this->morphTo();
    }

    public function phones()
    {
        return $this->morphToMany('App\Phone','phoneable');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer','addressable_id');
    }
}
