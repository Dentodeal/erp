<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    protected $guarded = [];
    protected $table = 'product_brands';

    public function products(){
        return $this->hasMany('App\Product','type_id');
    }
}
