<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    public $timestamps = false;
    
    protected $table = 'product_stock';

    protected $guarded = [];

    public function saleorder()
    {
        return $this->belongsTo('App\SaleOrder','reservable_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse','warehouse_id');
    }

    public static function increaseStock($product_id, $qty, $expiry_date = NULL, $warehouse_id = 1){
        $m = self::where([
            ['product_id', '=', $product_id],
            ['reservable_id', '=', 0],
            ['warehouse_id', '=', $warehouse_id],
            ['expiry_date', '=', $expiry_date]
        ])->first();
        if($m) {
            $m->update(['qty' => $m->qty + (int)$qty]);
        } else {
            self::create([
                'product_id' => $product_id,
                'reservable_id' => 0,
                'warehouse_id' => $warehouse_id,
                'expiry_date' => $expiry_date,
                'qty' => (int)$qty
            ]);
        }
    }
    public static function decreaseStock($product_id, $qty, $expiry_date = NULL, $warehouse_id = 1){
        $m = self::where([
            ['product_id', '=', $product_id],
            ['reservable_id', '=', 0],
            ['warehouse_id', '=', $warehouse_id],
            ['expiry_date', '=', $expiry_date]
        ])->first();
        if($m) {
            if((int)$m->qty == (int)$qty) $m->delete();
            else $m->update(['qty' => $m->qty - (int)$qty]);
        } else {
            self::create([
                'product_id' => $product_id,
                'reservable_id' => 0,
                'warehouse_id' => $warehouse_id,
                'expiry_date' => $expiry_date,
                'qty' => 0 - (int)$qty
            ]);
        }
    }
}
