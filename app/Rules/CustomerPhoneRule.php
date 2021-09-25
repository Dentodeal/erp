<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CustomerPhoneRule implements Rule
{
    protected $linked_accounts;
    protected $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id=NULL,$linked_accounts = [])
    {
        $this->id = $id;
        $this->linked_accounts = $linked_accounts;
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
        $model = DB::table('phones')
        ->join('phoneables','phones.id','=','phoneables.phone_id')
        ->join('addresses','phoneables.phoneable_id','=','addresses.id')
        ->join('customers','addresses.addressable_id','=','customers.id')
        ->where('addresses.tag_name','=','billing')
        ->where('phoneables.phoneable_type','=','App\\Address')
        ->where('addresses.addressable_type','=','App\\Customer')
        ->where('phones.content','=',$value['content'])
        ->where('phones.country_code','=',$value['country_code']);
        $ignore_ids=[];
        if($this->linked_accounts){
            foreach($this->linked_accounts as $item){
                if($item['relation_type'] == 'Sibling branch of'){
                    $related_account = \App\Customer::find($item['account']['id']);
                    $siblings = $related_account->siblings()->get();
                    if($siblings){
                        foreach($siblings as $sibling){
                            $ignore_ids[] = $sibling->id;
                        }
                    }
                }
                if($item['relation_type'] == 'Works at'){
                    $related_account = \App\Customer::find($item['account']['id']);
                    $worksHere = $related_account->worksHere()->get();
                    if($worksHere){
                        foreach($worksHere as $child){
                            $ignore_ids[] = $child->id;
                        }
                    }
                }
                $ignore_ids[] = $item['account']['id'];
            }
        }
        if($this->id){
            $ignore_ids[] = $this->id;
        }
        if(count($ignore_ids) > 0){
            $model->whereNotIn('customers.id',$ignore_ids);
        }
        $count = (int) $model->count();
        if($count > 0){
            return false;
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
        return 'Phone no is taken';
    }
}
