<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class SaleOrderExpiryStock implements Rule
{
    protected $id;
    protected $groupedByProductId;
    protected $product;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Collection $items,$id = NULL)
    {
        $this->id = $id;
        $this->groupedByProductId = $items;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->product = \App\Product::find($value['product_id']);
        if($this->product->expirable && $value['expiry_date']) 
        {
            $arr = [];
            foreach($this->groupedByProductId->get($this->product->id) as $it)
            {   
                if($it['expiry_date'])
                {
                    $arr[$it['expiry_date']] = array_key_exists($it['expiry_date'], $arr) ? $arr[$it['expiry_date']] + (int)$it['qty'] : (int)$it['qty'];
                }
            }
            $availableStockForExpiry = DB::table('product_stock')->where([
                ['expiry_date','=',$value['expiry_date']],
                ['reservable_id','=',0],
                ['product_id','=',$value['product_id']]
            ])->first()->qty;
            // dd($availableStockForExpiry);
            if($arr[$value['expiry_date']] > $availableStockForExpiry)
            {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Insufficient qty for the given expiry date for '.$this->product->name;
    }
}
