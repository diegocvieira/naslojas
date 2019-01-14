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
                        <span style="display: block; font-size: 25px; color: rgb(49, 49, 49);">Confirmação de produto</span>
                    </td>
                </tr>

                <tr>
                    <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 10px 50px 10px 50px;">
                        <span style="display: block; font-size: 14.5px; color: rgb(100, 100, 100);">A loja {{ $confirm->product->store->name }} respondeu o seu pedido de confirmação do produto:</span>
                    </td>
                </tr>

                <tr>
                    <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 40px 50px 0 50px;">
                        <img src="{{ asset('uploads/' . $confirm->product->store_id . '/products/' . $confirm->product->images->first()->image) }}" style="float: left; margin-right: 10px; width: 248px; height: 248px; object-fit: cover;" />

                        @if ($confirm->product->old_price)
                            <span style="margin-top: 10px; text-decoration: line-through; font-size: 12.5px; display: block; color: rgb(50, 50, 50);">de R$ {{ number_format($confirm->product->old_price, 2, ',', '.') }}</span>
                        @endif

                        <span style="margin: 10px 0; display: inline-block; font-weight: 700; font-size: 16.6px; color: rgb(50, 50, 50)"><span style="font-size: 12.5px;">R$</span> {{ number_format($confirm->product->price, 2, ',', '.') }}</span>

                        @if ($confirm->product->old_price)
                            <span style="margin-bottom: 10px; display: inline-block; margin-left: 10px; font-size: 14.5px; color: rgb(112, 202, 124);">{{ _discount($confirm->product->price, $confirm->product->old_price) }}% OFF</span>
                        @endif

                        @if ($confirm->product->installment && $confirm->product->installment_price)
                            <span style="margin-bottom: 10px; font-size: 12.5px; display: block; color: rgb(50, 50, 50);">
                                em até {{ $confirm->product->installment }}x de R$ {{ number_format($confirm->product->installment_price, 2, ',', '.') }}
                                {{ _taxes($confirm->product->installment, $confirm->product->installment_price, $confirm->product->price) }}
                            </span>
                        @endif

                        @if ($confirm->product->reserve && $confirm->product->reserve_discount)
                            <span style="color: #000; display: block; position: relative; left: -10px; font-size: 14.5px; margin-top: 10px;" title="Preço com desconto reservado pelo naslojas"><span style="font-size: 16.6px; font-weight: 700; background-color: rgb(255, 215, 223); padding: 5px 10px; border-radius: 25px; margin-right: 5px;">R$ {{ number_format(_reservePrice($confirm->product->price, $confirm->product->reserve_discount), 2, ',', '.') }}</span> na reserva pelo <i>naslojas</i></span>

                            @if ($confirm->product->installment && $confirm->product->installment_price)
                                <span style="color: #000; margin-top: 5px; font-size: 14.5px; display: block; padding-left: 10px;">
                                    em até {{ $confirm->product->installment }}x de R$ {{ number_format(_reservePrice(($confirm->product->installment_price * $confirm->product->installment) / $confirm->product->installment, $confirm->product->reserve_discount), 2, ',', '.') }}
                                    {{ _taxes($confirm->product->installment, $confirm->product->installment_price, $confirm->product->price) }}
                                </span>
                            @endif
                        @endif

                        <span style="display: block; font-size: 14.5px; color: rgb(150, 150, 150); line-height: 1.286;">{{ $confirm->product->title }}</span>

                        @if ($confirm->size)
                            <span style="margin-top: 10px; display: block; font-size: 14.5px; font-weight: 700; color: rgb(50, 50, 50);">Tamanho {{ $confirm->size }}</span>
                        @endif

                        <a href="{{ route('show-product', $confirm->product->slug) }}" style="margin-top: 20px; display: inline-block; font-size: 14.5px; color: rgb(122, 184, 236);">Ver produto no site</a>
                    </td>
                </tr>

                <tr>
                    <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 40px 50px 0 50px;">
                        <span style="text-align: center; display: block; font-weight: 700; font-size: 14.5px; color: rgb(255, 23, 68);">
                            @if ($type == 1)
                                {{ $confirm->size ? 'AINDA TEMOS O TAMANHO ' . $confirm->size . ' DESTE PRODUTO' : 'AINDA TEMOS ESTE PRODUTO' }}
                            @else
                                {{ $confirm->size ? 'NÃO TEMOS MAIS O TAMANHO ' . $confirm->size . ' DESTE PRODUTO' : 'NÃO TEMOS MAIS ESTE PRODUTO' }}
                            @endif
                        </span>
                    </td>
                </tr>

                @if ($type == 1)
                    <tr>
                        <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 10px 50px 0 50px;">
                            <span style="text-align: center; display: block; font-size: 14.5px; color: rgb(150, 150, 150);">Passe na {{ $confirm->product->store->name }} para conferir</span>
                        </td>
                    </tr>

                    <tr>
                        <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 5px 50px 0 50px;">
                            <span style="text-align: center; display: block; font-size: 14.5px; color: rgb(150, 150, 150);">{{ $confirm->product->store->street }}, {{ $confirm->product->store->number }} - {{ $confirm->product->store->district }} - {{ $confirm->product->store->city->title }}/{{ $confirm->product->store->city->state->letter }}</span>
                        </td>
                    </tr>

                    <tr>
                        <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 10px 50px 50px 50px;">
                            <a href="//maps.google.com/?q={{ $confirm->product->store->street }}, {{ $confirm->product->store->number }}, {{ $confirm->product->store->district }}, {{ $confirm->product->store->city->title }}, {{ $confirm->product->store->city->state->letter }}" style="text-align: center; display: block; font-size: 12.5px; color: rgb(50, 50, 50);" target="_blank">
                                <img src="{{ asset('images/icon-pin.png') }}" style="width: 18px; display: inline-block;" /><span style="display: inline-block; position: relative; top: -3px; margin-left: 5px;">ver no mapa</span>
                            </a>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td style="box-shadow: 5px 8.66px 9px 1px rgba(100, 100, 100, 0.118); background-color: #fff; padding: 10px 50px 50px 50px;">
                            <span style="text-align: center; display: block; font-size: 14.5px; color: rgb(150, 150, 150);">Já o retiramos do site!</span>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="padding: 40px 50px 0 50px;">
                        <span style="text-align: center; display: block; font-size: 12.5px; color: rgb(150, 150, 150);">Copyright 2019. Todos os direitos reservados.</span>
                    </td>
                </tr>

                <tr>
                    <td style="padding: 5px 50px 40px 50px;">
                        <span style="text-align: center; display: block; font-size: 12.5px; color: rgb(150, 150, 150);">Dogs Are Awesome Atividades de Internet Ltda - CNPJ 32.194.554/0001-63</span>
                    </td>
                </tr>
            </table>
        </span>
    </body>
</html>
