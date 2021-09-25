<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  </head>
  <body>
    <table style="width: 100%;">
      <tbody>
        <tr>
          <td class="logo" colspan="2" style="@if($sale_order->source == 'Dentodeal')background-color: #000; height: 100px; @else height:150px; @endif vertical-align: middle; text-align: center;">
            @if($sale_order->source == 'Dentodeal')
              <img src="img/dentodeal.png" alt="" style="width: 80%;">
            @else
              <img src="img/apexion.png" alt="" style="width: 80%;">
            @endif
          </td>
        </tr>
        <tr>
          <td style="vertical-align: top; padding-left: 10px;">
            <h3>From</h3>
            <p>
              Apexion Dental Products & Services<br>
              Ground Floor, P.K. Complex, Pushpa Junction<br>
              Kozhikode, PIN:673002, Kerala, India.<br>
              Tel: 0495 40 50 453
            </p>
          </td>
          <td style="vertical-align: top; padding-left: 10px;">
            <h3>To</h3>
            <p>
              @if(strpos($sale_order->customer->name,'-'))
                {{substr($sale_order->customer->name,0,strpos($sale_order->customer->name,'-'))}}<br>
              @else 
                {{$sale_order->customer->name}}<br>
              @endif
              {{$shipping_address}}
            </p>
        </tr>
        <tr>
          <td style="vertical-align: top; padding-left: 10px;">
            <h3 style="text-align: center; text-decoration: underline;">Order Details</h3>
            <p>
              Order No: {{$sale_order->source == 'Dentodeal'? $sale_order->order_no : $sale_order->serial_no}}<br>
              Bill Amount: <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$sale_order->total}} /-<br>
              Authorized By:
            </p>
          </td>
          <td style=" vertical-align: middle; text-align: center;">
            <img src="img/qr.png" alt="">
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>

<style>
    @page { margin: 0px; }
    html, body {
      margin: 10px; 
      height:100%;
      font-family: 'Helvetica';
    }
    table, th, td {
      border: 1px solid #000 ;
      border-collapse: collapse;
    }
</style>