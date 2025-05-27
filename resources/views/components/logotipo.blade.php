@props(['color' => 'black'])

@php
    $path = match ($color) {
        'black' => 'images/logo-black.png',
        'yellow' => 'images/logo-yellow.png',
    };
@endphp

<a href="{{ env('APP_URL') }}">
    <img src="{{ asset($path) }}" {{ $attributes->merge(['class' => '']) }} />
</a>
