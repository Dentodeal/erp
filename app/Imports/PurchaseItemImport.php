<?php

namespace App\Imports;

use App\PurchaseItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Cache;

class PurchaseItemImport implements ToCollection, WithHeadingRow

{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach($rows as $row){
            $costing = (float)$row['rate']*(1+((float)$row['gst']/100));
            $landing = $row['rate'];
            DB::table('product_cost')->updateOrInsert(
                [
                    'product_id' => $row['product_id']
                ],
                [
                    'landing_price' => $landing,
                    'cost_price' => $costing
                ]
            );
            $product = \App\Product::find($row['product_id']);
            $product->gst = $row['gst'] ? $row['gst'] : $product->gst;
            $product->mrp = $row['mrp'] ? $row['mrp'] : $product->mrp;
            $product->hsn = $row['hsn'] ? $row['hsn'] : $product->hsn;
            $product->save();
            PurchaseItem::create([
                'id' => $row['id'],
                'purchase_id' => $row['purchase_id'],
                'product_id' => $row['product_id'],
                'hsn' => $row['hsn'],
                'mrp' => $row['mrp'],
                'expirable' => $row['expirable'],
                'weight' => $row['weight'],
                'gst' => $row['expirable'],
                'qty' => $row['qty'],
                'is_free' => $row['is_free'],
                'rate' => $row['rate'],
                'discount' => $row['discount'],
                'taxable' => $row['taxable'],
                'tax_amount' => $row['tax_amount'],
                'total' => $row['total'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at'],
                'local' => 0,
            ]);
        }
    }
}
