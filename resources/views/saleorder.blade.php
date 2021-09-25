<h2 class="text-center">Sale Order</h2>
<table class="table mt-20">
    <tr class="table">
        <td class="table">
            <p><b>Customer</b>: {{$sale_order->customer->name}}</p>
            <p><b>Representative</b>: {{$sale_order->representative->name}}</p>
            <p><b>Created By</b>: {{$sale_order->created_by->name}}</p>
            @if($sale_order->cod)
            <p><b>COD:</b> Yes</p>
            @endif
        </td>
        <td class="table text-center" >
            <h4>Serial No: {{$sale_order->serial_no}}</h4>
            <p>Created At: {{ Carbon\Carbon::parse($sale_order->created_at)->format('d/m/Y') }}</p>
            <p>Source: {{$sale_order->source}}</p>
            @if($sale_order->source === 'Dentodeal')
            <p>Dentodeal Order No: {{$sale_order->order_no}}</p>
            @endif
        </td>
    </tr>
</table>

<table class="table mt-20" >
    <tbody>
        <tr class="table" style="background-color: #ddd;">
            <td class="table">#</td>
            <td class="table">Product</td>
            <td class="table">Qty</td>
            <td class="table">Exp</td>
            <td class="table">Rate</td>
            @if(!$sale_order->rate_includes_gst)
            <td class="table">Taxable</td>
            <td class="table">Tax</td>
            @endif
            <td class="table">Total</td>
        </tr>
        @foreach($sale_order->items as $item)
        <tr class="table">
            <td class="table">{{$loop->index+1}}</td>
            <td class="table">{{$item->product->name}}</td>
            <td class="table" style="text-align: right;">{{$item->qty}}</td>
            <td class="table" style="text-align: right;">{{$item->expiry_date}}</td>
            <td class="table" style="text-align: right;">{{$item->rate}}</td>
            @if(!$sale_order->rate_includes_gst)
            <td class="table" style="text-align: right;">{{$item->taxable}}</td>
            <td class="table" style="text-align: right;">{{$item->tax_amount}}</td>
            @endif
            <td class="table" style="text-align: right;">{{$item->total}}</td>
        </tr>
        @endforeach
        <tr class="table">
            <td class="table"></td>
            <td class="table">Total Qty</td>
            <td class="table" style="text-align: right;">{{$totalQty}}</td>
            <td class="table"></td>
            <td class="table">Subtotal</td>
            @if(!$sale_order->rate_includes_gst)
            <td class="table" style="text-align: right;">{{$totalTaxable}}</td>
            <td class="table" style="text-align: right;">{{$totalTax}}</td>
            @endif
            <td class="table" style="text-align: right;">{{$sale_order->subtotal}}</td>
        </tr>
        <tr class="table">
            <td class="table"></td>
            <td class="table"></td>
            <td class="table"></td>
            <td class="table"></td>
            <td class="table">@if($sale_order->rate_includes_gst) Discount @endif</td>
            @if(!$sale_order->rate_includes_gst)
            <td class="table"></td>
            <td class="table">Discount</td>
            @endif
            <td class="table" style="text-align: right;">{{$sale_order->discount}}</td>
        </tr>
        <tr class="table">
            <td class="table"></td>
            <td class="table"></td>
            <td class="table"></td>
            <td class="table"></td>
            <td class="table">@if($sale_order->rate_includes_gst) Freight @endif</td>
            @if(!$sale_order->rate_includes_gst)
            <td class="table"></td>
            <td class="table">Freight</td>
            @endif
            <td class="table" style="text-align: right;">{{$sale_order->freight}}</td>
        </tr>
        @if($sale_order->cod)
        <tr class="table">
            <td class="table"></td>
            <td class="table"></td>
            <td class="table"></td>
            <td class="table"></td>
            <td class="table">@if($sale_order->rate_includes_gst) COD Charge @endif</td>
            @if(!$sale_order->rate_includes_gst)
            <td class="table"></td>
            <td class="table">COD Charge</td>
            @endif
            <td class="table" style="text-align: right;">{{$sale_order->cod_charge}}</td>
        </tr>
        @endif
        <tr class="table">
            <td class="table"></td>
            <td class="table"></td>
            <td class="table"></td>
            <td class="table"></td>
            <td class="table">@if($sale_order->rate_includes_gst) Round @endif</td>
            @if(!$sale_order->rate_includes_gst)
            <td class="table"></td>
            <td class="table">Round</td>
            @endif
            <td class="table" style="text-align: right;">{{$sale_order->round}}</td>
        </tr>
        <tr class="table">
            <td class="table"></td>
            <td class="table"></td>
            <td class="table"></td>
            <td class="table"></td>
            <td class="table">@if($sale_order->rate_includes_gst) Total @endif</td>
            @if(!$sale_order->rate_includes_gst)
            <td class="table"></td>
            <td class="table">Total</td>
            @endif
            <td class="table" style="text-align: right;">{{$sale_order->total}}</td>
        </tr>
        @if($sale_order->rate_includes_gst)
        <tr class="table">
            <td class="table" colspan="6">*All Rates are inclusive of GST</td>
        </tr>
        @endif
    </tbody>
</table>
<h4 id="" style="text-align:center;margin-top:20px;">Remarks</h4>
<table class="table" style="width:100%;margin-top:10px;">
    <tbody>
        <tr class="table">
            <td>{!! $sale_order->remarks !!}</td>
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

    font-size:16px;
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