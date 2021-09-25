<?php

namespace App\Http\Controllers;

use App\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'shipments_index_visible_columns', [
            'serial_no',
            'sale_order_serial_no',
            'customer_name',
            'status',
            'created_at',
            'created_by_name',
            'source',
            'total'
        ]);
        $filters = getFilters($request, 'shipments_index_filtered');
        $search = getSearch($request, 'shipments_index_search');
        $pagination = getPagination($request, 'shipments_index_pagination');
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
                $select_arr[$key] = 'shipments.id AS id';
            }
            if($value == 'serial_no'){
                $select_arr[$key] = 'shipments.serial_no AS serial_no';
            }
            if($value == 'sale_order_serial_no'){
                $select_arr[$key] = 'sale_orders.serial_no AS sale_order_serial_no';
            }
            if($value == 'customer_name'){
                $select_arr[$key] = 'customers.name AS customer_name';
            }
            if($value == 'status'){
                $select_arr[$key] = 'shipments.status AS status';
            }
            if($value == 'created_at'){
                $select_arr[$key] = 'shipments.created_at AS created_at';
            }
            if($value == 'created_by_name'){
                $select_arr[$key] = 'created_by.name AS created_by_name';
            }
            if($value == 'representative_name'){
                $select_arr[$key] = 'representative.name AS representative_name';
            }
            if($value == 'cod'){
                $select_arr[$key] = '(CASE WHEN cod <> 0 THEN \'Yes\' ELSE \'No\' END) As cod';
            }
        }
        //dd($select_arr);
        $model = Shipment::select(DB::raw(implode(",",$select_arr)))
                            ->join('sale_orders','shipments.sale_order_id','=','sale_orders.id')
                            ->join('customers','sale_orders.customer_id','=','customers.id')
                            ->join(DB::raw('users created_by'),'shipments.created_by_id','=','created_by.id')
                            ->join(DB::raw('users representative'),'sale_orders.representative_id','=','representative.id');
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key == 'order_no'){
                    $model->where('sale_orders.order_no','like','%'.$val.'%');
                }
                if($key == 'status'){
                    $model->whereIn('shipments.status',$val);
                }
                if($key == 'source'){
                    $model->whereIn('source',$val);
                }
            }
        }
        if($search){
            $chunks = explode(" ",$search);
            $model->where(function ($query) use ($chunks,$search){
                foreach ($chunks as $it) {
                    if(strlen($it) > 0)
                    $query->where('shipments.serial_no','like','%'.$search.'%');
                    $query->orWhere('sale_orders.serial_no','like','%'.$search.'%');
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
                'name' => 'sale_order_serial_no',
                'label' => 'Sale Order',
                'field' => 'sale_order_serial_no',
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
                'name' => 'source',
                'label' => 'Source',
                'field' => 'source',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'cod',
                'label' => 'COD',
                'field' => 'cod',
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
                'name' => 'Source',
                'slug' => 'source',
                'options' => ['Apexion','Dentodeal'],
                'searcheable' => false,
                'value' => []
            ],
            [
                'field_type' => 'String',
                'name' => 'Order No',
                'slug' => 'order_no',
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Shipment::with([
            'sale_order',
            'sale_order.customer',
            'sale_order.representative',
            'created_by'
        ])->find($id);
        return $model;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipment $shipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = Shipment::find($id);
        $model->update($request->only([
            'length','width','height','weight', 'remarks', 'courier_id', 'tracking_number'
        ]));
         if ($request->length && $request->width && $request->height && $request->weight) {
            $model->update(['status' => 'Packed']);
        } else {
            $model->update(['status'=>'Awaiting Shipment']); 
        }
        return response()->json(['message'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shipment  $shipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipment $shipment)
    {
        //
    }

    public function complete(Request $request,$id)
    {
        $model = Shipment::find($id);
        $request->validate([
            'length' => Rule::requiredIf(function() use ($model) {
                return !$model->sale_order->otc;
            }),
            'width' => Rule::requiredIf(function() use ($model) {
                return !$model->sale_order->otc;
            }),
            'height' => Rule::requiredIf(function() use ($model) {
                return !$model->sale_order->otc;
            }),
            'weight' => Rule::requiredIf(function() use ($model) {
                return !$model->sale_order->otc;
            }),
        ]);
        $model->update($request->only([
            'length','width','height','weight', 'remarks', 'courier_id', 'tracking_number'
        ]));
        if(($request->length && $request->width && $request->height && $request->weight && $request->courier_id && $request->tracking_number) || $model->sale_order->otc){
            $model->update(['status'=>'Complete']); 
        } else if ($request->length && $request->width && $request->height && $request->weight) {
            $model->update(['status' => 'In Transit']);
        }
        $sale_order = \App\SaleOrder::find($model->sale_order_id);
        $sale_order->clearReservation();
        if($sale_order->type == 'Standard') {
            $sale_order->status = 'Complete';
        }
        $sale_order->save();
        return response()->json(['message'=>'success']);
    }

    public function label($id)
    {
        // Fetch all customers from database
        $data = Shipment::find($id);
        // Send data to the view using loadView function of PDF facade
        $shipping_address = json_decode($data->sale_order->shipping_address);
        $pdf = PDF::loadView('label', [
            'sale_order'=>$data->sale_order,
            'shipping_address' => $shipping_address->content
        ])->setPaper('a5', 'portrait');
        // If you want to store the generated pdf to the server then you can use the store function
        //$pdf->save(storage_path().'_filename.pdf');
        // Finally, you can download the file using download function
        $filename = Str::slug($data->serial_no,'_').'.pdf';
        return $pdf->download($filename);
    }

    public function count()
    {
        $counts = \App\Shipment::selectRaw('source,COUNT(shipments.id) AS count, shipments.status as status')->join('sale_orders','sale_orders.id','=','shipments.sale_order_id')
        ->groupBy('status','source')->get();
        // dd($counts->toArray());
        return $counts;
    }

    public function sendSms($id) {
        $model = Shipment::find($id);
        $phones = $model->sale_order->customer->billing_address->phones;
        $phone = $phones[0]['content'];
        // return $phone;
        $response = Http::get('https://www.fast2sms.com/dev/bulkV2', [
            'authorization' => 'C4Lnd2oyaKOm0jpiXDxueZ1vE8AzNtQch63SwUbGfq9B75PlHFZQAWowadzYPXsFV3lOJDCE0451bgvh',
            'message_text' => 'Your Order: '.$model->sale_order->serial_no.' is shipped.',
            'language' => 'english',
            'route' => 'v3',
            'sender_id' => ' TXTIND',
            'numbers' => $phone,
            'flash' => 0
        ]);
        return $response->body();
    }
}
