<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationTemplateItem extends Model
{
    protected $table = 'quotation_template_items';
    protected $guarded = [];

    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }
}
