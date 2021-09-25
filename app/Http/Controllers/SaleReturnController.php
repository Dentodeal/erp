<?php

namespace App\Http\Controllers;

use App\SaleReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'sale_returns_index_visible_columns', ['serial_no','customer_name','sale_order_serial_no','status','created_at']);
        $filters = getFilters($request, 'sale_returns_index_filtered');
        $search = getSearch($request, 'sale_returns_index_search');
        $pagination = getPagination($request, 'sale_returns_index_pagination');
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
                $select_arr[$key] = 'sale_returns.id AS id';
            }
            if($value == 'customer_name'){
                $select_arr[$key] = 'customers.name AS customer_name';
            }
            if($value == 'serial_no'){
                $select_arr[$key] = 'sale_returns.serial_no AS serial_no';
            }
            if($value == 'total'){
                $select_arr[$key] = 'sale_returns.total AS total';
            }
            if($value == 'sale_order_serial_no'){
                $select_arr[$key] = 'sale_orders.serial_no AS sale_order_serial_no';
            }
            if($value == 'status'){
                $select_arr[$key] = 'sale_returns.status AS status';
            }
            if($value == 'created_at'){
                $select_arr[$key] = 'sale_returns.created_at AS created_at';
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
        $model = SaleReturn::select(DB::raw($select_str))
                            ->join('sale_orders','sale_returns.sale_order_id','=','sale_orders.id')
                            ->join('customers','sale_orders.customer_id','=','customers.id')
                            ->join(DB::raw('users created_by'),'sale_returns.created_by_id','=','created_by.id');
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key == 'status'){
                    $model->whereIn('sale_returns.status',$val);
                }
            }
        }
        if($search){
            $chunks = explode(" ",$search);
            $model->where(function ($query) use ($chunks,$search){
                foreach ($chunks as $it) {
                    if(strlen($it) > 0)
                    $query->where('sale_returns.serial_no','like','%'.$search.'%');
                    $query->orWhere('customers.name','like','%'.$search.'%');
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
                'name' => 'customer_name',
                'label' => 'Customer',
                'field' => 'customer_name',
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
                'name' => 'sale_order_serial_no',
                'label' => 'Sale Order',
                'field' => 'sale_order_serial_no',
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
            [
                'name' => 'total',
                'label' => 'Total',
                'field' => 'total',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
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
                'options' => SaleReturn::select('status')->distinct()->get()->pluck('status'),
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
        $saleOrder = \App\SaleOrder::find($request->sale_order_id);
        $groupedByProductId = collect($saleOrder->items)->groupBy('product_id');
        $validator = Validator::make($request->all(), [
            'items.*' => [
                function ($attribute, $value, $fail) use ($groupedByProductId) {
                    $saleOrderItem = \App\SaleOrderItem::find($value['id']);
                    if ((int)$value['qty'] > ($saleOrderItem->qty - $saleOrderItem->returned_qty)) {
                        $fail('The qty entered is invalid.');
                    }
                },
            ],
        ])->validate();
        $arr = $request->only([
            'sale_order_id',
            'status',
            'freight',
            'subtotal',
            'total',
            'remarks'
        ]);
        $arr['serial_no'] = generateSerial('sale_returns','SR');
        $arr['created_by_id'] = \Auth::user()->id;
        $model = SaleReturn::create($arr);
        foreach($request->items as $item){
            $saleReturnItemsModel = new \App\SaleReturnItem(Arr::only($item,[
                'product_id',
                'gst',
                'rate',
                'is_free',
                'qty',
                'taxable',
                'tax_amount',
                'total',
                'expirable',
                'expiry_date',
            ]));
            $saleReturnItemsModel->sale_order_item_id = $item['id'];
            $model->items()->save($saleReturnItemsModel);
        }
        return response()->json([
            'message' => 'success', 'id' => $model->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return SaleReturn::with(['sale_order','sale_order.customer','sale_order.warehouse','sale_order.items','created_by','items','items.product'])->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleReturn $saleReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = SaleReturn::find($id);
        $arr = $request->only([
            'freight',
            'subtotal',
            'total',
        ]);
        $model->update($arr);
        foreach($request->items as $item){
            $saleReturnItemsModel = \App\SaleReturnItem::find($id);
            $saleReturnItemsModel->update(Arr::only($item,[
                'product_id',
                'gst',
                'rate',
                'is_free',
                'qty',
                'taxable',
                'tax_amount',
                'total',
                'expirable',
                'expiry_date',
                'sale_order_item_id',
            ]));
            $model->items()->save($saleReturnItemsModel);
        }
        return response()->json([
            'message' => 'success', 'id' => $model->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaleReturn  $saleReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleReturn $saleReturn)
    {
        $this->authorize($saleReturn,'delete');
        $saleReturn->items()->delete();
        $saleReturn->delete();
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function activate(Request $request, $id) 
    {
        $model = SaleReturn::find($id);
        Validator::make(['items' => $model->items],[
            'items.*' => function($attribute,$item,$fail) {
                $saleOrderItem = \App\SaleOrderItem::find($item['sale_order_item_id']);
                if($saleOrderItem->returned_qty == $saleOrderItem->qty) {
                    $fail($saleOrderItem->product->name. 'is completely returned');
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
                \App\ProductStock::increaseStock($item['product_id'], (int)$item['qty'], $item['expiry_date'], $model->sale_order->warehouse->id);
            }
            else
            {
                \App\ProductStock::increaseStock($item['product_id'], (int)$item['qty'], NULL, $model->sale_order->warehouse->id);
            }
            $saleOrderItem = \App\SaleOrderItem::find($item['sale_order_item_id']);
            $saleOrderItem->update(['returned_qty' => $item['qty']]);
        }
        $model->sale_order->customer->updateBalance();
        return response()->json([
            'message' => 'success', 'id' => $model->id
        ]);
    }
}
