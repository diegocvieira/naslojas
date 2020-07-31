@extends('app')

@section('content')
    @include ('inc.header')

    <div class="container-fluid page-client-config">
        {!! Form::model($client, ['method' => 'POST', 'route' => 'set-client-config', 'id' => 'form-client-config']) !!}
            <div class="row header-config">
                <div class="col-xs-4">
                    <span class="description">Mantenha os seus dados sempre atualizados</span>
                </div>

                <div class="col-xs-8">
                    {!! Form::submit('SALVAR ALTERAÇÕES') !!}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <ul class="navigation">
                        <li>
                            <a href="{{ route('get-client-config', 'profile') }}" class="option {{ (!isset($navigation) || isset($navigation) && $navigation == 'profile') ? 'active' : '' }}">Seus dados</a>
                        </li>

                        <li>
                            <a href="{{ route('get-client-config', 'address') }}" class="option {{ (isset($navigation) && $navigation == 'address') ? 'active' : '' }}">Endereço de entrega</a>
                        </li>

                        <li>
                            <a href="{{ route('get-client-config', 'access') }}" class="option {{ (isset($navigation) && $navigation == 'access') ? 'active' : '' }}">Dados de acesso</a>
                        </li>

                        <li>
                            <a href="{{ route('delete-client-account') }}" id="delete-client-account">Deletar conta</a>
                        </li>
                    </ul>

                    <img src="{{ asset('images/ssl.png') }}" class="ssl" />
                </div>

                <div class="col-xs-8">
                    {!! Form::hidden('section', $navigation ?? 'profile') !!}

                    <div class="fields {{ (!isset($navigation) || isset($navigation) && $navigation == 'profile') ? 'show-fields' : '' }}">
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
                            {!! Form::select('district', $districts, $client->district_id ? $client->district->id : null, ['title' => 'Bairro', 'class' => 'selectpicker']) !!}
                        </div>

                        <div class="form-group margin half">
                            {!! Form::text('city', $client->city_id ? $client->city->title : null, ['placeholder' => ' ', 'id' => 'city']) !!}
                            {!! Form::label('', 'Cidade') !!}
                        </div>

                        <div class="form-group half">
                            {!! Form::text('state', $client->city_id ? $client->city->state->letter : null, ['placeholder' => ' ', 'id' => 'state']) !!}
                            {!! Form::label('', 'Estado') !!}
                        </div>
                    </div>

                    <div class="fields {{ (isset($navigation) && $navigation == 'access') ? 'show-fields' : '' }}">
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
                </div>
            </div>
        {!! Form::close() !!}
    </div>

    @include ('inc.footer')
@endsection
