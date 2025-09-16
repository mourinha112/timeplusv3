@props([
    'href' => '#',
    'icon' => '',
    'title' => '',
    'description' => '',
    'iconColor' => 'info',
])

@php
    $iconColorClasses = [
        'info' => 'text-info',
        'success' => 'text-success',
        'warning' => 'text-warning',
        'error' => 'text-error',
        'primary' => 'text-primary',
    ];

    $iconColorClass = $iconColorClasses[$iconColor] ?? 'text-info';
@endphp

<a href="{{ $href }}"
    {{ $attributes->merge(['class' => 'p-4 border border-base-300 rounded-lg hover:bg-base-100 transition-colors']) }}>
    <div class="flex items-center gap-3">
        @if ($icon)
            <x-dynamic-component :component="$icon" class="w-6 h-6 {{ $iconColorClass }}" />
        @endif
        <div>
            <h4 class="font-semibold text-base-content">{{ $title }}</h4>
            <x-text>{{ $description }}</x-text>
        </div>
    </div>
</a>
