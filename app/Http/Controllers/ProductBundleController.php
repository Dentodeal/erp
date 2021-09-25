<?php

namespace App\Http\Controllers;

use App\ProductBundle;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductBundleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searcheableColumns = ['name'];
        $visibleColumns = getVisibleColumns($request, 'product_bundles_index_visible_columns', ['name']);
        $filters = getFilters($request, 'product_bundles_index_filtered');
        $search = getSearch($request, 'product_bundles_index_search');
        $pagination = getPagination($request, 'product_bundles_index_pagination');
        
        $select_arr = $searcheableColumns;
        
        
        foreach($visibleColumns as $vc){
            if(!in_array($vc,$select_arr))
                $select_arr[] = $vc;
        }
        foreach(array_keys($filters) as $filter_key){
            if(!in_array($filter_key,$select_arr))
                $select_arr[] = $filter_key;
        }
        $select_str = 'id,';
        foreach($select_arr as $it){
            $select_str .= $it.',';
        }
        $select_str = rtrim($select_str,",");
        $model = ProductBundle::select(DB::raw($select_str));
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
            });
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
            ]
        ];
        return $columns;
    }

    public function getFilterables()
    {

        $arr = [];
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
            'name' => 'required|unique:product_bundles,name',
            'sku' => 'required|unique:product_bundles,sku'
        ]);
        $model = ProductBundle::create([
            'name' => $request->name,
            'sku' => $request->sku
        ]);
        foreach($request->items as $item) {
            $productBundleItem = new \App\ProductBundleItem([
                'product_id' => $item['product_id'],
                'qty' => $item['qty']
            ]);
            $model->items()->save($productBundleItem);
        }
        return response([
            'message' => 'success',
            'id' => $model->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductBundle  $productBundle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ProductBundle::with(['items','items.product:id,name'])->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductBundle  $productBundle
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductBundle $productBundle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductBundle  $productBundle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductBundle $productBundle)
    {
        $request->validate([
            'name' => 'required|unique:product_bundles,name,'.$productBundle->id,
            'sku' => 'required|unique:product_bundles,sku,'.$productBundle->id,
        ]);
        $productBundle->update([
            'name' => $request->name,
            'sku' => $request->sku
        ]);
        foreach($request->items as $item) {
            if(array_key_exists('id', $item)) {
                $productBundleItem = \App\ProductBundleItem::find($item['id']);
            } else {
                $productBundleItem = new \App\ProductBundleItem;
            }
            $productBundleItem->product_id = $item['product_id'];
            $productBundleItem->qty = $item['qty'];
            $productBundleItem->save();
            $productBundle->items()->save($productBundleItem);
        }
        return response([
            'message' => 'success',
            'id' => $productBundle->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductBundle  $productBundle
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductBundle $productBundle)
    {
        $productBundle->delete();
        return response([
            'message' => 'success',
        ]);
    }

    public function search(Request $request){
        $model = ProductBundle::select('id', 'name');
        $chunks = explode(" ",$request->keyword);
        $model = $model->where(function($query) use($chunks){
            foreach($chunks as $it){
                $query->where('name','like','%'.$it.'%');
            }  
        })->get();
        return $model;
    }

    public function items($id){
        $model = ProductBundle::with(['items','items.product:id,name,gst'])->find($id);
        return $model;
    }

    public function deleteItem($id) {
        $model = \App\ProductBundleItem::find($id);
        if($model) {
            $model->delete();
        }
        return response([
            'message' => 'success',
        ]);
    }
}
