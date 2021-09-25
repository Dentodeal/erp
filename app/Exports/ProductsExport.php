<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
class ProductsExport implements FromArray, WithHeadings
{
    use Exportable;

    protected $heading = [];
    protected $data;
    function __construct(array $data, array $heading) {
        $this->data = $data;
        $this->heading = $heading;
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
        return $this->heading;
    }
}
