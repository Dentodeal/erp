<?php

namespace App\Http\Controllers;

use App\Exports\CustomerSaleExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Exports\CustomersExport;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class ExportController extends Controller
{
    public function index(Request $request)
    {
        $postData = $request->toArray();
        if($request->entity == 'Customers') {
            $model = \App\Customer::where('is_lead',0)->whereNotIn('name',['OTC','Customer Awaiting']);
            $withArr = [];
            if($this->loadAddress($postData['selected_columns'])) {
                $withArr['addresses'] = function($q) {
                    $q->where('tag_name','billing');
                };
            }
            if(in_array('phones',$postData['selected_columns'])) {
                $withArr[] = 'addresses.phones';
            }
            if(in_array('emails',$postData['selected_columns'])) {
                $withArr[] = 'emails';
            }
            if(in_array('tags',$postData['selected_columns'])) {
                $withArr[] = 'tags';
            }
            if(in_array('representatives',$postData['selected_columns'])) {
                $withArr[] = 'representatives:id,name';
            }
            $model = $model->with($withArr);
            $addressConstraints = [];
            if(is_array($postData['filters'])){
                foreach($postData['filters'] as $key => $filter) {
                    if(strpos($key,'address') !== FALSE) {
                        $addressConstraints[substr($key,8)] = $filter;
                    } elseif ($key == 'representatives') {
                        $model = $model->whereHas('representatives', function(Builder $query) use($filter) {
                            $query->whereIn('name',$filter);
                        });
                    } else {
                        if (is_array($filter)) {
                            $model->whereIn($key,$filter);
                        } else {
                            $model->where($key,'like','%'.$filter.'%');
                        }
                    }
                }
            }
            if(count($addressConstraints) > 0) {
                $model = $model->whereHas('addresses', function(Builder $query) use ($addressConstraints) {
                    foreach($addressConstraints as $key => $value) {
                        $query->whereIn($key,$value);
                    }
                });
            }
            $heading = ['id'];
            foreach($postData['selected_columns'] as $s) {
                $heading[] = $s;
            }
            // return $addressConstraints;
            $data = $model->get()->toArray();
            // return $data;
            Excel::store(new CustomersExport($data, $heading), 'customers.xlsx','public');
            return Storage::url('customers.xlsx');
        }
        if($request->entity == 'Products') {
            $model = \App\Product::with(['type','department','category','sub_category','brand']);
            if(is_array($postData['filters'])){
                foreach($postData['filters'] as $key => $filter) {
                    if(in_array($key,['type','department','category','sub_category','brand'])) {
                        $model = $model->whereHas($key, function(Builder $query) use ($filter) {
                            $query->whereIn('name', $filter);
                        });
                    } elseif(in_array($key,['gst','status'])) {
                        $model = $model->whereIn($key,$filter);
                    } else {
                        $model = $model->where($key,'like','%'.$filter.'%');
                    }
                }
            }
            $products = $model->get();
            $arr = [];
            $stockFlag = in_array('stock',$postData['selected_columns']);
            $costFlag = in_array('cost',$postData['selected_columns']);
            if($stockFlag) {
                $initialStockArr = collect(\App\StockEntryItem::selectRaw('products.id,products.name,SUM(qty) AS qty')
                ->join('products','products.id','=','stock_entry_items.product_id')
                ->groupBy('stock_entry_items.product_id')
                ->get())->keyBy('id');
                $soldArr = collect(\App\SaleOrderItem::selectRaw('products.id,products.name,SUM(qty) AS qty')
                ->join('sale_orders','sale_orders.id','=','sale_order_items.sale_order_id')
                ->join('products','products.id','=','sale_order_items.product_id')
                ->where('sale_orders.status','<>','Cancelled')
                ->groupBy('sale_order_items.product_id')
                ->where('sale_orders.created_at','>','2021-05-25 00:00:00')
                ->get()
                )->keyBy('id');
                $returnedArr = collect(\App\SaleReturnItem::selectRaw('products.id,products.name,SUM(qty) AS qty')
                ->join('sale_returns','sale_returns.id','=','sale_return_items.sale_return_id')
                ->join('products','products.id','=','sale_return_items.product_id')
                ->where('sale_returns.status','<>','Draft')->groupBy('sale_return_items.product_id')
                ->where('sale_returns.created_at','>','2021-05-25 00:00:00')
                ->get()
                )->keyBy('id');
                $purchasedArr = collect(\App\GoodsReceiveNoteItem::selectRaw('products.id,products.name,SUM(qty) AS qty')
                ->join('goods_receive_notes','goods_receive_notes.id','=','goods_receive_note_items.grn_id')
                ->join('products','products.id','=','goods_receive_note_items.product_id')
                ->where('goods_receive_notes.status','Active')->groupBy('goods_receive_note_items.product_id')
                ->where('goods_receive_notes.created_at','>','2021-05-25 00:00:00')
                ->get()
                )->keyBy('id');
                $availableStockArr = collect(\App\ProductStock::selectRaw('products.id,products.name,SUM(qty) AS qty')
                ->where('reservable_id',0)
                ->join('products','products.id','=','product_stock.product_id')
                ->groupBy('product_stock.product_id')
                ->get())->keyBy('id');
            }
            if($costFlag) {
                $costArr = collect(\App\Product::selectRaw('products.id,products.name,a.pur_id,purchase_items.cost')
                ->join(
                    DB::raw('(SELECT products.id, MAX(purchase_items.id) AS pur_id FROM purchase_items JOIN products ON products.id = purchase_items.product_id JOIN purchases ON purchases.id = purchase_items.purchase_id WHERE purchases.status IN (\'Admin Approved\',\'Active\') GROUP BY products.id) AS a')
                    ,'a.id','=','products.id'
                )->join('purchase_items','purchase_items.id','=','a.pur_id')
                ->get())->keyBy('id');
            }
            foreach($products as $product) {
                if ($stockFlag) {
                    $initialStock = $initialStockArr->has($product->id) ? $initialStockArr[$product->id]['qty'] : 0;
                    $sold = $soldArr->has($product->id) ? $soldArr[$product->id]['qty'] : 0;
                    $purchased = $purchasedArr->has($product->id) ? $purchasedArr[$product->id]['qty'] : 0;
                    $returned = $returnedArr->has($product->id) ? $returnedArr[$product->id]['qty'] : 0;
                    $availableStock = $availableStockArr->has($product->id) ? $availableStockArr[$product->id]['qty'] : 0;
                    $expectedStock = (int)$initialStock + (int)$purchased + (int)$returned - (int)$sold;
                }
                if ($costFlag) {
                    $cost = $costArr->has($product->id) ? $costArr[$product->id]['cost'] : 0;
                }
                $temp = [$product->id];
                foreach($postData['selected_columns'] as $s) {
                    if($s == 'stock') {
                        array_push($temp,$initialStock, $purchased, $returned, $sold,$expectedStock);
                    } elseif($s == 'cost') {
                        $temp[] = $cost;
                    } elseif(in_array($s,['type','department','category','sub_category','brand'])) {
                        $temp[] = $product->$s->name;
                    } else {
                        $temp[] = $product->$s;
                    }
                }
                $arr[] = $temp;
            }
            $heading = ['id'];
            foreach($postData['selected_columns'] as $s) {
                if($s == 'stock') {
                    array_push($heading,'Stock Entry(May 2021)','Purchased','Returned','Sold','Stock');
                } else {
                    $heading[] = $s;
                }
            }
            Excel::store(new ProductsExport($arr, $heading), 'products.xlsx','public');
            return Storage::url('products.xlsx');
        }
        if($request->entity == 'Customer Sale Summary') {
            $model = \App\SaleOrder::with(['customer','representative'])->whereNotNull('invoiced_at');
            if(is_array($postData['filters'])) {
                foreach($postData['filters'] as $key => $filter) {
                    if($key == 'gst_number') {
                        if($filter == 'Yes') {
                            $model = $model->whereHas('customer', function(Builder $query) { 
                                $query->whereNotNull('customers.gst_number');
                            });
                        } elseif ($filter == 'No') {
                            $model = $model->whereHas('customer', function(Builder $query) { 
                                $query->whereNull('customers.gst_number');
                            });
                        }
                    } elseif($key == 'invoiced_at') {
                        $model = $model->whereBetween('invoiced_at',[$filter['from'],$filter['to']]);
                    } elseif($key == 'representatives') {
                        $model = $model->whereHas('representative', function(Builder $query) use ($filter) { 
                            $query->whereIn('name', $filter);
                        });
                    } elseif($key == 'source') {
                        $model = $model->whereIn('source',$filter);
                    }
                }
            }
            $model = $model->get()->toArray();
            if(in_array('item_count', $postData['selected_columns'])) {
                $countArr = DB::table('sale_order_items')->selectRaw('sale_order_id,COUNT(id) as count')->groupBy('sale_order_id')->get()->keyBy('sale_order_id')->all();  
            }
            // return $countArr;
            $arr = [];
            foreach($model as $item) {
                $temp = [
                    $item['customer'] ? $item['customer']['name'] : '',
                    $item['customer'] ? $item['customer']['gst_number'] : '',
                    $item['source'],
                    $item['representative'] ? $item['representative']['name'] : '',
                    $item['total'],
                    $item['invoiced_at'],
                ];
                if(in_array('item_count',$postData['selected_columns'])) {
                    $temp[] = $countArr[$item['id']]->count;
                }
                $arr[] = $temp;
            }
            $heading = [
                'Customer',
                'GST No',
                'Source',
                'Representative',
                'Bill Total',
                'Invoiced At'
            ];
            if(in_array('item_count',$postData['selected_columns'])) {
                $heading[] = 'Items Count';
            }
            Excel::store(new CustomerSaleExport($arr, $heading), 'customer_sale_summary.xlsx','public');
            return Storage::url('customer_sale_summary.xlsx');
        }
    }

    public function loadAddress($data)
    {
        foreach($data as $item) {
            if(strpos($item,'address') !== FALSE) {
                return true;
            }
        }
        return false;
    }
}
