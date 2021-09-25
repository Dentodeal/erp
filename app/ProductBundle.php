<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductBundle extends Model
{
    protected $table = 'product_bundles';
    protected $guarded = [];

    public function items () {
        return $this->hasMany('\App\ProductBundleItem','bundle_id');
    }
}
