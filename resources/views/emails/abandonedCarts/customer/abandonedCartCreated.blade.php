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
            src="{{ config('app.url') . '/images/email/abandonedCarts/customer/icon-carrinho-abandonado-1.png' }}"
            alt="Tá precisando de um empurrãozinho?" />

        <h1 style="margin: 40px 0 0; text-transform: uppercase">
            Tá precisando de um <br> empurrãozinho?
        </h1>

    </td>
</tr>
<tr>
    <td style="padding: 20px 8% 0; text-align: center; font-size: 14px; line-height: 20px;">
        <p><strong><span style="font-size: 18px;">Olá</span> <span style="text-transform: capitalize; font-size: 16px; color:#33cc33;" ;> {{ $abandonedCart->name }}</span>,
                <br> tudo bem?</strong></p>
        <p><strong>Notamos que você estava prestes a concluir a compra do {{ $product['name'] }}</strong> e queremos garantir que você tenha a melhor experiência possível.</p>
        <p>Se precisar de mais informações ou de um empurrãozinho extra para finalizar essa compra, estamos aqui para ajudar!</p>
    </td>
</tr>
<tr>
    <td
        align="center"
        style="padding: 0 0 20px 0">
        <p><strong>Acesse agora:</strong></p>
        <a
            href="{{$abandonedCart->link_checkout }}"
            class="button"
            target="_blank"
            rel="noopener"
            style="font-size: 14px"
            clicktracking="off">
            Aproveitar
        </a>
    </td>
</tr>
<tr>
    <td
        align="center"
        style="padding: 0 0 40px">
        <strong>Estamos aqui para qualquer dúvida.</strong>
    </td>
</tr>
@endsection