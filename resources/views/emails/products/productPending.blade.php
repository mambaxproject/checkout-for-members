<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">
        <title></title>
        <style type="text/css" rel="stylesheet" media="all">
            @media only screen and (max-width: 600px) {
                .inner-body {
                    width: 100% !important;
                }
                .footer {
                    width: 100% !important;
                }
            }
            @media only screen and (max-width: 500px) {
                .button {
                    width: 100% !important;
                }
            }
        </style>
    </head>

    <body>
        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center">
                    <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            <td class="body" width="100%" cellpadding="0" cellspacing="0">
                                <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                    <tr>
                                        <td class="content-cell">
                                            <p>
                                                Olá! Tudo bem?
                                            </p>

                                            <p>
                                                Falta pouco para o seu lançamento!
                                                O Seu produto foi criado com sucesso, você está a poucos passos de transformar seu infoproduto em uma máquina de vendas.
                                                Aguarde a aprovação do produto e aproveite para acertar os últimos detalhes e garantir que tudo esteja perfeito para o seu grande lançamento.
                                            </p>

                                            <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td align="center">
                                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                                                            <tr>
                                                                <td align="center">
                                                                    <a href="{{ route('dashboard.products.edit', ['productUuid' => $product->client_product_uuid]) }}" class="button button-primary" target="_blank" rel="noopener">
                                                                        VER PRODUTO
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>

                                            <p>
                                                Qualquer dúvida, conte com a gente! <br>
                                                Equipe SuitPay
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                    <tr>
                                        <td class="content-cell" align="center">
                                            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
