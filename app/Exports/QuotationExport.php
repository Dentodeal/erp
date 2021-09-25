<?php

namespace App\Exports;

use App\Quotation;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class QuotationExport implements FromView
{
    use Exportable;
    protected $data;
    protected $include_gst_column;
    protected $use_mask_name;
    protected $billing_address;
    protected $include_hsn_column;

    function __construct(Quotation $data,$billing_address,$include_gst_column, $use_mask_name, $include_hsn_column)
    {
        $this->data = $data;
        $this->include_gst_column = $include_gst_column;
        $this->use_mask_name = $use_mask_name;
        $this->billing_address = $billing_address;
        $this->include_hsn_column = $include_hsn_column;
    }
    public function view(): View
    {
        $currency = $this->data->currency == 'INR' ? '' : '$';
        return view('quotation_excel', [
            'currency' => $currency,
            'quotation'=>$this->data,
            'totalQty' => $this->data->items()->sum('qty'),
            'totalTaxable' => $this->data->items()->sum('taxable'),
            'totalTax' => $this->data->items()->sum('tax_amount'),
            'include_gst_column' => $this->include_gst_column,
            'include_hsn_column' => $this->include_hsn_column,
            'use_mask_name' => $this->use_mask_name,
            'address' => $this->billing_address
        ]);
    }
}
