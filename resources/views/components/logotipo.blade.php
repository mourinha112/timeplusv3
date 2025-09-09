@php
    $homeUrl = match ($guard ?? 'user') {
        'master' => route('master.dashboard.show'),
        default => env('APP_URL'),
    };
@endphp

<a href="{{ $homeUrl }}">
    <img src="{{ asset('images/logotipo.png') }}" {{ $attributes->merge(['class' => '']) }} />
</a>
