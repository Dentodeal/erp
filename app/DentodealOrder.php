<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class DentodealOrder extends Model
{
    protected $guarded = [];
    protected $casts = [
        'customer_data' => 'array',
        'extra_data' => 'array'
    ];
    
    public function items() {
        return $this->hasMany('\App\DentodealOrderItem','dentodeal_order_id');
    }
}
