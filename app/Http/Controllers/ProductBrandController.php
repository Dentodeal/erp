<?php

namespace App\Http\Controllers;

use App\ProductBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Cache::rememberForever('product_brands', function () {
            return ProductBrand::select('id','name','code','created_at')->orderBy('id','desc')->get();
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
        'name' => 'required|unique:product_brands,name',
        'code' => 'required|unique:product_brands,code|size:2'
      ]);
      Cache::forget('product_brands');
      $model = ProductBrand::create($request->only(['name','code']));
      return response()->json([
        'message' => 'success',
        'id' => $model->id,
        'name' => $model->name
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductBrand  $productBrand
     * @return \Illuminate\Http\Response
     */
    public function show(ProductBrand $productBrand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductBrand  $productBrand
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductBrand $productBrand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductBrand  $productBrand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductBrand $productBrand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductBrand  $productBrand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $model = ProductBrand::find($id);
      $this->authorize('delete',$model);
      $model->delete();
      return response()->json([
          'message' => 'success'
      ]);
    }

    public function search(Request $request)
    {
        return ProductBrand::where('name','like','%'.$request->keyword.'%')->limit(15)->get();
    }
}
