@php
    $top_nav = true;
    $body_class = 'bg-white';
@endphp

@extends('mobile.base')

@section('content')
    <div class="container page-admin page-client-config">
        <h1 class="page-title">Minha conta</h1>
        <p class="page-description">Mantenha os seus dados sempre atualizados</p>

        {!! Form::model($client, ['method' => 'POST', 'route' => 'set-client-config', 'id' => 'form-client-config']) !!}
            {!! Form::hidden('section', $navigation ?? 'profile') !!}

            <ul class="navigation">
                <li>
                    <a href="{{ route('get-client-config', 'profile') }}">Seus dados</a>

                    <div class="fields {{ (isset($navigation) && $navigation == 'profile') ? 'show-fields' : '' }}">
                        <div class="form-group name">
                            {!! Form::text('name', null, ['placeholder' => ' ']) !!}
                            {!! Form::label('', 'Nome') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('phone', null, ['placeholder' => ' ', 'class' => 'mask-phone']) !!}
                            {!! Form::label('', 'Celular') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('birthdate', $client->birthdate ? date('d/m/Y', strtotime($client->birthdate)) : null, ['placeholder' => ' ', 'class' => 'mask-date']) !!}
                            {!! Form::label('', 'Aniversário') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('cpf', null, ['placeholder' => ' ', 'class' => 'mask-cpf']) !!}
                            {!! Form::label('', 'CPF') !!}
                        </div>
                    </div>
                </li>

                <li>
                    <a href="{{ route('get-client-config', 'address') }}">Endereço de entrega</a>

                    <div class="fields {{ (isset($navigation) && $navigation == 'address') ? 'show-fields' : '' }}">
                        <div class="form-group cep">
                            {!! Form::text('cep', null, ['placeholder' => ' ', 'id' => 'cep']) !!}
                            {!! Form::label('', 'Cep') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('street', null, ['placeholder' => ' ', 'id' => 'street']) !!}
                            {!! Form::label('', 'Endereço') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('number', null, ['placeholder' => ' ', 'id' => 'number']) !!}
                            {!! Form::label('', 'Número') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('complement', null, ['placeholder' => ' ']) !!}
                            {!! Form::label('', 'Complemento') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::select('district', $districts, $client->district_id ? $client->district->id : null, ['title' => 'Bairro', 'class' => 'selectpicker']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('city', $client->city_id ? $client->city->title : null, ['placeholder' => ' ', 'id' => 'city']) !!}
                            {!! Form::label('', 'Cidade') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('state', $client->city_id ? $client->city->state->letter : null, ['placeholder' => ' ', 'id' => 'state']) !!}
                            {!! Form::label('', 'Estado') !!}
                        </div>
                    </div>
                </li>

                <li>
                    <a href="{{ route('get-client-config', 'access') }}">Dados de acesso</a>

                    <div class="fields {{ (isset($navigation) && $navigation == 'access') ? 'show-fields' : '' }}">
                        {!! Form::input('password', 'current_password') !!}

                        <div class="form-group">
                            {!! Form::email('email', null, ['placeholder' => ' ']) !!}
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
                </li>

                <li>
                    <a href="{{ route('delete-client-account') }}" id="delete-client-account">Deletar conta</a>
                </li>
            </ul>

            {!! Form::submit('SALVAR ALTERAÇÕES') !!}
        {!! Form::close() !!}
    </div>
@endsection
