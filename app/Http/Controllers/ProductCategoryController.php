<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Cache::rememberForever('product_categories', function () {
            return ProductCategory::select('id','name','code','created_at')->orderBy('id','desc')->get();
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
        'name' => 'required|unique:product_categories,name',
        'code' => 'required|unique:product_categories,code|size:2'
      ]);
      Cache::forget('product_categories');
      $model = ProductCategory::create($request->only(['name','code']));
      return response()->json([
        'message' => 'success',
        'id' => $model->id,
        'name' => $model->name
      ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $model = ProductCategory::find($id);
      $this->authorize('delete',$model);
      $model->delete();
      return response()->json([
          'message' => 'success'
      ]);
    }

    public function search(Request $request)
    {
        return ProductCategory::where('name','like','%'.$request->keyword.'%')->limit(15)->get();
    }
}
