<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\SaleOrder;
use Illuminate\Support\Facades\DB;
class SaleOrderRevisitController extends Controller
{
    public function index(Request $request)
    {
        $visibleColumns = getVisibleColumns($request, 'sale_orders_revisit_index_visible_columns', ['serial_no','customer_name','status','bill_age','payment_status','created_at']);
        $filters = getFilters($request, 'sale_orders_revisit_index_filtered');
        $search = getSearch($request, 'sale_orders_revisit_index_search');
        $pagination = getPagination($request, 'sale_orders_revisit_index_pagination');

        $select_arr = ['id'];
        foreach($visibleColumns as $vc){
            if(!in_array($vc,$select_arr))
                $select_arr[] = $vc;
        }
        foreach(array_keys($filters) as $filter_key){
            if(!in_array($filter_key,$select_arr))
                $select_arr[] = $filter_key;
        }

        foreach($select_arr as $key => $value){
            if($value == 'id'){
                $select_arr[$key] = 'sale_orders.id AS id';
            }
            if($value == 'customer_name'){
                $select_arr[$key] = 'customers.name AS customer_name';
            }
            if($value == 'status'){
                $select_arr[$key] = 'sale_orders.status AS status';
            }
            if($value == 'created_at'){
                $select_arr[$key] = 'sale_orders.created_at AS created_at';
            }
            if($value == 'created_by_name'){
                $select_arr[$key] = 'created_by.name AS created_by_name';
            }
            if($value == 'representative_name'){
                $select_arr[$key] = 'representative.name AS representative_name';
            }
            if($value == 'bill_age'){
                $select_arr[$key] = 'DATEDIFF(NOW(),invoiced_at) bill_age';
            }
            if($value == 'balance_amount'){
                $select_arr[$key] = '(total - paid_amount) balance_amount';
            }
        }
        // dd($visibleColumns);
        $model = SaleOrder::select(DB::raw(implode(",",$select_arr)))->join('customers','sale_orders.customer_id','=','customers.id')
                            ->join(DB::raw('users created_by'),'sale_orders.created_by_id','=','created_by.id')
                            ->join(DB::raw('users representative'),'sale_orders.representative_id','=','representative.id');
        if(count($filters) > 0){
            foreach($filters as $key => $val){
                if($key == 'representative_name'){
                    $model->whereIn('representative.name',$val);
                }
                if($key == 'status'){
                    $model->whereIn('sale_orders.status',$val);
                }
                if($key == 'source'){
                    $model->whereIn('source',$val);
                }
            }
        }
        if($search){
            $chunks = explode(" ",$search);
            $model->where(function ($query) use ($chunks,$search){
                foreach ($chunks as $it) {
                    if(strlen($it) > 0)
                    $query->where('sale_orders.serial_no','like','%'.$search.'%');
                    $query->orWhere('customers.name','like','%'.$search.'%');
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
            'link_key' => 'serial_no',
            'visible_columns' => $visibleColumns,
            'model' => $model->where('payment_status','<>','Settled')->whereNotNull('invoiced_at')->paginate($pagination['rpp'],['*'],'page',$pagination['page']),
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
                'name' => 'serial_no',
                'label' => 'Serial No',
                'field' => 'serial_no',
                'required' => true,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'customer_name',
                'label' => 'Customer',
                'field' => 'customer_name',
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
                'name' => 'bill_age',
                'label' => 'Bill Age',
                'field' => 'bill_age',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'created_at',
                'label' => 'Created At',
                'field' => 'created_at',
                'required' => false,
                'sortable' => true,
                'align' => 'right',
                'field_type' => 'datetime'
            ],
            [
                'name' => 'created_by_name',
                'label' => 'Created By',
                'field' => 'created_by_name',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'representative_name',
                'label' => 'Representative',
                'field' => 'representative_name',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'invoiced_at',
                'label' => 'Invoiced At',
                'field' => 'invoiced_at',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'source',
                'label' => 'Source',
                'field' => 'source',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'billing_address',
                'label' => 'Billing Address',
                'field' => 'billing_address',
                'required' => false,
                'sortable' => true,
                'align' => 'left'
            ],
            [
                'name' => 'total',
                'label' => 'Total',
                'field' => 'total',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'balance_amount',
                'label' => 'Balance Amount',
                'field' => 'balance_amount',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
            [
                'name' => 'paid_amount',
                'label' => 'Paid Amount',
                'field' => 'paid_amount',
                'required' => false,
                'sortable' => true,
                'align' => 'right'
            ],
        ];
        return $columns;
    }

    public function getFilterables()
    {
        return [
            [
                'field_type' => 'Selection',
                'name' => 'Status',
                'slug' => 'status',
                'searcheable' => false,
                'options' => SaleOrder::select('status')->distinct()->get()->pluck('status'),
                'value' => []
            ],
            [
                'field_type' => 'Selection',
                'name' => 'Source',
                'slug' => 'source',
                'searcheable' => false,
                'options' => SaleOrder::select('source')->distinct()->get()->pluck('source'),
                'value' => []
            ],
            [
                'field_type' => 'Selection',
                'name' => 'Representative',
                'slug' => 'representative_name',
                'searcheable' => false,
                'options' => \App\User::select('users.id','users.name')->join('user_roles','user_roles.user_id','=','users.id')->join('roles','roles.id','=','user_roles.role_id')->where('roles.name','=','Sales')->get()->pluck('name'),
                'value' => []
            ],
        ];

    }
}
