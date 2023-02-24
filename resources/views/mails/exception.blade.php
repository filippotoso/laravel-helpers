<h1>{{ $message }}</h1>
<p>
    <b>Method:</b> {{ $method }}<br>
    <b>Url:</b> {{ $url }}<br>
    <b>IP:</b> {{ request()->ip() }}<br>
    <b>Content:</b> {{ $content }}<br>
    <b>Headers:</b>
    @foreach($headers as $header => $items)
        @foreach($items as $item)
            {{ $header }}: {{ $item}}<br>
        @endforeach
    @endforeach
    <br>
    {!! $html !!}
</p>

