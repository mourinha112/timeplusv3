@props([
    'title' => '',
    'value' => '',
    'description' => '',
    'icon' => '',
    'color' => 'blue',
    'subtitle' => null,
])

@php
    $colorClasses = [
        'blue' => ['bg' => 'from-blue-500 to-blue-600', 'text' => 'text-blue-100', 'icon' => 'text-blue-200'],
        'green' => ['bg' => 'from-green-500 to-green-600', 'text' => 'text-green-100', 'icon' => 'text-green-200'],
        'purple' => ['bg' => 'from-purple-500 to-purple-600', 'text' => 'text-purple-100', 'icon' => 'text-purple-200'],
        'red' => ['bg' => 'from-red-500 to-red-600', 'text' => 'text-red-100', 'icon' => 'text-red-200'],
        'yellow' => ['bg' => 'from-yellow-500 to-yellow-600', 'text' => 'text-yellow-100', 'icon' => 'text-yellow-200'],
    ];

    $colorConfig = $colorClasses[$color] ?? $colorClasses['blue'];
    $bgClass = "bg-gradient-to-br {$colorConfig['bg']}";
    $textClass = $colorConfig['text'];
    $iconClass = $colorConfig['icon'];
@endphp

<x-card {{ $attributes->merge(['class' => $bgClass . ' text-white']) }}>
    <x-card-body>
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold">{{ $title }}</h3>
                <p class="text-2xl font-bold">{{ $value }}</p>
                <p class="{{ $textClass }}">{{ $description }}</p>
                @if ($subtitle)
                    <p class="text-xs {{ $textClass }} mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            @if ($icon)
                <x-dynamic-component :component="$icon" class="w-10 h-10 {{ $iconClass }}" />
            @endif
        </div>
    </x-card-body>
</x-card>
