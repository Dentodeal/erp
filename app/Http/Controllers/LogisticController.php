<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\LogisticPartner;


class LogisticController extends Controller
{
    public function index(){
        $model = Cache::rememberForever('logistic_partners', function () {
            $model = LogisticPartner::select('id','name','address','contacts','created_at')->get();
            $model = $model->map(function($item){
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'address' => $item->address,
                    'contacts' => json_decode($item->contacts)
                ];
            });
            return $model;
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
            'name' => 'address',
            'label' => 'Address',
            'field' => 'address',
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

    public function show($id)
    {
        $model = LogisticPartner::find($id);
        $model->contacts = json_decode($model->contacts);
        return $model;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:logistic_partners',
        ]);
        $arr = $request->only([
            'name','address', 'contacts'
        ]);
        $arr['contacts'] = json_encode($arr['contacts']);
        LogisticPartner::create($arr);
        Cache::forget('logistic_partners');
        return response()->json([
            'message' => 'success'
        ]);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|unique:logistic_partners,name,'.$id,
        ]);
        $arr = $request->only([
            'name','address', 'contacts'
        ]);
        $arr['contacts'] = json_encode($arr['contacts']);
        $model = LogisticPartner::find($id);
        $model->update($arr);
        Cache::forget('logistic_partners');
        return response()->json([
            'message' => 'success'
        ]);
    }
}
