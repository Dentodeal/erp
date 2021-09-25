<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\DB;

class ProductsImport implements ToCollection, WithHeadingRow
{
    use Importable;
    protected $method;

    function __construct($method)
    {
        $this->method = $method;
    }

    public function collection(Collection $rows)
    {
        //return $rows[0];
        if($this->method == 'create')
        {
            $failures = [];
            foreach ($rows as $index => $row)
            {
                $val_array = [
                    'status' => 'required|in:Active,Draft,Pending Approval,Approved',
                    'name' => 'required|unique:products',
                    'alias' => 'nullable',
                    'reorder_code' => 'nullable',
                    'hsn' => 'nullable',
                    'sku' => 'sometimes|required|unique:products',
                    'enabled' => 'required|boolean',
                    'weight' => 'nullable|numeric',
                    'mrp' => 'nullable|numeric',
                    'gst' => 'required|numeric',
                    'gsp_customer' => 'nullable',
                    'gsp_dealer' => 'nullable',
                    'type_id' => 'required|integer',
                    'department_id' => 'required|integer',
                    'category_id' => 'required|integer',
                    'sub_category_id' => 'required|integer',
                    'brand_id' => 'required|integer',
                    'expirable' => 'required|boolean',
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
                foreach($rows as $row){
                    if(!$row->has('sku')){
                        $row['sku'] = $this->getSku($row);
                    }
                    $arr = [
                        'status' => $row['status'],
                        'name' => $row['name'],
                        'alias' => $row['alias'],
                        'reorder_code' => $row['reorder_code'],
                        'hsn' => $row['hsn'],
                        'sku' => $row['sku'],
                        'enabled' => $row['enabled'],
                        'weight' => $row['weight'],
                        'mrp' => $row['mrp'],
                        'gst' => $row['gst'],
                        'gsp_customer' => $row['gsp_customer'],
                        'gsp_dealer' => $row['gsp_dealer'],
                        'type_id' => $row['type_id'],
                        'department_id' => $row['department_id'],
                        'category_id' => $row['category_id'],
                        'sub_category_id' => $row['sub_category_id'],
                        'brand_id' => $row['brand_id'],
                        'expirable' => $row['expirable'],
                    ];
                    if($row->has('id')){
                        $arr['id'] = $row['id'];
                    }
                    Product::create($arr);
                }
            }
        }

        if($this->method == 'update')
        {
            $failures = [];
            foreach ($rows as $index => $row)
            {
                $val_array = [
                    'id' => 'required',
                    'status' => 'required|in:Active,Draft,Pending Approval,Approved',
                    'name' => 'required|unique:products,namem'.$row['id'],
                    'alias' => 'nullable',
                    'reorder_code' => 'nullable',
                    'hsn' => 'nullable',
                    'sku' => 'sometimes|required|unique:products,sku,'.$row['id'],
                    'enabled' => 'required|boolean',
                    'weight' => 'nullable|numeric',
                    'mrp' => 'nullable|numeric',
                    'gst' => 'required|numeric',
                    'gsp_customer' => 'nullable',
                    'gsp_dealer' => 'nullable',
                    'type_id' => 'required|integer',
                    'department_id' => 'required|integer',
                    'category_id' => 'required|integer',
                    'sub_category_id' => 'required|integer',
                    'brand_id' => 'required|integer',
                    'expirable' => 'required|boolean',
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
                foreach($rows as $row){
                    if(!$row->has('sku')){
                        $row['sku'] = $this->getSku($row);
                    }
                    Product::where('id',$row['id'])->update([
                        'status' => $row['status'],
                        'name' => $row['name'],
                        'alias' => $row['alias'],
                        'reorder_code' => $row['reorder_code'],
                        'hsn' => $row['hsn'],
                        'sku' => $row['sku'],
                        'enabled' => $row['enabled'],
                        'weight' => $row['weight'],
                        'mrp' => $row['mrp'],
                        'gst' => $row['gst'],
                        'gsp_customer' => $row['gsp_customer'],
                        'gsp_dealer' => $row['gsp_dealer'],
                        'type_id' => $row['type_id'],
                        'department_id' => $row['department_id'],
                        'category_id' => $row['category_id'],
                        'sub_category_id' => $row['sub_category_id'],
                        'brand_id' => $row['brand_id'],
                        'expirable' => $row['expirable'],
                    ]);
                }
            }
        }
    }
    public function getSku($row)
    {
        $pc = '';
        $pc .= \App\ProductType::where('id',$row['type_id'])->first()->code;
        $pc .= \App\ProductDepartment::where('id',$row['department_id'])->first()->code;
        $pc .= \App\ProductCategory::where('id',$row['category_id'])->first()->code;
        $pc .= \App\ProductSubCategory::where('id',$row['sub_category_id'])->first()->code;
        $pc .= \App\ProductBrand::where('id',$row['brand_id'])->first()->code;
        $count = 0;
        $pc = $pc.str_pad($count, 3,0,STR_PAD_LEFT);
        if($row->has('id')){
            $data['sku'] = $pc;
            while(Validator::make($data,['sku'=> 'unique:products,sku'])->fails()){
                $pc = substr($pc, 0, -3);
                $count = $count + 1;
                $pc = $pc.str_pad($count, 3,0,STR_PAD_LEFT);
                $data['sku'] = $pc;
            }
        }
        else{
            $data['sku'] = $pc;
            while(Validator::make($data,['sku'=> 'unique:products,sku,'.$row['id']])->fails()){
                $pc = substr($pc, 0, -3);
                $count = $count + 1;
                $pc = $pc.str_pad($count, 3,0,STR_PAD_LEFT);
                $data['sku'] = $pc;
            }
        }
        return $pc;
    }
}
