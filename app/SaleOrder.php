<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SaleOrder extends Model
{
    protected $table = 'sale_orders';
    protected $guarded = [];

    protected $casts = [
        'cod' => 'boolean',
        'rate_includes_gst' => 'boolean',
        'flood_cess_included' => 'boolean',
        'otc' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany('App\SaleOrderItem', 'sale_order_id');
    }

    public function expiryItems()
    {
        return $this->hasMany('App\SaleOrderExpiryItem', 'sale_order_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse', 'warehouse_id');
    }

    public function pricelist()
    {
        return $this->belongsTo('App\Pricelist', 'pricelist_id');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User', 'created_by_id');
    }

    public function invoiced_by()
    {
        return $this->belongsTo('App\User', 'invoiced_by_id');
    }

    public function representative()
    {
        return $this->belongsTo('App\User', 'representative_id');
    }

    public function shipment()
    {
        return $this->hasOne('App\Shipment', 'sale_order_id');
    }

    public function transactions()
    {
        return $this->morphMany('App\Transaction', 'payable');
    }

    public function sale_returns()
    {
        return $this->hasMany('App\SaleReturn');
    }

    public function quotation()
    {
        return $this->belongsTo('App\Quotation', 'quotation_id');
    }

    public function boxes()
    {
        return $this->hasMany('App\PackagingBox', 'sale_order_id');
    }

    public static function createEntry($data)
    {
        // dd($data);
        $arr = Arr::only($data, [
            'status',
            'source',
            'order_no',
            'rate_includes_gst',
            'flood_cess_included',
            'otc',
            'cod',
            'discount',
            'freight',
            'cod_charge',
            'round',
            'remarks',
            'billing_address',
            'shipping_address',
            'po_number',
            'order_date'
        ]);
        if (array_key_exists('dentodeal_data', $data))
            $arr['dentodeal_data'] = $data['dentodeal_data'];
        $arr['customer_id'] = $data['customer']['id'];
        $arr['representative_id'] = $data['representative']['id'];
        $arr['pricelist_id'] = $data['pricelist']['id'];
        $arr['warehouse_id'] = $data['warehouse']['id'];
        $arr['created_by_id'] = $data['created_by']['id'];
        if (array_key_exists('quotation_id', $data)) {
            $arr['quotation_id'] = $data['quotation_id'];
        }
        if (array_key_exists('type', $data)) {
            $arr['type'] = $data['type'];
        } else {
            $arr['type'] = 'Standard';
        }
        if (array_key_exists('currency', $data)) {
            $arr['currency'] = $data['currency'];
        } else {
            $arr['currency'] = 'INR';
        }
        if (array_key_exists('bank_charges', $data)) {
            $arr['bank_charges'] = $data['bank_charges'];
        } else {
            $arr['bank_charges'] = 0;
        }
        $arr['payment_status'] = 'Unsettled';
        $arr['balance_amount'] = 0;
        $arr['subtotal'] = 0;
        $arr['total'] = 0;
        $arr['serial_no'] = generateSerial('sale_orders', 'SO');
        $arr['shipping_address'] = array_key_exists('shipping_address', $arr) ? json_encode($arr['shipping_address']) : NULL;
        //dd($arr);
        $model = self::create($arr);
        $subtotal = 0;
        $collection = collect($data['items']);
        $groupedByProductId = $collection->groupBy('product.id');
        $newItems = [];
        $ignoreIds = [];
        foreach ($data['items'] as $item) {
            $product = \App\Product::find($item['product']['id']);
            if ($product->expirable && !array_key_exists('expiry_date', $item)) {
                if (!in_array($item['product']['id'], $ignoreIds)) {
                    $expiryOptions = $product->stocks()->where([
                        ['reservable_id', '=', 0],
                        ['warehouse_id', '=', $data['warehouse']['id']],
                        ['qty', '>', 0]
                    ])->orderBy('expiry_date')->get()->toArray();
                    $qty = $item['qty'];
                    // expiry options are sorted by expiry date
                    foreach ($groupedByProductId[$item['product']['id']] as $it) {
                        foreach ($expiryOptions as $ind => $obj) {
                            if ($it['qty'] > 0) {
                                if ($it['qty'] >= $obj['qty']) {
                                    $temp = $item;
                                    $temp['qty'] = (int)$obj['qty'];
                                    $it['qty'] = (int)$it['qty'] - (int)$obj['qty'];
                                    $temp['expiry_date'] = $obj['expiry_date'];
                                    $temp['expirable'] = TRUE;
                                    $temp['gst'] = array_key_exists('gst', $item) && $item['gst'] ? $item['gst'] : $product->gst;
                                    array_splice($expiryOptions, $ind, 1);
                                    $newItems[] = $temp;
                                } else {
                                    $temp = $item;
                                    $temp['qty'] = (int)$it['qty'];
                                    $temp['expiry_date'] = $obj['expiry_date'];
                                    $temp['expirable'] = TRUE;
                                    $temp['gst'] = array_key_exists('gst', $item) && $item['gst'] ? $item['gst'] : $product->gst;
                                    $expiryOptions[$ind]['qty'] = $obj['qty'] - $it['qty'];
                                    $newItems[] = $temp;
                                    break;
                                }
                            }
                        }
                        $ignoreIds[] = $item['product']['id'];
                    }
                }
            } else if ($product->expirable && $item['expiry_date']) {
                $temp = $item;
                $temp['expirable'] = true;
                $temp['expiry_date'] = $item['expiry_date'];
                $temp['gst'] = array_key_exists('gst', $item) && $item['gst'] ? $item['gst'] : $product->gst;
                $newItems[] = $temp;
            } else {
                $temp = $item;
                $temp['expirable'] = false;
                $temp['expiry_date'] = NULL;
                $temp['gst'] = array_key_exists('gst', $item) && $item['gst'] ? $item['gst'] : $product->gst;
                $newItems[] = $temp;
            }
        }
        foreach ($newItems as $item) {
            $saleOrderItemsModel = new \App\SaleOrderItem(Arr::only($item, [
                'rate',
                'taxable',
                'qty',
                'expirable',
                'expiry_date',
                'gst',
                'tax_amount',
                'total',
            ]));
            $saleOrderItemsModel->product_id = $item['product']['id'];
            $itemTotal = round((float)$item['rate'] * (int)$item['qty'], 2);;
            if (!$data['rate_includes_gst']) {
                $taxable = $itemTotal;
                $saleOrderItemsModel->taxable = $taxable;
                if ($data['flood_cess_included'] && (float)$item['gst'] != 5) {
                    $taxAmount = round($taxable * (((float)$item['gst'] + 1) / 100), 2);
                    $saleOrderItemsModel->tax_amount = $taxAmount;
                } else {
                    $taxAmount = round($taxable * ((float)$item['gst'] / 100), 2);
                    $saleOrderItemsModel->tax_amount = $taxAmount;
                }
                $itemTotal = $taxable + $taxAmount;
                $saleOrderItemsModel->total = $itemTotal;
            } else {
                $saleOrderItemsModel->total = $itemTotal;
            }
            $subtotal += $itemTotal;
            $saleOrderItemsModel->use_mask = array_key_exists('use_mask', $item) ? $item['use_mask'] : 0;
            $saleOrderItemsModel->mask_name = array_key_exists('use_mask', $item) && $item['use_mask'] ? $item['mask_name'] : '';
            $model->items()->save($saleOrderItemsModel);
        }
        $model->subtotal = $subtotal;
        $model->total = $subtotal - (float)$data['discount'] + (float)$data['freight'] + (float)$data['round'] + (float)$data['cod_charge'];
        $model->save();
        return $model;
    }

    public function updateEntry($data)
    {
        $arr = Arr::only($data, [
            'status',
            'source',
            'order_no',
            'rate_includes_gst',
            'flood_cess_included',
            'otc',
            'cod',
            'discount',
            'freight',
            'cod_charge',
            'round',
            'remarks',
            'billing_address',
            'shipping_address',
            'po_number',
            'order_date'
        ]);
        $arr['customer_id'] = $data['customer']['id'];
        $arr['representative_id'] = $data['representative']['id'];
        $arr['pricelist_id'] = $data['pricelist']['id'];
        $arr['warehouse_id'] = $data['warehouse']['id'];
        $arr['created_by_id'] = $data['created_by']['id'];
        $arr['payment_status'] = 'Unsettled';
        $arr['balance_amount'] = 0;
        $arr['subtotal'] = 0;
        $arr['total'] = 0;
        $arr['shipping_address'] = json_encode($arr['shipping_address']);
        $this->update($arr);

        $subtotal = 0;
        $newItems = [];
        foreach ($data['items'] as $item) {
            $product = \App\Product::find($item['product']['id']);
            if ($product->expirable && $item['expiry_date']) {
                $temp = $item;
                $temp['expirable'] = true;
                $temp['expiry_date'] = $item['expiry_date'];
                $temp['gst'] = array_key_exists('gst', $item) && $item['gst'] ? $item['gst'] : $product->gst;
                $newItems[] = $temp;
            } else {
                $temp = $item;
                $temp['expirable'] = false;
                $temp['expiry_date'] = NULL;
                $temp['gst'] = array_key_exists('gst', $item) && $item['gst'] ? $item['gst'] : $product->gst;
                $newItems[] = $temp;
            }
        }
        foreach ($newItems as $item) {
            if (array_key_exists('id', $item)) {
                $saleOrderItemsModel = \App\SaleOrderItem::find($item['id']);
                // $saleOrderItemsModel->update($d);
            } else {
                $saleOrderItemsModel = new \App\SaleOrderItem();
            }
            // $saleOrderItemsModel->gst = array_key_exists('gst',$item) && $item['gst'] ? $item['gst'] : $product->gst;
            // $saleOrderItemsModel->expirable = $product->expirable;
            $saleOrderItemsModel->product_id = $item['product']['id'];
            $saleOrderItemsModel->rate = $item['rate'];
            $saleOrderItemsModel->qty = $item['qty'];
            $saleOrderItemsModel->expirable = $item['expirable'];
            $saleOrderItemsModel->expiry_date = $item['expiry_date'];
            $saleOrderItemsModel->gst = $item['gst'];
            $itemTotal = round((float)$item['rate'] * (int)$item['qty'], 2);;
            if (!$data['rate_includes_gst']) {
                $taxable = $itemTotal;
                $saleOrderItemsModel->taxable = $taxable;
                if ($data['flood_cess_included'] && (float)$item['gst'] != 5) {
                    $taxAmount = round($taxable * (((float)$item['gst'] + 1) / 100), 2);
                    $saleOrderItemsModel->tax_amount = $taxAmount;
                } else {
                    $taxAmount = round($taxable * ((float)$item['gst'] / 100), 2);
                    $saleOrderItemsModel->tax_amount = $taxAmount;
                }
                $itemTotal = $taxable + $taxAmount;
                $saleOrderItemsModel->total = $itemTotal;
            } else {
                $saleOrderItemsModel->total = $itemTotal;
            }
            $saleOrderItemsModel->use_mask = array_key_exists('use_mask', $item) ? $item['use_mask'] : 0;
            $saleOrderItemsModel->mask_name = array_key_exists('use_mask', $item) && $item['use_mask'] ? $item['mask_name'] : '';
            if (array_key_exists('id', $item)) {
                $saleOrderItemsModel->save();
            } else {
                $this->items()->save($saleOrderItemsModel);
            }
            $subtotal += $itemTotal;
        }
        $this->subtotal = $subtotal;
        $this->total = $subtotal - (float)$data['discount'] + (float)$data['freight'] + (float)$data['round'] + (float)$data['cod_charge'];
        $this->save();
        return $this;
    }

    public function getSettled()
    {
        return \App\Transaction::where([
            ['payable_id', '=', $this->id],
            ['payable_type', '=', 'App\SaleOrder'],
            ['standalone', '=', 0]
        ])->sum('amount');
    }

    public function deleteTransactions()
    {
        $m = DB::table('transactions')->where([
            ['payable_id', '=', $this->id],
            ['payable_type', '=', 'App\SaleOrder'],
            ['parent_id', '=', 0]
        ])->get();
        foreach ($m as $item) {
            $transaction = \App\Transaction::find($item->id);
            $transaction->children()->delete();
            $transaction->delete();
        }
    }

    public function settleBy($amount, $payment_via, $reference_id, $remarks, $parent_id = 0)
    {
        $balance = $this->total - $this->getSettled();
        if ($amount >= $balance) {
            $this->payment_status = 'Settled';
        } elseif (($amount == 0 && $balance > 0 && $balance < $this->total) || ($amount < $balance)) {
            $this->payment_status = 'Partially Settled';
        } else {
            $this->payment_status = 'Unsettled';
        }
        $this->save();
        $serial_no = NULL;
        if (!$parent_id) {
            $serial_no = generateSerial('transactions', 'TR');
        }
        $transaction = new \App\Transaction([
            'serial_no' => $serial_no,
            'customer_id' => $this->customer->id,
            'type' => 'settlement',
            'payment_via' => $payment_via,
            'amount' => $amount,
            'reference_id' => $reference_id,
            'remarks' => $remarks,
            'created_by_id' => \Auth::user() ? \Auth::user()->id : 1,
            'parent_id' => $parent_id
        ]);
        $this->transactions()->save($transaction);
    }

    public function registerPayment(Request $request)
    {
        $items = $request->items;
        $cbalance = $this->customer->getBalance();
        if ($cbalance < 0 && $request->settle_using_cbalance) {
            $unsettledTransactions =  $this->customer->unsettledTransactions();
            $saleOrder = SaleOrder::find($items[0]['id']);
            foreach ($unsettledTransactions as $transaction) {
                if ($transaction['balance'] > $items[0]['settle_by'])
                // There will be only one item (current sale order)  in items since customer balance  < 0
                {
                    $saleOrder->settleBy($items[0]['settle_by'], $transaction['payment_via'], $transaction['reference_id'], $transaction['remarks'], $transaction['id']);
                    unset($items[0]); // after this items will be zero
                    break;
                } else {
                    $saleOrder->settleBy($transaction['balance'], $transaction['payment_via'], $transaction['reference_id'], $transaction['remarks'], $transaction['id']);
                    $items[0]['settle_by'] = $items[0]['settle_by'] - $transaction['balance'];
                    if ($items[0]['settle_by'] == 0) {
                        unset($items[0]);
                        break;
                    }
                }
            }
            if (count($items) > 0) {
                // After settling the saleorder with all unsettled transactions
                // Check whether still customer balance is negative. If negative then it must be sale returns
                $cbalance = $this->customer->getBalance();
                if ($cbalance < 0 && abs($cbalance) >= $items[0]['settle_by']) {
                    $saleOrder->payment_status = 'Settled';
                    $saleOrder->save();
                    unset($items[0]);
                } else if ($request->paid_amount > 0) {
                    if ($request->paid_amount + abs($cbalance) >= $items[0]['settle_by']) {
                        $parentTransaction = \App\Transaction::create([
                            'customer_id' => $this->customer->id,
                            'type' => 'settlement',
                            'payment_via' => $request->payment_via,
                            'amount' => $request->paid_amount,
                            'reference_id' => $request->reference_id,
                            'remarks' => $request->remarks,
                            'created_by_id' => \Auth::user()->id,
                            'standalone' => true,
                            'parent_id' => 0
                        ]);
                        $saleOrder->settleBy($items[0]['settle_by'] - abs($cbalance), $request->payment_via, $request->reference_id, $request->remarks, $parentTransaction->id);
                        $saleOrder->payment_status = 'Settled';
                        $saleOrder->save();
                        unset($items[0]);
                        return true; // No more execution
                    } else {
                        $saleOrder->settleBy($request->paid_amount, $request->payment_via, $request->reference_id, $request->remarks);
                        return true; // No more execution
                    }
                }
            }
        }
        if ($request->paid_amount > 0) {
            $parent_id = 0;
            if ($request->remain_amount > 0) {
                $parentTransaction = \App\Transaction::create([
                    'customer_id' => $this->customer->id,
                    'type' => 'settlement',
                    'payment_via' => $request->payment_via,
                    'amount' => $request->paid_amount,
                    'reference_id' => $request->reference_id,
                    'remarks' => $request->remarks,
                    'created_by_id' => \Auth::user()->id,
                    'standalone' => true,
                    'parent_id' => 0
                ]);
                $parent_id = $parentTransaction->id;
            }
            foreach ($items as $item) {
                $saleOrder = SaleOrder::find($item['id']);
                $saleOrder->settleBy($item['settle_by'], $request->payment_via, $request->reference_id, $request->remarks, $parent_id);
            }
        }
    }
    public static function reserveStock($model, $items)
    {
        //dd($items);
        foreach ($items as $item) {
            self::reserveItem($model, $item);
        }
    }

    public static function reserveItem($model, $item)
    {
        $lot_number = NULL;
        if ($item->expiry_date) {   // If there is any expiry date specified then it should never go negative
            $m = \App\ProductStock::where([
                ['product_id', '=', $item['product_id']],
                ['warehouse_id', '=', $model->warehouse_id],
                ['expiry_date', '=', $item['expiry_date']],
                ['reservable_id', '=', 0]
            ])->first();
            if ($m->qty == (int)$item['qty']) {
                $lot_number = $m->lot_number;
                $m->delete();
            } else {
                $lot_number = $m->lot_number;
                $m->update(['qty' => ($m->qty - $item['qty'])]);
            }
        } else {
            $m = \App\ProductStock::where([
                ['product_id', '=', $item['product_id']],
                ['warehouse_id', '=', $model->warehouse_id],
                ['expiry_date', '=', NULL],
                ['reservable_id', '=', 0]
            ])->first();
            if ($m) {
                if ($m->qty == (int)$item['qty']) {
                    $lot_number = $m->lot_number;
                    $m->delete();
                } else {
                    $lot_number = $m->lot_number;
                    $m->update(['qty' => ($m->qty - $item['qty'])]);
                }
            } else {
                \App\ProductStock::insert([
                    'product_id' => $item['product_id'],
                    'reservable_id' => 0,
                    'warehouse_id' => $model->warehouse_id,
                    'qty' => (0 - $item['qty'])
                ]);
            }
        }
        \App\ProductStock::insert([
            'product_id' => $item['product_id'],
            'reservable_id' => $model->id,
            'reservable_type' => 'App\SaleOrder',
            'warehouse_id' => $model->warehouse_id,
            'expiry_date' => $item['expiry_date'],
            'lot_number' => $lot_number,
            'qty' => $item['qty']
        ]);
    }

    public static function revertStock($model, $items)
    {
        // dd($items);
        foreach ($items as $item) {
            self::revertItem($model, $item);
        }
        $model->clearReservation();
    }

    public static function revertItem($model, $item)
    {
        if ($item['expiry_date']) {
            $m = \App\ProductStock::where([
                ['product_id', '=', $item['product_id']],
                ['reservable_id', '=', 0],
                ['expiry_date', '=', $item['expiry_date']],
                ['warehouse_id', '=', $model->warehouse_id]
            ])->first();

            if ($m) {
                $m->update(['qty' => $m->qty + (int)$item['qty']]);
            } else {
                $lot_number = DB::table('product_stock')->where([
                    ['product_id', '=', $item['product_id']],
                    ['reservable_id', '=', $model->id],
                    ['reservable_type', '=', 'App\SaleOrder'],
                    ['expiry_date', '=', $item['expiry_date']],
                    ['warehouse_id', '=', $model->warehouse_id]
                ])->first()->lot_number;
                \App\ProductStock::create([
                    'product_id' => $item['product_id'],
                    'reservable_id' => 0,
                    'expiry_date' => $item['expiry_date'],
                    'warehouse_id' => $model->warehouse_id,
                    'lot_number' => $lot_number,
                    'qty' => (int)$item['qty']
                ]);
            }
        } else {
            $m = \App\ProductStock::where([
                ['product_id', '=', $item['product_id']],
                ['reservable_id', '=', 0],
                ['expiry_date', '=', NULL],
                ['warehouse_id', '=', $model->warehouse_id]
            ])->first();
            if ($m) {
                $m->update(['qty' => $m->qty + (int)$item['qty']]);
            } else {
                $lot_number = DB::table('product_stock')->where([
                    ['product_id', '=', $item['product_id']],
                    ['reservable_id', '=', $model->id],
                    ['reservable_type', '=', 'App\SaleOrder'],
                    ['expiry_date', '=', NULL],
                    ['warehouse_id', '=', $model->warehouse_id]
                ])->first()->lot_number;
                \App\ProductStock::create([
                    'product_id' => $item['product_id'],
                    'reservable_id' => 0,
                    'expiry_date' => NULL,
                    'lot_number' => $lot_number,
                    'warehouse_id' => $model->warehouse_id,
                    'qty' => (int)$item['qty']
                ]);
            }
        }
    }

    public function clearReservation()
    {
        DB::table('product_stock')->where([
            ['reservable_id', '=', $this->id],
            ['reservable_type', '=', 'App\SaleOrder'],
        ])->delete();
    }

    public function generateShipment()
    {
        $today_date = \Carbon\Carbon::now()->format('ymd');
        $serial_no = 'SH' . $today_date . '0000';
        $sn_val_arr['serial_no'] = $serial_no;
        $count = 0;
        while (Validator::make($sn_val_arr, ['serial_no' => 'unique:shipments,serial_no'])->fails()) {
            $serial_no = substr($serial_no, 0, -4);
            $count = $count + 1;
            $serial_no = $serial_no . str_pad($count, 4, 0, STR_PAD_LEFT);
            $sn_val_arr['serial_no'] = $serial_no;
        }
        $shipment = new \App\Shipment([
            'serial_no' => $serial_no,
            'created_by_id' => request()->user()->id,
            'status' => 'Awaiting Shipment',
        ]);
        $this->shipment()->save($shipment);
        // $model->save();
        activity()
            ->causedBy($this)
            ->performedOn($this->shipment)
            ->withProperties($this->getChanges())
            ->log('shipment_generated');
    }
}
