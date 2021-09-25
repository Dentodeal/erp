<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductBundleItem extends Model
{
    protected $table = 'product_bundle_items';
    protected $guarded = [];

    public function product () {
        return $this->belongsTo('\App\Product','product_id');
    }
}
