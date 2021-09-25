<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeSet extends Model
{
    protected $guarded = [];
    public function attributes(){
        return $this->belongsToMany('App\Attribute','product_attribute_set_attribute','attribute_set_id','attribute_id')->withPivot('sort_order','group');
    }

    public function products()
    {
        return $this->hasMany('App\Product','attribute_set_id');
    }
}
