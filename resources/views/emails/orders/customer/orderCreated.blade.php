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
                src="{{ config('app.url') . '/images/email/orders/icon-pedido-recebido.png' }}"
                alt="Pedido recebido com sucesso!"
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Pedido recebido <br> com sucesso!
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p><strong>Olá {{ $order->user->name }}, tudo bem?</strong></p>
            <p>
                Seu pedido para o <strong>{{ $order->items->implode('product.parentProduct.name') }} foi recebido</strong>.
            </p>

            @if ($order->isPending())
                <p>
                    Agora, para garantir seu acesso, basta <strong>emitir o {{ $order->paymentMethod }} e realizar</strong> o pagamento dentro do prazo. <strong>Não deixe para depois</strong>!
                </p>
                <p>
                    <strong>Prazo para pagamento: {{ $order->payments?->last()?->due_date->format('d/m/Y') }}</strong>.
                    <br>
                    Emita o pagamento agora e finalize sua compra!
                </p>
            @endif
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 0 0 20px 0"
        >
            <a
                href="{{ route('checkout.checkout.thanks', $order->orderHash) }}"
                class="button"
                target="_blank"
                rel="noopener"
                style="font-size: 14px"
            >
                Emitir pagamento
            </a>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            <strong>Qualquer dúvida, estamos <br> aqui para ajudar!</strong>
        </td>
    </tr>
@endsection
