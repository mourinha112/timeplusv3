<div {{ $attributes->merge(['class' => 'card bg-base-100 card-md shadow-sm']) }}>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
