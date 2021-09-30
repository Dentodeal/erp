<?php

namespace App\Http\Controllers;

use App\Purchase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'purchases_index_visible_columns', ['bill_number', 'status','created_at']);
        $filters = getFilters($request, 'purchases_index_filtered');
        $search = getSearch($request, 'purchases_index_search');
        $pagination = getPagination($request, 'purchases_index_pagination');
        $select_arr = [
            'purchases.id AS id',
            'suppliers.name AS supplier_name',
            'purchases.status AS status',
            'purchases.type AS type',
            'purchases.created_at AS created_at',
            'bill_date',
            'bill_number',
            'bill_total',
            'grand_total',
        ];
        $model = Purchase::select($select_arr)->join('suppliers','purchases.supplier_id','=','suppliers.id');
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key != 'status'){
                    $chunks = explode(" ",$val);
                    $model->where(function ($query) use ($chunks,$key){
                        foreach ($chunks as $it) {
                            if(strlen($it) > 0)
                            $query->where('purchases.'.$key,'like','%'.$it.'%');
                        }
                    });
                }
                else{
                    $model->where(function ($query) use ($val,$key){
                        $query->whereIn('purchases.'.$key,$val);
                    });
                }
            }
        }
        if($search){
            $chunks = explode(" ",$search);
            $model->where(function ($query) use ($chunks,$search){
                foreach ($chunks as $it) {
                    if(strlen($it) > 0)
                    $query->where('purchases.bill_number','like','%'.$search.'%');
                    $query->orWhere('suppliers.name','like','%'.$search.'%');
                }
            });
        }
        if($pagination['sortBy'])
        {
            $model->orderBy($pagination['sortBy'],$pagination['desc']);
        }
        else{
            $model->orderBy('id','DESC');
        }
        return response()->json([
            'link_key' => 'bill_number',
            'visible_columns' => $visibleColumns,
            'model' => $model->paginate($pagination['rpp'],['*'],'page',$pagination['page']),
            'sortby' => $pagination['sortBy'],
            'descending' => $pagination['desc'] == 'DESC' ? true : false,
            'filtered' => $filters,
            'search' => $search,
          ]);
    }

    public function getColumns()
    {
        $columns = [
            [
                'name' => 'bill_number',
                'label' => 'Bill No',
                'field' => 'bill_number',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'supplier_name',
                'label' => 'Supplier',
                'field' => 'supplier_name',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'type',
                'label' => 'Type',
                'field' => 'type',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'bill_date',
                'label' => 'Bill Date',
                'field' => 'bill_date',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'field' => 'status',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'field' => 'created_at',
                'required' => false,
                'sortable' => true,
                'align' => 'right',
                'field_type' => 'datetime'
            ],
            [
                'name' => 'bill_total',
                'label' => 'Bill Total',
                'field' => 'bill_total',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'grand_total',
                'label' => 'Grand Total',
                'field' => 'grand_total',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
        ];
        return $columns;
    }

    public function getFilterables()
    {
        $allAttributes = [
            [
                'field_type' => 'Decimal',
                'name' => 'Bill Total',
                'slug' => 'bill_total',
                'max' => (int)ceil(Purchase::max('bill_total')),
                'min' => (int)ceil(Purchase::min('bill_total')),
            ],
            [
                'field_type' => 'Decimal',
                'name' => 'Grand Total',
                'slug' => 'grand_total',
                'max' => (int)ceil(Purchase::max('grand_total')),
                'min' => (int)ceil(Purchase::min('grand_total')),
            ],
            [
                'field_type' => 'Selection',
                'name' => 'Status',
                'slug' => 'status',
                'searcheable' => false,
                'options' => Purchase::select('status')->distinct()->get()->pluck('status'),
                'value' => []
            ],
        ];
        return $allAttributes;
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
        if($request->status != 'Draft') {
            if($request->grn_id) {
                $grnModel = \App\GoodsReceiveNote::find($request->grn_id);
                $grnItems = $grnModel->items;
                $groupedGrnItems = $grnItems->groupBy('product_id')->all();
                $groupedItems = collect($request->items)->groupBy('product_id')->all();
                
                Validator::make(['items' => $grnItems->toArray()], [
                    'items.*' => [
                        function($attribute, $value, $fail) use ($groupedGrnItems, $groupedItems){
                            if(!array_key_exists($value['product_id'], $groupedItems)) {
                                $fail(\App\Product::find($value['product_id'])->name.' is missing compared to GRN');
                            } else if(collect($groupedGrnItems[$value['product_id']])->sum('qty') != collect($groupedItems[$value['product_id']])->sum('qty')) {
                                $fail('Qty mismatch for '.\App\Product::find($value['product_id'])->name.' against GRN, Given: '.collect($groupedItems[$value['product_id']])->sum('qty').', Required: '.collect($groupedGrnItems[$value['product_id']])->sum('qty'));
                            }
                        }
                    ]
                ])->validate();
            }
        }
        $request->validate([
            'bill_number' => 'required|unique:purchases,bill_number',
        ]);
        $model = new Purchase($request->only([
            'supplier_id',
            'created_by_id',
            'bill_number',
            'bill_date',
            'delivery_date',
            'status',
            'warehouse_id',
            'discount_subtotal',
            'taxable_discount',
            'bill_freight',
            'external_freight',
            'bill_freight_gst',
            'round',
            'row_discount_mode',
            'remarks',
            'freight_split_method'
        ]));
        $model->grn_id = $request->grn_id ? $request->grn_id : 0;
        $model->type = $request->grn_id ? 'Standard' : 'Price Adjustment';
        $model->bill_freight_includes_gst = false;
        $model->subtotal = 0;
        $model->bill_total = 0;
        $model->grand_total = 0;
        $model->taxable_total = 0;
        $model->tax_row_total = 0;
        $model->tax_total = 0;
        $model->save();
        $taxRowTotal = 0;
        $disc = 0; 
        $taxTotal = 0;
        $items = collect($request->items);
        $taxableTotal = $items->sum(function($item) use ($request){
            $discount = (float)$item['discount'] > 0 ? (float)$item['discount'] : 0;
            if($request->row_discount_mode == 'percentage') {
                return round((float)$item['rate'] * (int)$item['qty'] *(1 - ($discount/100)),2);
            } else {
                return round(((float)$item['rate'] * (int)$item['qty']) - $discount,2);
            }
        });
        if ((float)$request->taxable_discount > 0) {
            $disc = (float)$request->taxable_discount / $taxableTotal;
        }
        foreach($request->items as $item)
        {
            $item_model = new \App\PurchaseItem(Arr::only($item,[
                'product_id',
                'hsn',
                'gst',
                'qty',
                'local',
                'is_free',
                'rate',
                'discount',
                'weight',
                'mrp'
            ]));
            $discount = (float)$item['discount'] > 0 ? (float)$item['discount'] : 0;
            if($request->row_discount_mode == 'percentage') {
                $taxable = round((float)$item['rate'] * (int)$item['qty'] *(1 - ($discount/100)),2);
            } else {
                $taxable = round(((float)$item['rate'] * (int)$item['qty']) - $discount,2);
            }
            $item_model->taxable = $taxable;
            $tax_amount = round($taxable*(float)$item['gst']/100,2);
            $item_model->tax_amount = $tax_amount;
            $taxRowTotal += $tax_amount;
            if($disc > 0) {
                $taxTotal += round(($taxable * (1 - $disc)) * (float)$item['gst'] / 100, 2);
            }
            $model->items()->save($item_model);
            $prod_model = \App\Product::find($item['product_id']);
            $prod_model->update([
                'mrp' => $item['mrp'],
                'hsn' => $item['hsn'],
                'weight' => $item['weight'],
                'gst' => $item['gst'],
            ]);
            activity()
                ->causedBy($model)
                ->performedOn($prod_model)
                ->withProperties($prod_model->getChanges())
                ->log('updated');
        }
        if($disc == 0) $taxTotal = $taxRowTotal;
        $model->taxable_total = round($taxableTotal,2);
        $model->tax_row_total = round($taxRowTotal,2);
        $model->tax_total = round($taxTotal,2);
        $subtotal = $taxableTotal - (float)$request->taxable_discount + $taxTotal + (float)$request->bill_freight_gst;
        $model->subtotal = round($subtotal,2);
        $billTotal = $subtotal - (float)$request->discount_subtotal + (float)$request->round + (float)$request->bill_freight;
        $model->bill_total = round($billTotal,2);
        $model->grand_total = round($billTotal + (float)$request->external_freight,2);
        $model->save();
        $model->calculateLanding();
        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Purchase::with(['items','items.product:id,name','supplier','warehouse','created_by','grn','grn.items','grn.items.product:id,name'])->find($id);
        $model->return_count = $model->purchase_returns()->count();
        return $model;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = Purchase::find($id);
        if($request->status != 'Draft') {
            if ($model->type == 'Standard') {
                $grnModel = \App\GoodsReceiveNote::find($model->grn_id);
                $grnItems = $grnModel->items;
                $groupedGrnItems = $grnItems->groupBy('product_id')->all();
            }
            $items = $request->items;
            $groupedItems = collect($items)->groupBy('product_id')->all();
            if ($model->type == 'Standard') {
                Validator::make(['status' => $grnModel->status],[
                    'status' => [
                        function($attribute, $value, $fail) {
                            if($value != 'Active') {
                                $fail('The GRN associated with this purchase entry is not activated. Please activate the GRN before proceeding');
                            }
                        }
                    ]
                ])->validate();
                Validator::make(['items' => $grnItems->toArray()], [
                    'items.*' => [
                        function($attribute, $value, $fail) use ($groupedGrnItems, $groupedItems){
                            if(!array_key_exists($value['product_id'], $groupedItems)) {
                                $fail(\App\Product::find($value['product_id'])->name.' is missing compared to GRN');
                            } else if(collect($groupedGrnItems[$value['product_id']])->sum('qty') != collect($groupedItems[$value['product_id']])->sum('qty')) {
                                $fail('Qty mismatch for '.\App\Product::find($value['product_id'])->name.' against GRN, Given: '.collect($groupedItems[$value['product_id']])->sum('qty').', Required: '.collect($groupedGrnItems[$value['product_id']])->sum('qty'));
                            }
                        }
                    ]
                ])->validate();
            }
        }
        
        $request->validate([
            'bill_number' => 'required|unique:purchases,bill_number,'.$id
        ]);
        $model->update($request->only([
            'supplier_id',
            'created_by_id',
            'bill_number',
            'bill_date',
            'delivery_date',
            'status',
            'warehouse_id',
            'discount_subtotal',
            'taxable_discount',
            'bill_freight',
            'external_freight',
            'bill_freight_gst',
            'round',
            'row_discount_mode',
            'remarks',
            'freight_split_method'
        ]));
        $taxRowTotal = 0;
        $disc = 0; 
        $taxTotal = 0;
        $items = collect($request->items);
        $taxableTotal = $items->sum(function($item) use ($request){
            $discount = (float)$item['discount'] > 0 ? (float)$item['discount'] : 0;
            if($request->row_discount_mode == 'percentage') {
                return round((float)$item['rate'] * (int)$item['qty'] *(1 - ($discount/100)),2);
            } else {
                return round(((float)$item['rate'] * (int)$item['qty']) - $discount,2);
            }
        });
        // dd($taxableTotal);
        if ((float)$request->taxable_discount > 0) {
            $disc = (float)$request->taxable_discount / $taxableTotal;
        }
        $model->items()->delete();
        foreach($request->items as $item)
        {
            $item_model = new \App\PurchaseItem(Arr::only($item,[
                'product_id',
                'hsn',
                'gst',
                'qty',
                'local',
                'is_free',
                'rate',
                'discount',
                'taxable',
                'tax_amount',
                'weight',
                'mrp'
            ]));
            $discount = (float)$item['discount'] > 0 ? (float)$item['discount'] : 0;
            if($request->row_discount_mode == 'percentage') {
                $taxable = round((float)$item['rate'] * (int)$item['qty'] *(1 - ($discount/100)),2);
            } else {
                $taxable = round(((float)$item['rate'] * (int)$item['qty']) - $discount,2);
            }
            $item_model->taxable = $taxable;
            $tax_amount = round($taxable*(float)$item['gst']/100,2);
            $item_model->tax_amount = $tax_amount;
            $taxRowTotal += $tax_amount;
            if($disc > 0) {
                $taxTotal += round(($taxable * (1 - $disc)) * (float)$item['gst'] / 100, 2);
            }
            $model->items()->save($item_model);
            $prod_model = \App\Product::find($item['product_id']);
            $prod_model->update([
                'mrp' => $item['mrp'],
                'hsn' => $item['hsn'],
                'weight' => $item['weight'],
                'gst' => $item['gst'],
            ]);
            activity()
                ->causedBy($model)
                ->performedOn($prod_model)
                ->withProperties($prod_model->getChanges())
                ->log('updated');
        }
        if($disc == 0) $taxTotal = $taxRowTotal;
        $model->taxable_total = round($taxableTotal,2);
        $model->tax_row_total = round($taxRowTotal,2);
        $model->tax_total = round($taxTotal,2);
        $subtotal = $taxableTotal - (float)$request->taxable_discount + $taxTotal + (float)$request->bill_freight_gst;
        $model->subtotal = round($subtotal,2);
        $billTotal = $subtotal - (float)$request->discount_subtotal + (float)$request->round + (float)$request->bill_freight;
        $model->bill_total = round($billTotal,2);
        $model->grand_total = round($billTotal + (float)$request->external_freight,2);
        $model->save();
        $model->calculateLanding();
        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        if($purchase->status == 'Draft') {
            $purchase->delete();
        }
        return response()->json([
            'message' => 'success'
        ]);
    }
    public function revert($id)
    {
        $model = Purchase::find($id);
        $originalStatus = $model->status;
        $model->status = 'Draft';
        $model->save();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->log('reverted');
        return response()->json([
            'message' => 'success'
        ]);
    }
    public function sendAdmin($id)
    {
        $model = Purchase::find($id);
        if($model->type == 'Standard') {
            $grnModel = \App\GoodsReceiveNote::find($model->grn_id);
            $grnItems = $grnModel->items;
            $groupedGrnItems = $grnItems->groupBy('product_id')->all();
        }
        $items = $model->items;
        $groupedItems = $items->groupBy('product_id')->all();
        if($model->type == 'Standard') {
            Validator::make(['status' => $grnModel->status],[
                'status' => [
                    function($attribute, $value, $fail) {
                        if($value != 'Active') {
                            $fail('The GRN associated with this purchase entry is not activated. Please activate the GRN before proceeding');
                        }
                    }
                ]
            ])->validate();
            Validator::make(['items' => $grnItems->toArray()], [
                'items.*' => [
                    function($attribute, $value, $fail) use ($groupedGrnItems, $groupedItems){
                        if(!array_key_exists($value['product_id'], $groupedItems)) {
                            $fail(\App\Product::find($value['product_id'])->name.' is missing compared to GRN');
                        } else if(collect($groupedGrnItems[$value['product_id']])->sum('qty') != collect($groupedItems[$value['product_id']])->sum('qty')) {
                            $fail('Qty mismatch for '.\App\Product::find($value['product_id'])->name.' against GRN, Given: '.collect($groupedItems[$value['product_id']])->sum('qty').', Required: '.collect($groupedGrnItems[$value['product_id']])->sum('qty'));
                        }
                    }
                ]
            ])->validate();
        }
        $model->status = 'Pending Approval';
        $model->save();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->log('Sent Admin for Approval');
        return response()->json([
            'message' => 'success'
        ]);
    }
    public function activate($id)
    {
        $model = Purchase::find($id);
        $model->status = 'Active';
        $model->save();
        // $model->calculateLanding();
        // $model->updateStock();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->log('Activated');
        return response()->json([
            'message' => 'success'
        ]);
    }
    public function approve(Request $request, $id)
    {
        $model = Purchase::find($id);
        $model->status = 'Admin Approved';
        $model->save();
        $model->calculateLanding();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->log('Approved');
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function getReturnItems ($id) 
    {
        $model = Purchase::find($id);
        $returns_ids = $model->purchase_returns()->get()->pluck('id');
        return [
            'items' => \App\PurchaseReturnItem::with(['product','purchase_return'])->whereIn('purchase_return_id',$returns_ids)->get(),
            'model' => $model
        ];
    }
}
