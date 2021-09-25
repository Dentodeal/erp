<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiveNote extends Model
{
    protected $table = 'goods_receive_notes';
    
    protected $guarded = [];

    public function items() {
        return $this->hasMany('App\GoodsReceiveNoteItem','grn_id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Supplier','supplier_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse','warehouse_id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User','created_by_id');
    }

    public function purchase()
    {
        return $this->hasOne('App\Purchase','grn_id');
    }

    public function revertStock() {
        foreach($this->items as $item){
            if($item['expirable']){
                $this->removeStock($item['product_id'], $item['qty'], $item['expiry_date']);
            }
            else
            {
                $this->removeStock($item['product_id'], $item['qty']);
            }
        }
    }

    public function updateStock()
    {
        foreach($this->items as $item){
            if($item['expirable']){
                $this->addStock($item['product_id'], $item['qty'], $item['expiry_date']);
            }
            else
            {
                $this->addStock($item['product_id'], $item['qty']);
            }
        }
    }

    public function addStock($product_id, $qty, $expiry_date=NULL)
    {
        $m2 = \App\ProductStock::where([
            ['product_id', '=', $product_id],
            ['expiry_date', '=', $expiry_date],
            ['reservable_id','=',0],
            ['warehouse_id','=',$this->warehouse_id]
        ])->first();
        if($m2)
        {
            $m2->update(['qty' => $m2->qty + (int)$qty]);
        }
        else
        {
            \App\ProductStock::create([
                'product_id' => $product_id,
                'expiry_date' => $expiry_date,
                'warehouse_id' => $this->warehouse_id,
                'reservable_id' => 0,
                'qty' => (int)$qty
            ]);
        }
    }

    public function removeStock($product_id, $qty, $expiry_date=NULL)
    {
        $m2 = \App\ProductStock::where([
            ['product_id', '=', $product_id],
            ['expiry_date', '=', $expiry_date],
            ['reservable_id','=',0],
            ['warehouse_id','=',$this->warehouse_id]
        ])->first();
        if($m2)
        {
            $m2->update(['qty' => $m2->qty - (int)$qty]);
        }
        else
        {
            \App\ProductStock::create([
                'product_id' => $product_id,
                'expiry_date' => $expiry_date,
                'warehouse_id' => $this->warehouse_id,
                'reservable_id' => 0,
                'qty' => 0 - (int)$qty
            ]);
        }
    }
}
