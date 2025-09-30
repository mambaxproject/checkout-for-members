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
                src="{{ config('app.url') . '/images/email/affiliate/icon-accepted-affiliate.png' }}"
                alt="Convite especial para você!"
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Afiliação <br> concluída!
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p>Olá {{ $affiliate?->user?->name }}! Tudo bem?</p>
            <p><strong>Hoje é dia de comemorar!</strong> Você está oficialmente na nossa <strong>equipe de afiliados!</strong></p>
            <p>Estamos felizes em recebê-lo como parte do time e <strong>prontos para apoiar você</strong> na construção de uma parceria de sucesso.</p>
            <p><strong>A partir de agora, você tem:</strong></p>
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
                                            src="{{ config('app.url') . '/images/email/affiliate/icon-accepted-affiliate-01.png' }}"
                                            alt="Acesso ao maior ecossistema de soluções financeiras para negócios digitais."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Acesso ao maior ecossistema de soluções financeiras para negócios digitais.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="{{ config('app.url') . '/images/email/affiliate/icon-accepted-affiliate-02.png' }}"
                                            alt="Comissões e benefícios especiais de acordo com seu desempenho."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Comissões e benefícios especiais de acordo com seu desempenho.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="{{ config('app.url') . '/images/email/affiliate/icon-accepted-affiliate-03.png' }}"
                                            alt="Suporte exclusivo para otimizar sua experiência."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Suporte exclusivo para otimizar sua experiência.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
            <p><strong>Estamos aqui para ajudar em cada passo! <br> Acesse sua conta e comece já.</strong></p>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 20px 0"
        >
            <a
                href="{{ route('dashboard.affiliates.productsAffiliate') }}"
                class="button"
                target="_blank"
                rel="noopener"
                style="font-size: 14px"
            >
                Acesse seu painel
            </a>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            <p><strong>Qualquer coisa, é só chamar!</strong></p>
        </td>
    </tr>
@endsection
