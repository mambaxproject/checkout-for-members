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

            <img width="180px" src="{{ config('app.url') . '/images/email/client/icon-access-granted.png' }}"
                alt="Acesso liberado ao cliente" />


            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                🔓 ACESSO LIBERADO<br>PARA O CLIENTE!
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p>Olá, <strong>{{ $order->shop->name }}</strong>! Tudo bem?</p>
            <p>Temos novidade: O cliente <strong>{{ $order->user->name }}</strong> confirmou a compra do produto
                <strong>{{ $order->items->implode('product.parentProduct.name', ', ') }}</strong>
                no valor de:<strong> R$ {{ number_format($order->first_amount, 2, ',', '.') }} </strong>Agora ele(a) já
                possui acesso imediato ao conteúdo exclusivo.</p>
            <p><strong>👉 Acompanhe tudo pelo seu painel de controle:<br> relatórios de vendas, avaliações e feedbacks para
                    continuar aprimorando <br>sua oferta.</strong></p>
        </td>
    </tr>
    <tr>
        <td align="center" style="padding: 20px 0">
            <a href="{{ route('dashboard.products.index') }}" class="button" target="_blank" rel="noopener"
                style="font-size: 14px">
                Acessar agora
            </a>
        </td>
    </tr>
    <tr>
        <td align="center" style="padding: 0 0 40px">
            <strong>Se precisar de qualquer coisa, <br> nossa equipe está à disposição.</strong>
        </td>
    </tr>
@endsection
