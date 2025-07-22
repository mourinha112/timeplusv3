@props(['color' => 'primary'])

@php
    $colors = [
        'primary' => 'btn btn-info btn-block',
    ];
@endphp

<button {{ $attributes->merge(['class' => "{$colors[$color]}"]) }}>
    {{ $slot }}
</button>
