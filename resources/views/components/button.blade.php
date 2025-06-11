@props(['color' => 'primary'])

@php
$formatting = 'h-full font-semibold py-3 px-4 rounded-md transition-colors duration-300 cursor-pointer text-xs uppercase';

$colors = [
'primary' => 'text-gray-800 bg-yellow-500 hover:bg-yellow-600',
'secondary' => 'text-white bg-black hover:bg-black/80',
];


@endphp

<button {{ $attributes->merge(['class' => "{$formatting} {$colors[$color]}"]) }}>
    {{ $slot }}
</button>
