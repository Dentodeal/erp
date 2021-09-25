<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table = 'emails';
    protected $guarded = [];

    public function customers()
    {
        return $this->morphedByMany('App\Customer','emailable');
    }

    public function suppliers()
    {
        return $this->morphedByMany('App\Supplier','emailable');
    }
}
