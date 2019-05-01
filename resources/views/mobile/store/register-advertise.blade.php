<?php
    $top_nav = true;
    $header_title = 'Divulgar ofertas | naslojas.com';
    $body_class = 'bg-white';
?>

@extends('mobile.base')

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
            <div class="section-title">
                <h2>COMO FUNCIONA</h2>
            </div>

            <div class="item">
                <img src="{{ asset('images/advise/hw1.png') }}" class="img-responsive" />
                <p>O cliente encontra os seus produtos nas redes sociais, com um link para o <i>naslojas</i>, e faz o pedido online</p>
            </div>

            <div class="item">
                <img src="{{ asset('images/advise/hw2.png') }}" class="img-responsive" />
                <p>A loja recebe um e-mail avisando do pedido e faz a confirmação da venda, se ainda tiver o produto</p>
            </div>

            <div class="item">
                <img src="{{ asset('images/advise/hw3.png') }}" class="img-responsive" />
                <p>O entregador pega o produto na loja, leva para o cliente e volta na loja com o pagamento</p>
            </div>
        </div>

        <div class="social">
            <div class="item">
                <img src="{{ asset('images/advise/emoji.png') }}" class="emoji" alt="Emoji" />

                <span>Como seus seguidores visualizam<br><b>suas postagens hoje</b></span>

                <img src="{{ asset('images/advise/social-mobile.png') }}" class="img-social img-responsive" alt="Divulgação" />
            </div>

            <div class="item">
                <img src="{{ asset('images/advise/emoji2.png') }}" class="emoji" alt="emoji" />

                <span>Como eles irão visualizar<br><b>com o naslojas</b></span>

                <img src="{{ asset('images/advise/social-mobile2.png') }}" class="img-social img-responsive" alt="Divulgação" />
            </div>

            <a href="{{ route('store-register-get') }}" class="btn-advertise">CADASTRAR LOJA</a>
        </div>

        <div class="steps">
            <div class="section-title">
                <h2>COMO PARTICIPAR</h2>
                <h3>NÓS FAZEMOS O TRABALHO PESADO!</h3>
            </div>

            <div class="shapes">
                <div class="step">
                    <p>Nós cuidamos de toda<br>a parte <b>tech</b> para você<br>focar nas <b>vendas</b></p>
                </div>

                <div class="step">
                    <p>Também cuidamos<br>do <b>frete</b>, das <b>fotos</b><br>e da <b>divulgação</b></p>
                </div>

                <div class="step">
                    <p>Você só precisa <b>postar</b><br>os produtos e responder<br>os <b>pedidos</b></p>
                </div>
            </div>

            <!--<div class="row">
                <div class="col-xs-12">
                    <div class="lines">
                        <span class="line vertical"></span>
                        <span class="line horizontal"></span>
                    </div>
                </div>
            </div>-->

            <div class="opcionais">
                <div class="item">
                    <p>Frete para toda a<br>cidade por R$5<br><b>(OPCIONAL)</b></p>
                </div>

                <div class="item">
                    <p>Fotos por apenas<br>R$1 por produto<br><b>(OPCIONAL)</b></p>
                </div>

                <div class="item">
                    <p>Material impresso a<br>preços imperdíveis<br><b>(OPCIONAL)</b></p>
                </div>
            </div>
        </div>

        <div class="promotion">
            <h2>PROMOÇÃO DE LANÇAMENTO!</h2>

            <div class="text">
                <span class="top">Cadastre <b>100</b> produtos e<br>ganhe <b>1 ano</b> de <i>naslojas</i></span>
                <h3>TOTALMENTE GRÁTIS</h3>
                <span class="bottom">Válido por tempo limitadíssimo</span>
            </div>

            <div class="keys">
                <div class="content">
                    <span class="key">{</span>
                    <span class="item">Mantenha a sua loja<span>aberta 24hs por dia</span></span>
                </div>

                <div class="content">
                    <span class="key">{</span>
                    <span class="item">Sem mensalidade e<span>sem comissão nas vendas</span></span>
                </div>

                <div class="content">
                    <span class="key">{</span>
                    <span class="item">Nunca foi tão fácil vender<span>seus produtos online</span></span>
                </div>
            </div>
        </div>

        <div class="plans">
            <div class="section-title">
                <h2>PLANOS DE ADESÃO</h2>
                <h3>Os planos serão aplicados quando a promoção de lançamento for encerrada<br>A QUALQUER MOMENTO</h3>
            </div>

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

        <div class="attendance">
            <div class="col-xs-12">
                <h2>Atendimento rápido e prático<span>antes e depois de cadastrar a sua loja</span></h2>

                <h3>Se preferir, solicite a visita de um dos nossos representantes</h3>
            </div>

            <div class="contact">
                <div class="item">
                    <img src="{{ asset('images/advise/whatsapp.png') }}" alt="WhatsApp" />
                    <h4>(53) 9 9178-6097</h4>
                    <span>manda um whats<br>sem compromisso</span>
                </div>

                <div class="item">
                    <img src="{{ asset('images/advise/email.png') }}" alt="E-mail" />
                    <h4>contato@naslojas.com</h4>
                    <span>envie um e-mail<br>com todas as dúvidas</span>
                </div>
            </div>

            <div class="col-xs-12">
                <span class="vertical-line"></span>

                <p>O que está esperando?<br>Saia na frente da concorrência este ano</p>
            </div>

            <a href="{{ route('store-register-get') }}" class="btn-advertise">CADASTRAR LOJA</a>

            <img src="{{ asset('images/advise/race.png') }}" class="race img-responsive" />
        </div>
    </div>
@endsection
