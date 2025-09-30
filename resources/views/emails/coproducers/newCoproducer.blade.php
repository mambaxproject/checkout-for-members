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
                Convite especial <br> para você!
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p>Olá {{ $coproducer->name }}! Tudo bem?</p>
            <p><strong>Temos uma ótima notícia para você!</strong> Você foi convidado a se tornar se tornar <strong>um parceiro de produção</strong> do {{ $product->name }}, como um sócio nas vendas e no <strong>sucesso do produto!</strong></p>
            <p><strong>Ao se tornar coprodutor</strong>, você possui uma <strong>participação nos lucros de cada venda</strong>, além de se tornar um parceiro estratégico na divulgação. <strong>Vamos impulsionar esse projeto</strong> e maximizar ainda mais os resultados.</p>
            <p><strong>Como coprodutor, você terá:</strong></p>
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
                                            src="{{ config('app.url') . '/images/email/coproducer/icon-new-coproducer-01.png' }}"
                                            alt="Participação nas vendas, garantindo maior rentabilidade."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Participação nas vendas, garantindo maior rentabilidade.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="{{ config('app.url') . '/images/email/coproducer/icon-new-coproducer-02.png' }}"
                                            alt="Acesso ao maior ecossistema de soluções financeiras para negócios digitais."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Acesso ao maior ecossistema de soluções financeiras para negócios digitais.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="{{ config('app.url') . '/images/email/coproducer/icon-new-coproducer-03.png' }}"
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
            <strong>Acesse o link abaixo e confirme <br> para poder ter acesso.</strong>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 20px 0"
        >
            <a
                href="{{ route('coproducer.join', $coproducer) }}"
                class="button"
                target="_blank"
                rel="noopener"
                style="font-size: 14px"
            >
                Aceitar convite
            </a>
        </td>
    </tr>
@endsection
