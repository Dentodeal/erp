<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
class StockMismatch implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'id',
            'Product',
            'sku',
            'initial',
            'purchased',
            'returned',
            'sold',
            'Expected',
            'Current',
            'cost',
            'gst(%)'
        ];
    }
}
