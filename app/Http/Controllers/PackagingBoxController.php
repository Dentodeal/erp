<?php

namespace App\Http\Controllers;

use App\PackagingBox;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PackagingBoxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $so_id)
    {
        $saleOrder = \App\SaleOrder::find($so_id);
        return $saleOrder->boxes;
    }
    public function itemsIndex(Request $request, $so_id)
    {
        $box_ids = \App\SaleOrder::find($so_id)->boxes->pluck('id');
        $saleOrder = \App\SaleOrder::with([
            'items:product_id,sale_order_id',
            'items.product:id,name',
            'items.product.boxes' => function ($query) use ($box_ids){
            $query->whereIn('box_id', $box_ids);
        }])->find($so_id);

        return $saleOrder;
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
    public function store(Request $request,$so_id)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $model = new PackagingBox;
        $model->name = $request->name;
        $model->sale_order_id = $so_id;
        $model->save();
        $sync_arr = Arr::pluck($request->items,'id');
        $model->items()->sync($sync_arr);
        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PackagingBox  $packagingBox
     * @return \Illuminate\Http\Response
     */
    public function show($so_id, $id)
    {
        $packagingBox = PackagingBox::find($id);
        $packagingBox->load('items:id,name,weight');
        return $packagingBox;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PackagingBox  $packagingBox
     * @return \Illuminate\Http\Response
     */
    public function edit(PackagingBox $packagingBox)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PackagingBox  $packagingBox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $so_id, $id) 
    {
        $request->validate([
            'name' => 'required'
        ]);
        $model = PackagingBox::find($id);
        $model->name = $request->name;
        $model->save();
        $sync_arr = Arr::pluck($request->items,'id');
        $model->items()->sync($sync_arr);
        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PackagingBox  $packagingBox
     * @return \Illuminate\Http\Response
     */
    public function destroy($so_id, $id)
    {
        $packagingBox = PackagingBox::find($id);
        $packagingBox->delete();
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function download($so_id, $id) {
        $model = PackagingBox::find($id);
        $model->load('items');
        $pdf = \PDF::loadView('packaging', [
            'data'=>$model,
        ]);
        $filename = 'box.pdf';
        return $pdf->download($filename);
    }
}
