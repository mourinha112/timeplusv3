@props(['required' => false])

<legend class="fieldset-legend">
    {{ $slot }}:

    @if($required)
        <span class="text-red-600">*</span>
    @endif
</legend>