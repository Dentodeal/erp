<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItem extends Model
{
    protected $table = 'purchase_return_items';
    protected $guarded = [];

    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }

    public function purchase_return () {
        return $this->belongsTo('App\PurchaseReturn','purchase_return_id');
    }
}
