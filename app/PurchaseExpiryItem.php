<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PurchaseExpiryItem extends Model
{
    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo('App\Product','product_id');
    }
}
