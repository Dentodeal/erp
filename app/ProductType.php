<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $guarded = [];
    protected $table = 'product_types';

    public function products(){
        return $this->hasMany('App\Product','type_id');
    }
}
