<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'has_gst' => 'boolean',
        'non_billable_account' => 'boolean'
    ];
    /*
    function siblingsOfMine()
    {
        return $this->belongsToMany('App\Customer', 'customer_siblings', 'customer_id', 'sibling_id');
    }

    function siblingOf()
    {
        return $this->belongsToMany('App\Customer', 'customer_siblings', 'sibling_id', 'customer_id');
    }

    public function getSiblingsAttribute()
    {
        if ( ! array_key_exists('siblings', $this->relations)) $this->loadSiblings();

        return $this->getRelation('siblings');
    }

    public function loadSiblings()
    {
        if ( ! array_key_exists('siblings', $this->relations))
        {
            $siblings = $this->mergeSibilings();

            $this->setRelation('siblings', $siblings);
        }
    }

    protected function mergeSibilings()
    {
        return $this->siblingsOfMine->merge($this->siblingOf);
    }*/

    public function linked()
    {
        return $this->belongsToMany('App\Customer','customer_relations','customer_id','relation_id')->withPivot('relation_type','specification');
    }

    public function linkedOf()
    {
        return $this->belongsToMany('App\Customer','customer_relations','relation_id','customer_id')->withPivot('relation_type','specification');
    }

    public function siblings()
    {
        return $this->belongsToMany('App\Customer','customer_relations','customer_id','relation_id')->wherePivot('relation_type','Sibling branch of');
    }

    public function relatedTo()
    {
        return $this->belongsToMany('App\Customer','customer_relations','customer_id','relation_id')->wherePivot('relation_type','Related to');
    }

    public function worksHere()
    {
        return $this->belongsToMany('App\Customer','customer_relations','customer_id','relation_id')->wherePivot('relation_type','Works here');
    }

    public function representatives()
    {
        return $this->belongsToMany('App\User','customer_representative','customer_id','user_id');
    }

    public function addresses(){
        return $this->morphMany('App\Address','addressable');
    }

    public function billing_address() {
        return $this->morphOne('App\Address','addressable')->where('tag_name','billing');
    }

    public function emails()
    {
        return $this->morphTomany('App\Email','emailable');
    }

    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function sale_orders()
    {
        return $this->hasMany('App\SaleOrder','customer_id');
    }

    public function sale_returns()
    {
        return $this->hasManyThrough('App\SaleReturn','App\SaleOrder');
    }

    public function getLedger()
    {
        $saleOrders = $this->sale_orders()->whereNotNull('invoiced_at')->select('id','serial_no','total','invoiced_at')->get();
        $settlements = $this->transactions()->where('type','settlement')->where('parent_id',0)->select('id','serial_no','amount','created_at','payment_via','reference_id')->get();
        $saleReturns = $this->sale_returns()->select('sale_returns.id','sale_returns.serial_no','sale_returns.total','sale_returns.created_at')->where('sale_returns.status','active')->get();
        $refunds = $this->transactions()->where('type','refund')->where('parent_id',0)->select('id','serial_no','amount','created_at','payment_via','reference_id')->get();

        $arr = [];
        foreach($saleOrders as $saleOrder)
        {
            $arr[] = ['record_id' => $saleOrder['id'],'type' => 'sale_order', 'debit' => $saleOrder['total'],'record' => $saleOrder['serial_no'],'date' => $saleOrder['invoiced_at']];
        }
        foreach($settlements as $settlement)
        {
            $arr[] = ['record_id' => $settlement['id'], 'type' => 'settlement'.'('.$settlement['payment_via'].')', 'credit' => $settlement['amount'],'record' => $settlement['serial_no'],'date' => $settlement['created_at']];
        }
        foreach($saleReturns as $saleReturn)
        {
            $arr[] = ['record_id' => $saleReturn['id'], 'type' => 'sale_return', 'credit' => $saleReturn['total'],'record' => $saleReturn['serial_no'],'date' => $saleReturn['created_at']];
        }
        foreach($refunds as $refund)
        {
            $arr[] = ['record_id' => $refund['id'], 'type' => 'refund'.'('.$settlement['payment_via'].')', 'debit' => $refund['amount'],'record' => $refund['serial_no'],'date' => $refund['created_at']];
        }

        $collection = collect($arr);
        return $collection->sortBy('date')->values()->all();
    }

    public function getBalance () {
        $totalSettledAmount = \App\Transaction::whereIn('type',['settlement','writeoff'])
        ->where('customer_id',$this->id)
        ->where('parent_id',0)
        ->sum('amount');

        $totalSaleReturn = \App\SaleReturn::join('sale_orders','sale_returns.sale_order_id','=','sale_orders.id')
        ->where('sale_orders.customer_id',$this->id)
        ->where('sale_returns.status','Active')
        ->sum('sale_returns.total');

        $totalSaleAmount = \App\SaleOrder::where('customer_id',$this->id)
        ->whereNotNull('invoiced_at')->sum('total');

        $balance = $totalSaleAmount - $totalSettledAmount - $totalSaleReturn;

        return $balance;
    }

    public function updateBalance() {
        $balance = $this->getBalance();
        $this->balance = $balance;
        $this->save();
    }

    public function transactions () 
    {
        return $this->hasMany('App\Transaction','customer_id');
    }

    public function hasAnyUnsettledSaleOrders($except_id = NULL)
    {
        if($except_id)
        {
            return $this->sale_orders()
            ->where('payment_status','<>','Settled')
            ->whereNotNull('invoiced_at')
            ->where('id','<>',$except_id)
            ->count();
        }
        return $this->sale_orders()->where('payment_status','<>','Settled')->whereNotNull('invoiced_at')->count();
    }

    public function getUnsettledOrders()
    {
        $o = $this->sale_orders()->where('payment_status','<>','Settled')->whereNotNull('invoiced_at')->orderBy('invoiced_at')->get();
        foreach ($o as $index => $item) {
            $m = \App\SaleOrder::find($item->id);
            $o[$index]['balance_amount'] = (float)$m->total - (float)$m->getSettled();
        }
        return $o;
    }
    
    public function unsettledTransactions()
    {
        $standalones = $this->transactions()->where('standalone',1)->get();
        $arr = [];
        foreach($standalones as $standalone) {
            $transaction = \App\Transaction::find($standalone['id']);
            $childrenSum = $transaction->getChildrenAmountSum();
            if($childrenSum != $transaction->amount)
            $arr[] = [
                'id' => $transaction->id,
                'balance' => (float)$transaction->amount - (float)$childrenSum,
                'payment_via' => $transaction->payment_via,
                'reference_id' => $transaction->reference_id,
                'remarks' => $transaction->remarks,
                'amount' => $transaction->amount
            ];
        }
        return $arr;
    }
    public function buildRelations($linked_accounts)
    {
        foreach($linked_accounts as $item)
        {
            //return $item;
            if($item['relation_type'] == 'Works here')
            {
                $related_account = \App\Customer::find($item['account']['id']);
                $related_account->linked()->detach($this->id);
                $this->linked()->detach($related_account->id);
                $related_account->linked()->attach($this->id,['relation_type' => 'Works at','specification'=>'']);
                $this->linked()->attach($related_account->id,['relation_type' => 'Works here','specification'=>$item['specification']]);
            }
            elseif($item['relation_type'] == 'Sibling branch of')
            {
                $related_account = \App\Customer::find($item['account']['id']);
                $related_account->linked()->detach($this->id);
                $this->linked()->detach($related_account->id);
                $siblings = $related_account->siblings()->get();
                if($siblings)
                {
                    foreach($siblings as $sibling){
                        $sibling->linked()->detach($this->id);
                        $this->linked()->detach($sibling->id);
                        $sibling->linked()->attach($this->id,['relation_type' => 'Sibling branch of','specification'=>'']);
                        $this->linked()->attach($sibling->id,['relation_type' => 'Sibling branch of','specification'=>'']);
                    }
                }
                $related_account->linked()->attach($this->id,['relation_type' => 'Sibling branch of','specification'=>'']);
                $this->linked()->attach($related_account->id,['relation_type' => 'Sibling branch of','specification'=>$item['specification']]);
            }
            elseif($item['relation_type'] == 'Works at')
            {
                $related_account = \App\Customer::find($item['account']['id']);
                $related_account->linked()->detach($this->id);
                $this->linked()->detach($related_account->id);
                $related_account->linked()->attach($this->id,['relation_type' => 'Works here','specification'=>'']);
                $this->linked()->attach($related_account->id,['relation_type' => 'Works at','specification'=>$item['specification']]);
            }
            elseif($item['relation_type'] == 'Belongs to')
            {
                //return 'kitti';
                $related_account = \App\Customer::find($item['account']['id']);
                $related_account->linked()->detach($this->id);
                $this->linked()->detach($related_account->id);
                $related_account->linked()->attach($this->id,['relation_type' => 'Owner of','specification'=>'']);
                $this->linked()->attach($related_account->id,['relation_type' => 'Belongs to','specification'=>$item['specification']]);
            }
            elseif($item['relation_type'] == 'Owner of')
            {
                $related_account = \App\Customer::find($item['account']['id']);
                $related_account->linked()->detach($this->id);
                $this->linked()->detach($related_account->id);
                $related_account->linked()->attach($this->id,['relation_type' => 'Belongs to','specification'=>'']);
                $this->linked()->attach($related_account->id,['relation_type' => 'Owner of','specification'=>$item['specification']]);
            }

            elseif($item['relation_type'] == 'Related to')
            {
                $related_account = Customer::find($item['account']['id']);
                $related_account->linked()->detach($this->id);
                $related_account->linked()->attach($this->id,['relation_type' => 'Related to','specification'=>'']);
                $this->linked()->detach($related_account->id);
                $this->linked()->attach($related_account->id,['relation_type' => 'Related to','specification'=>$item['specification']]);
            }
        }
    }

    public function getBillingAddress(){
        return $this->addresses()->where('tag_name','billing')->first();
    }

    public function getShippingAddress(){
        return $this->addresses()->where([
            ['tag_name','=','shipping'],
            ['id','=',$this->default_shipping],
        ])->first();
    }

}
