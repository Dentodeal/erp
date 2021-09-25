<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockEntryItem extends Model
{
    protected $table = 'stock_entry_items';

    protected $guarded = [];

    protected $casts = [
        'expiry_data' => 'array',
        'expirable' => 'boolean'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
