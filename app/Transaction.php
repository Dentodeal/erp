<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $guarded = [];
    
    public function payable()
    {
        return $this->morphTo();
    }

    public function getChildrenAmountSum ()
    {
        return \App\Transaction::where('parent_id',$this->id)->sum('amount');
    }

    public function children ()
    {
        return $this->hasMany('App\Transaction','parent_id');
    }

    public function created_by () 
    {
        return $this->belongsTo('App\User','created_by_id');
    }
}
