<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'warehouses';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:M d Y',
        'updated_at' => 'datetime:M d Y',
    ];
    public function products()
    {
        return $this->belongsToMany('App\Product','product_stock','warehouse_id','product_id');
    }

    public function saleOrders()
    {
        return $this->hasMany('App\SaleOrder','warehouse_id');
    }

    public function purchases()
    {
        return $this->hasMany('App\Purchase','warehouse_id');
    }
}
