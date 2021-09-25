<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'suppliers_index_visible_columns', ['name', 'status','gst_number']);
        $filters = getFilters($request, 'suppliers_index_filtered');
        $search = getSearch($request, 'suppliers_index_search');
        $pagination = getPagination($request, 'suppliers_index_pagination');
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                $select_arr[] = $key;
            }
        }
        $select_arr = ['id']; 
        $select_arr = array_unique(array_merge($select_arr,$visibleColumns));
        $model = Supplier::select($select_arr);
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key != 'status'){
                    $chunks = explode(" ",$val);
                    $model->where(function ($query) use ($chunks,$key){
                        foreach ($chunks as $it) {
                            if(strlen($it) > 0)
                            $query->where($key,'like','%'.$it.'%');
                        }
                    });
                }
                else{
                    $model->where(function ($query) use ($val,$key){
                        $query->whereIn($key,$val);
                    });
                }
            }
        }
        if($search){
            $chunks = explode(" ",$search);
            $model->where(function ($query) use ($chunks,$search){
                foreach ($chunks as $it) {
                    if(strlen($it) > 0)
                    $query->where('name','like','%'.$search.'%');
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
                'name' => 'status',
                'label' => 'Status',
                'field' => 'status',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'gst_number',
                'label' => 'GST No',
                'field' => 'gst_number',
                'required' => false,
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
                'options' => ['Draft','Pending Activation','Active'],
                'value' => []
            ],
            [
                'field_type' => 'String',
                'name' => 'GST No',
                'slug' => 'gst_number'
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
        //return $request->toArray();
        $request->validate([
            'name' => 'required|unique:suppliers,name',
        ]);
        $model = Supplier::create($request->only('name','gst_number','status','remarks'));
        $email_ids = [];
        foreach ($request->emails as $email)
        {
            if($email['content'])
            {
                $email_model = \App\Email::firstOrCreate(
                    ['content' => $email['content']],
                    ['source' => 'Supplier(ERP)'],
                );
                $email_ids[] = $email_model->id;
            }
        }
        $model->emails()->sync($email_ids);
        $billing_address = \App\Address::create([
            'tag_name' => 'billing',
            'line_1' => $request->billing_address['line_1'],
            'line_2' => $request->billing_address['line_2'],
            'pin' => $request->billing_address['pin'],
            'district' => $request->billing_address['district'],
            'state' => $request->billing_address['state'],
            'country' => $request->billing_address['country'],
            'addressable_id' => $model->id,
            'addressable_type' => 'App\Supplier',
        ]);
        $billing_phones_ids = [];
        foreach($request->billing_address['phones'] as $phone)
        {
            if($phone['content']){
                $phone_model = \App\Phone::firstOrCreate(
                    ['content' => $phone['content'],'country_code' => $phone['country_code']],
                    ['source' => 'ERP']
                );
                $phone_model->addTags([$request->billing_address['district'],$request->billing_address['state'],$request->billing_address['country']]);
                $billing_phones_ids[] = $phone_model->id;
            }
        }
        $billing_address->phones()->sync($billing_phones_ids);
        $model->save();
        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Supplier::with(['addresses','addresses.phones','emails'])->find($id);
        //$model->loadSiblings();
        return $model;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $model = Supplier::find($id);
        $request->validate([
            'name' => 'required|unique:suppliers,name,'.$id,
        ]);
        $model->update($request->only('name','gst_number','status','purchase_type','remarks'));
        $email_ids = [];
        foreach ($request->emails as $email)
        {
            if($email['content'])
            {
                $email_model = \App\Email::firstOrCreate(
                    ['content' => $email['content']],
                    ['source' => 'Supplier(ERP)'],
                );
                $email_ids[] = $email_model->id;
            }
        }
        $model->emails()->sync($email_ids);
        $billing_address = $model->addresses()->where('tag_name','billing')->first();
        $billing_address->update([
            'tag_name' => 'billing',
            'line_1' => $request->billing_address['line_1'],
            'line_2' => $request->billing_address['line_2'],
            'pin' => $request->billing_address['pin'],
            'district' => $request->billing_address['district'],
            'state' => $request->billing_address['state'],
            'country' => $request->billing_address['country'],
            'addressable_id' => $model->id,
            'addressable_type' => 'App\Supplier',
        ]);
        $billing_phones_ids = [];
        foreach($request->billing_address['phones'] as $phone)
        {
            $phone_model = \App\Phone::firstOrCreate(
                ['content' => $phone['content'],'country_code' => $phone['country_code']],
                ['source' => 'ERP']
            );
            $phone_model->addTags([$request->billing_address['district'],$request->billing_address['state'],$request->billing_address['country']]);
            $billing_phones_ids[] = $phone_model->id;
        }
        $billing_address->phones()->sync($billing_phones_ids);
        $model->save();
        activity()
            ->causedBy(\Auth::user())
            ->performedOn($model)
            ->withProperties($this->show($model->id))
            ->log('updated');
        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete',$supplier);
        $supplier->delete();
        return response()->json(['message' => 'success']);
    }

    public function search(Request $request)
    {
        $model = Supplier::select('id','name')->where('name','like','%'.$request->search.'%')->where('status','<>','draft')->limit(15)->get();
        return $model;
    }

    public function requestActivation($id)
    {
        $model = Supplier::find($id);
        $model->status = 'Pending Activation';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function activate($id)
    {
        $model = Supplier::find($id);
        $model->status = 'Active';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function revert($id)
    {
        $model = Supplier::find($id);
        $model->status = 'Draft';
        $model->save();
        return response()->json(['message' => 'success']);
    }

}
