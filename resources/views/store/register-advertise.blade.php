<?php
    $top_nav = true;
    $header_title = 'Divulgar ofertas | naslojas.com';
    $body_class = 'bg-white';
?>

@extends('base')

@section('content')
    <div class="container page-store-advertise">
        <span class="year">201<span>9</span></span>

        <h1>Ano para inovar o comércio de Pelotas</h1>

        <img src="{{ asset('images/advise/people.png') }}" class="img-responsive img-people" />

        <a href="{{ route('store-register-get') }}" class="btn-advertise">CADASTRAR LOJA</a>

        <div class="cards">
            <div class="card">
                <p>Venda seus produtos<br>online em Pelotas</p>
            </div>

            <div class="card">
                <p>Suas ofertas no<br>site e no app</p>
            </div>

            <div class="card">
                <p>Divulgue online<br>com link para venda</p>
            </div>

            <div class="card">
                <p>Aumente seu alcance<br>e seu faturamento</p>
            </div>

            <div class="card">
                <p>Tecnologia e inovação<br>para a sua loja</p>
            </div>
        </div>

        <div class="how-works">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 section-title">
                        <h2>COMO FUNCIONA</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <img src="{{ asset('images/advise/hw1.png') }}" />

                        <p class="margin">O cliente encontra os seus produtos<br>nas redes sociais, com um link para o<br><i>naslojas</i>, e faz o pedido online</p>
                    </div>

                    <div class="col-xs-4">
                        <img src="{{ asset('images/advise/hw2.png') }}" />

                        <p>A loja recebe um e-mail avisando do<br>pedido e faz a confirmação da venda,<br>se ainda tiver o produto</p>
                    </div>

                    <div class="col-xs-4">
                        <img src="{{ asset('images/advise/hw3.png') }}" />

                        <p class="margin">O entregador pega o produto na loja,<br>leva para o cliente e volta na loja<br>com o pagamento</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="social">
            <div class="container">
                <div class="row">
                    <div class="col-xs-6">
                        <img src="{{ asset('images/advise/emoji.png') }}" class="emoji" alt="Emoji" />

                        <span>Como seus seguidores visualizam<br><b>suas postagens hoje</b></span>

                        <img src="{{ asset('images/advise/social.jpg') }}" class="img-social img-responsive" alt="Divulgação" />
                    </div>

                    <div class="col-xs-6">
                        <img src="{{ asset('images/advise/emoji2.png') }}" class="emoji" alt="emoji" />

                        <span>Como eles irão visualizar<br><b>com o naslojas</b></span>

                        <img src="{{ asset('images/advise/social2.jpg') }}" class="img-social img-responsive" alt="Divulgação" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <a href="{{ route('store-register-get') }}" class="btn-advertise">CADASTRAR LOJA</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="steps">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 section-title">
                        <h2>COMO PARTICIPAR</h2>
                        <h3>NÓS FAZEMOS O TRABALHO PESADO!</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4 step">
                        <img src="{{ asset('images/advise/shape.png') }}" />
                        <p>Nós cuidamos de toda<br>a parte <b>tech</b> para você<br>focar nas <b>vendas</b></p>
                    </div>

                    <div class="col-xs-4 step">
                        <img src="{{ asset('images/advise/shape2.png') }}" />
                        <p>Oferecemos <b>frete</b><br>para toda a cidade<br>por apenas <b>R$5</b></p>
                    </div>

                    <div class="col-xs-4 step">
                        <img src="{{ asset('images/advise/shape3.png') }}" />
                        <p>Você só precisa <b>postar</b><br>os produtos e responder<br>os <b>pedidos</b></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="promotion">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>PROMOÇÃO DE LANÇAMENTO!</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 text">
                        <span class="top">Cadastre <b>100</b> produtos e ganhe <b>1 ano</b> de <i>naslojas</i></span>
                        <h3>TOTALMENTE GRÁTIS</h3>
                        <span class="bottom">Válido por tempo limitadíssimo</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4 text-right">
                        <span class="key">{</span>
                        <span class="item">Mantenha a sua loja<span>aberta 24hs por dia</span></span>
                    </div>

                    <div class="col-xs-4 text-center">
                        <span class="key">{</span>
                        <span class="item">Sem mensalidade e<span>sem comissão nas vendas</span></span>
                    </div>

                    <div class="col-xs-4 text-left">
                        <span class="key">{</span>
                        <span class="item">Nunca foi tão fácil vender<span>seus produtos online</span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="plans" id="planos">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 section-title">
                        <h2>PLANOS DE ADESÃO</h2>
                        <h3>Os planos serão aplicados quando a promoção de lançamento for encerrada<br>A QUALQUER MOMENTO</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="plan">
                            <img src="{{ asset('images/advise/plan.png') }}" alt="Plano de 1 mês" />
                            <span class="bold">R$<span>59</span></span>
                            <span class="bold">1 mês</span>
                            <hr>
                            <span class="text">Todos os recursos</span>
                            <span class="text">Sem limite de produtos</span>
                        </div>

                        <div class="plan">
                            <img src="{{ asset('images/advise/plan2.png') }}" class="img-plan2" alt="Plano de 2 meses" />
                            <span class="bold">R$<span>159</span></span>
                            <span class="bold">3 meses</span>
                            <span class="bold">10% OFF</span>
                            <span class="text">Todos os recursos</span>
                            <span class="text">Sem limite de produtos</span>
                        </div>

                        <div class="plan">
                            <img src="{{ asset('images/advise/plan3.png') }}" class="img-plan3" alt="Plano de 6 meses" />
                            <span class="bold">R$<span>299</span></span>
                            <span class="bold">6 meses</span>
                            <span class="bold">15% OFF</span>
                            <span class="text">Todos os recursos</span>
                            <span class="text">Sem limite de produtos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="attendance">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h2>Atendimento rápido e prático<br>Antes e depois de cadastrar a sua loja</h2>

                        <h3>Se preferir, solicite a visita de um dos nossos representantes</h3>
                    </div>
                </div>

                <div class="row contact">
                    <div class="col-xs-3 col-xs-offset-3">
                        <img src="{{ asset('images/advise/whatsapp.png') }}" alt="WhatsApp" />
                        <h4>(53) 9 9178-6097</h4>
                        <span>manda um whats<br>sem compromisso</span>
                    </div>

                    <div class="col-xs-3">
                        <img src="{{ asset('images/advise/email.png') }}" alt="E-mail" />
                        <h4>contato@naslojas.com</h4>
                        <span>envie um e-mail<br>com todas as dúvidas</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <span class="vertical-line"></span>

                        <p>O que está esperando?<br>Saia na frente da concorrência este ano</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <a href="{{ route('store-register-get') }}" class="btn-advertise">CADASTRAR LOJA</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <img src="{{ asset('images/advise/race.png') }}" class="race" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
