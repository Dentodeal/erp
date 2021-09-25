<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationTemplate extends Model
{
    protected $table = 'quotation_templates';
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany('App\QuotationTemplateItem','quotation_template_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse','warehouse_id');
    }

    public function pricelist()
    {
        return $this->belongsTo('App\Pricelist','pricelist_id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User','created_by_id');
    }

    public function representative()
    {
        return $this->belongsTo('App\User','representative_id');
    }
    
    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id');
    }
}
