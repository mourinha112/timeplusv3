<div>
    <input type="radio" id="{{ $attributes->get('wire:model') }}" name="{{ $attributes->get('wire:model') }}" {{ $attributes->merge(['class' => "radio radio-sm radio-info mr-2"]) }} />
    {{ $slot }}
</div>
