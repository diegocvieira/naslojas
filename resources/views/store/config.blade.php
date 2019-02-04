<?php
    $top_nav_store = true;
?>

@extends('base')

@section('content')
    <div class="container page-store-config">
        {!! Form::model($user->store, ['method' => 'POST', 'route' => 'set-store-config', 'id' => 'form-store-config']) !!}
            <div class="row header-config">
                <div class="col-xs-4">
                    <span class="description">Mantenha os dados da loja sempre atualizados</span>
                </div>

                <div class="col-xs-8">
                    {!! Form::submit('SALVAR ALTERAÇÕES') !!}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <ul class="navigation">
                        <li>
                            <a href="{{ route('get-store-config', 'store-profile') }}" class="option {{ (!isset($navigation) || isset($navigation) && $navigation == 'store-profile') ? 'active' : '' }}">Dados da loja</a>
                        </li>

                        <li>
                            <a href="{{ route('get-store-config', 'address') }}" class="option {{ (isset($navigation) && $navigation == 'address') ? 'active' : '' }}">Endereço</a>
                        </li>

                        <li>
                            <a href="{{ route('get-store-config', 'hours') }}" class="option {{ (isset($navigation) && $navigation == 'hours') ? 'active' : '' }}">Horário de atendimento</a>
                        </li>

                        <li>
                            <a href="{{ route('get-store-config', 'freights') }}" class="option {{ (isset($navigation) && $navigation == 'freights') ? 'active' : '' }}">Frete</a>
                        </li>

                        <li>
                            <a href="{{ route('get-store-config', 'access') }}" class="option {{ (isset($navigation) && $navigation == 'access') ? 'active' : '' }}">Dados de acesso</a>
                        </li>

                        <li>
                            <a href="{{ route('get-store-config') }}" class="activate-profile">
                                Perfil da loja

                                <span class="switch {{ $user->store->status ? 'active-profile' : '' }}"></span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('delete-store-account') }}" id="delete-store-account">Deletar conta</a>
                        </li>
                    </ul>
                </div>

                <div class="col-xs-8">
                    <div class="fields {{ (!isset($navigation) || isset($navigation) && $navigation == 'store-profile') ? 'show-fields' : '' }}">
                        <div class="form-group name">
                            {!! Form::text('name', null, ['placeholder' => ' ']) !!}
                            {!! Form::label('', 'Nome da loja') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('phone', null, ['placeholder' => ' ', 'class' => 'mask-phone']) !!}
                            {!! Form::label('', 'Telefone') !!}
                        </div>

                        <div class="form-group slug">
                            {!! Form::text('slug', null, ['placeholder' => ' ', 'id' => 'slug', 'class' => 'move-placeholder']) !!}
                            {!! Form::label('', 'Url') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('min_parcel_price', null, ['placeholder' => ' ', 'class' => 'mask-money']) !!}
                            {!! Form::label('', 'Valor mínimo da parcela') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('max_parcel', null, ['placeholder' => ' ', 'class' => 'mask-number']) !!}
                            {!! Form::label('', 'Máximo de parcelas') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('max_product_unit', null, ['placeholder' => ' ', 'class' => 'mask-number']) !!}
                            {!! Form::label('', 'Máximo de unidades por produto') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('cnpj', null, ['placeholder' => ' ', 'class' => 'mask-cnpj']) !!}
                            {!! Form::label('', 'CNPJ') !!}
                        </div>
                    </div>

                    <div class="fields {{ (isset($navigation) && $navigation == 'address') ? 'show-fields' : '' }}">
                        <div class="form-group cep">
                            {!! Form::text('cep', null, ['placeholder' => ' ', 'id' => 'cep']) !!}
                            {!! Form::label('', 'Cep') !!}

                            <a href="http://www.buscacep.correios.com.br/sistemas/buscacep/" target="_blank">Buscar cep</a>
                        </div>

                        <div class="form-group">
                            {!! Form::text('street', null, ['placeholder' => ' ', 'id' => 'street']) !!}
                            {!! Form::label('', 'Endereço') !!}
                        </div>

                        <div class="form-group margin half">
                            {!! Form::text('number', null, ['placeholder' => ' ', 'id' => 'number']) !!}
                            {!! Form::label('', 'Número') !!}
                        </div>

                        <div class="form-group half">
                            {!! Form::text('complement', null, ['placeholder' => ' ']) !!}
                            {!! Form::label('', 'Complemento') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('district', null, ['placeholder' => ' ', 'id' => 'district']) !!}
                            {!! Form::label('', 'Bairro') !!}
                        </div>

                        <div class="form-group margin half">
                            {!! Form::text('city', $user->store->city_id ? $user->store->city->title : null, ['placeholder' => ' ', 'id' => 'city']) !!}
                            {!! Form::label('', 'Cidade') !!}
                        </div>

                        <div class="form-group half">
                            {!! Form::text('state', $user->store->city_id ? $user->store->city->state->letter : null, ['placeholder' => ' ', 'id' => 'state']) !!}
                            {!! Form::label('', 'Estado') !!}
                        </div>
                    </div>

                    <div class="fields {{ (isset($navigation) && $navigation == 'hours') ? 'show-fields' : '' }}">
                        @foreach ($weeks as $week_id => $week)
                            <div class="form-group">
                                {!! Form::hidden('week_id[]', $week_id) !!}

                                @foreach ($user->store->operatings as $store_operating)
                                    <?php
                                        $accept_weeks[] = $store_operating->week;
                                        $opening_morning = substr($store_operating->opening_morning, 0, -3);
                                        $closed_morning = substr($store_operating->closed_morning, 0, -3);
                                        $opening_afternoon = substr($store_operating->opening_afternoon, 0, -3);
                                        $closed_afternoon = substr($store_operating->closed_afternoon, 0, -3);
                                    ?>

                                    @if ($store_operating->week == $week_id)
                                        {!! Form::text('operating[' . $week_id . ']', $opening_morning . $closed_morning . $opening_afternoon . $closed_afternoon, ['placeholder' => ' ', 'class' => 'mask-week operating']) !!}
                                    @endif
                                @endforeach

                                @if (!isset($accept_weeks) || !in_array($week_id, $accept_weeks))
                                    {!! Form::text('operating[' . $week_id . ']', null, ['placeholder' => ' ', 'class' => 'mask-week operating', 'required']) !!}
                                @endif

                                {!! Form::label('', $week) !!}
                            </div>
                        @endforeach
                    </div>

                    <div class="fields freights-fields {{ (isset($navigation) && $navigation == 'freights') ? 'show-fields' : '' }}">
                        @foreach ($districts as $key => $district)
                            <div class="form-group half {{ $key % 2 == 0 ? 'margin' : '' }}">
                                {!! Form::hidden('district_id[]', $district->id) !!}

                                @foreach ($user->store->freights as $store_freight)
                                    <?php $accept_districts[] = $store_freight->district_id; ?>

                                    @if ($store_freight->district_id == $district->id)
                                        {!! Form::text('freight_price[]', $store_freight->price, ['placeholder' => ' ', 'class' => 'mask-money']) !!}
                                    @endif
                                @endforeach

                                @if (!isset($accept_districts) || !in_array($district->id, $accept_districts))
                                    {!! Form::text('freight_price[]', null, ['placeholder' => ' ', 'class' => 'mask-money']) !!}
                                @endif

                                {!! Form::label('', $district->name) !!}
                            </div>
                        @endforeach
                    </div>

                    <div class="fields {{ (isset($navigation) && $navigation == 'access') ? 'show-fields' : '' }}">
                        {!! Form::input('password', 'current_password') !!}

                        <div class="form-group">
                            {!! Form::email('email', $user->email, ['placeholder' => ' ']) !!}
                            {!! Form::label('', 'E-mail') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::input('password', 'password', null, ['placeholder' => ' ', 'id' => 'password']) !!}
                            {!! Form::label('', 'Nova senha') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::input('password', 'password_confirmation', null, ['placeholder' => ' ']) !!}
                            {!! Form::label('', 'Confirmar nova senha') !!}
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection
