<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['content'];
    public $timestamps = false;
    public function phones()
    {
        return $this->morphedByMany('App\Phone','taggable');
    }

    public function emails()
    {
        return $this->morphedByMany('App\Email','taggable');
    }

    public function customers()
    {
        return $this->morphedByMany('App\Customer','taggable');
    }
}
