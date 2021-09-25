<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    protected $table = 'sale_returns';
    protected $guarded = [];

    public function items(){
        return $this->hasMany('App\SaleReturnItem','sale_return_id');
    }

    public function sale_order()
    {
        return $this->belongsTo('App\SaleOrder','sale_order_id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User','created_by_id');
    }
}
