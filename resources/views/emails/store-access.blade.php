<html>
    <head>
        <style type="text/css">
            /**This is to overwrite Outlook.com’s Embedded CSS************/
            table {border-collapse:separate;}
            .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td {line-height: 100%}
            .ExternalClass {width: 100%;}
            .ExternalClass {display:inline-block; line-height: 100% !important;}
            /**This is to center your email in Outlook.com************/

            .yshortcuts {color: #3f51b5;}

            p {margin: 0; padding: 0; margin-bottom: 0;} /*optional*/
            a, a:link, a:visited {color: rgb(66, 133, 244); text-decoration: none;} /*optional*/
            a, a:hover {text-decoration: none;}
            img:hover {cursor: default;}
        </style>
    </head>
    <body style="background: rgb(250, 250, 250);" alink="#3f51b5" link="#3f51b5" bgcolor="rgb(250, 250, 250)" text="#FFFFFF">
        <span id="body_style" style="padding: 0; display: block;">
            <table id="Tabela_01" style="margin-bottom: 30px;" width="600" height="auto" border="0" cellpadding="0" cellspacing="0" align="center">
                <tr>
                    <td>
                        <a href="{{ url('/') }}">
                            <img style="display: block; cursor: pointer; margin: 30px auto 30px auto; width: 200px;" src="{{ asset('images/logo-naslojas.png') }}" />
                        </a>
                    </td>
                </tr>

                <tr>
                    <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 50px 50px 20px 50px; border-radius: 5px 5px 0 0;">
                        <span style="display: block; font-size: 20px; color: rgb(49, 49, 49);">O sindilojas cadastrou a sua loja no naslojas.<br>Para acessar sua conta, basta utilizar os dados de acesso:</span>
                    </td>
                </tr>

                <tr>
                    <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 10px 50px 5px 50px;">
                        <span style="display: block; font-size: 14.5px; color: rgb(100, 100, 100);">E-mail: {{ $data->email }}</span>
                    </td>
                </tr>

                <tr>
                    <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 0 50px 50px 50px;">
                        <span style="display: block; font-size: 14.5px; color: rgb(100, 100, 100);">Senha: {{ $data->password }}</span>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 40px 50px 0 50px;">
                        <span style="text-align: center; display: block; font-size: 12.5; color: rgb(150, 150, 150);">Copyright {{ date('Y') }}. Todos os direitos reservados.</span>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 0 50px 40px 50px;">
                        <span style="text-align: center; display: block; font-size: 12.5; color: rgb(150, 150, 150);">Dogs Are Awesome Atividades de Internet Ltda - CNPJ 32.194.554/0001-63</span>
                    </td>
                </tr>
            </table>
        </span>
    </body>
</html>
