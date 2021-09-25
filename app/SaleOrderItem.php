<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SaleOrderItem extends Model
{
    protected $table = 'sale_order_items';
    protected $guarded = [];

    protected $casts = [
        'use_mask' => 'boolean',
        'expirable' => 'boolean'
    ];
    
    public function sale_order(){
        return $this->belongsTo('App\SaleOrder','sale_order_id');
    }

    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }

}
