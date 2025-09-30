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
                src="{{ config('app.url') . '/images/email/icon-product-approved.png' }}"
                alt="Seu produto já está disponível para compra"
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Seu produto já <br> está disponível <br> para compra
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p><strong>É oficial!</strong> Seu produto está pronto para ser visto e comprado. Seu trabalho está <strong>tomando forma</strong> e agora é <strong>hora de atrair seu público</strong> e alcançar resultados incríveis. Vamos nessa?</p>
            <p><strong>Acompanhe suas vendas:</strong></p>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 20px 0"
        >
            <a
                href="{{ route('dashboard.products.index') }}"
                class="button"
                target="_blank"
                rel="noopener"
                style="font-size: 14px"
            >
                Acessar Plataforma
            </a>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            <strong>Qualquer dúvida, <br> conte com a gente!</strong>
        </td>
    </tr>
@endsection
