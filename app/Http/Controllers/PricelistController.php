<?php

namespace App\Http\Controllers;

use App\Pricelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PricelistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Cache::rememberForever('pricelists', function () {
            return Pricelist::select('id','name','created_at')->get();
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
          'name' => 'required|unique:pricelists,name'
        ]);
        Pricelist::create($request->only(['name']));
        Cache::forget('pricelists');
        return response()->json(['message' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pricelist  $pricelist
     * @return \Illuminate\Http\Response
     */
    public function show(Pricelist $pricelist)
    {
        return $pricelist;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pricelist  $pricelist
     * @return \Illuminate\Http\Response
     */
    public function edit(Pricelist $pricelist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pricelist  $pricelist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pricelist $pricelist)
    {
      $request->validate([
        'name' => 'required|unique:pricelists,name,'.$pricelist->id
      ]);
      $pricelist->update($request->only(['name']));
      Cache::forget('pricelists');
      return response()->json(['message' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pricelist  $pricelist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pricelist $pricelist)
    {
      $this->authorize('delete',$pricelist);
      $pricelist->products()->sync([]);
      $pricelist->delete();
      Cache::forget('pricelists');
      return response()->json(['message' => 'success']);
    }
}
