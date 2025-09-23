@props(['title', 'value', 'description' => null, 'route' => null])

<div class="stat">
    <div class="stat-title flex justify-between">
        {{ $title }}
        @if ($route)
            <a wire:navigate href="{{ $route }}" class="badge badge-xs badge-ghost">Visualizar</a>
        @endif
    </div>
    <div class="stat-value">{{ $value }}</div>
    @if ($description)
        <div class="stat-desc">{{ $description }}</div>
    @endif
</div>
