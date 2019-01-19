{!! Form::model($user->store, ['method' => 'POST', 'route' => 'set-store-config', 'id' => 'form-store-config']) !!}
    {!! Form::input('password', 'current_password') !!}

    <div class="form-group">
        {!! Form::email('email', $user->email, ['placeholder' => ' ']) !!}
        {!! Form::label('', 'E-mail') !!}
    </div>

    <div class="form-group margin half">
        {!! Form::input('password', 'password', null, ['placeholder' => ' ', 'id' => 'password']) !!}
        {!! Form::label('', 'Nova senha') !!}
    </div>

    <div class="form-group half">
        {!! Form::input('password', 'password_confirmation', null, ['placeholder' => ' ']) !!}
        {!! Form::label('', 'Confirmar nova senha') !!}
    </div>

    <div class="form-group name">
        {!! Form::text('name', null, ['placeholder' => ' ']) !!}
        {!! Form::label('', 'Nome da loja') !!}
    </div>

    <div class="form-group slug">
        {!! Form::text('slug', null, ['placeholder' => ' ', 'id' => 'slug', 'class' => 'move-placeholder']) !!}
        {!! Form::label('', 'Slug') !!}
    </div>

    <div class="form-group">
        {!! Form::text('cep', null, ['placeholder' => ' ', 'id' => 'cep']) !!}
        {!! Form::label('', 'Cep') !!}
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

    <div class="form-group margin half margin-bottom">
        {!! Form::text('city', $user->store->city_id ? $user->store->city->title : null, ['placeholder' => ' ', 'id' => 'city']) !!}
        {!! Form::label('', 'Cidade') !!}
    </div>

    <div class="form-group half margin-bottom">
        {!! Form::text('state', $user->store->city_id ? $user->store->city->state->letter : null, ['placeholder' => ' ', 'id' => 'state']) !!}
        {!! Form::label('', 'Estado') !!}
    </div>

    <div class="switch-container" title="Mostra ou oculta a sua loja no site">
        <label class="switch">
            {!! Form::checkbox('status') !!}
            <span class="slider"></span>
        </label>

        <span class="title-switch">Perfil da loja</span>
    </div>

    <div class="switch-container" title="Habilita ou desabilita o recurso de reserva dos produtos da sua loja">
        <label class="switch">
            {!! Form::checkbox('reserve') !!}
            <span class="slider"></span>
        </label>

        <span class="title-switch">Reserva de produtos</span>
    </div>

    {!! Form::submit('SALVAR') !!}

    <a href="{{ route('delete-store-account') }}" id="delete-store-account">Deletar conta</a>
{!! Form::close() !!}
