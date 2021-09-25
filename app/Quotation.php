<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    //
    protected $guarded = [];

    protected $casts = [
        'rate_includes_gst' => 'boolean',
        'bank' => 'array'
    ];

    public function items()
    {
        return $this->hasMany('App\QuotationItem','quotation_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse','warehouse_id');
    }

    public function pricelist()
    {
        return $this->belongsTo('App\Pricelist','pricelist_id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User','created_by_id');
    }

    public function representative()
    {
        return $this->belongsTo('App\User','representative_id');
    }
    
    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id');
    }

    public function sale_orders()
    {
        return $this->hasMany('App\SaleOrder','quotation_id');
    }
}
