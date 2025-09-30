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
                src="{{ config('app.url') . '/images/email/orders/shop/icon-purchase-cancel.png' }}"
                alt="Oops! A compra não foi aprovada."
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Oops! A compra <br> não foi aprovada.
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p>Olá {{ $order->user->name }}! Tudo bem?</p>
            <p>Recebemos uma <strong>tentativa de compra</strong> de um cliente via {{ $order->paymentMethod }}, mas, <strong>infelizmente, ela não foi aprovada.</strong> Isso pode acontecer por diversos motivos, como <strong>saldo insuficiente</strong> ou informações incorretas.</p>
            <p><strong>Não se preocupe!</strong> Sabemos que essas situações podem ocorrer, e estamos aqui para ajudar.</p>
            <p><strong>Aqui estão algumas dicas para solucionar:</strong></p>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 6% 20px;">
            <table style="background-color: #f4f4f4; border-radius: 16px;">
                <tbody>
                    <tr>
                        <td style="padding: 3%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="https://novo-checkout.suitpay.app/images/email/orders/shop/img-new-sale-01.png"
                                            alt="Entre em contato com seu cliente: Pergunte se ele gostaria de tentar novamente ou se precisa de ajuda com o pagamento."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;"><strong>Entre em contato com seu cliente:</strong> Pergunte se ele gostaria de tentar novamente ou se precisa de ajuda com o pagamento.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="https://novo-checkout.suitpay.app/images/email/orders/shop/img-new-sale-01.png"
                                            alt="Verifique os dados da transação: Às vezes, um pequeno erro pode causar a reprovação."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;"><strong>Verifique os dados da transação:</strong> Às vezes, um pequeno erro pode causar a reprovação.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="https://novo-checkout.suitpay.app/images/email/orders/shop/img-new-sale-01.png"
                                            alt="Considere oferecer uma alternativa: Se possível, sugira outro método de pagamento."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;"><strong>Considere oferecer uma alternativa:</strong> Se possível, sugira outro método de pagamento.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            <p><strong>Vamos juntos garantir que seu cliente tenha <br> uma ótima experiência de compra!</strong></p>
            <p><strong>Caso necessário, não hesite em nos <br> contatar para que possamos ajudar.</strong></p>
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
                Falar com suporte
            </a>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            <strong>Qualquer dúvida, estamos por aqui.</strong>
        </td>
    </tr>
@endsection
