<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDepartment extends Model
{
    protected $guarded = [];
    protected $table = 'product_departments';

    public function products(){
        return $this->hasMany('App\Product','type_id');
    }
}
