<label class="label">
    <input type="checkbox" id="{{ $attributes->get('wire:model') }}" name="{{ $attributes->get('wire:model') }}" {{ $attributes->merge(['class' => "checkbox"]) }} />
    {{ $slot }}
</label>
