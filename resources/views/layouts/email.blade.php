<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <meta name="x-apple-disable-message-reformatting" />

    <title>Convite especial para você!</title>

    <style>
        /* Reset básico */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse !important;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Estilos inline-safe */
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #33cc33;
            color: #ffffff !important;
            text-align: center;
            text-transform: uppercase;
            border-radius: 5px;
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #f4f4f4;">

    <table
        role="presentation"
        border="0"
        cellpadding="0"
        cellspacing="0"
        width="100%"
        style="background-color: #f4f4f4; margin: 0; padding: 0;"
    >

        <tbody>
            <tr>
                <td align="center">

                    <table
                        role="presentation"
                        class="email-container"
                        border="0"
                        cellpadding="0"
                        cellspacing="0"
                    >

                        <tbody>

                            @yield('content')

                            <!-- FOOTER -->
                            <tr>
                                <td style="padding: 20px; text-align: center; background-color: #efffea; font-size: 14px">
                                    <p>Atenciosamente <br /> <strong>Equipe SuitPay 💚</strong></p>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </td>
            </tr>
        </tbody>

    </table>

</body>

</html>
