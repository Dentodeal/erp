<table>
    <tbody>
        <tr>
            <td colspan="3">
                <b>Apexion Dental Products and Services</b>
            </td>
            <td colspan="3" style="text-align:right;">
                @if($quotation->type == 'Export')
                <b>Proforma Invoice</b>
                @else
                <b>Quotation</b>
                @endif
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
            <td colspan="3">Date: {{ Carbon\Carbon::parse($quotation->created_at)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td colspan="3">Kerala, PIN: 673002 Tel: 0495-4050453</td>
            <td colspan="3">Serial No: {{ $quotation->serial_no }}</td>
        </tr>
        <tr>
            <td colspan="3">GSTIN: 32AASFA2560M1Z9</td>
            <td colspan="3">
               Valid Until {{ Carbon\Carbon::parse($quotation->valid_until)->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <b>Quotation for:</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
                <b>{{$quotation->customer->name}}</b>
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
                @if($quotation->bank)
                <p>
                    <b>Bank Details:</b><br style="mso-data-placement:same-cell;" />
                    @if(array_key_exists('acc_name',$quotation->bank) && $quotation->bank['acc_name'])
                        Name: {{$quotation->bank['acc_name']}}<br style="mso-data-placement:same-cell;" />
                    @endif
                    @if(array_key_exists('bank_name',$quotation->bank) && $quotation->bank['bank_name'])
                        Bank: {{$quotation->bank['bank_name']}}<br style="mso-data-placement:same-cell;" />
                    @endif
                    @if(array_key_exists('acc_no',$quotation->bank) && $quotation->bank['acc_no'])
                        A/c No: {{$quotation->bank['acc_no']}}<br style="mso-data-placement:same-cell;" />
                    @endif
                    @if(array_key_exists('ifsc',$quotation->bank) && $quotation->bank['ifsc'])
                        IFSC: {{$quotation->bank['ifsc']}}<br style="mso-data-placement:same-cell;" />
                    @endif
                    @if(array_key_exists('upi',$quotation->bank) && $quotation->bank['upi'])
                        UPI: {{$quotation->bank['upi']}}<br style="mso-data-placement:same-cell;" />
                    @endif
                    @if(array_key_exists('bank_address',$quotation->bank) && $quotation->bank['bank_address'])
                        UPI: {{$quotation->bank['bank_address']}}<br style="mso-data-placement:same-cell;" />
                    @endif
                    @if(array_key_exists('swift',$quotation->bank) && $quotation->bank['swift'])
                        UPI: {{$quotation->bank['swift']}}<br style="mso-data-placement:same-cell;" />
                    @endif
                @else
                <p>
                    <b>Bank Details:</b><br style="mso-data-placement:same-cell;" />
                    Name: Apexion Dental Products and Services<br style="mso-data-placement:same-cell;" />
                    Bank: ICICI Bank Ltd.<br style="mso-data-placement:same-cell;" />
                    A/c No: 060305500009<br style="mso-data-placement:same-cell;" />
                    IFSC: ICIC0000603<br style="mso-data-placement:same-cell;" />
                    UPI: apexiondental@icici
                </p>
                @endif
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
        <tr>
            <td style="text-align:center;">Sale Person</td>
            <td style="text-align:center;"> P.O Number</td>
            <td style="text-align:center;">Inco Term</td>
            <td style="text-align:center;">Shipping Point</td>
            <td style="text-align:center;">Ship Via</td>
            <td style="text-align:center;">Ship Date</td>
        </tr>
        <tr>
            <td style="text-align:center;">{{$quotation->representative->name}}</td>
            <td style="text-align:center;">{{$quotation->po_number}}</td>
            <td style="text-align:center;">{{$quotation->inco_term}}</td>
            <td style="text-align:center;">{{$quotation->fob_point}}</td>
            <td style="text-align:center;">{{$quotation->ship_via}}</td>
            <td style="text-align:center;">{{\Carbon\Carbon::parse($quotation->ship_date)->format('d/m/Y')}}</td>
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
            @if($include_gst_column && $quotation->type !== 'Export')
            <td>GST</td>
            @endif
            <td>Rate</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td>Taxable</td>
            <td>Tax</td>
            @endif
            <td>Total</td>
        </tr>
        @foreach($quotation->items as $item)
        <tr>
            <td style="text-align:center">{{$loop->index+1}}</td>
            <td>{{$use_mask_name ? ($item->use_mask ? $item->mask_name : $item->product_name) : $item->product_name}}</td>
            @if($include_hsn_column)
            <td>{{$item->product ? $item->product->hsn : ''}}</td>
            @endif
            <td>{{$item->qty}}</td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td>{{$item->gst}}%</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$item->rate}}</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
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
            @if($include_gst_column && $quotation->type !== 'Export')
            <td></td>
            @endif
            <td></td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td style="text-align:right;">{{$currency}}{{$totalTaxable}}</td>
            <td style="text-align:right;">{{$currency}}{{$totalTax}}</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$quotation->subtotal}}</td>
        </tr>
        @if($quotation->discount > 0)
        <tr>
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td></td>
            <td></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td></td>
            @endif
            <td style="text-align:right;">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Discount @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td></td>
            <td style="text-align:right;">Discount</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$quotation->discount}}</td>
        </tr>
        @endif
        @if($quotation->freight > 0)
        <tr class="table">
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td></td>
            <td></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td></td>
            @endif
            <td style="text-align:right;">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Freight @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td></td>
            <td style="text-align:right;">Freight</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$quotation->freight}}</td>
        </tr>
        @endif
        @if($quotation->bank_charges > 0)
        <tr class="table">
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td></td>
            <td></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td></td>
            @endif
            <td style="text-align:right;">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Bank Charges @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td></td>
            <td style="text-align:right;">Bank CHarges</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$quotation->bank_charges}}</td>
        </tr>
        @endif
        @if($quotation->prev_balance > 0)
        <tr class="table">
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td></td>
            <td></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td></td>
            @endif
            <td style="text-align:right;">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Prev Balance @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td></td>
            <td style="text-align:right;">Prev Balance</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$quotation->prev_balance}}</td>
        </tr>
        @endif
        @if($quotation->round != '0.00')
        <tr>
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td></td>
            <td></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td></td>
            @endif
            <td style="text-align:right;">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Round @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td></td>
            <td style="text-align:right;">Round</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$quotation->round}}</td>
        </tr>
        @endif
        <tr>
            <td></td>
            @if($include_hsn_column)
            <td></td>
            @endif
            <td></td>
            <td></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td></td>
            @endif
            <td style="text-align:right;">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Grand Total @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td></td>
            <td style="text-align:right;">Grand Total</td>
            @endif
            <td style="text-align:right;">{{$currency}}{{$quotation->total}}</td>
        </tr>
        @if($quotation->rate_includes_gst && $quotation->type !== 'Export')
        <tr>
            <td @if($include_gst_column) colspan="6" @else colspan="5" @endif>*All Rates are inclusive of GST</td>
        </tr>
        @endif
        @if($quotation->instructions)
        <tr>
            <td>
                <h4>Instructions:</h4>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                {!! $quotation->instructions !!}
            </td>
        </tr>
        @endif
        <tr>
            <td>
                <h4>Terms and Conditions</h4>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                @if($quotation->type !== 'Export')
                <ul>
                    <li>All Local Taxes are Included</li>
                    <li>Payment: <span class="red">100% in Advance</span></li>
                    <li>Forwarding Charges: <span class="blue">@if($quotation->freight > 0) Included @else Extra @endif</span></li>
                </ul>
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="6">
                {!! $quotation->terms !!}
                <p>If you have any queries regarding this quote, please contact <br/> @if($quotation->contact_person) {{$quotation->contact_person}} @else {{$quotation->representative->name}}@endif, {{$quotation->contact_phone}}, {{$quotation->contact_email}}</p>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:center;"><h3>Thank you for your business</h3></td>
        </tr>
        <tr>
            <td colspan="3">
                <img width="200" src="img/gpay_apexion_1.jpg" alt="">
            </td>
            <td colspan="3">
                <img width="200" src="img/apexion_sign.jpg" alt="">
            </td>
        </tr>
    </tbody>
</table>