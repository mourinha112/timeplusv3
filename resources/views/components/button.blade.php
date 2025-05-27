@php
    $formatting = 'w-full font-semibold py-3 rounded-md transition-colors duration-300 cursor-pointer text-xs uppercase';
    $color = 'text-white bg-yellow-500 hover:bg-yellow-600';
@endphp

<button {{ $attributes->merge(['class' => "{$formatting} {$color}"]) }}>
    {{ $slot }}
</button>
