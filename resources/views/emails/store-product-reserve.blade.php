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
                    <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 50px 50px 0 50px; border-radius: 5px 5px 0 0;">
                        <span style="display: block; font-size: 25px; color: rgb(49, 49, 49);">Reserva de produto</span>
                    </td>
                </tr>

                <tr>
                    <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 10px 50px 10px 50px;">
                        <span style="display: block; font-size: 14.5px; color: rgb(100, 100, 100);">{{ $reserve->client->name }} solicitou que a loja {{ $reserve->product->store->name }} reserve o seguinte produto:</span>
                    </td>
                </tr>

                <tr>
                    <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 40px 50px 0 50px;">
                        <img src="{{ asset('uploads/' . $reserve->product->store_id . '/products/' . $reserve->product->images->first()->image) }}" style="float: left; margin-right: 10px; width: 248px; height: 248px; object-fit: cover;" />

                        @if ($reserve->product->old_price)
                            <span style="margin-top: 10px; text-decoration: line-through; font-size: 12.5px; display: block; color: rgb(50, 50, 50);">de R$ {{ number_format($reserve->product->old_price, 2, ',', '.') }}</span>
                        @endif

                        <span style="margin: 10px 0; display: inline-block; font-weight: 700; font-size: 14.5px; color: rgb(50, 50, 50)"><span style="font-size: 12.5px;">R$</span> {{ number_format($reserve->product->price, 2, ',', '.') }}</span>

                        @if ($reserve->product->old_price)
                            <span style="margin-bottom: 10px; display: inline-block; margin-left: 10px; font-size: 14.5px; color: rgb(112, 202, 124);">{{ _discount($reserve->product->price, $reserve->product->old_price) }}% OFF</span>
                        @endif

                        @if ($reserve->product->installment && $reserve->product->installment_price)
                            <span style="margin-bottom: 10px; font-size: 12.5px; display: block; color: rgb(50, 50, 50);">
                                em até {{ $reserve->product->installment }}x de R$ {{ number_format($reserve->product->installment_price, 2, ',', '.') }}
                                {{ _taxes($reserve->product->installment, $reserve->product->installment_price, $reserve->product->price) }}
                            </span>
                        @endif

                        <span style="display: block; font-size: 14.5px; color: rgb(150, 150, 150); line-height: 1.286;">{{ $reserve->product->title }}</span>

                        @if ($reserve->size)
                            <span style="margin-top: 10px; display: block; font-size: 14.5px; font-weight: 700; color: rgb(50, 50, 50);">Tamanho {{ $reserve->size }}</span>
                        @endif

                        <a href="{{ route('show-product', $reserve->product->slug) }}" style="margin-top: 20px; display: inline-block; font-size: 14.5px; color: rgb(122, 184, 236);">Ver produto no site</a>
                    </td>
                </tr>

                <tr>
                    <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); border-radius: 0 0 5px 5px; background-color: #fff; padding: 40px 50px 50px 50px;">
                        <a href="{{ route('product-reserve-email-url', ['1', $reserve->token]) }}" style="float: left; border-radius: 25px; font-weight: 700; font-size: 14.5; margin-top: 10px; background-color: rgb(255, 23, 68); color: #fff; padding: 13px 25px;">RESERVAR POR 24H</a>

                        <a href="{{ route('product-reserve-email-url', ['0', $reserve->token]) }}" style="float: right; border-radius: 25px; font-weight: 700; font-size: 14.5; margin-top: 10px; background-color: rgb(255, 23, 68); color: #fff; padding: 13px 25px;">NÃO TEMOS O PRODUTO</a>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 40px 50px 0 50px;">
                        <span style="text-align: center; display: block; font-size: 12.5; color: rgb(150, 150, 150);">Copyright 2019. Todos os direitos reservados.</span>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 5px 50px 40px 50px;">
                        <span style="text-align: center; display: block; font-size: 12.5; color: rgb(150, 150, 150);">Dogs Are Awesome Atividades de Internet Ltda - CNPJ 32.194.554/0001-63</span>
                    </td>
                </tr>
            </table>
        </span>
    </body>
</html>
