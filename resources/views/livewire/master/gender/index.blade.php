<div>
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-xl font-bold text-base-content flex items-center gap-3">
                <x-carbon-gender-female class="w-8 text-info" />
                Generos
            </h1>
        </x-heading>

        <div class="flex justify-end">
            <a wire:navigate href="{{ route('master.gender.create') }}" class="btn btn-info btn-sm">
                <x-carbon-add class="w-4 h-4" /> Novo Genero
            </a>
        </div>

        <livewire:master.gender.show-table />
    </div>
</div>
