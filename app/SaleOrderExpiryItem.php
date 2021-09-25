<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SaleOrderExpiryItem extends Model
{
    protected $table = 'sale_order_expiry_items';
    protected $guarded = [];

    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }
}
