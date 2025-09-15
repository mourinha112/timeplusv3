@props([
    'label' => '',
    'value' => '',
    'mono' => false,
])

<div>
    <span class="text-sm font-medium text-base-content/70">{{ $label }}:</span>
    <p class="text-base-content {{ $mono ? 'font-mono' : '' }}">{{ $value }}</p>
</div>
