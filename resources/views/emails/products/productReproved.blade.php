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
                src="{{ config('app.url') . '/images/email/products/icon-product-reproved.png' }}"
                alt="Seu produto precisa de pequenos ajustes."
            />

            <h1 style="margin: 40px 0 0; text-transform: uppercase">
                Seu produto <br> precisa de pequenos <br> ajustes.
            </h1>

        </td>
    </tr>
    <tr>
        <td style="padding: 20px 8%; text-align: center; font-size: 14px; line-height: 20px;">
            <p>Olá {{ $product->shop->owner->name }},</p>
            <p>Infelizmente, <strong>não conseguimos publicar seu produto desta vez, mas não se preocupe!</strong> Pequenos ajustes podem fazer toda a diferença. Aqui estão algumas dicas para resolver:</p>
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
                                            src="{{ config('app.url') . '/images/email/products/img-product-reproved-01.png' }}"
                                            alt="Revise as informações conforme as diretrizes da plataforma."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Revise as informações conforme as diretrizes da plataforma.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="{{ config('app.url') . '/images/email/products/img-product-reproved-01.png' }}"
                                            alt="Faça as alterações necessárias e reenvie para análise."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Faça as alterações necessárias e reenvie para análise.</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding: 10px;">
                                        <img
                                            src="{{ config('app.url') . '/images/email/products/img-product-reproved-01.png' }}"
                                            alt="Se precisar de ajuda, nossa equipe está à disposição para auxiliar você nesse processo."
                                            style="width: 24px;"
                                        >
                                    </td>
                                    <td style="font-size: 14px; line-height: 20px; padding: 6px;">Se precisar de ajuda, nossa equipe está à disposição para auxiliar você nesse processo.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 20px 0 0"
        >
            <strong>Vamos juntos garantir que seu lançamento <br> seja impecável? Verifique os motivos de recusa:</strong>
        </td>
    </tr>
    <tr>
        <td
            align="center"
            style="padding: 20px 0 40px"
        >
            <a
                href="{{ route('dashboard.products.edit', ['productUuid' => $product->client_product_uuid]) }}"
                class="button"
                target="_blank"
                rel="noopener"
                style="font-size: 14px"
            >
                Acessar Plataforma
            </a>
        </td>
    </tr>
@endsection
