<?php

namespace App\Http\Controllers;

use App\QuotationTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuotationTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = QuotationTemplate::select('id','name','created_at')->orderBy('name')->where('created_by_id',request()->user()->id)->get();
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
              'name' => 'created_at',
              'label' => 'Created At',
              'field' => 'created_at',
              'field_type' => 'datetime',
              'sortable' => true,
            ],
          ];
        return response()->json([
            'link_key' => 'name',
            'columns' => $columns,
            'visible_columns' => ['name','created_at'],
            'model' => $model
        ]);
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
        $request->validate([
            'name' => 'required|unique:quotation_templates,name'
        ]);
        $arr = $request->only([
            'name',
            'bank',
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
            'ship_via'
        ]);
        $arr['representative_id'] = $request->representative ? $request->representative['id'] : null;
        $arr['customer_id'] = $request->customer ? $request->customer['id'] : null;
        $arr['pricelist_id'] = $request->pricelist['id'];
        $arr['warehouse_id'] = $request->warehouse['id'];
        $arr['created_by_id'] = $request->created_by['id']; 
        $model = QuotationTemplate::create($arr);

        foreach($request->items as $item){
            $quotationTemplateItemsModel = new \App\QuotationTemplateItem(Arr::only($item,[
                'product_id',
                'product_name',
                'gst',
                'rate',
                'qty',
                'taxable',
                'tax_amount',
                'use_mask',
                'mask_name',
                'total',
            ]));
            $model->items()->save($quotationTemplateItemsModel);
        }
        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\QuotationTemplate  $quotationTemplate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return QuotationTemplate::with(['items.product','created_by','representative','warehouse','pricelist','customer'])->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\QuotationTemplate  $quotationTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(QuotationTemplate $quotationTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QuotationTemplate  $quotationTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:quotation_templates,name,'.$id
        ]);
        $model = QuotationTemplate::find($id);
        $arr = $request->only([
            'name',
            'type',
            'bank',
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
            'ship_via'
        ]);
        $arr['representative_id'] = $request->representative ? $request->representative['id'] : null;
        $arr['customer_id'] = $request->customer ? $request->customer['id'] : null;
        $arr['pricelist_id'] = $request->pricelist['id'];
        $arr['warehouse_id'] = $request->warehouse['id'];
        $arr['created_by_id'] = $request->created_by['id']; 
        $model->update($arr);
        $model->items()->delete();
        foreach($request->items as $item){
            $quotationTemplateItemsModel = new \App\QuotationTemplateItem(Arr::only($item,[
                'product_id',
                'product_name',
                'gst',
                'rate',
                'qty',
                'taxable',
                'tax_amount',
                'use_mask',
                'mask_name',
                'total',
            ]));
            $model->items()->save($quotationTemplateItemsModel);
        }
        return response()->json(['message'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\QuotationTemplate  $quotationTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuotationTemplate $quotationTemplate)
    {
        //
    }
}
