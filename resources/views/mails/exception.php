<html>

<body>
    <h1>{{ $message }}</h1>
    <p>
        <b>Method:</b> {{ $method }}<br>
        <b>Url:</b> {{ $url }}<br>
        <b>Request:</b> {{ $request }}<br>
        <b>Contet:</b> {{ $content }}<br>
    </p>
    {!! $exception !!}
</body>

</html>