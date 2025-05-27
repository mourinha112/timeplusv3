@php
    $formatting = "w-full px-4 py-3 border rounded-sm text-sm transition-all duration-300 ease-in-out focus:ring-2 focus:outline-none";
    $color = "text-gray-600 border-gray-200 hover:border-yellow-400 focus:border-yellow-600 focus:ring-yellow-400";
@endphp

<div>
    <input name="{{ $attributes->get('wire:model') }}" {{ $attributes->merge(['class' => "{$formatting} {$color}"]) }} />

    @error($attributes->get('wire:model'))
        <small class="text-red-600">{{ $message }}</small>
    @enderror
</div>
