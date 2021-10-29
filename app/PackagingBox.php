<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackagingBox extends Model
{
    protected $table = 'packaging_boxes';

    public function items() {
        return $this->belongsToMany('App\Product', 'packaging_box_item', 'box_id', 'product_id')->withPivot('qty');
    }

    public function sale_order() {
        return $this->belongsTo('App\SaleOrder', 'sale_order_id');
    }
}
