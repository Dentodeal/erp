<?php

namespace App\Http\Controllers;

use App\SaleOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Rules\SaleOrderExpiryStock;
use Barryvdh\DomPDF\Facade as PDF;

class SaleOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'sale_orders_index_visible_columns', ['serial_no','customer_name','status','created_at']);
        $filters = getFilters($request, 'sale_orders_index_filtered');
        $search = getSearch($request, 'sale_orders_index_search');
        $pagination = getPagination($request, 'sale_orders_index_pagination');
        // dd($pagination);
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
                $select_arr[$key] = 'sale_orders.id AS id';
            }
            if($value == 'customer_name'){
                $select_arr[$key] = 'customers.name AS customer_name';
            }
            if($value == 'status'){
                $select_arr[$key] = 'sale_orders.status AS status';
            }
            if($value == 'shipment_status'){
                $select_arr[$key] = 'shipments.status AS shipment_status';
            }
            if($value == 'serial_no'){
                $select_arr[$key] = 'sale_orders.serial_no AS serial_no';
            }
            if($value == 'created_at'){
                $select_arr[$key] = 'sale_orders.created_at AS created_at';
            }
            if($value == 'created_by_name'){
                $select_arr[$key] = 'created_by.name AS created_by_name';
            }
            if($value == 'representative_name'){
                $select_arr[$key] = 'representative.name AS representative_name';
            }
            if($value == 'invoiced_by'){
                $select_arr[$key] = 'invoiced_by.name AS invoiced_by';
            }
        }
        $select_str = '';
        foreach($select_arr as $it){

                $select_str .= $it.',';
            
        }
        // dd($select_str);
        $select_str = rtrim($select_str,",");
        //dd($pageIndexVisibleColumnModel);
        $model = SaleOrder::select(DB::raw($select_str))->join('customers','sale_orders.customer_id','=','customers.id')
                            ->where('sale_orders.status','<>','INIT_BAL');
        if(strpos($select_str,'shipment_status') || array_key_exists('shipment_status',$filters)) {
            $model->leftJoin('shipments','shipments.sale_order_id','=','sale_orders.id');
        }
        if(strpos($select_str,'representative_name')|| array_key_exists('representative_name',$filters)) {
            $model->join(DB::raw('users representative'),'sale_orders.representative_id','=','representative.id');
        }

        if(strpos($select_str,'created_by_name')|| array_key_exists('created_by_name',$filters)) {
            $model->join(DB::raw('users created_by'),'sale_orders.created_by_id','=','created_by.id');
        }

        if(strpos($select_str,'invoiced_by')|| array_key_exists('invoiced_by',$filters)) {
            $model->leftJoin(DB::raw('users invoiced_by'),'sale_orders.invoiced_by_id','=','invoiced_by.id');
        }
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key == 'representative_name'){
                    $model->whereIn('representative.name',$val);
                }
                if($key == 'status'){
                    $model->whereIn('sale_orders.status',$val);
                }
                if($key == 'shipment_status'){
                    $model->whereIn('shipments.status',$val);
                }
                if($key == 'source'){
                    $model->whereIn('source',$val);
                }
            }
        }
        //dd($model->toSql());
        if ($search) {
            $chunks = explode(" ",$search);
            $model->where(function ($query) use ($chunks,$search){
                foreach ($chunks as $it) {
                    if(strlen($it) > 0)
                    $query->where('sale_orders.serial_no','like','%'.$search.'%');
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
            //'attributes' => $allAttributes
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
                'name' => 'shipment_status',
                'label' => 'Shipment Status',
                'field' => 'shipment_status',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'order_no',
                'label' => 'Order No',
                'field' => 'order_no',
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
                'name' => 'payment_status',
                'label' => 'Payment Status',
                'field' => 'payment_status',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'representative_name',
                'label' => 'Representative',
                'field' => 'representative_name',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'invoiced_at',
                'label' => 'Invoiced At',
                'field' => 'invoiced_at',
                'required' => false,
                'sortable' => true,
                'align' => 'right',
                'field_type' => 'datetime'
            ],
            [
                'name' => 'invoiced_by',
                'label' => 'Invoiced By',
                'field' => 'invoiced_by',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'source',
                'label' => 'Source',
                'field' => 'source',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'billing_address',
                'label' => 'Billing Address',
                'field' => 'billing_address',
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
            [
                'name' => 'otc',
                'label' => 'OTC',
                'field' => 'otc',
                'required' => false,
                'sortable' => true,
                'align' => 'right',
                'field_type' => 'boolean'
            ],
            [
                'name' => 'cod',
                'label' => 'COD',
                'field' => 'cod',
                'required' => false,
                'sortable' => true,
                'align' => 'right',
                'field_type' => 'boolean'
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
                'options' => SaleOrder::select('status')->distinct()->get()->pluck('status'),
                'value' => []
            ],
            [
                'field_type' => 'Selection',
                'name' => 'Shipment Status',
                'slug' => 'shipment_status',
                'searcheable' => false,
                'options' => \App\Shipment::select('status')->distinct()->get()->pluck('status'),
                'value' => []
            ],
            [
                'field_type' => 'Selection',
                'name' => 'Source',
                'slug' => 'source',
                'searcheable' => false,
                'options' => SaleOrder::select('source')->distinct()->get()->pluck('source'),
                'value' => []
            ],
            [
                'field_type' => 'Selection',
                'name' => 'Representative',
                'slug' => 'representative_name',
                'searcheable' => false,
                'options' => \App\User::select('users.id','users.name')->join('user_roles','user_roles.user_id','=','users.id')->join('roles','roles.id','=','user_roles.role_id')->where('roles.name','=','Sales')->get()->pluck('name'),
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
        $collection = collect($request->items);
        $groupedByProductId = $collection->groupBy('product.id');        
        $request->validate([
            'items.*' => [
                function($attribute, $item, $fail) use ($groupedByProductId) {
                    $product = \App\Product::find($item['product']['id']);
                    if($product->expirable) {
                        if(!$item['expiry_date']) $fail($product->name.'?Missing?-?-');
                        else {
                            $arr = [];
                            foreach($groupedByProductId->get($product->id) as $it)
                            {   
                                $arr[$it['expiry_date']] = array_key_exists($it['expiry_date'], $arr) ? $arr[$it['expiry_date']] + (int)$it['qty'] : (int)$it['qty'];
                            }
                            $availableStockForExpiry = 0;
                            $m = DB::table('product_stock')->where([
                                ['expiry_date','=',$item['expiry_date']],
                                ['reservable_id','=',0],
                                ['product_id','=',$item['product']['id']]
                            ])->first();
                            if($m) $availableStockForExpiry = $m->qty;
                            // dd($availableStockForExpiry);
                            if($arr[$item['expiry_date']] > $availableStockForExpiry)
                            {
                                $fail($product->name.'?'.$item['expiry_date'].'?'.$arr[$item['expiry_date']].'?'.$availableStockForExpiry);
                            }
                        }
                    } else {
                        $totalQty = 0;
                        foreach($groupedByProductId->get($product->id) as $it)
                        {   
                            $totalQty += (int)$it['qty'];
                        }
                        $availableStock = 0;
                        $m = DB::table('product_stock')->where([
                            ['expiry_date','=',NULL],
                            ['reservable_id','=',0],
                            ['product_id','=',$item['product']['id']]
                        ])->first();
                        if($m) $availableStock = $m->qty;
                        if($totalQty > $availableStock)
                        {
                            $fail($product->name.'?'.'-'.'?'.$totalQty.'?'.$availableStock);
                        }

                    }
                }
            ]
        ]);
        
        //-=======================Validation -======================================
        $model = SaleOrder::createEntry($request->all());
        SaleOrder::reserveStock($model,$model->items()->get());
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('created');
        return response()->json(['message'=>'success','id' => $model->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SaleOrder  $saleOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = SaleOrder::with([
            'items',
            'items.product:id,name,weight',
            'warehouse:id,name',
            'pricelist:id,name',
            'created_by:id,name',
            'representative:id,name',
            'customer:id,name,non_billable_account',
            'shipment',
            'invoiced_by:id,name',
            'quotation:id,serial_no,status',
        ])->find($id);
        $model->shipping_address = json_decode($model->shipping_address);
        $model->return_count = $model->sale_returns()->count();
        $model->paid_amount = $model->getSettled();
        return $model;
    }

    public function getSaleReturnItems ($id) 
    {
        $model = SaleOrder::find($id);
        $returns_ids = $model->sale_returns()->get()->pluck('id');
        return [
            'items' => \App\SaleReturnItem::with(['product','sale_return'])->whereIn('sale_return_id',$returns_ids)->get(),
            'model' => $model
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SaleOrder  $saleOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleOrder $saleOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SaleOrder  $saleOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = SaleOrder::find($id);
        // dd($model->items);
        $collection = collect($request->items);
        $groupedByProductId = $collection->groupBy('product.id');
        // dd($groupedByProductId);
        SaleOrder::revertStock($model,$model->items);           
        $validator = Validator::make($request->all(), [
            'items.*' => [
                function($attribute, $item, $fail) use($groupedByProductId) {
                    $product = \App\Product::find($item['product']['id']);
                    if($product->expirable) {
                        if(!$item['expiry_date']) $fail($product->name.'?Missing?-?-');
                        else {
                            $arr = [];
                            foreach($groupedByProductId->get($product->id) as $it)
                            {   
                                $arr[$it['expiry_date']] = array_key_exists($it['expiry_date'], $arr) ? $arr[$it['expiry_date']] + (int)$it['qty'] : (int)$it['qty'];
                            }
                            $availableStockForExpiry = 0;
                            $m = DB::table('product_stock')->where([
                                ['expiry_date','=',$item['expiry_date']],
                                ['reservable_id','=',0],
                                ['product_id','=',$item['product']['id']]
                            ])->first();
                            if($m) $availableStockForExpiry = $m->qty;
                            // dd($availableStockForExpiry);
                            if($arr[$item['expiry_date']] > $availableStockForExpiry)
                            {
                                $fail($product->name.'?'.$item['expiry_date'].'?'.$arr[$item['expiry_date']].'?'.$availableStockForExpiry);
                            }
                        }
                    } else {
                        $totalQty = 0;
                        foreach($groupedByProductId->get($product->id) as $it)
                        {   
                            $totalQty += (int)$it['qty'];
                        }
                        $availableStock = 0;
                        $m = DB::table('product_stock')->where([
                            ['expiry_date','=',NULL],
                            ['reservable_id','=',0],
                            ['product_id','=',$item['product']['id']]
                        ])->first();
                        if($m) $availableStock = $m->qty;
                        if($totalQty > $availableStock)
                        {
                            $fail($product->name.'?'.'-'.'?'.$totalQty.'?'.$availableStock);
                        }

                    }
                }
            ]
        ]);
                
        if ($validator->fails()) {
            SaleOrder::reserveStock($model,$model->items);
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'There are errors in the form submitted'
            ], 422);
        }
        
        $model->updateEntry($request->all());
        SaleOrder::reserveStock($model,$model->items()->get());
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('updated');
        return response()->json(['message'=>'success','id' => $model->id]);
    }

    public function deleteItem ($id) {
        $model = \App\SaleOrderItem::find($id);
        \App\SaleOrder::revertItem($model->sale_order,$model);
        \App\ProductStock::where([
            ['reservable_id','=',$model->sale_order->id],
            ['reservable_type','=','App\SaleOrder'],
            ['product_id','=',$model->product->id],
            ['qty','=',$model->qty],
            ['expiry_date','=',$model->expiry_date]
        ])->delete();
        $model->delete();
        return response()->json(['message'=>'success']); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SaleOrder  $saleOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Deleteting sale order is disabled. Instead use cancel function to cancel a sale order
        /*
        $model = SaleOrder::find($id);
        SaleOrder::revertStock($model, $model->items);
        $model->items()->delete();
        $this->authorize('delete',$model);
        $model->delete();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->log('deleted');
        return response()->json([
            'message' => 'success'
        ]);*/
    }

    public function sendToAccounts($id)
    {
        $model = SaleOrder::find($id);
        $model->status = 'Invoice Pending';
        $model->save();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('sent_for_invoice');
            return response()->json(['message' => 'success']);
    }
    public function requestApproval($id)
    {
        $model = SaleOrder::find($id);
        $model->status = 'Pending Approval';
        $model->save();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('approval_requested');
            return response()->json(['message' => 'success']);
    }

    public function approve(Request $request,$id)
    {
        $model = SaleOrder::find($id);
        $model->status = 'Approved';
        $model->remarks = $model->remarks.'<br/>'.$request->remarks;
        $model->revisit = 1;
        $model->save();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('approved');
            return response()->json(['message' => 'success']);
    }

    public function requestConfirmation($id)
    {
        $model = SaleOrder::find($id);
        $model->status = 'Pending Confirmation';
        $model->save();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('confirmation_requested');
            return response()->json(['message' => 'success']);
    }

    public function confirm($id)
    {
        $model = SaleOrder::find($id);
        $model->status = 'Confirmed';
        $model->save();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('confirmed');
            return response()->json(['message' => 'success']);
    }

    public function reject($id)
    {
        $model = SaleOrder::find($id);
        $model->status = 'Rejected';
        $model->revisit = 0;
        $model->save();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('rejected');
            return response()->json(['message' => 'success']);
    }

    public function invoice(Request $request, $id)
    {
        $model = SaleOrder::find($id);
        $model->registerPayment($request);
        if($model->type == 'Standard') {
            $model->status = 'Invoiced';
        } else {
            $model->status = 'Complete';
        }
        $model->invoiced_at = \Carbon\Carbon::now();
        $model->invoiced_by_id = $request->user()->id;
        $model->save();
        $model->customer->updateBalance();
        $user = $request->user();
        if($model->type == 'Standard') {
            $model->generateShipment();
            // $this->generateShipment($model);
        }
        activity()
            ->causedBy($request->user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('invoiced');
        return response()->json(['message' => 'success']);
    }

    public function complete($id)
    {
        $model = SaleOrder::find($id);
        $model->status = 'Complete';
        $model->clearReservation();
        $model->save();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('completed');
            return response()->json(['message' => 'success']);
    }

    public function revert($id)
    {
        $model = SaleOrder::find($id);
        if($model->status != 'Draft') {
            if($model->status != 'Invoice Pending')
            {
                SaleOrder::revertStock($model,$model->items);
                SaleOrder::reserveStock($model,$model->items);
            }
            $model->status = 'Draft';
            $model->payment_status = 'Unsettled';
            $model->invoiced_at = null;
            $model->revisit = 0;
            $model->invoiced_by_id = NULL;
            $model->save();
            $model->deleteTransactions();
            $model->sale_returns()->delete(); // TODO: ensure any refund associated also deleted
            $model->shipment()->delete();
            $model->customer->updateBalance();
            activity()
                ->causedBy(request()->user())
                ->performedOn($model)
                ->withProperties($model->getChanges())
                ->log('status_updated');
        }
        return response()->json(['message' => 'success']);
    }

    public function cancelOrder($id)
    {
        $model = SaleOrder::find($id);
        $this->authorize('cancel',$model);
        $shipment = $model->shipment;
        if($shipment) {
            Validator::make(['status' => $model->shipment->status],[
                'status' => [
                    function($attribute,$status,$fail){
                        if($status == 'Complete') {
                            $fail('This sale order is shipped. Cannot Cancel');
                        }
                    }
                ]
            ])->validate();
        }
        SaleOrder::revertStock($model,$model->items);
        $model->invoiced_at = null;
        $model->invoiced_by_id = NULL;
        $model->status = 'Cancelled';
        $model->save();
        $model->shipment()->update(['status'=>'Cancelled']);
        $model->customer->updateBalance();

        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('status_updated');
            return response()->json(['message' => 'success']);
    }

    public function registerPayment(Request $request, $id)
    {   
        $model= SaleOrder::find($id);
        $model->registerPayment($request);
        $model->customer->updateBalance();
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function download($id)
    {
        // Fetch all customers from database
        $data = SaleOrder::with(['items','items.product','created_by','representative','warehouse','pricelist','expiryItems'])->find($id);
        // Send data to the view using loadView function of PDF facade
        $pdf = PDF::loadView('saleorder', [
            'sale_order'=>$data,
            'totalQty' => $data->items()->sum('qty'),
            'totalTaxable' => $data->items()->sum('taxable'),
            'totalTax' => $data->items()->sum('tax_amount'),
            ]);
        // If you want to store the generated pdf to the server then you can use the store function
        //$pdf->save(storage_path().'_filename.pdf');
        // Finally, you can download the file using download function
        $filename = Str::slug($data->serial_no,'_').'.pdf';
        return $pdf->download($filename);
    }

    public function packaging(Request $request, $id)
    {
        // Fetch all customers from database
        $data = $request->toArray();
        // Send data to the view using loadView function of PDF facade
        $pdf = PDF::loadView('packaging', [
            'data'=>$data,
            ]);
        $filename = 'box.pdf';
        return $pdf->download($filename);
    }

    public function count()
    {
        $counts = \App\SaleOrder::selectRaw('source,COUNT(id) AS count, status')->where([
            ['customer_id','<>',0]
        ])->groupBy('source','status')->get();
        return $counts;
    }

    public function export(Request $request,$id)
    {
      // Fetch all customers from database
        $data = SaleOrder::with(['items','created_by','representative','warehouse','pricelist'])->find($id);
        $customer = \App\Customer::find($data->customer_id);
        $billing_address = '';
        $billing_model = $customer->getBillingAddress();
        if($billing_model) {
            $billing_address .= $billing_model->line_1.', ';
            if($billing_model->line_2){
                $billing_address .= $billing_model->line_2.', ';
            }
            if($billing_model->district){
                $billing_address .= $billing_model->district.', ';
            }
            $billing_address .= $billing_model->state.', ';
            if($billing_model->pin){
                $billing_address .= 'PIN: '.$billing_model->pin.', ';
            }
            $billing_address .= $billing_model->country.', ';
            $billing_phones = $billing_model->phones()->get();
            $billing_address .= 'Ph: ';
            foreach($billing_phones as $phone){
                $billing_address .= '('.$phone->country_code.')'.$phone->content.' ';
            }
        }
        // Send data to the view using loadView function of PDF facade
        if($request->mode == 'pdf'){
            $currency = $data->currency == 'INR' ? '' : '$';
            $pdf = PDF::loadView('saleorder_invoice', [
                'saleorder'=>$data,
                'currency' => $currency,
                'totalQty' => $data->items()->sum('qty'),
                'totalTaxable' => $data->items()->sum('taxable'),
                'totalTax' => $data->items()->sum('tax_amount'),
                'include_gst_column' => $request->include_gst_column,
                'use_mask_name' => $request->use_mask_name,
                'include_hsn_column' => $request->include_hsn_column,
                'address' => $billing_address,
            ]);
            // If you want to store the generated pdf to the server then you can use the store function
            //$pdf->save(storage_path().'_filename.pdf');
            // Finally, you can download the file using download function
            $filename = Str::slug($data->serial_no,'_').'.pdf';
            return $pdf->download($filename);
        }
        if($request->mode == 'excel'){
            return (new \App\Exports\SaleExport($data,$billing_address,$request->include_gst_column,$request->use_mask_name, $request->include_hsn_column))->download('file.xlsx');
        }
    }
}
