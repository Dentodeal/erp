<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
class LeadController extends Controller
{

    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'leads_index_visible_columns', ['name', 'status']);
        $filters = getFilters($request, 'leads_index_filtered');
        $search = getSearch($request, 'leads_index_search');
        $pagination = getPagination($request, 'leads_index_pagination');
        $select_arr = ['id'];
        $filters = [];
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                $select_arr[] = $key;
            }
        }
        $select_arr = array_unique(array_merge($select_arr,$visibleColumns));
        $model = \App\Customer::select($select_arr)->where('is_lead',1);
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key != 'status' && $key != 'type'){
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
                    $query->where('name','like','%'.$it.'%');
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

    public function getTypes(){
        return \App\Customer::select('type')->distinct()->get()->pluck('type');
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
                'name' => 'type',
                'label' => 'Type',
                'field' => 'type',
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

    public function show($id)
    {

    }
}
