<h2 class="text-center">{{$data['name']}}</h2>
<table class="table mt-20" >
    <tbody>
        @foreach($data['items'] as $index => $item)
        <tr class="table">
            <td class="table">{{$index + 1}}</td>
            <td class="table">{{$item->name}}</td>
        </tr>
        @endforeach
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