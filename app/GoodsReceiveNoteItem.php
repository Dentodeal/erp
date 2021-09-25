<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiveNoteItem extends Model
{
    protected $table = 'goods_receive_note_items';
    
    protected $guarded = [];

    public function product() {
        return $this->belongsTo('App\Product','product_id');
    }

    public function grn() {
        return $this->belongsTo('App\GoodsReceiveNote','grn_id');
    }
}
