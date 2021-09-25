<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'countries';

    public function states()
    {
        return $this->hasMany('\App\State','country_id');
    }

    public function districts()
    {
        return $this->hasManyThrough('\App\District','\App\State');
    }
}
