<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laravel</title>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <h1> {{ $stock->name }} {{ $stock->ticker }} </h1>
        current {{ Str::currency($json->c) }} {{ Str::percentage(($json->c - $json->pc) / $json->pc * 100) }} <br>
        open {{ Str::currency($json->o) }} <br>
        previous close {{ Str::currency($json->pc) }} <br>

        <h2> Consolidated position </h2>
        {{ Str::integer($trades->sum('quantity')) }} units <br>
        {{ Str::currency($trades->sum('total') / $trades->sum('quantity')) }} {{ $stock->currency }} <br>
        {{ Str::currency($trades->sum('total')) }} {{ $stock->currency }} <br>
        <div class="title m-b-md">
            <h3> {{ trans_choice('general.trades', $trades->count()) }} </h3>
            @foreach ($trades as $trade)
                {{ $trade->purchase_date }} date <br>
                {{ Str::integer($trade->quantity) }} units <br>
                {{ Str::currency($trade->purchase_price) }} {{ $stock->currency }} <br>
                {{ Str::currency($trade->total) }} {{ $stock->currency }} <br>
                <br>
            @endforeach
        </div>
    </div>
</div>
</body>
</html>
