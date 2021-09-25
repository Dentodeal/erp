<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pricelist extends Model
{
    protected $casts = [
        'created_at' => 'datetime:M d Y',
        'updated_at' => 'datetime:M d Y',
    ];
    protected $table = 'pricelists';
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany('App\Product','product_price','pricelist_id','product_id')->withPivot('margin')->withTimestamps();
    }
}
