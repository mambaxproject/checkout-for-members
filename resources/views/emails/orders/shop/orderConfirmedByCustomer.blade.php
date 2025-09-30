@extends('layouts.email')

@section('content')
    <tr>
        <td style="
                padding: 130px 0 0;
                text-align: center;
                background-color: #ffffff;
                background-image: url({{ config('app.url') . '/images/email/background.png' }});
                background-repeat: no-repeat;
                background-position: center top
            ">

            <img
                width="180px"
                src="{{ config('app.url') . '/images/email/orders/shop/icon-new-sale.png' }}"
                alt="Nova venda! Pagamento confirmado pelo cliente."
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Nova venda! Pagamento <br> confirmado pelo cliente.
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p><strong>Parabéns!</strong> Seu cliente acabou de realizar uma compra através do [PAYMENT_METHOD]. Já <strong>processamos o pagamento</strong> e já está tudo em ordem em sua conta SuitPay.</p>
            <p><strong>Acompanhe as suas <br> vendas na plataforma:</strong></p>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 20px 0"
        >
            <a
                href="#"
                class="button"
                target="_blank"
                rel="noopener"
                style="font-size: 14px"
            >
                Acessar agora
            </a>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            <strong>Estamos à disposição <br> para qualquer suporte.</strong>
        </td>
    </tr>
@endsection
