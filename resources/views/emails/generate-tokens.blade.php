Foram gerados {{ count($tokens) }} tokens:
<br><br>
@foreach ($tokens as $token)
    {{ $token }}
    <br>
@endforeach
