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
                    src="{{ config('app.url') . '/images/email/orders/icon-link-telegram.png' }}"
                    alt="Link do Telegram!"
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Aqui está seu <br> link de acesso!
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p><strong>Olá {{ $order->user->name }}, tudo bem?</strong></p>
            <p>
                Seu link de acesso ao grupo do telegram já está disponível.
            </p>
        </td>
    </tr>
    @foreach($order->telegramGroupMembers as $member)
        <tr>
            <td
                    align="center"
                    style="padding: 0 0 20px 0"
            >
                <a
                        href="{{ $member->invite_link }}"
                        class="button"
                        target="_blank"
                        rel="noopener"
                        style="font-size: 14px"
                >
                    Entrar no grupo ({{$member->telegramGroup->name}})
                </a>
            </td>
        </tr>
    @endforeach
    <tr>
        <td
                align="center"
                style="padding: 0 0 40px"
        >
            <strong>Qualquer dúvida, estamos <br> aqui para ajudar!</strong>
        </td>
    </tr>
@endsection
