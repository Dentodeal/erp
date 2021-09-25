<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Model\Bashbee\Type\Varchar;

class PurchaseItem extends Model
{
    protected $table = 'purchase_items';

    protected $guarded = [];

    public function purchase()
    {
        return $this->belongsTo('App\Purchase','purchase_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product','product_id');
    }

}
