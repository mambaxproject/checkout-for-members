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
                src="{{ config('app.url') . '/images/email/products/icon-product-updated.png' }}"
                alt="Atualizações salvas com sucesso!"
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Atualizações <br> salvas com sucesso!
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p><strong>Ótimas notícias:</strong> seu {{ $product->name }} foi editado com sucesso! Manter seu conteúdo <strong>sempre atualizado</strong>, com os ajustes necessários é essencial para o <strong>sucesso no digital</strong>. Continue refinando sua <strong>oferta e garanta</strong> que seus clientes tenham a melhor experiência possível.</p>
            <p><strong>Dê uma olhadinha nas edições:</strong></p>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 20px 0"
        >
            <a
                href="{{ route('dashboard.products.edit', ['productUuid' => $product->client_product_uuid]) }}"
                class="button"
                target="_blank"
                rel="noopener"
                style="font-size: 14px"
            >
                Ver produto
            </a>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            <strong>Se precisar de ajuda, estamos <br> sempre à disposição!</strong>
        </td>
    </tr>
@endsection
