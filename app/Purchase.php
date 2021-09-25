<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Purchase extends Model
{
    protected $table = 'purchases';

    protected $casts = [
        //'created_at' => 'datetime:Y-m-d',
    ];

    protected $guarded = [];
    
    public function items()
    {
        return $this->hasMany('App\PurchaseItem','purchase_id');
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

    public function grn() {
        return $this->belongsTo('App\GoodsReceiveNote','grn_id');
    }

    public function purchase_returns () {
        return $this->hasMany('App\PurchaseReturn');
    }
    
    public function calculateLanding()
    {
        $percentage_of_subtotal_discount = 0;
        if((float)$this->subtotal_discount > 0){
            $percentage_of_subtotal_discount = (float)$this->discount_subtotal / (float)$this->subtotal;
        }
        $percentage_of_taxable_discount = 0;
        if((float)$this->taxable_discount > 0){
            $percentage_of_taxable_discount = (float)$this->taxable_discount / (float)$this->taxable_total;
        }
        $groupedItems = collect($this->items)->groupBy('product_id')->toArray();
        
        foreach($this->items as $item){
            $itemModel = \App\PurchaseItem::find($item['id']);
            if(!$item['is_free'] && !$item['local']){
                $landing = (float)$item['rate'];
                if(count($groupedItems[$item['product_id']]) > 1){
                    $landing = collect($groupedItems[$item['product_id']])->sum('taxable') / collect($groupedItems[$item['product_id']])->sum('qty');
                }
                if($item['discount'] && (float)$item['discount'] > 0){
                    //landing calculation
                    if($this->row_discount_mode == 'percentage'){
                        $landing = ( $landing * (1 - ((float)$item['discount']/100)) );
                    }
                    else{
                        $landing = ( $landing - (float)$item['discount'] );
                    }
                }
                if($percentage_of_subtotal_discount){
                    //updating landing if there is any discount on subtotal. Reminder: subtotal always includes GST
                    $landing = $landing * (1 - $percentage_of_subtotal_discount);
                }
                if($percentage_of_taxable_discount){
                    $landing = $landing * (1 - $percentage_of_taxable_discount);
                }
                if((float)$this->bill_freight > 0 || (float)$this->external_freight > 0){
                    $billFreight = (float)$this->bill_freight;
                    $externalFreight = (float)$this->external_freight;
                    $freight = $billFreight + $externalFreight;
                    $costing = 0;
                    if($this->freight_split_method == 'weight') {
                        $totalWeight = $this->items->sum(function($item){
                            return (float)$item['weight'] * (int)$item['qty'];
                        });
                        $freightOnSingleGram = $freight / $totalWeight;
                        $costing = (float)$landing + ((float)$freightOnSingleGram * (float)$item['weight']);
                    }
                    elseif ($this->freight_split_method == 'quantity') {
                        $itemQty = 0;
                        if(count($groupedItems[$item['product_id']]) > 1){
                            $itemQty = collect($groupedItems[$item['product_id']])->sum('qty');
                        } else $itemQty = (int)$item['qty'];
                        $totalQty = $this->items->sum('qty');
                        $freightOnsingleQty = $freight / $totalQty;
                        $costing = (float)$landing + (float)$freightOnsingleQty * $itemQty;
                    }
                    elseif ($this->freight_split_method === 'equal') {
                        $freightOnsingleLine = $freight / (float)$this->taxable_total; // percentage of freight on each line
                        $costing = (float)$landing * (1 + $freightOnsingleLine);
                    } else {
                        $totalWeight = $this->items->sum(function($item){
                            return (float)$item['weight'] * (int)$item['qty'];
                        });
                        $freightOnSingleGram = $freight / $totalWeight;
                        $costing = (float)$landing + ((float)$freightOnSingleGram * (float)$item['weight']);
                    }
                    $itemModel->cost = $costing;
                    $itemModel->landing = $landing;
                    $itemModel->save();
                } else {
                    $itemModel->cost = $landing;
                    $itemModel->landing = $landing;
                    $itemModel->save();
                }
            }
        }
    }
}
