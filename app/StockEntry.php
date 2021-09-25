<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    protected $table = 'stock_entries';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $guarded = [];

    protected $with = [
        'items',
        'items.product:id,name',
        'created_by'
    ];

    public function items() {
        return $this->hasMany(StockEntryItem::class,'stock_entry_id');
    }

    public function created_by() {
        return $this->belongsTo(User::class,'created_by_id');
    }
}
