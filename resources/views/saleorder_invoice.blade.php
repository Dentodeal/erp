<table>
    <tbody>
        <tr>
            <td class="vertical-top cyan">
                <b>Apexion Dental Products and Services</b><br/>
                <i class="text-p cyan2">Smile Delighted</i>
            </td>
            <td colspan="2" class="text-right vertical-top">
                <h2>Invoice</h2>
            </td>
        </tr>
        <tr>
            <td class="vertical-top" rowspan="2">
                <p>Ground Floor P.K.Complex, Pushpa Junction,<br/>
                Kozhikode, Kerala, PIN: 673002<br/>
                Tel: 0495-4050453<br/>
                GSTIN: 32AASFA2560M1Z9
                </p>
            </td>
            <td class="width-10 nowrapspace text-right no-padding">Date:</td>
            <td class="width-1 nowrapspace no-padding">{{ Carbon\Carbon::parse($saleorder->created_at)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="text-right no-padding">Serial No:</td>
            <td class="no-padding">{{ $saleorder->serial_no }}</td>
        </tr>
        <tr>
            <td class="vertical-top">
                <h3 class="mb5">Customer</h3>
                <p class="mt0"><b>{{$saleorder->customer->name}}</b></p>
                <p>{{$address}}</p>
            </td>
            <td colspan="2">
            </td>
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
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td class="table width-5 nowrapspace">GST</td>
            @endif
            <td class="table width-10 nowrapspace">Rate</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td class="table width-5 nowrapspace">Taxable</td>
            <td class="table width-10 nowrapspace">Tax</td>
            @endif
            <td class="table width-5 nowrapspace">Total</td>
        </tr>
        @foreach($saleorder->items as $item)
        <tr class="table">
            <td class="table text-center">{{$loop->index+1}}</td>
            <td class="table">{{$use_mask_name ? ($item->use_mask ? $item->mask_name : $item->product->name) : $item->product->name}}</td>
            @if($include_hsn_column)
            <td class="table">{{$item->product ? $item->product->hsn : ''}}</td>
            @endif
            <td class="table text-right">{{$item->qty}}</td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td class="table text-right">{{$item->gst}}%</td>
            @endif
            <td class="table text-right">{{$currency}}{{$item->rate}}</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
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
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right"></td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td class="table text-right">{{$totalTaxable}}</td>
            <td class="table text-right">{{$totalTax}}</td>
            @endif
            <td class="table text-right">{{$currency}}{{$saleorder->subtotal}}</td>
        </tr>
        @if($saleorder->discount > 0)
        <tr class="table">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table"></td>
            <td class="table"></td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right">@if($saleorder->rate_includes_gst && $saleorder->type !== 'Export') Discount @endif</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td class="table"></td>
            <td class="table text-right">Discount</td>
            @endif
            <td class="table text-right">{{$currency}}{{$saleorder->discount}}</td>
        </tr>
        @endif
        @if($saleorder->freight > 0)
        <tr class="table">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table"></td>
            <td class="table"></td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right">@if($saleorder->rate_includes_gst && $saleorder->type !== 'Export') Freight @endif</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td class="table"></td>
            <td class="table text-right">Freight</td>
            @endif
            <td class="table text-right">{{$currency}}{{$saleorder->freight}}</td>
        </tr>
        @endif
        @if($saleorder->bank_charges > 0)
        <tr class="table">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table"></td>
            <td class="table"></td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right">@if($saleorder->rate_includes_gst && $saleorder->type !== 'Export') Bank Charges @endif</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td class="table"></td>
            <td class="table text-right">Bank Charges</td>
            @endif
            <td class="table text-right">{{$currency}}{{$saleorder->bank_charges}}</td>
        </tr>
        @endif
        @if($saleorder->round != '0.00')
        <tr class="table">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table"></td>
            <td class="table"></td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right">@if($saleorder->rate_includes_gst && $saleorder->type !== 'Export') Round @endif</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td class="table"></td>
            <td class="table text-right">Round</td>
            @endif
            <td class="table text-right">{{$currency}}{{$saleorder->round}}</td>
        </tr>
        @endif
        <tr class="table">
            <td class="table"></td>
            @if($include_hsn_column)
            <td class="table"></td>
            @endif
            <td class="table"></td>
            <td class="table"></td>
            @if($include_gst_column && $saleorder->type !== 'Export')
            <td class="table"></td>
            @endif
            <td class="table text-right">@if($saleorder->rate_includes_gst && $saleorder->type !== 'Export') Grand Total @endif</td>
            @if(!$saleorder->rate_includes_gst && $saleorder->type !== 'Export')
            <td class="table"></td>
            <td class="table text-right">Grand Total</td>
            @endif
            <td class="table text-right">{{$currency}}{{$saleorder->total}}</td>
        </tr>
        @if($saleorder->rate_includes_gst && $saleorder->type !== 'Export')
        <tr class="table">
            <td class="table" @if($include_gst_column) colspan="6" @else colspan="5" @endif>*All Rates are inclusive of GST</td>
        </tr>
        @endif
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