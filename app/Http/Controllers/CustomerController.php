<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Exports\CustomersExport;
use Spatie\Activitylog\Models\Activity;
use App\Rules\CustomerPhoneRule;
use App\Rules\CustomerEmailRule;
use App\Rules\CustomerGstRule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'customers_index_visible_columns', ['name', 'status','representative']);
        $filters = getFilters($request, 'customers_index_filtered');
        $search = getSearch($request, 'customers_index_search');
        $pagination = getPagination($request, 'customers_index_pagination');
        $select_arr = ['id'];
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                $select_arr[] = $key;
            }
        }
        $select_arr = array_unique(array_merge($select_arr,$visibleColumns));
        foreach($select_arr as $key=>$value)
        {
            if($value == 'representative'){
                $select_arr[$key] = 'GROUP_CONCAT(distinct(users.name)) representative';
            }
            if($value == 'id'){
                $select_arr[$key] = 'customers.id';
            }
            if($value == 'name'){
                $select_arr[$key] = 'customers.name';
            }
            if($value == 'status'){
                $select_arr[$key] = 'customers.status';
            }
        }
        
        $select_str = '';
        foreach($select_arr as $it){
            $select_str .= $it.',';
        }
        $select_str = rtrim($select_str,",");
        $model = Customer::select(DB::raw($select_str))->where('is_lead',0);
        $model->leftJoin('customer_representative','customers.id','=','customer_representative.customer_id')
            ->leftJoin('users','users.id','=','customer_representative.user_id')->groupBy('name');
            
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key == 'representative'){
                    foreach($val as $it){
                        $model->orHaving('representative','like','%'.$it.'%');
                    }
                }  
                elseif($key != 'status' && $key != 'type'){
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
                    $query->where('customers.name','like','%'.$it.'%');
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
        //dd($select_arr);
        return response()->json([
            'link_key' => 'name',
            'visible_columns' => $visibleColumns,
            'model' => $model->paginate($pagination['rpp'],['*'],'page',$pagination['page']),
            'sortby' => $pagination['sortBy'],
            'descending' => $pagination['desc'] == 'DESC' ? true : false,
            'filtered' => $filters,
            'search' => $search,
            //'attributes' => $allAttributes
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
            [
                'name' => 'balance',
                'label' => 'Balance',
                'field' => 'balance',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'type',
                'label' => 'Type',
                'field' => 'type',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'representative',
                'label' => 'Representative',
                'field' => 'representative',
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
                'options' => ['Draft','Active','Pending Approval','Approved', 'Disabled'],
                'value' => []
            ],
            [
                'field_type' => 'Selection',
                'name' => 'Representative',
                'slug' => 'representative',
                'searcheable' => false,
                'options' => \App\User::representatives()->pluck('name'),
                'value' => []
            ],
            [
                'field_type' => 'String',
                'name' => 'GST No',
                'slug' => 'gst_number'
            ],
            [
                'field_type' => 'Selection',
                'name' => 'Type',
                'slug' => 'type',
                'searcheable' => false,
                'options' => $this->getTypes(),
                'value' => []
            ]
        ];
        return $allAttributes;
    }

    public function getTypes(){
        return Customer::select('type')->distinct()->get()->pluck('type');
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
        $linked_accounts = json_decode($request->linked_accounts,true);
        $request->validate([
            'name' => 'required|unique:customers,name',
            'billing_address.phones.*'=> [new CustomerPhoneRule(NULL,$linked_accounts)],
            'gst_number'=> [new CustomerGstRule(NULL,$linked_accounts,$request->has_gst)],
            'emails.*' => [new CustomerEmailRule(NULL,$linked_accounts)]
        ]);
        $model = Customer::create($request->only('name','gst_number','has_gst','status','type','non_billable_account','is_lead','remarks','initial_balance'));
        
        $email_ids = [];
        foreach ($request->emails as $email)
        {
            if($email['content'])
            {
                $email_model = \App\Email::firstOrCreate(
                    ['content' => $email['content']],
                    ['source' => 'ERP'],
                );
                $email_ids[] = $email_model->id;
            }
        }
        $model->emails()->sync($email_ids);
        if(!$request->non_billable_account){
            $billing_address = \App\Address::create([
                'tag_name' => 'billing',
                'line_1' => $request->billing_address['line_1'],
                'line_2' => $request->billing_address['line_2'],
                'pin' => $request->billing_address['pin'],
                'district' => $request->billing_address['district'],
                'state' => $request->billing_address['state'],
                'country' => $request->billing_address['country'],
                'addressable_id' => $model->id,
                'addressable_type' => 'App\Customer',
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
            foreach($request->shipping_addresses as $address)
            {
                $address_model = \App\Address::create([
                    'tag_name' => 'shipping',
                    'line_1' => $address['line_1'],
                    'line_2' => $address['line_2'],
                    'pin' => $address['pin'],
                    'district' => $address['district'],
                    'state' => $address['state'],
                    'country' => $address['country'],
                    'addressable_id' => $model->id,
                    'addressable_type' => 'App\Customer',
                ]);
                if($request->default_shipping == $address['id']){
                    $model->default_shipping = $address_model->id;
                }
                $shipping_phones_ids = [];
                foreach($address['phones'] as $phone)
                {
                    $phone_model = \App\Phone::firstOrCreate(
                        ['content' => $phone['content'],'country_code' => $phone['country_code']],
                        ['source' => 'ERP']
                    );
                    $phone_model->addTags([$request->type,$address['district'],$address['state'],$address['country']]);
                    $shipping_phones_ids[] = $phone_model->id;
                }
                $address_model->phones()->sync($shipping_phones_ids);
            }
        }
        if($linked_accounts)
        {
            //return json_decode($request->linked_accounts,true);
            $model->buildRelations($linked_accounts);
        }
        $tag_ids = [];
        foreach($request->tags as $tag)
        {
            $tag_model = \App\Tag::firstOrCreate(['content'=>$tag]);
            $tag_ids[] = $tag_model->id;
        }
        $model->tags()->sync($tag_ids);
        $model->representatives()->sync($request->representatives);
        $model->save();
        return response()->json([
            'message' => 'success',
            'id' => $model->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Customer::with([
            'addresses',
            'addresses.phones',
            'addresses.addressable',
            'emails',
            'linked',
            'representatives',
            'tags',
            'transactions' => function ($query) {
                $query->orderBy('created_at', 'desc')->where('parent_id',0);
            },
            'transactions.payable',
            'transactions.created_by',
            'transactions.children'])->find($id);
        $model->balance = $model->getBalance();
        //$model->loadSiblings();
        return $model;
    }

    public function getLedger($id)
    {
        $model = Customer::find($id);
        return $model->getLedger();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return $request->toArray();
        //return $request->shipping_addresses;
        $linked_accounts = json_decode($request->linked_accounts,true);
        $request->validate([
            'name' => 'required|unique:customers,name,'.$id,
            'billing_address.phones.*'=> [new CustomerPhoneRule($id,$linked_accounts)],
            'emails.*' => [new CustomerEmailRule($id,$linked_accounts)],
            'gst_number'=> [new CustomerGstRule($id,$linked_accounts,$request->has_gst)],
        ]);
        $model = Customer::find($id);
        $model->update($request->only('name','gst_number','has_gst','status','type','non_billable_account','initial_balance'));
        $email_ids = [];
        foreach ($request->emails as $email)
        {
            if($email['content'])
            {
                $email_model = \App\Email::firstOrCreate(
                    ['content' => $email['content']],
                    ['source' => 'ERP']
                );
                $email_ids[] = $email_model->id;
            }
        }
        $model->emails()->sync($email_ids);
        if(!$request->non_billable_account){
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
                'addressable_type' => 'App\Customer',
            ]);
            $billing_phones_ids = [];
            foreach($request->billing_address['phones'] as $phone)
            {
                $phone_model = \App\Phone::firstOrCreate(
                    ['content' => $phone['content'],'country_code' => $phone['country_code']],
                    ['source' => 'ERP']
                );
                $phone_model->addTags([$request->type,$request->billing_address['district'],$request->billing_address['state'],$request->billing_address['country']]);
                $billing_phones_ids[] = $phone_model->id;
            }
            $billing_address->phones()->sync($billing_phones_ids);
            foreach($request->shipping_addresses as $address)
            {
                $address_model = $model->addresses()->where([
                    ['tag_name','shipping'],
                    ['id',$address['id']]
                ])->first();
                if($address_model){
                    $address_model->update([
                        'tag_name' => 'shipping',
                        'line_1' => $address['line_1'],
                        'line_2' => $address['line_2'],
                        'pin' => $address['pin'],
                        'district' => $address['district'],
                        'state' => $address['state'],
                        'country' => $address['country'],
                        'addressable_id' => $model->id,
                        'addressable_type' => 'App\Customer',
                    ]);
                }
                else{
                    $address_model = \App\Address::create([
                        'tag_name' => 'shipping',
                        'line_1' => $address['line_1'],
                        'line_2' => $address['line_2'],
                        'pin' => $address['pin'],
                        'district' => $address['district'],
                        'state' => $address['state'],
                        'country' => $address['country'],
                        'addressable_id' => $model->id,
                        'addressable_type' => 'App\Customer',
                    ]);
                }
                if($request->default_shipping == $address['id']){
                    $model->default_shipping = $address_model->id;
                }
                $shipping_phones_ids = [];
                foreach($address['phones'] as $phone)
                {
                    $phone_model = \App\Phone::firstOrCreate(
                        ['content' => $phone['content'],'country_code' => $phone['country_code']],
                        ['source' => 'ERP']
                    );
                    $phone_model->addTags([$address['district'],$address['state'],$address['country']]);
                    $shipping_phones_ids[] = $phone_model->id;
                }
                $address_model->phones()->sync($shipping_phones_ids);
            }
            \App\Address::destroy($request->address_ids_to_delete);
        }
        else{
            \App\Address::destroy($model->addresses()->get()->pluck('id'));
        }
        if($linked_accounts)
        {
            //return json_decode($request->linked_accounts,true);
            $model->buildRelations($linked_accounts);
        }
        else{
            $model->linked()->sync([]);
            $model->linkedOf()->sync([]);
        }
        $tag_ids = [];
        foreach($request->tags as $tag)
        {
            $tag_model = \App\Tag::firstOrCreate(['content'=>$tag]);
            $tag_ids[] = $tag_model->id;
        }
        $model->tags()->sync($tag_ids);
        $model->representatives()->sync($request->representatives);
        $model->save();
        activity()
            ->causedBy(request()->user())
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
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Customer::find($id);
        $this->authorize('delete',$model);
        $model->delete();
        activity()
            ->causedBy(request()->user())
            ->performedOn($model)
            ->log('deleted');
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function search(Request $request)
    {
        $model = Customer::select('id','name', 'status')->where('name','like','%'.$request->search.'%');
        if(!$request->include_leads){
            $model->where('is_lead',0)->where('status','<>','Draft');
        }
        if($request->billable){
            $model->where('non_billable_account',false);
        }
        if($request->except_id)
        {
            $model->where('id','<>',$request->except_id);
        }
        if($request->relation_type == 'Works here' || $request->relation_type == 'Related to' || $request->relation_type == 'Belongs to'){
            $model->whereNotIn('type',['Institution','Clinic']);
        }
        elseif($request->relation_type == 'Works at' || $request->relation_type == 'Owner of' ){
            $model->whereIn('type',['Institution','Clinic']);
        }
        elseif($request->relation_type == 'Sibling branch of'){
            $model->where('type',$request->type);
        }
        $model->limit(15);
        if($request->has('onlyname')){
            return $model->get()->pluck('name');
        }
        else{
            return $model->get();
        }
    }

    public function getAddresses(Request $request,$id)
    {
        $model = Customer::with(['addresses','addresses.phones','representatives'])->where('status','<>','draft')->find($id);
        return $model;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'method' => 'required',
        ]);

        $path = $request->file('file')->store('product_imports');
        $import = new \App\Imports\CustomerImport($request->method);
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

    public function addShipping(Request $request,$id)
    {
        $model = Customer::find($id);
        $address_model = \App\Address::create([
            'tag_name' => 'shipping',
            'line_1' => $request['line_1'],
            'line_2' => $request['line_2'],
            'pin' => $request['pin'],
            'district' => $request['district'],
            'state' => $request['state'],
            'country' => $request['country'],
            'addressable_id' => $id,
            'addressable_type' => 'App\Customer',
        ]);
        if($request->set_as_default){
            $model->default_shipping = $address_model->id;
        }
        $model->save();
        $shipping_phones_ids = [];
        foreach($request['phones'] as $phone)
        {
            $phone_model = \App\Phone::firstOrCreate(
                ['content' => $phone['content'],'country_code' => $phone['country_code']],
                ['source' => 'ERP']
            );
            $phone_model->addTags([$request['district'],$request['state'],$request['country']]);
            $shipping_phones_ids[] = $phone_model->id;
        }
        $address_model->phones()->sync($shipping_phones_ids);

        return response()->json([
            'message' => 'success',
            'id' => $address_model->id
        ]);
    }

    public function requestApproval($id)
    {
        $model = Customer::find($id);
        $model->status = 'Pending Approval';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function approve($id)
    {
        $model = Customer::find($id);
        $model->status = 'Approved';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function activate($id)
    {
        $model = Customer::find($id);
        $model->status = 'Active';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function revert($id)
    {
        $model = Customer::find($id);
        $model->status = 'Draft';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function disable($id)
    {
        $model = Customer::find($id);
        $model->status = 'Disabled';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function getSales($id){
        return Customer::find($id)->sale_orders()->get();
    }

    public function updateRemark(Request $request, $id)
    {
        $model = Customer::find($id);
        $now = \Carbon\Carbon::now();
        $model->remarks = $model->remarks.'<br/>'.$now->format('D, d/m/Y, g:i A').'<br/>'.request()->user()->name.'<br/>'.$request->remark.'<br/>';
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function updateStatus(Request $request, $id)
    {
        $model = Customer::find($id);
        $model->status = $request->status;
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function convert($id){
        $model = Customer::find($id);
        $address = $model->addresses()->first();
        $arr = [
            'line_1' => $address->line_1,
            'country' => $address->country,
            'state' => $address->state,
            'district' => $address->district,
            'pin' => $address->pin,
        ];
        Validator::make($arr,[
            'line_1' => 'required',
            'country' => 'required',
            'state' => 'required',
            'district' => Rule::requiredIf($arr['country'] == 'India'),
        ])->validate();

        $model->is_lead = false;
        $address_model = \App\Address::create([
            'tag_name' => 'shipping',
            'line_1' => $address['line_1'],
            'line_2' => $address['line_2'],
            'pin' => $address['pin'],
            'district' => $address['district'],
            'state' => $address['state'],
            'country' => $address['country'],
            'addressable_id' => $model->id,
            'addressable_type' => 'App\Customer',
        ]);
        $model->default_shipping = $address_model->id;
        $shipping_phones_ids = [];
        foreach($address->phones()->get() as $phone)
        {
            $phone_model = \App\Phone::firstOrCreate(
                ['content' => $phone['content'],'country_code' => $phone['country_code']],
                ['source' => 'ERP']
            );
            $phone_model->addTags([$model->type,$address['district'],$address['state'],$address['country']]);
            $shipping_phones_ids[] = $phone_model->id;
        }
        $address_model->phones()->sync($shipping_phones_ids);
        $model->save();
        return response()->json(['message' => 'success']);
    }

    public function getBalance($id)
    {
        $customer = Customer::find($id);
        return $customer->getBalance();
    }

    public function getUnsettledOrders($id)
    {
        $customer = Customer::find($id);
        return $customer->getUnsettledOrders();
    }
}
