<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleReturnItem extends Model
{
    protected $table = 'sale_return_items';
    protected $guarded = [];

    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }

    public function sale_return () {
        return $this->belongsTo('App\SaleReturn','sale_return_id');
    }
}
