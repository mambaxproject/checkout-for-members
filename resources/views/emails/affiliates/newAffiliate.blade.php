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
                src="{{ config('app.url') . '/images/email/coproducer/icon-new-coproducer.png' }}"
                alt="Convite especial para você!"
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Convite especial <br /> para você!
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p>Olá {{ $affiliate->name }}! Tudo bem?</p>
            <p><strong>Temos uma ótima notícia para você!</strong> Você foi convidado a se tornar afiliado do {{ $product->name }}</p>
            <p><strong>Como afiliado do {{ $product->name }}</strong>, você receberá um link individual e poderá contribuir com a divulgação, aproveitando todos os <strong>benefícios dessa parceria</strong>.</p>
            <p><strong>Tornando-se afiliado, você <br> terá os seguintes benefícios:</strong></p>
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
                                            src="{{ config('app.url') . '/images/email/affiliate/icon-new-affiliate-01.png' }}"
                                            alt="Participação nas vendas, garantindo maior rentabilidade."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Mais rentabilidade para seu bolso, com comissões exclusivas de afiliados.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="{{ config('app.url') . '/images/email/affiliate/icon-new-affiliate-02.png' }}"
                                            alt="Acesso ao maior ecossistema de soluções financeiras para negócios digitais."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Acesso ao maior ecossistema de soluções financeiras para negócios digitais.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="{{ config('app.url') . '/images/email/affiliate/icon-new-affiliate-03.png' }}"
                                            alt="Suporte especializado para te ajudar em cada etapa."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Suporte especializado para te ajudar em cada etapa.</td>
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
            <p><strong>Juntos, iremos alcançar resultados extraordinários. <br> Aceite o convite e comece agora mesmo!</strong></p>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 20px 0 40px"
        >
            <a
                href="https://seusite.com"
                class="button"
                style="font-size: 14px"
            >
                Aceitar convite
            </a>
        </td>
    </tr>
@endsection
