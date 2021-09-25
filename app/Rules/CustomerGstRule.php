<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CustomerGstRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

     protected $id;
     protected $linked_accounts;
     protected $has_gst;
    public function __construct($id=NULL,$linked_accounts = [],$has_gst)
    {
        $this->id = $id;
        $this->linked_accounts = $linked_accounts;
        $this->has_gst = $has_gst;
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
        if(!$this->has_gst) {
            return true;
        }
        $model = DB::table('customers')->where('gst_number',$value);
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
            $ignore_ids[] = (int)$this->id;
        }
        if(count($ignore_ids) > 0){
            $model->whereNotIn('id',$ignore_ids);
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
        return 'The GST No. is taken.';
    }
}
