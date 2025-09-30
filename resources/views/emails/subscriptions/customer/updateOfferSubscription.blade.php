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
                src="{{ config('app.url') . '/images/email/orders/icon-pagamento-confirmado.png' }}"
                alt="Atualiza os dados da sua assinatura"
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Atualize sua assinatura do produto {{ $order->item->product->parentProduct->name }}
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8% 0; text-align: center; font-size: 14px; line-height: 20px;">
            <p><strong>Olá {{ $order->user->name }}, tudo bem?</strong></p>
            <p>
                Clique no botão abaixo para atualizar o plano da sua assinatura do produto <strong>{{ $order->item->product->parentProduct->name }}</strong>.
            </p>
        </td>
    </tr>

    <tr>
        <td
            align="center"
            style="padding: 0 0 20px 0"
        >
            <p><strong>Clique no botão abaixo para acessar a área de atualização</strong></p>
            <a
                href="{{ $linkCustomerUpdateSubscription }}"
                class="button"
                target="_blank"
                rel="noopener"
                style="font-size: 14px"
            >
                Link de atualização da assinatura
            </a>
        </td>
    </tr>

    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            <strong>Aproveite a jornada e, se precisar de qualquer ajuda, <br> nossa equipe está pronta para apoiar você.</strong>
        </td>
    </tr>
@endsection
