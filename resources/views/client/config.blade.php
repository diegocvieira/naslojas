{!! Form::model($client, ['method' => 'POST', 'route' => 'set-client-config', 'id' => 'form-client-config']) !!}
    {!! Form::input('password', 'current_password') !!}

    <div class="form-group">
        {!! Form::text('name', null, ['placeholder' => ' ']) !!}
        {!! Form::label('', 'Nome') !!}
    </div>

    <div class="form-group">
        {!! Form::email('email', null, ['placeholder' => ' ']) !!}
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

    {!! Form::submit('SALVAR') !!}

    <a href="{{ route('delete-client-account') }}" id="delete-client-account">Deletar conta</a>
{!! Form::close() !!}
