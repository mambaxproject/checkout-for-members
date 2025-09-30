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
                alt="Tudo certo com o seu pagamento!"
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Tudo certo com <br> o seu pagamento!
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8% 0; text-align: center; font-size: 14px; line-height: 20px;">
            <p><strong>Olá {{ $order->user->name }}, tudo bem?</strong></p>
            <p><strong>Parabéns!</strong> O pagamento do seu pedido foi confirmado com sucesso. Agora, <strong>você já pode acessar o {{ $product->name }}</strong> e aproveitar tudo o que <strong>preparamos para você</strong>.</p>
        </td>
    </tr>

    @if ($linkAccess)
        <tr>
            <td
                align="center"
                style="padding: 0 0 20px 0"
            >
                <p><strong>Acesse agora:</strong></p>
                <a
                    href="{{ $linkAccess }}"
                    class="button"
                    target="_blank"
                    rel="noopener"
                    style="font-size: 14px"
                >
                    Link do acesso
                </a>
            </td>
        </tr>
    @endif

    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            <strong>Aproveite a jornada e, se precisar de qualquer ajuda, <br> nossa equipe está pronta para apoiar você.</strong>
        </td>
    </tr>
@endsection
