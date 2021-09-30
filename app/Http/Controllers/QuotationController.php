<?php

namespace App\Http\Controllers;
use App\Quotation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'quotations_index_visible_columns', ['serial_no','customer_name','status','created_at']);
        $filters = getFilters($request, 'quotations_index_filtered');
        $search = getSearch($request, 'quotations_index_search');
        $pagination = getPagination($request, 'quotations_index_pagination');

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
                $select_arr[$key] = 'quotations.id AS id';
            }
            if($value == 'customer_name'){
                $select_arr[$key] = 'customers.name AS customer_name';
            }
            if($value == 'status'){
                $select_arr[$key] = 'quotations.status AS status';
            }
            if($value == 'type'){
                $select_arr[$key] = 'quotations.type AS type';
            }
            if($value == 'created_at'){
                $select_arr[$key] = 'quotations.created_at AS created_at';
            }
            if($value == 'created_by_name'){
                $select_arr[$key] = 'created_by.name AS created_by_name';
            }
            if($value == 'representative_name'){
                $select_arr[$key] = 'representative.name AS representative_name';
            }
            if($value == 'type'){
                $select_arr[$key] = 'quotations.type AS type';
            }
            
            if($value == 'invoiced_by'){
                $select_arr[$key] = 'invoiced_by.name AS invoiced_by';
            }
        }
        // dd($select_arr);
        $model = Quotation::select($select_arr)->join('customers','quotations.customer_id','=','customers.id')
                            ->join(DB::raw('users created_by'),'quotations.created_by_id','=','created_by.id')
                            ->join(DB::raw('users representative'),'quotations.representative_id','=','representative.id');
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key != 'status' && $key != 'created_by_name' && $key != 'type'){
                    $chunks = explode(" ",$val);
                    $model->where(function ($query) use ($chunks,$key){
                        foreach ($chunks as $it) {
                            if(strlen($it) > 0)
                            $query->where('quotations.'.$key,'like','%'.$it.'%');
                        }
                    });
                } else if($key == 'created_by_name') {
                    $model->where(function ($query) use ($val,$key){
                        $query->whereIn('created_by.name',$val);
                    });
                }
                else{
                    $model->where(function ($query) use ($val,$key){
                        $query->whereIn('quotations.'.$key,$val);
                    });
                }
            }
        }
        if($search){
            $chunks = explode(" ",$search);
            $model->where(function ($query) use ($chunks,$search){
                foreach ($chunks as $it) {
                    if(strlen($it) > 0)
                    $query->where('quotations.serial_no','like','%'.$search.'%');
                    $query->orWhere('customers.name','like','%'.$search.'%');
                    $query->orWhere('quotations.po_number','like','%'.$search.'%');
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
                'name' => 'type',
                'label' => 'Type',
                'field' => 'type',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'po_number',
                'label' => 'PO Number',
                'field' => 'po_number',
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
                'name' => 'representative_name',
                'label' => 'Representative',
                'field' => 'representative_name',
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
                'options' => ['Draft','Complete'],
                'value' => []
            ],
            [
                'field_type' => 'Selection',
                'name' => 'Type',
                'slug' => 'type',
                'searcheable' => false,
                'options' => ['Standard','Export'],
                'value' => []
            ],
            [
                'field_type' => 'Selection',
                'name' => 'Created By',
                'slug' => 'created_by_name',
                'searcheable' => false,
                'options' => \App\User::select('id','name')->whereIn('id',\App\Quotation::select('created_by_id')->distinct()->get()->pluck('created_by_id'))->get()->pluck('name'),
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $arr = $request->only([
            'status',
            'rate_includes_gst',
            'flood_cess_included',
            'subtotal',
            'discount',
            'freight',
            'round',
            'total',
            'remarks',
            'instructions',
            'terms',
            'fob_point',
            'po_number',
            'valid_until',
            'contact_person',
            'contact_email',
            'contact_phone',
            'ship_date',
            'ship_via',
            'type',
            'bank_charges',
            'prev_balance',
            'bank',
            'currency',
            'inco_term',
        ]);
        $arr['representative_id'] = $request->representative['id'];
        $arr['customer_id'] = $request->customer['id'];
        $arr['pricelist_id'] = $request->pricelist['id'];
        $arr['warehouse_id'] = $request->warehouse['id'];
        $arr['created_by_id'] = $request->created_by['id']; 
        $today_date = \Carbon\Carbon::now()->format('ymd');
        $serial_no = 'AQ'.$today_date.'0000';
        $sn_val_arr['serial_no'] = $serial_no;
        $count = 0;
        while(Validator::make($sn_val_arr,['serial_no'=>'unique:quotations,serial_no'])->fails()){
            $serial_no = substr($serial_no, 0, -4);
            $count = $count + 1;
            $serial_no = $serial_no.str_pad($count, 4,0,STR_PAD_LEFT);
            $sn_val_arr['serial_no'] = $serial_no;
        }
        $arr['serial_no'] = $serial_no;
        //dd($arr);
        $model = Quotation::create($arr);

        foreach($request->items as $item){
            $arr = Arr::only($item,[
                'product_id',
                'product_name',
                'mask_name',
                'use_mask',
                'gst',
                'rate',
                'qty',
                'taxable',
                'tax_amount',
                'total',
            ]);
            $arr['gst'] = $item['gst'] ? $item['gst'] : ($item['product_id'] > 0 ? \App\Product::find($item['product_id'])->gst : 0);
            $quotationItemsModel = new \App\QuotationItem($arr);
            $model->items()->save($quotationItemsModel);
        }
        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Quotation::with([
            'items',
            'items.product:id,name,weight',
            'created_by:id,name',
            'representative:id,name',
            'warehouse:id,name',
            'pricelist:id,name',
            'customer:id,name,is_lead,status',
            'sale_orders:id,serial_no,status'
        ])->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function edit(Quotation $quotation)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = Quotation::find($id);
        $arr = $request->only([
            'status',
            'rate_includes_gst',
            'flood_cess_included',
            'subtotal',
            'discount',
            'freight',
            'round',
            'total',
            'remarks',
            'instructions',
            'terms',
            'fob_point',
            'po_number',
            'valid_until',
            'contact_person',
            'contact_email',
            'contact_phone',
            'ship_date',
            'ship_via',
            'bank_charges',
            'prev_balance',
            'bank',
            'currency',
            'inco_term',
        ]);
        $arr['representative_id'] = $request->representative['id'];
        $arr['customer_id'] = $request->customer['id'];
        $arr['pricelist_id'] = $request->pricelist['id'];
        $arr['warehouse_id'] = $request->warehouse['id'];
        $arr['created_by_id'] = $request->created_by['id'];
        //dd($arr);
        $model->update($arr);
        //$model->items()->delete();
        $count = 1;
        foreach($request->items as $item){
            $arr = Arr::only($item,[
                'product_id',
                'product_name',
                'mask_name',
                'use_mask',
                'gst',
                'rate',
                'qty',
                'taxable',
                'tax_amount',
                'total',
            ]);
            $arr['gst'] = $item['gst'] ? $item['gst'] : ($item['product_id'] > 0 ? \App\Product::find($item['product_id'])->gst : 0);
            $arr['order'] = $count;
            if (array_key_exists('id',$item)) {
                $quotationItemModel = \App\QuotationItem::find($item['id']);
                $quotationItemModel->update($arr);
            } else {
                $quotationItemsModel = new \App\QuotationItem($arr);
                $model->items()->save($quotationItemsModel);
            }
            $count++;
        }
        return response()->json(['message'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotation $quotation)
    {
        $this->authorize('delete',$quotation);
        $quotation->delete();
        return response()->json(['message'=>'success']);
    }

    public function deleteItem($id)
    {
        $model = \App\QuotationItem::find($id);
        $model->delete();
        return response()->json(['message'=>'success']);
    }

    public function export(Request $request,$id)
    {
      // Fetch all customers from database
        $data = Quotation::with(['items','created_by','representative','warehouse','pricelist'])->find($id);
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
        $pdf = PDF::loadView('quotation', [
            'quotation'=>$data,
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
          return (new \App\Exports\QuotationExport($data,$billing_address,$request->include_gst_column,$request->use_mask_name, $request->include_hsn_column))->download('file.xlsx');
      }
    }

    public function convert($id)
    {
        $model = Quotation::find($id);
        $collection = $model->items;
        $groupedByProductId = $collection->groupBy('product_id');
        $validator = Validator::make(['items' => $model->items->toArray()], [
            'items.*' => [
                function($attribute, $item, $fail) use($groupedByProductId, $model) {
                    $product = \App\Product::find($item['product_id']);
                    $totalQty = 0;
                    foreach($groupedByProductId->get($product->id) as $it)
                    {   
                        $totalQty += (int)$it['qty'];
                    }
                    $availableStock = $product->availableStock($model->warehouse_id);
                    if($totalQty > $availableStock)
                    {
                        $fail('Stock not available for given qty for'.$product->name.' [Given: '.$totalQty.', Available: '.$availableStock.']');
                    }
                }
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'There are errors in the form submitted'
            ], 422);
        }
        $customer = \App\Customer::find($model->customer_id);
        $billing_model = $customer->getBillingAddress();
        $billing_address = '';
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

        $shipping_model = $customer->getShippingAddress();
        $shipping_address = '';
        $shipping_address .= $shipping_model->line_1.', ';
        if($shipping_model->line_2){
            $shipping_address .= $shipping_model->line_2.', ';
        }
        if($shipping_model->district){
            $shipping_address .= $shipping_model->district.', ';
        }
        $shipping_address .= $shipping_model->state.', ';
        if($shipping_model->pin){
            $shipping_address .= 'PIN: '.$shipping_model->pin.', ';
        }
        $shipping_address .= $shipping_model->country.', ';
        $billing_phones = $shipping_model->phones()->get();
        $shipping_address .= 'Ph: ';
        foreach($billing_phones as $phone){
            $shipping_address .= '('.$phone->country_code.')'.$phone->content.' ';
        }
        $items = $model->items->toArray();
        foreach($items as $index => $item) {
            $product = \App\Product::find($item['product_id']);
            $items[$index]['product']['id'] = $product->id;
            $items[$index]['expirable'] = $product->expirable;
        }
        $data =[
            'status' => 'Draft',
            'customer' => $model->customer,
            'representative' => $model->representative,
            'pricelist' => $model->pricelist,
            'warehouse' => $model->warehouse,
            'source' => 'Apexion',
            'order_no' => NULL,
            'created_by' => request()->user(),
            'rate_includes_gst' => $model->rate_includes_gst,
            'flood_cess_included' => $model->flood_cess_included,
            'otc' => 0,
            'cod' => 0,
            'discount' => $model->discount,
            'freight' => $model->freight,
            'cod_charge' => 0,
            'round' => $model->round,
            'remarks' => $model->remarks,
            'billing_address' => $billing_address,
            'shipping_address' => [
                'id' => $shipping_model->id,
                'content' => $shipping_address,
            ],
            'po_number' => $model->po_number,
            'items' => $items,
            'quotation_id' => $model->id,
            'type' => $model->type,
            'currency' => $model->currency,
            'bank_charges' => $model->bank_charges,
        ];
        $saleOrder = \App\SaleOrder::createEntry($data);
        if($model->type == 'Export') {
            $saleOrder->generateShipment();
        }
        \App\SaleOrder::reserveStock($saleOrder,$saleOrder->items()->get());
        foreach($model->items as $item){
            $quotationItemModel = \App\QuotationItem::find($item['id']);
            $quotationItemModel->converted_qty = $item['qty'];
            $quotationItemModel->save();
        }
        $model->status = 'Converted';
        $model->save();
        activity()
            ->causedBy($model)
            ->performedOn($saleOrder)
            ->withProperties($saleOrder->getChanges())
            ->log('created');
        return response()->json(['message'=>'success','id'=>$saleOrder->id]);
    }
    public function partialConvert(Request $request, $id)
    {
        $model = Quotation::find($id);
        $collection = collect($request->items);
        $groupedByProductId = $collection->groupBy('product_id');
        $validator = Validator::make($request->all(), [
            'items.*' => [
                function ($attribute, $item, $fail) use ($groupedByProductId, $model) {
                    $quotationItemModel = \App\QuotationItem::find($item['id']);
                    if ($item['qty'] > ($quotationItemModel->qty - $quotationItemModel->converted_qty)) {
                        $fail('The qty entered is invalid.');
                    }
                    $product = \App\Product::find($item['product_id']);
                    $totalQty = 0;
                    foreach($groupedByProductId->get($product->id) as $it)
                    {   
                        $totalQty += (int)$it['qty'];
                    }
                    $availableStock = $product->availableStock($model->warehouse_id);
                    if($totalQty > $availableStock)
                    {
                        $fail('Stock not available for given qty for'.$product->name.' [Given: '.$totalQty.', Available: '.$availableStock.']');
                    }
                },
            ],
        ])->validate();
        
        $customer = \App\Customer::find($model->customer_id);
        $billing_model = $customer->getBillingAddress();
        $billing_address = '';
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

        $shipping_model = $customer->getShippingAddress();
        $shipping_address = '';
        $shipping_address .= $shipping_model->line_1.', ';
        if($shipping_model->line_2){
            $shipping_address .= $shipping_model->line_2.', ';
        }
        if($shipping_model->district){
            $shipping_address .= $shipping_model->district.', ';
        }
        $shipping_address .= $shipping_model->state.', ';
        if($shipping_model->pin){
            $shipping_address .= 'PIN: '.$shipping_model->pin.', ';
        }
        $shipping_address .= $shipping_model->country.', ';
        $billing_phones = $shipping_model->phones()->get();
        $shipping_address .= 'Ph: ';
        foreach($billing_phones as $phone){
            $shipping_address .= '('.$phone->country_code.')'.$phone->content.' ';
        }

        $items = $request->items;
        foreach($items as $index => $item) {
            $product = \App\Product::find($item['product_id']);
            $items[$index]['product']['id'] = $product->id;
            $items[$index]['expirable'] = $product->expirable;
        }
        $data =[
            'status' => 'Draft',
            'customer' => $model->customer,
            'representative' => $model->representative,
            'pricelist' => $model->pricelist,
            'warehouse' => $model->warehouse,
            'source' => 'Apexion',
            'order_no' => NULL,
            'created_by' => request()->user(),
            'rate_includes_gst' => $model->rate_includes_gst,
            'flood_cess_included' => $model->flood_cess_included,
            'otc' => 0,
            'cod' => 0,
            'discount' => $model->discount,
            'freight' => $model->freight,
            'cod_charge' => 0,
            'round' => $model->round,
            'remarks' => $model->remarks,
            'billing_address' => $billing_address,
            'shipping_address' => json_encode([
                'id' => $shipping_model->id,
                'content' => $shipping_address,
            ]),
            'po_number' => $model->po_number,
            'items' => $items,
            'quotation_id' => $model->id,
            'type' => $model->type,
            'currency' => $model->currency,
            'bank_charges' => $model->bank_charges,
        ];
        $saleOrder = \App\SaleOrder::createEntry($data);
        if($model->type == 'Export') {
            $saleOrder->generateShipment();
        }
        \App\SaleOrder::reserveStock($saleOrder,$saleOrder->items()->get());
        foreach($request->items as $item){
            $quotationItemModel = \App\QuotationItem::find($item['id']);
            $quotationItemModel->converted_qty = $quotationItemModel->converted_qty + $item['qty'];
            $quotationItemModel->save();
        }
        $flag = false;
        $items = $model->items()->get();
        foreach($items as $item) {
            if($item['converted_qty'] != $item['qty']) {
                $flag = true;
                break;
            }
        }
        if ($flag) $model->status = 'Partially Converted';
        else $model->status = 'Converted';
        $model->save();
        activity()
            ->causedBy($model)
            ->performedOn($saleOrder)
            ->withProperties($saleOrder->getChanges())
            ->log('created');
        return response()->json(['message'=>'success','id'=>$saleOrder->id]);
    }

    public function addPayment(Request $request,$id) {
        $model = Quotation::find($id);
        $payments = $model->payments;
        $payments[] = [
            'id' => time(),
            'payment_via' => $request->payment_via,
            'currency' => $request->currency,
            'transaction_id' => $request->transaction_id,
            'amount' => $request->amount
        ];
        $model->payments = $payments;
        $model->save();
        return response()->json([
            'message' => 'success',
            'payments' => $payments 
        ]);
    }

    public function deletePayment ($id, $payment_id) {
        $model = Quotation::find($id);
        $payments = $model->payments;
        $deleteIndex = NULL;
        foreach($payments as $index => $item) {
            if($item['id'] == $payment_id) {
                $deleteIndex = $index;
            }
        }
        if($deleteIndex !== NULL) {
            unset($payments[$deleteIndex]);
        }
        $model->payments = $payments;
        $model->save();
        return response()->json([
            'message' => 'success',
            'payments' => $payments 
        ]);
    } 

    public function addDocuments(Request $request, $id) {
        $model = Quotation::find($id);
        $path = $request->file('file')->store('docs','public');
        $documents = $model->documents;
        $documents[] = [
            'id' => time(),
            'name' => $request->name,
            'link' => $path
        ];
        $model->documents = $documents;
        $model->save();
        return response()->json([
            'message' => 'success',
            'documents' => $documents
        ]);
    }

    public function deleteDocument ($id, $doc_id) {
        $model = Quotation::find($id);
        $documents = $model->documents;
        $deleteIndex = NULL;
        foreach($documents as $index => $item) {
            if($item['id'] == $doc_id) {
                $deleteIndex = $index;
            }
        }
        if($deleteIndex !== NULL) {
            Storage::disk('public')->delete($documents[$deleteIndex]['link']);
            unset($documents[$deleteIndex]);
        }
        $model->documents = $documents;
        $model->save();
        return response()->json([
            'message' => 'success',
            'documents' => $documents 
        ]);
    } 
}
