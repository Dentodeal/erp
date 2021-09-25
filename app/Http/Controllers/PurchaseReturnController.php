<?php

namespace App\Http\Controllers;

use App\PurchaseReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'purchase_returns_index_visible_columns', ['serial_no','supplier_name','purchase_bill_number','status','created_at']);
        $filters = getFilters($request, 'purchase_returns_index_filtered');
        $search = getSearch($request, 'purchase_returns_index_search');
        $pagination = getPagination($request, 'purchase_returns_index_pagination');
        $select_arr = ['id'];
        foreach($visibleColumns as $vc){
            if(!in_array($vc,$select_arr))
                $select_arr[] = $vc;
        }
        foreach(array_keys($filters) as $filter_key){
            if(!in_array($filter_key,$select_arr))
                $select_arr[] = $filter_key;
        }

        foreach($select_arr as $key => $value){
            if($value == 'id'){
                $select_arr[$key] = 'purchase_returns.id AS id';
            }
            if($value == 'supplier_name'){
                $select_arr[$key] = 'suppliers.name AS supplier_name';
            }
            if($value == 'serial_no'){
                $select_arr[$key] = 'purchase_returns.serial_no AS serial_no';
            }
            if($value == 'purchase_bill_number'){
                $select_arr[$key] = 'purchases.bill_number AS purchase_bill_number';
            }
            if($value == 'status'){
                $select_arr[$key] = 'purchase_returns.status AS status';
            }
            if($value == 'created_at'){
                $select_arr[$key] = 'purchase_returns.created_at AS created_at';
            }
            if($value == 'created_by_name'){
                $select_arr[$key] = 'created_by.name AS created_by_name';
            }
        }
        $select_str = '';
        foreach($select_arr as $it){
            $select_str .= $it.',';
        }
        $select_str = rtrim($select_str,",");
        //dd($pageIndexVisibleColumnModel);
        $model = PurchaseReturn::select(DB::raw($select_str))
                            ->join('purchases','purchase_returns.purchase_id','=','purchases.id')
                            ->join('suppliers','purchases.supplier_id','=','suppliers.id')
                            ->join(DB::raw('users created_by'),'purchase_returns.created_by_id','=','created_by.id');
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key == 'status'){
                    $model->whereIn('purchase_returns.status',$val);
                }
            }
        }
        if($search){
            $chunks = explode(" ",$search);
            $model->where(function ($query) use ($chunks,$search){
                foreach ($chunks as $it) {
                    if(strlen($it) > 0)
                    $query->where('purchase_returns.serial_no','like','%'.$search.'%');
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
            'link_key' => 'serial_no',
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
                'name' => 'serial_no',
                'label' => 'Serial No',
                'field' => 'serial_no',
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
                'name' => 'status',
                'label' => 'Status',
                'field' => 'status',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'purchase_bill_number',
                'label' => 'Purchase',
                'field' => 'purchase_bill_number',
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
                'name' => 'created_by_name',
                'label' => 'Created By',
                'field' => 'created_by_name',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
        ];
        return $columns;
    }

    public function getFilterables()
    {
        return [
            [
                'field_type' => 'Selection',
                'name' => 'Status',
                'slug' => 'status',
                'searcheable' => false,
                'options' => PurchaseReturn::select('status')->distinct()->get()->pluck('status'),
                'value' => []
            ],
        ];

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
        $purchase = \App\Purchase::find($request->purchase_id);
        $groupedByProductId = collect($purchase->items)->groupBy('product_id');
        $validator = Validator::make($request->all(), [
            'items.*' => [
                function ($attribute, $value, $fail) use ($groupedByProductId) {
                    $purchaseItem = \App\PurchaseItem::find($value['id']);
                    if ((int)$value['qty'] > ($purchaseItem->qty - $purchaseItem->returned_qty)) {
                        $fail('The qty entered is invalid.');
                    }
                },
            ],
        ])->validate();
        $arr = $request->only([
            'purchase_id',
            'status',
            'remarks'
        ]);
        $arr['serial_no'] = generateSerial('sale_returns','PR');
        $arr['created_by_id'] = \Auth::user()->id;
        $model = PurchaseReturn::create($arr);
        foreach($request->items as $item){
            $purchaseReturnItemsModel = new \App\PurchaseReturnItem(Arr::only($item,[
                'product_id',
                'qty',
                'expirable',
                'expiry_date',
            ]));
            $purchaseReturnItemsModel->purchase_item_id = $item['id'];
            $model->items()->save($purchaseReturnItemsModel);
        }
        return response()->json([
            'message' => 'success', 'id' => $model->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PurchaseReturn  $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = PurchaseReturn::with(['purchase','purchase.supplier','purchase.warehouse','purchase.items','created_by','items','items.product'])->find($id);
        
        return $model;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchaseReturn  $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseReturn $purchaseReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseReturn  $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = PurchaseReturn::find($id);
        foreach($request->items as $item){
            $purchaseReturnItemsModel = \App\PurchaseReturnItem::find($id);
            $purchaseReturnItemsModel->update(Arr::only($item,[
                'product_id',
                'qty',
                'expirable',
                'expiry_date',
                'purchase_item_id',
            ]));
            $model->items()->save($purchaseReturnItemsModel);
        }
        return response()->json([
            'message' => 'success', 'id' => $model->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchaseReturn  $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseReturn $purchaseReturn)
    {
        $this->authorize($purchaseReturn,'delete');
        $purchaseReturn->items()->delete();
        $purchaseReturn->delete();
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function activate(Request $request, $id) 
    {
        $model = PurchaseReturn::find($id);
        Validator::make(['items' => $model->items],[
            'items.*' => function($attribute,$item,$fail) {
                $purchaseItem = \App\PurchaseItem::find($item['purchase_item_id']);
                if($purchaseItem->returned_qty == $purchaseItem->qty) {
                    $fail($purchaseItem->product->name. 'is completely returned');
                }
            }
        ])->validate();
        $model->status = 'Active';
        $model->remarks = $request->remarks;
        $model->save();
        foreach($model->items as $item)
        {
            if($item['expirable'])
            {
                \App\ProductStock::decreaseStock($item['product_id'], (int)$item['qty'], $item['expiry_date'], $model->purchase->warehouse->id);
            }
            else
            {
                \App\ProductStock::decreaseStock($item['product_id'], (int)$item['qty'], NULL, $model->purchase->warehouse->id);
            }
            $purchaseItem = \App\PurchaseItem::find($item['purchase_item_id']);
            $purchaseItem->update(['returned_qty' => $item['qty']]);
        }
        return response()->json([
            'message' => 'success', 'id' => $model->id
        ]);
    }
}
