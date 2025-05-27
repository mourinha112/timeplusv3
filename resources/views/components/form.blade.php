<form {{ $attributes->merge(['class' => 'flex flex-col gap-5']) }}>
    {{ $slot }}
</form>