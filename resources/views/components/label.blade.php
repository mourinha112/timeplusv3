@props(['required' => false])

<legend class="fieldset-legend font-normal text-sm text-slate-800">
    {{ $slot }}:

    @if($required)
        <span class="text-red-600">*</span>
    @endif
</legend>