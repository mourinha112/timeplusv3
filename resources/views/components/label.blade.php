@props(['required' => false])

<label class="text-xs text-gray-700">
    {{ $slot }}:

    @if($required)
        <span class="text-red-600">*</span>
    @endif
</label>
