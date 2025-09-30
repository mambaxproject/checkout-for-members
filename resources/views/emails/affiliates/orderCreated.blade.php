@extends('layouts.email')
@section('content')
<h2
    style="
margin: 0;
line-height: 29px;
mso-line-height-rule: exactly;
font-family: Poppins,
sans-serif;
font-size: 18px;
font-style: normal;
font-weight: normal;
color: #3d405b;
"
>
    Você tem uma nova venda!
    <br>
    Recebemos o pedido #{{$order->client_orders_uuid}}, para o evento {{ $order->items->implode('product.name') }},
    e estamos processando o pagamento!
    <br>
    Confira abaixo os detalhes do pedido:
</h2>
<br><br>
<table
    style="
    margin: 0;
    line-height: 29px;
    mso-line-height-rule: exactly;
    font-family: Poppins,
    sans-serif;
    font-size: 18px;
    font-style: normal;
    font-weight: normal;
    color: #3d405b;
    border-spacing: 20em !important;
    border-collapse: collapse !important;
    "
    >
    <tr
        style="
        border-bottom: 1px solid
        #ead5ee;
        "
        >
        <td>Número do pedido</td>
        <td style="text-align: right">
            <strong>{{$order->client_orders_uuid}}</strong>
        </td>
    </tr>
    <tr>
        <td
            aria-hidden="true"
            height="10"
            style="
            font-size: 0;
            line-height: 0px;
            "
            >
            &nbsp;
        </td>
    </tr>
    <tr
        style="
        border-bottom: 1px solid
        #ead5ee;
        "
        >
        <td>Método de pagamento</td>
        <td style="text-align: right">
            <strong>Cartão de crédito</strong>
        </td>
    </tr>
    <tr>
        <td
            aria-hidden="true"
            height="10"
            style="
            font-size: 0;
            line-height: 0px;
            "
            >
            &nbsp;
        </td>
    </tr>
    <tr
        style="
        border-bottom: 1px solid
        #ead5ee;
        "
        >
        <td>Valor</td>
        <td style="text-align: right">
            <strong>{{$order->total}}</strong>
        </td>
    </tr>
</table>
@endsection
@section('action')
<a
    href="{{ route('site.payment.thanks', $order) }}"
    class="es-button"
    target="_blank"
    style="
    mso-style-priority: 100 !important;
    text-decoration: none;
    -webkit-text-size-adjust: none;
    -ms-text-size-adjust: none;
    mso-line-height-rule: exactly;
    color: #ffffff;
    font-size: 18px;
    padding: 15px 35px 15px 35px;
    display: inline-block;
    background: #6e17b0;
    border-radius: 10px;
    font-family: Poppins,
    sans-serif;
    font-weight: bold;
    font-style: normal;
    line-height: 22px;
    width: auto;
    text-align: center;
    "
    >Ver detalhes da compra</a
    >
@endsection
