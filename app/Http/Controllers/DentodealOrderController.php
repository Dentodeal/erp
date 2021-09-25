<?php

namespace App\Http\Controllers;

use App\DentodealOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DentodealOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'dentodeal_orders_index_visible_columns', ['order_no','customer_name','status','erp_status','payment_method', 'total', 'shipping_charge','cod_charge']);
        $filters = getFilters($request, 'dentodeal_orders_index_filtered');
        $search = getSearch($request, 'dentodeal_orders_index_search');
        $pagination = getPagination($request, 'dentodeal_orders_index_pagination');
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

        $select_str = '';
        foreach($select_arr as $it){
            $select_str .= $it.',';
        }
        // dd($select_str);
        $select_str = rtrim($select_str,",");
        //dd($pageIndexVisibleColumnModel);
        $model = DentodealOrder::select(DB::raw($select_str));
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key == 'status'){
                    $model->whereIn('status',$val);
                }
            }
        }
        //dd($model->toSql());
        if ($search) {
            $chunks = explode(" ",$search);
            $model->where(function ($query) use ($chunks,$search){
                foreach ($chunks as $it) {
                    if(strlen($it) > 0)
                    $query->where('serial_no','like','%'.$search.'%');
                    $query->orWhere('customer_name','like','%'.$search.'%');
                    $query->orWhere('customer_email','like','%'.$search.'%');
                }
            });
        }
        if($pagination['sortBy'])
        {
            $model->orderBy($pagination['sortBy'],$pagination['desc']);
        }
        else{
            $model->orderBy('order_no','DESC');
        }
        

        return response()->json([
            'link_key' => 'order_no',
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
                'name' => 'order_no',
                'label' => 'Order No',
                'field' => 'order_no',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'customer_name',
                'label' => 'Customer Name',
                'field' => 'customer_name',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'customer_email',
                'label' => 'Customer Email',
                'field' => 'customer_email',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'status',
                'label' => 'Dentodeal Status',
                'field' => 'status',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'erp_status',
                'label' => 'Status',
                'field' => 'erp_status',
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
                'name' => 'payment_method',
                'label' => 'Payment Method',
                'field' => 'payment_method',
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
                'options' => DentodealOrder::select('status')->distinct()->get()->pluck('status'),
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
        // \Illuminate\Support\Facades\Log::info($request->toArray());
        $data = $request->toArray();
        $customer = \App\Customer::where('name','Customer Awaiting')->first();
        $representative = \App\User::where('name', 'Dentodeal')->first();
        $newData = [
            'status' => 'Draft',
            'source' => 'Dentodeal',
            'order_no' => $data['order_no'],
            'rate_includes_gst' => 1,
            'flood_cess_included' => 1,
            'otc' => 0,
            'cod' => $data['cod_amount'] > 0 ? 1 : 0,
            'discount' => $data['discount'],
            'freight' => $data['freight'],
            'cod_charge' => $data['cod_amount'],
            'round' => 0,
            'remarks' => '',
            'billing_address' => NULL,
            'shipping_address' => NULL,
            'po_number' => NULL,
            'order_date' => NULL,
            'customer' => [
                'id' => $customer->id
            ],
            'representative' => [
                'id' => $representative->id
            ],
            'pricelist' => [
                'id' => 1
            ],
            'warehouse' => [
                'id' => 1
            ],
            'created_by' => [
                'id' => $representative->id
            ],
        ];
        $newData['items'] = [];
        /*
        $saleOrder = \App\SaleOrder::create([
            
        ]);
        */
        // $subtotal = 0;
        $remarks = '';
        foreach($data['items'] as $item){
            $product = \App\Product::where('enabled',1)->where('sku', $item['sku'])->orWhere('dentodeal_sku', $item['sku'])->first();
            if($product) {
                $qty = $product->dentodeal_bundled ? $product->dentodeal_bundle_qty * (int)$item['qty'] : (int)$item['qty'];
                if($product->availableStock(1) >= $qty) {
                    $newData['items'][] = [
                        'product' => ['id' => $product->id],
                        'rate' => $product->dentodeal_bundled ? round((float)$item['price'] / $product->dentodeal_bundle_qty, 2) : (float)$item['price'],
                        'is_free' => 0,
                        'qty' => $qty,
                        'taxable' => 0,
                        'tax_amount' => 0,
                        'total' => 0,
                    ];
                    /*
                    $saleOrderItemsModel = new \App\SaleOrderItem([
                        'product_id' => $product->id,
                        'rate' => $product->dentodeal_bundled ? round((float)$item['price'] / $product->dentodeal_bundle_qty, 2) : (float)$item['price'],
                        'is_free' => 0,
                        'qty' => $product->dentodeal_bundled ? $product->dentodeal_bundle_qty * (int)$item['qty'] : (int)$item['qty'],
                        'taxable' => 0,
                        'tax_amount' => 0,
                        'total' => 0,
                        'expiry_date' => NULL
                    ]);
                    
                    $saleOrderItemsModel->gst = $product->gst;
                    $saleOrderItemsModel->expirable = $product->expirable;
                    $itemTotal = round((float)$item['price'] * (int)$item['qty'],2);
                    $saleOrderItemsModel->total = $itemTotal;
                    $saleOrderItemsModel->save();
                    $subtotal += $itemTotal;
                    $saleOrder->items()->save($saleOrderItemsModel);
                    */
                } else {
                    $remarks .= '<p><strong>Stock not available for:</strong></p>';
                    $remarks .= '<p> Product: '.$item['name'].', SKU: '.$item['sku'].', Rate: '.$item['price'].', Qty: '.$item['qty'].'</p>';
                }
            } else {
                $remarks .= '<p><strong>Product not found:</strong></p>';
                $remarks .= '<p> Product: '.$item['name'].', SKU: '.$item['sku'].', Rate: '.$item['price'].', Qty: '.$item['qty'].'</p>';
            }

        }
        //$saleOrder->subtotal = $subtotal;
        //$saleOrder->total = $subtotal - (float)$data['discount'] + (float)$data['freight'] + (float)$data['cod_amount'];
        $remarks .= '<p><strong>Order Data</strong></p>';
        $remarks .= '<p>Subtotal: '.$data['subtotal'].', Total: '.$data['total'].'</p>';
        $remarks .= '<p><strong>Customer Data</strong></p>';
        $remarks .= '<p>Email: '.$data['customer_email'].'</p>';
        $remarks .= '<p><i>Billing Address</i></p>';
        $remarks .= '<p>'.$data['billing_address']['name'].'</br>';
        $remarks .= '<p>'.implode(", ", $data['billing_address']['street']).', '.$data['billing_address']['city'].'</br>';
        $remarks .= '<p>'.$data['billing_address']['state'].', '.$data['billing_address']['country'].'</br>';
        $remarks .= '<p>PIN: '.$data['billing_address']['pin'].', Phone: '.$data['billing_address']['phone'].'</p>';
        $remarks .= '<p><i>Shipping Address</i></p>';
        $remarks .= '<p>'.$data['shipping_address']['name'].'</br>';
        $remarks .= '<p>'.implode(", ", $data['shipping_address']['street']).', '.$data['shipping_address']['city'].'</br>';
        $remarks .= '<p>'.$data['shipping_address']['state'].', '.$data['shipping_address']['country'].'</br>';
        $remarks .= '<p>PIN: '.$data['shipping_address']['pin'].', Phone: '.$data['shipping_address']['phone'].'</p>';
        $remarks .= '<p><strong>Payment Information</strong></p>';
        $remarks .= '<p>Payment Method: '.$data['payment'].', Transaction ID: '.$data['payment_info'].'</p>';
        foreach($data['status_histories'] as $it) {
            $remarks .= '<p>'.$it['comment'].', '.$it['created_at'].', '.$it['status'].'</p>';
        }
        $newData['dentodeal_data'] = $remarks;
        // $saleOrder->dentodeal_data = $remarks;
        // $saleOrder->save();
        $saleOrder = \App\SaleOrder::createEntry($newData);
        \App\SaleOrder::reserveStock($saleOrder,$saleOrder->items()->get());
        return 1;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DentodealOrder  $dentodealOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = DentodealOrder::with('items')->find($id);
        return $model;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DentodealOrder  $dentodealOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(DentodealOrder $dentodealOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DentodealOrder  $dentodealOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DentodealOrder $dentodealOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DentodealOrder  $dentodealOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(DentodealOrder $dentodealOrder)
    {
        //
    }
}
