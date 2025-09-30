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
            <p>Tentamos processar seu pagamento via {{ $order->paymentMethod }}, <strong>mas ele foi recusado</strong>.</p>
            <p><strong>Isso pode acontecer por alguns motivos</strong>, como saldo insuficiente, dados incorretos ou alguma restrição no seu banco.</p>
            <p><strong>Mas fica tranquilo!</strong> Você pode tentar novamente agora mesmo.</p>
        </td>
    </tr>
    <tr>
        <td style="padding: 0 6% 20px;">
            <h4 style="text-align: center;">O que fazer?</h4>
            <table style="background-color: #f4f4f4; border-radius: 16px; width: 100%;">
                <tbody>
                    <tr>
                        <td style="padding: 3%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        1.
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Verifique se os <strong>dados do pagamento</strong> estão corretos</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        2.
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;"><strong>Confirme com seu banco</strong> se está tudo certo.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        3.
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Tente outra <strong>forma de pagamento</strong>.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>

    @isset($order->items->last()->product->code)
        <tr>
            <td align="center">
                <p><strong>Refaça seu pagamento agora:</strong></p>
            </td>
        </tr>
        <tr>
            <td
                align="center"
                style="padding: 20px 0"
            >
                <a
                    href="{{ route('checkout.checkout.product', $order->items->last()->product->code) }}"
                    class="button"
                    target="_blank"
                    rel="noopener"
                    style="font-size: 14px"
                >
                    Tentar novamente
                </a>
            </td>
        </tr>
    @endisset

    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            Se precisar de ajuda, <br> estamos aqui para te apoiar!
        </td>
    </tr>
@endsection
