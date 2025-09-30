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
                src="{{ config('app.url') . '/images/email/orders/icon-pagamento-recorrente.png' }}"
                alt="Pagamento recorrente concluído com sucesso!"
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Pagamento recorrente <br> renovado com sucesso!
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p>Olá <strong>{{ $order->user->name }}</strong>, tudo bem?</p>
            <p><strong>Seu pagamento recorrente foi processado com sucesso!</strong> A renovação garante que você continue com acesso ininterrupto ao <strong>{{ $order->items->implode('product.parentProduct.name') }}</strong>.</p><br>
            <p>Próximo pagamento agendado para: <br><strong>{{ $product->nextCharge()->format('d/m/Y') }}</strong>.</p><br>
            <p><strong>Estamos felizes em fazer parte dessa jornada com você</strong>. Caso tenha dúvidas ou precise de algo, é <strong>só entrar em contato conosco</strong>.</p>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            <strong>Obrigado por confiar no SuitSales!</strong>
        </td>
    </tr>
@endsection
