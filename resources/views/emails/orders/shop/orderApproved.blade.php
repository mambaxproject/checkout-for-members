@extends('layouts.email')

@section('content')
    <tr>
        <td
            style="
                padding: 130px 0 0;
                text-align: center;
                background-color: #ffffff;
                background-image: url({{ config('app.url') . '/images/email/background.png' }});
                background-repeat: no-repeat;
                background-position: center top
            ">

            <img width="180px" src="{{ config('app.url') . '/images/email/orders/shop/icon-sale-made.png' }}"
                alt="Nova venda! Pagamento confirmado pelo cliente" />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Nova venda! Pagamento <br> confirmado pelo cliente.
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p>Parabéns! Seu cliente <strong>{{ $order->user->name }}</strong> acabou de realizar a compra do produto
                <strong>{{ $order->items->implode('product.parentProduct.name', ', ') }}</strong> através do
                <strong>{{ $order->payment_method }}</strong>
                no valor de <strong> R$ {{ number_format($order->first_amount, 2, ',', '.') }}</strong>.<br>Já processamos o
                pagamento e está tudo em ordem em sua conta SuitPay.</p>
            <p><strong>Acompanhe todas vendas <br> na plataforma:</strong></p>
        </td>
    </tr>
    <tr>
        <td align="center" style="padding: 20px 0">
            <a href="{{ route('dashboard.home.index') }}" class="button" target="_blank" rel="noopener"
                style="font-size: 14px">
                Acessar agora
            </a>
        </td>
    </tr>
    <tr>
        <td align="center" style="padding: 0 0 40px">
            <strong>Se precisar de qualquer <br> coisa, conte conosco.</strong>
        </td>
    </tr>
@endsection
