<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $guarded = [];

    public function sale_order()
    {
        return $this->belongsTo('App\SaleOrder','sale_order_id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User','created_by_id');
    }
}
