@props(['required' => false])

<label class="text-sm">
    {{ $slot }}:

    @if($required)
        <span class="text-red-600">*</span>
    @endif
</label>
