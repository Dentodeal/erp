<table>
    <tbody>
        <tr>
            <td colspan="3">
                <b>Apexion Dental Products and Services</b>
            </td>
            <td colspan="3" style="text-align:right;">
                <b>Invoice</b>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <i class="text-p cyan2">Smile Delighted</i>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
                Ground Floor P.K.Complex, Pushpa Junction Kozhikode,
            </td>
            <td colspan="3">Date: {{ Carbon\Carbon::parse($saleorder->created_at)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td colspan="3">Kerala, PIN: 673002 Tel: 0495-4050453</td>
            <td colspan="3">Serial No: {{ $saleorder->serial_no }}</td>
        </tr>
        <tr>
            <td colspan="3">GSTIN: 32AASFA2560M1Z9</td>
            <td colspan="3">
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <b>Customer:</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
                <b>{{$saleorder->customer->name}}</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
                <p>{{$address}}</p>
            </td>
            <td colspan="3">
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="background-color:#333; color:#fff;text-align:center">
            <td>#</td>
            <td>Product</td>
            @if($include_hsn_column)
            <td>HSN</td>
            @endif
            <td>Qty</td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td>GST</td>
            @endif
            <td>Rate</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td>Taxable</td>
            <td>Tax</td>
            @endif
            <td>Total</td>
        </tr>
        @foreach($saleorder->items as $item)
        <tr>
            <td style="text-align:center">{{$loop->index+1}}</td>
            <td>{{$use_mask_name ? ($item->use_mask ? $item->mask_name : $item->product->name) : $item->product->name}}</td>
            @if($include_hsn_column)
            <td>{{$item->product ? $item->product->hsn : ''}}</td>
            @endif
            <td>{{$item->qty}}</td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td>{{$item->gst}}%</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$item->rate}}</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td style="text-align:right;">{{$currency}}{{$item->taxable}}</td>
            <td style="text-align:right;">{{$currency}}{{$item->tax_amount}}</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$item->total}}</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td style="text-align:right;"><b>Total</b></td>
            <td style="text-align:right;">{{$totalQty}}</td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td></td>
            @endif
            <td></td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td style="text-align:right;">{{$currency}}{{$totalTaxable}}</td>
            <td style="text-align:right;">{{$currency}}{{$totalTax}}</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$saleorder->subtotal}}</td>
        </tr>
        @if($saleorder->discount > 0)
        <tr>
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td></td>
            <td></td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td></td>
            @endif
            <td style="text-align:right;">@if($saleorder->rate_includes_gst && $saleorder->type !== 'Export') Discount @endif</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td></td>
            <td style="text-align:right;">Discount</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$saleorder->discount}}</td>
        </tr>
        @endif
        @if($saleorder->freight > 0)
        <tr class="table">
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td></td>
            <td></td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td></td>
            @endif
            <td style="text-align:right;">@if($saleorder->rate_includes_gst && $saleorder->type !== 'Export') Freight @endif</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td></td>
            <td style="text-align:right;">Freight</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$saleorder->freight}}</td>
        </tr>
        @endif
        @if($saleorder->bank_charges > 0)
        <tr class="table">
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td></td>
            <td></td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td></td>
            @endif
            <td style="text-align:right;">@if($saleorder->rate_includes_gst && $saleorder->type !== 'Export') Bank Charges @endif</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td></td>
            <td style="text-align:right;">Bank CHarges</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$saleorder->bank_charges}}</td>
        </tr>
        @endif
        @if($saleorder->round != '0.00')
        <tr>
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td></td>
            <td></td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td></td>
            @endif
            <td style="text-align:right;">@if($saleorder->rate_includes_gst && $saleorder->type !== 'Export') Round @endif</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td></td>
            <td style="text-align:right;">Round</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$saleorder->round}}</td>
        </tr>
        @endif
        <tr>
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td></td>
            <td></td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td></td>
            @endif
            <td style="text-align:right;">@if($saleorder->rate_includes_gst && $saleorder->type !== 'Export') Grand Total @endif</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td></td>
            <td style="text-align:right;">Grand Total</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$saleorder->total}}</td>
        </tr>
        @if($saleorder->rate_includes_gst && $saleorder->type !== 'Export')
        <tr>
            <td @if($include_gst_column) colspan="6" @else colspan="5" @endif>*All Rates are inclusive of GST</td>
        </tr>
        @endif
        <tr>
            <td colspan="6" style="text-align:center;"><h3>Thank you for your business</h3></td>
        </tr>
    </tbody>
</table>