{!! Form::model($user->store, ['method' => 'POST', 'route' => 'set-store-config', 'id' => 'form-store-config']) !!}
    {!! Form::input('password', 'current_password') !!}

    {!! Form::email('email', $user->email, ['placeholder' => 'E-mail']) !!}

    {!! Form::input('password', 'password', null, ['placeholder' => 'Nova senha', 'id' => 'password', 'class' => 'margin half']) !!}

    {!! Form::input('password', 'password_confirmation', null, ['placeholder' => 'Confirmar nova senha', 'class' => 'half']) !!}

    {!! Form::text('name', null, ['placeholder' => 'Nome da loja', 'id' => 'name']) !!}

    <span class="slug">
        {!! Form::text('slug', null, ['id' => 'slug']) !!}
    </span>

    {!! Form::text('cep', null, ['placeholder' => 'Cep', 'id' => 'cep']) !!}

    {!! Form::text('street', null, ['placeholder' => 'Endereço', 'id' => 'street']) !!}

    {!! Form::text('number', null, ['placeholder' => 'Número', 'class' => 'margin half', 'id' => 'number']) !!}

    {!! Form::text('complement', null, ['placeholder' => 'Complemento', 'class' => 'half']) !!}

    {!! Form::text('district', null, ['placeholder' => 'Bairro', 'id' => 'district']) !!}

    {!! Form::text('city', $user->store->city_id ? $user->store->city->title : null, ['placeholder' => 'Cidade', 'class' => 'margin half', 'id' => 'city']) !!}

    {!! Form::text('state', $user->store->city_id ? $user->store->city->state->letter : null, ['placeholder' => 'Estado', 'class' => 'half', 'id' => 'state']) !!}

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
