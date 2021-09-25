<?php

namespace App\Http\Controllers;

use App\StockEntry;
use Illuminate\Http\Request;

class StockEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = StockEntry::all();
        $columns = [
            [
                'name' => 'name',
                'label' => 'Name',
                'field' => 'name',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'field' => 'status',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'created_by',
                'label' => 'Created By',
                'field' => 'created_by',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'field' => 'created_at',
                'sortable' => true,
                'field_type' => 'datetime'
            ],
        ];
        $model = $model->toArray();
        foreach($model as $index => $it) 
            $model[$index]['created_by'] = $it['created_by'] ? $it['created_by']['name'] : '';
        return response()->json([
            'columns' => $columns,
            'model' => $model,
            'link_key' => 'name'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:stock_entries,name',
            // 'items.*.product.id' => 'required|unique:stock_entry_items,product_id'
        ]);
        $request->status = 'Draft';
        $model = new StockEntry($request->only(['name','remarks']));
        $model->status = 'Draft';
        $model->created_by_id = \Auth::user()->id;
        $model->save();
        foreach($request->items as $item) {
            $modelItem = new \App\StockEntryItem([
                'product_id' => $item['product']['id'],
                'qty' => $item['qty'],
                'weight' => $item['weight'],
                'length' => $item['length'],
                'breadth' => $item['breadth'],
                'height' => $item['height'],
                'gtin' => $item['gtin'],
                'reorder_code' => $item['reorder_code'],
                'origin_country' => $item['origin_country'],
                'expiry_data' => $item['expiry_data'],
                'expirable' => $item['expirable']
            ]);
            $model->items()->save($modelItem);
            $product = \App\Product::find($item['product']['id']);
            $product->enabled = 1;
            $product->save();
        }
        return response()->json(['message' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockEntry  $stockEntry
     * @return \Illuminate\Http\Response
     */
    public function show(StockEntry $stockEntry)
    {
        return $stockEntry;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockEntry  $stockEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(StockEntry $stockEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockEntry  $stockEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockEntry $stockEntry)
    {
        $request->validate([
            'name' => 'required|unique:stock_entries,name,'.$stockEntry->id,
            // 'items.*.product.id' => 'required|unique:stock_entry_items,product_id,'.$stockEntry->id.',stock_entry_id'
        ]);
        $stockEntry->update($request->only(['name','remarks']));
        foreach($request->items as $item) {
            if(array_key_exists('id', $item)) {
                $modelItem = \App\StockEntryItem::find($item['id']);
                $modelItem->update([
                    'product_id' => $item['product']['id'],
                    'qty' => $item['qty'],
                    'weight' => $item['weight'],
                    'length' => $item['length'],
                    'breadth' => $item['breadth'],
                    'height' => $item['height'],
                    'gtin' => $item['gtin'],
                    'reorder_code' => $item['reorder_code'],
                    'origin_country' => $item['origin_country'],
                    'expiry_data' => $item['expiry_data'],
                    'expirable' => $item['expirable']
                ]);
            } else {
                $modelItem = new \App\StockEntryItem([
                    'product_id' => $item['product']['id'],
                    'qty' => $item['qty'],
                    'weight' => $item['weight'],
                    'length' => $item['length'],
                    'breadth' => $item['breadth'],
                    'height' => $item['height'],
                    'gtin' => $item['gtin'],
                    'reorder_code' => $item['reorder_code'],
                    'origin_country' => $item['origin_country'],
                    'expiry_data' => $item['expiry_data'],
                    'expirable' => $item['expirable']
                ]);
                $stockEntry->items()->save($modelItem);
                $product = \App\Product::find($item['product']['id']);
                $product->enabled = 1;
                $product->save();
            }
        }
        return response()->json(['message' => 'success']);
    }

    public function deleteItem($id) {
        $modelItem = \App\StockEntryItem::find($id);
        $product = \App\Product::find($modelItem->product->id);
        $product->enabled = 1;
        $product->save();
        $modelItem->delete();
        return response()->json(['message' => 'success']);
    }

    public function activate($id) {
        $model = \App\StockEntry::find($id);
        if($model->status == 'Pending'){
            foreach($model->items as $item) {
                // $m = \App\ProductStock::where('product_id',$item['product_id'])->delete();
                if($item['expirable']) {
                    foreach($item['expiry_data'] as $it) {
                        \App\ProductStock::increaseStock($item['product_id'], $it['qty'], $it['date']);
                    }
                } else {
                    \App\ProductStock::increaseStock($item['product_id'], $item['qty']);
                }
                $product = \App\Product::find($item['product_id']);
                $product->enabled = 1;
                $product->weight = $item['weight'];
                $product->length = $item['length'];
                $product->breadth = $item['breadth'];
                $product->height = $item['height'];
                $product->gtin = $item['gtin'];
                $product->reorder_code = $item['reorder_code'];
                $product->origin_country = $item['origin_country'];
                $product->expirable = $item['expirable'];
                $product->save();
            }
            $model->status = 'Active';
            $model->save();
        }
        return response()->json(['message' => 'success']);
    }

    public function sendtoaccounts($id) {
        $model = \App\StockEntry::find($id);
        $model->status = 'Pending';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function revert($id) {
        $model = \App\StockEntry::find($id);
        if($model->status == 'Active') {
            foreach($model->items as $item) {
                // $m = \App\ProductStock::where('product_id',$item['product_id'])->delete();
                if($item['expirable']) {
                    foreach($item['expiry_data'] as $it) {
                        \App\ProductStock::decreaseStock($item['product_id'], $it['qty'], $it['date']);
                    }
                } else {
                    \App\ProductStock::decreaseStock($item['product_id'], $item['qty']);
                }
                $product = \App\Product::find($item['product_id']);
                $product->enabled = 0;
                $product->expirable = 0;
                $product->save();
            }
        }
        $model->status = 'Draft';
        $model->save();
        return response()->json(['message' => 'success']);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockEntry  $stockEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockEntry $stockEntry)
    {
        if($stockEntry->status != 'Active') {
            foreach($stockEntry->items as $item) {
                $product = \App\Product::find($item['product_id']);
                $product->enabled = 1;
                $product->save();
            }
            $stockEntry->items()->delete();
            $stockEntry->delete();
        }
        return response()->json(['message' => 'success']);
    }
}
