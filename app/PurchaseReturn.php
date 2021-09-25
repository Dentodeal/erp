<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $table = 'purchase_returns';
    protected $guarded = [];

    public function items(){
        return $this->hasMany('App\PurchaseReturnItem','purchase_return_id');
    }

    public function purchase()
    {
        return $this->belongsTo('App\Purchase','purchase_id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User','created_by_id');
    }
}
