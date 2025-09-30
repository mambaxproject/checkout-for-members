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
                src="{{ config('app.url') . '/images/email/coproducer/icon-accepted-coproducer.png' }}"
                alt="Você agora é um coprodutor!"
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Você agora é um <br> coprodutor!
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p>Olá {{ $coproducer->name }}! Tudo bem?</p>
            <p>Com uma <strong>grande satisfação anunciamos</strong> que você oficialmente é um <strong>coprodutor</strong> do {{ $product->name }}.</p>
            <p><strong>Mal podemos esperar</strong> para que você comece <br> essa incrível jornada.</p>
            <p><strong>Como coprodutor, você agora tem:</strong></p>
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
                                            src="{{ config('app.url') . '/images/email/coproducer/icon-accepted-coproducer-01.png' }}"
                                            alt="Participação nos lucros de cada venda do {{ $product->name }}."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Participação nos lucros de cada venda do {{ $product->name }}.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="{{ config('app.url') . '/images/email/coproducer/icon-accepted-coproducer-02.png' }}"
                                            alt="Acesso ao maior ecossistema de soluções financeiras para negócios digitais."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Acesso ao maior ecossistema de soluções financeiras para negócios digitais.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="{{ config('app.url') . '/images/email/coproducer/icon-accepted-coproducer-03.png' }}"
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
            <strong>Acesse sua conta e comece a <br> explorar todas as oportunidades:</strong>
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
                Acesse sua conta
            </a>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 0 0 40px"
        >
            <strong>Qualquer coisa, é só chamar!</strong>
        </td>
    </tr>
@endsection
