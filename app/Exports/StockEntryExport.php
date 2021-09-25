<?php

namespace App\Exports;

use App\StockEntry;
use Maatwebsite\Excel\Concerns\FromCollection;

class StockEntryExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return StockEntry::all();
    }
}
