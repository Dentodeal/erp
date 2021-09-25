<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
class CustomersExport implements FromArray, WithHeadings
{
    use Exportable;
    protected $heading = [];
    protected $data;
    function __construct(array $data, array $heading) {
        $this->data = $data;
        $this->heading = $heading;
    }

    public function array(): array
    {
        $arr = [];
        foreach($this->data as $item) {
            $temp = [];
            foreach($this->heading as $head) {
                if(strpos($head,'address') !== FALSE) {
                    $temp[$head] = $item['addresses'] ? $item['addresses'][0][substr($head,8)] : '';
                } elseif($head == 'representatives') {
                    $temp[$head] = implode(",",array_map(function($it){
                        return $it['name'];
                    },$item['representatives']));
                } elseif($head == 'phones') {
                    if($item['addresses']) {
                        $temp[$head] = implode(",",array_map(function($it){
                            return '('.$it['country_code']. ') ' .$it['content'];
                        },$item['addresses'][0]['phones']));
                    } else {
                        $temp[$head] = '';
                    }
                } elseif($head == 'emails') {
                    $temp[$head] = implode(",",array_map(function($it){
                        return $it['content'];
                    },$item['emails']));
                } else {
                    $temp[$head] = $item[$head];
                }
            }
            $arr[] = $temp;
        }
        return $arr;
    }

    public function headings(): array
    {
        return $this->heading;
    }

}
