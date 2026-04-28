@php
    $wireModel = $attributes->get('wire:model') ?? $attributes->get('wire:model.live');
    $boundValue = null;
    if ($wireModel) {
        $boundValue = data_get($this ?? null, $wireModel);
    }
    $isChecked = $attributes->has('checked') || (bool) $boundValue;
@endphp
<label class="label">
    <input type="checkbox"
        id="{{ $wireModel }}"
        name="{{ $wireModel }}"
        @if ($isChecked) checked @endif
        {{ $attributes->except('checked')->merge(['class' => 'checkbox']) }} />
    {{ $slot }}
</label>
