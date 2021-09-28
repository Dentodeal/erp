<?php

namespace App\Http\Controllers;

use App\GoodsReceiveNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GoodsReceiveNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $visibleColumns = ['serial_no', 'status','supplier_name','delivery_date', 'created_by_name'];
        $filters = getFilters($request, 'goods_receive_notes_index_filtered');
        $search = getSearch($request, 'goods_receive_notes_index_search');
        $pagination = getPagination($request, 'goods_receive_notes_index_pagination');
        $select_arr = [
            'goods_receive_notes.serial_no AS serial_no',
            'goods_receive_notes.id AS id',
            'suppliers.name AS supplier_name',
            'goods_receive_notes.status AS status',
            'goods_receive_notes.delivery_date AS delivery_date',
            'users.name as created_by_name',
            'purchases.bill_number as purchase_serial'
        ];
        $model = GoodsReceiveNote::select($select_arr)->join('suppliers','goods_receive_notes.supplier_id','=','suppliers.id')
            ->join('users','goods_receive_notes.created_by_id', 'users.id')
            ->leftJoin('purchases','purchases.grn_id','goods_receive_notes.id');
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key != 'status'){
                    $chunks = explode(" ",$val);
                    $model->where(function ($query) use ($chunks,$key){
                        foreach ($chunks as $it) {
                            if(strlen($it) > 0)
                            $query->where('goods_receive_notes.'.$key,'like','%'.$it.'%');
                        }
                    });
                }
                else{
                    $model->where(function ($query) use ($val,$key){
                        $query->whereIn('goods_receive_notes.'.$key,$val);
                    });
                }
            }
        }
        if($search){
            $chunks = explode(" ",$search);
            $model->where(function ($query) use ($chunks,$search){
                foreach ($chunks as $it) {
                    if(strlen($it) > 0)
                    $query->where('goods_receive_notes.serial_no','like','%'.$search.'%');
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
                'name' => 'purchase_serial',
                'label' => 'Purchase',
                'field' => 'purchase_serial',
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
                'name' => 'delivery_date',
                'label' => 'Delivery Date',
                'field' => 'delivery_date',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'created_by_name',
                'label' => 'Created By',
                'field' => 'created_by_name',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],

        ];
        return $columns;
    }

    public function getFilterables()
    {
        $allAttributes = [
            [
                'field_type' => 'Selection',
                'name' => 'Status',
                'slug' => 'status',
                'searcheable' => false,
                'options' => GoodsReceiveNote::select('status')->distinct()->get()->pluck('status'),
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
        $request->validate([
            'items.*' => [
                function($attribute,$value,$fail){
                    if(!(int)$value['qty'] > 0) {
                        $fail('Qty is invalid');
                    }
                },
                function($attribute,$value,$fail){
                    if((bool)$value['expirable'] && !$value['expiry_date']) {
                        $fail('Expiry date not provided');
                    }
                }
            ],
            'supplier_id' => 'required',
            'warehouse_id' => 'required',
            'delivery_date' => 'required'
        ]);
        $model = new GoodsReceiveNote($request->only(['supplier_id','warehouse_id','delivery_date','status','remarks']));
        $model->created_by_id = \Auth::user()->id;
        $today_date = \Carbon\Carbon::now()->format('ymd');
        $serial_no = 'GRN'.$today_date.'000';
        $sn_val_arr['serial_no'] = $serial_no;
        $count = 0;
        while(Validator::make($sn_val_arr,['serial_no'=>'unique:goods_receive_notes,serial_no'])->fails()){
            $serial_no = substr($serial_no, 0, -3);
            $count = $count + 1;
            $serial_no = $serial_no.str_pad($count, 3,0,STR_PAD_LEFT);
            $sn_val_arr['serial_no'] = $serial_no;
        }
        $model->serial_no = $serial_no;
        $model->save();
        foreach($request->items as $item) {
            $GRNItemModel = new \App\GoodsReceiveNoteItem([
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'expirable' => $item['expirable'],
                'expiry_date' => $item['expiry_date']
            ]);
            $model->items()->save($GRNItemModel);
        }
        if($request->status == 'Active') {
            $model->updateStock();
        }
        return response()->json([
            'message' => 'success', 'id' => $model->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodsReceiveNote  $goodsReceiveNote
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return GoodsReceiveNote::with(['items','items.product','created_by','supplier','warehouse','purchase'])->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GoodsReceiveNote  $goodsReceiveNote
     * @return \Illuminate\Http\Response
     */
    public function edit(GoodsReceiveNote $goodsReceiveNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GoodsReceiveNote  $goodsReceiveNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = GoodsReceiveNote::find($id);
        $request->validate([
            'items.*' => [
                function($attribute,$value,$fail){
                    if(!(int)$value['qty'] > 0) {
                        $fail('Qty is invalid');
                    }
                },
                function($attribute,$value,$fail){
                    if((bool)$value['expirable'] && !$value['expiry_date']) {
                        $fail('Expiry date not provided');
                    }
                }
            ],
            'supplier_id' => 'required',
            'warehouse_id' => 'required',
            'delivery_date' => 'required'
        ]);
        $model->update($request->only(['supplier_id','warehouse_id','delivery_date','status','remarks']));
        $model->save();
        $model->items()->delete();
        foreach($request->items as $item) {
            $GRNItemModel = new \App\GoodsReceiveNoteItem([
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'expirable' => $item['expirable'],
                'expiry_date' => $item['expiry_date']
            ]);
            $model->items()->save($GRNItemModel);
        }
        if($request->status == 'Active') {
            $model->updateStock();
        }
        return response()->json([
            'message' => 'success', 'id' => $model->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoodsReceiveNote  $goodsReceiveNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodsReceiveNote $goodsReceiveNote)
    {
        if($goodsReceiveNote->status == 'Draft' || $goodsReceiveNote->status == 'Draft - Reverted') {
            $goodsReceiveNote->delete();
        }
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function activate($id) {
        $model = GoodsReceiveNote::find($id);
        $model->status = 'Active';
        $model->save();
        $model->updateStock();
        return response()->json([
            'message' => 'success', 'id' => $model->id
        ]);
    }

    public function revert($id) {
        $model = GoodsReceiveNote::find($id);
        $model->status = 'Draft - Reverted';
        $model->save();
        $model->revertStock();
        return response()->json([
            'message' => 'success', 'id' => $model->id
        ]);
    }
}
