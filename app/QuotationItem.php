<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $guarded = [];
    protected $casts = [
        'use_mask' => 'boolean'
    ];
    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }
}
