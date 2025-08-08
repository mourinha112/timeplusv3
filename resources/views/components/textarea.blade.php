<div>
    <textarea id="{{ $attributes->get('wire:model') }}" name="{{ $attributes->get('wire:model') }}" {{ $attributes->merge(['class' => "textarea w-full"]) }}></textarea>

    @error($attributes->get('wire:model'))
        <small class="text-red-600 text-xs">{{ $message }}</small>
    @enderror
</div>
