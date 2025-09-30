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
                src="{{ config('app.url') . '/images/email/products/icon-product-created.png' }}"
                alt="Falta pouco para o seu lançamento!"
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Falta pouco para o <br> seu lançamento!
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p><strong>O seu produto foi criado com sucesso</strong>, você está a poucos passos de transformar seu <strong>infoproduto em uma máquina de vendas</strong>. Aguarde a aprovação do produto e aproveite para <strong>acertar os últimos detalhes e garantir</strong> que tudo esteja perfeito para o seu grande lançamento.</p>
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
            <strong>Lembre-se, estamos aqui para <br> ajudar no que precisar!</strong>
        </td>
    </tr>
@endsection
