<?php

namespace App\Http\Controllers;

use App\Product;
use App\Attribute;
use App\AttributeEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Exports\ProductsExport;
use Spatie\Activitylog\Models\Activity;

class ProductController extends Controller
{
    protected $searcheableColumns =  ['name','alias','sku'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searcheableColumns = $this->searcheableColumns;
        $visibleColumns = getVisibleColumns($request, 'products_index_visible_columns', ['name', 'sku','status','enabled','mrp']);
        $filters = getFilters($request, 'products_index_filtered');
        $search = getSearch($request, 'products_index_search');
        $pagination = getPagination($request, 'products_index_pagination');
        
        $select_arr = $this->searcheableColumns;
        
        
        foreach($visibleColumns as $vc){
            if(!in_array($vc,$select_arr))
                $select_arr[] = $vc;
        }
        foreach(array_keys($filters) as $filter_key){
            if(!in_array($filter_key,$select_arr) && $filter_key != 'reached_reorder_point')
                $select_arr[] = $filter_key;
        }
        if(count($filters) > 0){
            if(array_key_exists('reached_reorder_point', $filters)){
                $select_arr[] = 'reorder_point';
                $select_arr[] = 'available_stock';
            }
        }
        $select_str = 'id,';
        foreach($select_arr as $it){
            if($it == 'expirable'){
                $select_str .= '(CASE WHEN expirable <> 0 THEN \'Yes\' ELSE \'No\' END) As expirable,';
            }
            elseif($it == 'enabled'){
                $select_str .= '(CASE WHEN enabled <> 0 THEN \'Yes\' ELSE \'No\' END) As enabled,';
            }
            else{
                $select_str .= $it.',';
            }
        }
        
        $select_str = rtrim($select_str,",");
        $model = Product::select(DB::raw($select_str));
        if(in_array('total_stock',$select_arr)){
            $model->leftJoin(DB::raw('(SELECT product_id, SUM(qty) AS total_stock FROM product_stock GROUP BY product_id) AS g'),'products.id','=','g.product_id');
        }
        if(in_array('available_stock',$select_arr)){
            $model->leftJoin(DB::raw('(SELECT product_id, SUM(qty) AS available_stock FROM product_stock WHERE reservable_id = 0 GROUP BY product_id) AS h'),'products.id','=','h.product_id');
        }
        if(in_array('reserved_stock',$select_arr)){
            $model->leftJoin(DB::raw('(SELECT product_id, SUM(qty) AS reserved_stock FROM product_stock WHERE reservable_id <> 0 GROUP BY product_id) AS i'),'products.id','=','i.product_id');
        }
        if($pagination['sortBy'])
        {
            $model->orderBy($pagination['sortBy'],$pagination['desc']);
        }
        else{
            $model->orderBy('id','DESC');
        }
        // ============================ SEARCHING ============================================
        if($search){
            $chunks = explode(" ",$search);
            $model->where(function ($query) use ($chunks){
                $query->where(function ($query) use ($chunks){
                    foreach ($chunks as $it) {
                        if(strlen($it) > 0)
                        $query->where('name','like','%'.$it.'%');
                    }
                });
                $query->orWhere(function ($query) use ($chunks){
                    foreach ($chunks as $it) {
                        if(strlen($it) > 0)
                        $query->where('alias','like','%'.$it.'%');
                    }
                });
                $query->orWhere(function ($query) use ($chunks){
                    foreach ($chunks as $it) {
                        if(strlen($it) > 0)
                        $query->where('sku','like','%'.$it.'%');
                    }
                });
            });
        }
        // ============================= FILTERING ======================================
        if(count($filters) > 0)
        {   
            foreach($filters as $key => $val)
            {
                if(in_array($key,['status','gst']))
                {
                    $model->whereIn($key,$val);
                }
                if($key == 'hsn')
                {
                    $model->where($key,'like','%'.$val.'%');
                }
                if($key == 'reached_reorder_point' && $val == 'Yes') {
                    $model->where('available_stock','<=','reorder_point');
                }
                if(in_array($key,['mrp']))
                {
                    $model->whereBetween($key,[$val['min'],$val['max']]);
                }
                if(in_array($key,['enabled','expirable']))
                {
                    $model->where($key,$val == 'Yes'?1:0);
                }
            }
        }
        //return $model->toSql();
        return response()->json([
            'link_key' => 'name',
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
                'name' => 'name',
                'label' => 'Name',
                'field' => 'name',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'sku',
                'label' => 'SKU',
                'field' => 'sku',
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
                'name' => 'mrp',
                'label' => 'MRP',
                'field' => 'mrp',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'hsn',
                'label' => 'HSN',
                'field' => 'hsn',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'gst',
                'label' => 'GST',
                'field' => 'gst',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'gsp_customer',
                'label' => 'GSP Customer',
                'field' => 'gsp_customer',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'gsp_dealer',
                'label' => 'GSP Dealer',
                'field' => 'gsp_dealer',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'weight',
                'label' => 'Weight',
                'field' => 'weight',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'expirable',
                'label' => 'Expirable',
                'field' => 'expirable',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'enabled',
                'label' => 'Enabled',
                'field' => 'enabled',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'total_stock',
                'label' => 'Total Stock',
                'field' => 'total_stock',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'available_stock',
                'label' => 'Available Stock',
                'field' => 'available_stock',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'reserved_stock',
                'label' => 'Reserved Stock',
                'field' => 'reserved_stock',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'reorder_point',
                'label' => 'Reorder Point',
                'field' => 'reorder_point',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ]
        ];
        return $columns;
    }

    public function getFilterables()
    {

        $arr = [
            [
                'name' => 'Status',
                'slug' => 'status',
                'searcheable' => false,
                'field_type' => 'Selection',
                'options' => ['Active','Draft','Pending Approval','Approved'],
                'value' => []
            ],
            [
                'name' => 'HSN',
                'slug' => 'hsn',
                'field_type' => 'String',
                'value' => []
            ],
            [
                'name' => 'GST',
                'slug' => 'gst',
                'searcheable' => false,
                'field_type' => 'Selection',
                'options' => collect(config('app.gst_options'))->pluck('value')->all(),
                'value' => []
            ],
            [
                'name' => 'MRP',
                'slug' => 'mrp',
                'searcheable' => false,
                'field_type' => 'Decimal',
                'max'=>(int)Product::select('mrp')->max('mrp'),
                'min'=>(int)Product::select('mrp')->min('mrp'),
                'value' => []
            ],
            [
                'name' => 'Enabled',
                'slug' => 'enabled',
                'searcheable' => false,
                'field_type' => 'Boolean',
                'value' => []
            ],
            [
                'name' => 'Reached Reorder Point',
                'slug' => 'reached_reorder_point',
                'searcheable' => false,
                'field_type' => 'Boolean',
                'value' => []
            ],
            [
                'name' => 'Expirable',
                'slug' => 'expirable',
                'searcheable' => false,
                'field_type' => 'Boolean',
                'value' => []
            ]
        ];
        return $arr;
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
            'status' => 'required|in:Active,Draft,Pending Approval,Approved',
            'name' => 'required|unique:products',
            'alias' => 'nullable',
            'reorder_code' => 'nullable',
            'hsn' => 'nullable',
            'sku' => 'required|unique:products',
            'enabled' => 'required|boolean',
            'weight' => 'nullable|numeric',
            'mrp' => 'nullable|numeric',
            'gst' => 'required|numeric',
            'gsp_customer' => 'nullable',
            'gsp_dealer' => 'nullable',
            'type_id' => 'required|integer',
            'department_id' => 'required|integer',
            'category_id' => 'required|integer',
            'sub_category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'expirable' => 'required|boolean',
        ]);
        $model = Product::create($request->only([
            'status','name','alias','sku','gst','reorder_code','hsn','enabled','expirable','weight','mrp','gsp_customer','gsp_dealer','type_id',
            'department_id','category_id','sub_category_id','brand_id','description','remarks',
            'dentodeal_sku','dentodeal_enabled','dentodeal_bundled','dentodeal_bundle_qty','dentodeal_frontend_url','dentodeal_id','reorder_point',
            'sync_stock_dentodeal','mask_name',
            'default_supplier_id','length','breadth','height','gtin','origin_country'
        ]));
        $images = json_decode($request->images,true);
        foreach($images as $img)
        {
            $img_model = new \App\Image([
                'name' => $img['name'],
                'description' => $img['description'],
                'uri' => $img['uri'],
                'thumbnail_uri' => $img['thumbnail_uri']
            ]);
            $model->images()->save($img_model);
        }
        activity()
            ->causedBy(\Auth::user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('created');
        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::with('images','type','department','category','sub_category','brand','stocks', 'default_supplier')->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = Product::find($id);
        $oldModel = $model;
        $request->validate([
            'status' => 'required|in:Active,Draft,Pending Approval,Approved',
            'name' => 'required|unique:products,name,'.$model->id,
            'alias' => 'nullable',
            'reorder_code' => 'nullable',
            'hsn' => 'nullable',
            'sku' => 'required|unique:products,sku,'.$model->id,
            'enabled' => 'required|boolean',
            'weight' => 'nullable|numeric',
            'mrp' => 'nullable|numeric',
            'gst' => 'required|numeric',
            'gsp_customer' => 'nullable',
            'gsp_dealer' => 'nullable',
            'type_id' => 'required|integer',
            'department_id' => 'required|integer',
            'category_id' => 'required|integer',
            'sub_category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'expirable' => 'required|boolean',
        ]);
        //expirable is removed from this list. Because once expirable is set it cannot be changed
        $model->update($request->only([
            'status','name','alias','sku','gst','reorder_code','hsn','enabled','weight','mrp','gsp_customer','gsp_dealer','type_id',
            'department_id','category_id','sub_category_id','brand_id','description','remarks',
            'dentodeal_sku','dentodeal_enabled','dentodeal_bundled','dentodeal_bundle_qty','dentodeal_frontend_url','dentodeal_id','reorder_point',
            'sync_stock_dentodeal','mask_name',
            'default_supplier_id','length','breadth','height','gtin','origin_country'
        ]));
        $images = json_decode($request->images,true);
        foreach($images as $img)
        {
            $img_model = \App\Image::updateorCreate([
                'uri' => $img['uri'],
                'thumbnail_uri' => $img['thumbnail_uri'],
                'imageable_id' => $model->id,
                'imageable_type' => 'App\Product'
            ],[
                'name' => $img['name'],
                'description' => $img['description']
            ]);
        }
        activity()
            ->causedBy(\Auth::user())
            ->performedOn($model)
            ->withProperties($model->getChanges())
            ->log('updated');
        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Product::find($id);
        $this->authorize('delete',$model);
        
        $model->delete();
        activity()
            ->causedBy(\Auth::user())
            ->performedOn($model)
            ->log('deleted');
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function import(Request $request)
    {
        //return $request->file('file')->getMimeType();
        $request->validate([
            'file' => 'required|file',
            'method' => 'required',
        ]);

        $path = $request->file('file')->store('product_imports');
        $import = new \App\Imports\ProductsImport($request->method);
        try{
            $import->import($path, 'local', \Maatwebsite\Excel\Excel::XLSX);
        }
        catch(\App\Exceptions\ImportException $e) {
            return response()->json([
                'errors' => array_slice($e->failures(),0,50),
                'message' => 'Error in submitted file'
            ],422);
        }
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function export(){
        return (new ProductsExport)->download('products.xlsx');
    }

    public function getSku(Request $request, $id = NULL)
    {
        $pc = '';
        $pc .= \App\ProductType::where('id',$request->type_id)->first()->code;
        $pc .= \App\ProductDepartment::where('id',$request->department_id)->first()->code;
        $pc .= \App\ProductCategory::where('id',$request->category_id)->first()->code;
        $pc .= \App\ProductSubCategory::where('id',$request->sub_category_id)->first()->code;
        $pc .= \App\ProductBrand::where('id',$request->brand_id)->first()->code;
        $count = 0;
        $pc = $pc.str_pad($count, 3,0,STR_PAD_LEFT);
        if(!$id){
            $data['sku'] = $pc;
            while(Validator::make($data,['sku'=> 'unique:products,sku'])->fails()){
                $pc = substr($pc, 0, -3);
                $count = $count + 1;
                $pc = $pc.str_pad($count, 3,0,STR_PAD_LEFT);
                $data['sku'] = $pc;
            }
        }
        else{
            $data['sku'] = $pc;
            while(Validator::make($data,['sku'=> 'unique:products,sku,'.$id])->fails()){
                $pc = substr($pc, 0, -3);
                $count = $count + 1;
                $pc = $pc.str_pad($count, 3,0,STR_PAD_LEFT);
                $data['sku'] = $pc;
            }
        }
        
        return response()->json(['code' => $pc]);
    }

    public function search(Request $request){
        $select_arr = array_merge(['id'],$this->searcheableColumns);
        $model = Product::select($select_arr);
        if(!$request->has('inclall'))
            $model = $model->where('enabled',1);
        $chunks = explode(" ",$request->keyword);
        $model->where(function($query) use($chunks){
                  foreach($chunks as $it){
                      $query->where('name','like','%'.$it.'%');
                  }  
                })
                ->orWhere(function($query) use($chunks){
                    foreach($chunks as $it){
                        $query->where('sku','like','%'.$it.'%');
                    }  
                })
                ->orWhere(function($query) use($chunks){
                    foreach($chunks as $it){
                        $query->where('alias','like','%'.$it.'%');
                    }  
                })->limit(15);
        if($request->has('onlyname')){
            return $model->get()->pluck('name');
        }
        return $model->get();
    }

    public function getCost($id){
        return Product::find($id)->getCost();
    }

    public function getPurchases($id){
        return Product::find($id)->purchase_items()->with('purchase')->get();
    }

    public function getSales($id){
        return Product::find($id)->sale_items()->with('sale_order:id,serial_no,status,customer_id,created_at,rate_includes_gst,flood_cess_included','sale_order.customer:id,name,type')->get();
    }

    public function getPricelists($id){
        return DB::table('product_price')->where('product_id',$id)->get();
    }

    public function updatePricelists(Request $request,$id){
        foreach($request->pricelists as $pricelist){
            DB::table('product_price')->updateOrInsert(
                ['pricelist_id'=>$pricelist['id'],'product_id'=>$id],
                ['margin' => $pricelist['margin']]
            );
        }
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function getStock($id,$warehouse_id = NULL)
    {
        $model = Product::with(['stocks','stocks.saleorder','stocks.warehouse'])->find($id);
        
        if($warehouse_id){
            $data = [];
            $data['available_stock'] = $model->stocks()->where('reservable_id',0)->where('warehouse_id',$warehouse_id)->sum('qty');
            $data['stock_options'] = $model->stocks()->where('reservable_id',0)->where('warehouse_id',$warehouse_id)->get();
            return $data;
        } else {
            return [
                'status' => \App\ProductStock::with(['warehouse','saleorder'])->where('product_id',$id)->orderBy('reservable_id')->orderBy('qty','DESC')->get(),
                'total' => \App\ProductStock::where('product_id',$id)->where('reservable_id',0)->sum('qty')
            ];
        }
        
    }
    public function updateMinMargin(Request $request, $id)
    {
        $model = Product::find($id);
        $model->min_margin = $request->min_margin;
        $model->save();
        return response()->json(['message'=>'success']);
    }
    public function getBasic($id,$warehouse_id)
    {
        $product = Product::find($id);
        return [
            'expirable' => $product->expirable,
            'mrp' => $product->mrp,
            'gst' => $product->gst,
            'hsn' => $product->hsn,
            'weight' => $product->weight,
            'available_stock' => $product->availableStock($warehouse_id),
            'expiry_options' => $product->stocks()->where([
                ['reservable_id','=',0],
                ['warehouse_id','=',$warehouse_id],
                ['qty','>',0],
            ])->orderBy('expiry_date')->get(),
            'cost' => $product->getCost() ? $product->getCost()->cost : 0,
            'min_margin' => $product->min_margin,
            'reorder_code' => $product->reorder_code,
            'mask_name' => $product->mask_name,
        ];
    }

    public function requestApproval($id)
    {
        $model = Product::find($id);
        $model->status = 'Pending Approval';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function approve($id)
    {
        $model = Product::find($id);
        $model->status = 'Approved';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function activate($id)
    {
        $model = Product::find($id);
        $model->status = 'Active';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function revert($id)
    {
        $model = Product::find($id);
        $model->status = 'Draft';
        $model->save();
        return response()->json(['message' => 'success']);
    }
}
