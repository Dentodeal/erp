<?php

namespace App\Imports;

use App\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class CustomerImport implements ToCollection, WithHeadingRow
{
    use Importable;
    protected $method;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    function __construct($method)
    {
        $this->method = $method;
    }

    public function collection(Collection $rows)
    {
        if($this->method == 'create')
        {
            $failures = [];
            foreach ($rows as $index => $row)
            {
                $val_array = [
                    'name' => [
                        'required',
                        'unique:customers,name'
                    ],
                ];
                $validator = Validator::make($row->toArray(),$val_array);
                if($validator->fails()){
                    $failures[] = [
                        'row' => $index + 1,
                        'errors' => $validator->errors()->all(),
                    ];
                }
            }
            if(count($failures) > 0)
            {
                throw new \App\Exceptions\ImportException($failures);
            }
            else
            {
                foreach($rows as $row)
                {
                    $model = Customer::create(Arr::only($row->toArray(),['id','name','gst_number','has_gst','status','type','non_billable_account']));
                    if($row['email'])
                    {
                        $email_model = \App\Email::firstOrCreate(
                            ['content' => $row['email']],
                            ['source' => 'ERP'],
                        );
                        
                        $model->emails()->sync([$email_model->id]);
                    }
                    $billing_address = \App\Address::create([
                        'tag_name' => 'billing',
                        'line_1' => $row['line_1'],
                        'line_2' => $row['line_2'],
                        'pin' => $row['pin'],
                        'district' => $row['district'],
                        'state' => $row['state'],
                        'country' => $row['country'],
                        'addressable_id' => $model->id,
                        'addressable_type' => 'App\Customer',
                    ]);
                    $phones = explode(",",$row['phones']);
                    $phones_ids = [];
                    foreach($phones as $phone){
                        $open_bracket_pos = strpos($phone,"(");
                        $closing_bracket_pos = strpos($phone,")");
                        $country_code = substr($phone,$open_bracket_pos+1,$closing_bracket_pos - $open_bracket_pos - 1);
                        $content = substr($phone,$closing_bracket_pos+1);
                        $phone_model = \App\Phone::firstOrCreate(
                            ['content' => $content,'country_code' => $country_code],
                            ['source' => 'ERP']
                        );
                        $phone_model->addTags([$row['type'],$row['district'],$row['state'],$row['country']]);
                        $phones_ids[] = $phone_model->id;
                    }
                    $billing_address->phones()->sync($phones_ids);
                    $shipping_address = \App\Address::create([
                        'tag_name' => 'shipping',
                        'line_1' => $row['line_1'],
                        'line_2' => $row['line_2'],
                        'pin' => $row['pin'],
                        'district' => $row['district'],
                        'state' => $row['state'],
                        'country' => $row['country'],
                        'addressable_id' => $model->id,
                        'addressable_type' => 'App\Customer',
                    ]);
                    $shipping_address->phones()->sync($phones_ids);
                    $model->default_shipping = $shipping_address->id;
                    $model->save();
                    $representatives = explode(",",$row['representatives']);
                    $representative_ids = [];
                    foreach($representatives as $rep){
                        $rep_model = \App\User::where('name',$rep)->first();
                        $representative_ids[] = $rep_model->id;
                    }
                    $model->representatives()->sync($representative_ids);
                }
            }
        }
    }
}

