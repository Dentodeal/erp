<table>
    <tbody>
        <tr>
            <td class="vertical-top cyan">
                <b>Apexion Dental Products and Services</b><br/>
                <i class="text-p cyan2">Smile Delighted</i>
            </td>
            <td colspan="2" class="text-right vertical-top">
                @if($quotation->type == 'Export')
                <h2>Proforma Invoice</h2>
                @else
                <h2>Quotation</h2>
                @endif
            </td>
        </tr>
        <tr>
            <td class="vertical-top" rowspan="3">
                <p>Ground Floor P.K.Complex, Pushpa Junction,<br/>
                Kozhikode, Kerala, PIN: 673002<br/>
                Tel: 0495-4050453<br/>
                GSTIN: 32AASFA2560M1Z9
                </p>
            </td>
            <td class="width-10 nowrapspace text-right no-padding">Date:</td>
            <td class="width-1 nowrapspace no-padding">{{ Carbon\Carbon::parse($quotation->created_at)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="text-right no-padding">Serial No:</td>
            <td class="no-padding">{{ $quotation->serial_no }}</td>
        </tr>
        <tr>
            <td class="text-right no-padding">Valid Until:</td>
            <td class="no-padding">
                {{ Carbon\Carbon::parse($quotation->valid_until)->format('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <td class="vertical-top">
                <h3 class="mb5">Quotation for:</h3>
                <p class="mt0"><b>{{$quotation->customer->name}}</b></p>
                <p>{{$address}}</p>
            </td>
            <td colspan="2">
                @if($quotation->bank)
                <p>
                    <b>Bank Details:</b><br/>
                    @if(array_key_exists('acc_name',$quotation->bank) && $quotation->bank['acc_name'])
                        Name: {{$quotation->bank['acc_name']}}<br/>
                    @endif
                    @if(array_key_exists('bank_name',$quotation->bank) && $quotation->bank['bank_name'])
                        Bank: {{$quotation->bank['bank_name']}}<br/>
                    @endif
                    @if(array_key_exists('acc_no',$quotation->bank) && $quotation->bank['acc_no'])
                        A/c No: {{$quotation->bank['acc_no']}}<br/>
                    @endif
                    @if(array_key_exists('ifsc',$quotation->bank) && $quotation->bank['ifsc'])
                        IFSC: {{$quotation->bank['ifsc']}}<br/>
                    @endif
                    @if(array_key_exists('upi',$quotation->bank) && $quotation->bank['upi'])
                        UPI: {{$quotation->bank['upi']}}<br/>
                    @endif
                    @if(array_key_exists('bank_address',$quotation->bank) && $quotation->bank['bank_address'])
                        UPI: {{$quotation->bank['bank_address']}}<br/>
                    @endif
                    @if(array_key_exists('swift',$quotation->bank) && $quotation->bank['swift'])
                        UPI: {{$quotation->bank['swift']}}<br/>
                    @endif
                @else
                <p>
                    <b>Bank Details:</b><br/>
                    Name: Apexion Dental Products and Services<br/>
                    Bank: ICICI Bank Ltd.<br/>
                    A/c No: 060305500009<br/>
                    IFSC: ICIC0000603<br/>
                    UPI: apexiondental@icici
                </p>
                @endif
            </td>
        </tr>
    </tbody>
</table>
<table class="table">
    <tbody>
        <tr class="table bg-grey text-center">
            <td class="table">Sale Person</td>
            <td class="table"> P.O Number</td>
            <td class="table">Inco Term</td>
            <td class="table">Shipping Point</td>
            <td class="table">Ship Via</td>
            <td class="table">Ship Date</td>
        </tr>
        <tr class="table text-center">
            <td class="table">{{$quotation->representative->name}}</td>
            <td class="table">{{$quotation->po_number}}</td>
            <td class="table">{{$quotation->inco_term}}</td>
            <td class="table">{{$quotation->fob_point}}</td>
            <td class="table">{{$quotation->ship_via}}</td>
            <td class="table">{{\Carbon\Carbon::parse($quotation->ship_date)->format('d/m/Y')}}</td>
        </tr>
    </tbody>
</table>

<table class="table mt20">
    <tbody>
        <tr class="table bg-dark white text-center">
            <td class="table width-5 nowrapspace">#</td>
            <td class="table" >Product</td>
            @if($include_hsn_column)
            <td class="table">HSN</td>
            @endif
            <td class="table width-5 nowrapspace">Qty</td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td class="table width-5 nowrapspace">GST</td>
            @endif
            <td class="table width-10 nowrapspace">Rate</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td class="table width-5 nowrapspace">Taxable</td>
            <td class="table width-10 nowrapspace">Tax</td>
            @endif
            <td class="table width-5 nowrapspace">Total</td>
        </tr>
        @foreach($quotation->items as $item)
        <tr class="table">
            <td class="table text-center">{{$loop->index+1}}</td>
            <td class="table">{{$use_mask_name ? ($item->use_mask ? $item->mask_name : $item->product_name) : $item->product_name}}</td>
            @if($include_hsn_column)
            <td class="table">{{$item->product ? $item->product->hsn : ''}}</td>
            @endif
            <td class="table text-right">{{$item->qty}}</td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td class="table text-right">{{$item->gst}}%</td>
            @endif
            <td class="table text-right">{{$currency}}{{$item->rate}}</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td class="table text-right">{{$currency}}{{$item->taxable}}</td>
            <td class="table text-right">{{$currency}}{{$item->tax_amount}}</td>
            @endif
            <td class="table text-right">{{$currency}}{{$item->total}}</td>
        </tr>
        @endforeach
        <tr class="table bg-white dark">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table text-right"><b>Total</b></td>
            <td class="table text-right">{{$totalQty}}</td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right"></td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td class="table text-right">{{$currency}}{{$totalTaxable}}</td>
            <td class="table text-right">{{$currency}}{{$totalTax}}</td>
            @endif
            <td class="table text-right">{{$currency}}{{$quotation->subtotal}}</td>
        </tr>
        @if($quotation->discount > 0)
        <tr class="table">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table"></td>
            <td class="table"></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Discount @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td class="table"></td>
            <td class="table text-right">Discount</td>
            @endif
            <td class="table text-right">{{$currency}}{{$quotation->discount}}</td>
        </tr>
        @endif
        @if($quotation->freight > 0)
        <tr class="table">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table"></td>
            <td class="table"></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Freight @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td class="table"></td>
            <td class="table text-right">Freight</td>
            @endif
            <td class="table text-right">{{$currency}}{{$quotation->freight}}</td>
        </tr>
        @endif
        @if($quotation->bank_charges > 0)
        <tr class="table">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table"></td>
            <td class="table"></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Bank Charges @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td class="table"></td>
            <td class="table text-right">Bank Charges</td>
            @endif
            <td class="table text-right">{{$currency}}{{$quotation->bank_charges}}</td>
        </tr>
        @endif
        @if($quotation->prev_balance > 0)
        <tr class="table">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table"></td>
            <td class="table"></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Prev Balance @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td class="table"></td>
            <td class="table text-right">Prev Balance</td>
            @endif
            <td class="table text-right">{{$currency}}{{$quotation->prev_balance}}</td>
        </tr>
        @endif
        @if($quotation->round != '0.00')
        <tr class="table">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table"></td>
            <td class="table"></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Round @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td class="table"></td>
            <td class="table text-right">Round</td>
            @endif
            <td class="table text-right">{{$currency}}{{$quotation->round}}</td>
        </tr>
        @endif
        <tr class="table">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table"></td>
            <td class="table"></td>
            @if($include_gst_column && $quotation->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right">@if($quotation->rate_includes_gst && $quotation->type !== 'Export') Grand Total @endif</td>
            @if(!$quotation->rate_includes_gst && $quotation->type !== 'Export')
            <td class="table"></td>
            <td class="table text-right">Grand Total</td>
            @endif
            <td class="table text-right">{{$currency}}{{$quotation->total}}</td>
        </tr>
        @if($quotation->rate_includes_gst && $quotation->type !== 'Export')
        <tr class="table">
            <td class="table" @if($include_gst_column) colspan="6" @else colspan="5" @endif>*All Rates are inclusive of GST</td>
        </tr>
        @endif
    </tbody>
</table>
<table class="mt5">
    <tbody>
        <tr>
            <td class="vertical-top">
                @if($quotation->instructions)
                <h4>Instructions:</h4>
                <div class="pl15">
                    {!! $quotation->instructions !!}
                </div>
                @endif
                <h4>Terms and Conditions</h4>
                @if($quotation->type !== 'Export')
                <ul>
                    <li>All Local Taxes are Included</li>
                    <li>Payment: <span class="red">100% in Advance</span></li>
                    <li>Forwarding Charges: <span class="blue">@if($quotation->freight > 0) Included @else Extra @endif</span></li>
                </ul>
                @endif
                <div class="pl15">
                    {!! $quotation->terms !!}
                </div>
                <p>If you have any queries regarding this quote, please contact <br/> @if($quotation->contact_person) {{$quotation->contact_person}} @else {{$quotation->representative->name}}@endif, {{$quotation->contact_phone}}, {{$quotation->contact_email}}</p>
            </td>
            <td class="text-center">
                <p>Scan here for payment</p>
                <img width="200" src="img/gpay_apexion_1.jpg" alt="">
            </td>
            <td class="text-center">
                <img width="200" src="img/apexion_sign.jpg" alt="">
            </td>
        </tr>
    </tbody>
</table>
<table class="mt5">
    <tbody>
        <tr>
            <td class="text-center"><h3>Thank you for your business</h3></td>
        </tr>
    </tbody>
</table>
<style>
h1,h2,h3,h4{
    margin:0;
}
table{
    width:100%;
    border-collapse: collapse;
}
th,td,p,div{
    /*padding:5px 2px;*/

    font-size:11px;
}
.table th, .table td{
    padding:4px 2px;
}
.table, .th, .td {
  border: 1px solid #444    ;
  border-collapse: collapse;
}
.vertical-top{
    vertical-align:top;
}
.cyan{
    color:#008060;
}
.text-right{
    text-align:right;
}
.text-center{
    text-align:center;
}
.text-p{
    font-size:12px;
}
.cyan2{
    color:#004d39;
}
.width-10{
    width:10%;
}
.width-5{
    width:5%;
}
.width-1{
    width:1%;
}
.nowrapspace{
    white-space: nowrap;
}
.no-padding{
    padding:0;
}
.mb5{
    margin-bottom: 5px;
}
.mt0{
    margin-top: 0;
}
.mt20{
    margin-top:20px;
}
.mt5{
    margin-top: 5px;
}
.bg-grey{
    background-color: #ddd;
}
.bg-dark{
    background-color: #444;
}
.bg-white{
    background: #fff;
}
.dark{
    color:#444;
}
.red{
    color:#ff0000
}
.blue{
    color:rgb(9, 73, 133);
}
.white{
    color:#fff;
}
.pl15{
    padding-left: 15px;
}
</style>