<?php

namespace App\Http\Controllers;

use App\PackagingBox;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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
            'items.product.boxes' => function ($query) use ($box_ids) {
                $query->whereIn('box_id', $box_ids);
            }
        ])->find($so_id);

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
    public function store(Request $request, $so_id)
    {
        $saleOrder = \App\SaleOrder::find($so_id);
        $request->validate([
            'name' => 'required',
            'items.*' => function ($attribute, $value, $fail) use ($so_id, $saleOrder) {
                $packedQty = DB::table('packaging_box_item')->join('packaging_boxes', 'packaging_boxes.id', '=', 'packaging_box_item.box_id')
                    ->where('product_id', $value['product']['id'])
                    ->where('sale_order_id', $so_id)
                    ->sum('qty');
                $totalQty = $saleOrder->items()->where('product_id', $value['product']['id'])->sum('qty');
                if ($value['qty'] > ($totalQty - $packedQty)) {
                    $fail('Qty given for ' . $value['product']['name'] . ' is invalid. Packed: ' . $packedQty . ', Available to pack: ' . ($totalQty - $packedQty));
                }
            }
        ]);
        $model = new PackagingBox;
        $model->name = $request->name;
        $model->sale_order_id = $so_id;
        $model->save();
        // $sync_arr = Arr::pluck($request->items,'id');
        $syncArr = [];
        foreach ($request->items as $item) {
            $syncArr[$item['product']['id']] = [
                'qty' => $item['qty']
            ];
        }
        $model->items()->sync($syncArr);
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
        $packagingBox->load('items:id,name');
        $data = [
            'name' => $packagingBox->name,
            'id' => $packagingBox->id,
            'items' => $packagingBox->items->map(function ($item) {
                return [
                    'product' => [
                        'id' => $item->id,
                        'name' => $item->name,
                    ],
                    'qty' => $item->pivot->qty
                ];
            })
        ];
        return $data;
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
        $saleOrder = \App\SaleOrder::find($so_id);
        $request->validate([
            'name' => 'required',
            'items.*' => function ($attribute, $value, $fail) use ($so_id, $saleOrder, $id) {
                $packedQty = DB::table('packaging_box_item')->join('packaging_boxes', 'packaging_boxes.id', '=', 'packaging_box_item.box_id')
                    ->where('product_id', $value['product']['id'])
                    ->where('sale_order_id', $so_id)
                    ->where('packaging_boxes.id', '<>', $id)
                    ->sum('qty');
                $totalQty = $saleOrder->items()->where('product_id', $value['product']['id'])->sum('qty');
                if ($value['qty'] > ($totalQty - $packedQty)) {
                    $fail('Qty given for ' . $value['product']['name'] . ' is invalid. Packed: ' . $packedQty . ', Available to pack: ' . ($totalQty - $packedQty));
                }
            }
        ]);
        $model = PackagingBox::find($id);
        $model->name = $request->name;
        $model->save();
        // $sync_arr = Arr::pluck($request->items, 'id');
        // $model->items()->sync($sync_arr);
        $syncArr = [];
        foreach ($request->items as $item) {
            $syncArr[$item['product']['id']] = [
                'qty' => $item['qty']
            ];
        }
        $model->items()->sync($syncArr);
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

    public function download($so_id, $id)
    {
        $model = PackagingBox::find($id);
        $model->load('items');
        $pdf = \PDF::loadView('packaging', [
            'data' => $model,
        ]);
        $filename = 'box.pdf';
        return $pdf->download($filename);
    }
}
