<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSubCategory extends Model
{
    protected $guarded = [];
    protected $table = 'product_sub_categories';
    public function products(){
        return $this->hasMany('App\Product','type_id');
    }
}
