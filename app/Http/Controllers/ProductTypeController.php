<?php

namespace App\Http\Controllers;

use App\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Cache::rememberForever('product_types', function () {
            return ProductType::select('id','name','code','created_at')->orderBy('id','desc')->get();
        });
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
            'name' => 'code',
            'label' => 'Code',
            'field' => 'code',
            'required' => false,
            'sortable' => true,
            'align' => 'left'
          ],
          [
            'name' => 'created_at',
            'label' => 'Created At',
            'field' => 'created_at',
            'sortable' => true,
          ],
        ];
        return response()->json([
          'columns' => $columns,
          'model' => $model,
        ]);
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
          'name' => 'required|unique:product_types,name',
          'code' => 'required|unique:product_types,code|size:1'
        ]);
        Cache::forget('product_types');
        $model = ProductType::create($request->only(['name','code']));
        return response()->json([
          'message' => 'success',
          'id' => $model->id,
          'name' => $model->name
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function show(ProductType $productType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductType $productType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $model = ProductType::find($id);
      $request->validate([
        'name' => 'required|unique:product_types,name,'.$id,
        'code' => 'required|unique:product_types,code,'.$id.'|size:1'
      ]);
      Cache::forget('product_types');
      $model->update($request->only(['name','code']));
      return response()->json([
        'message' => 'success',
        'id' => $model->id,
        'name' => $model->name
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductType  $productType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $model = ProductType::find($id);
      $this->authorize('delete',$model);
      $model->delete();
      return response()->json([
          'message' => 'success'
      ]);
    }
}
