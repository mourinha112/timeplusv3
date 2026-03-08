<div>
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-xl font-bold text-base-content flex items-center gap-3">
                <x-carbon-education class="w-8 text-info" />
                Tipos de Formacao
            </h1>
        </x-heading>

        <div class="flex justify-end">
            <a wire:navigate href="{{ route('master.training-type.create') }}" class="btn btn-info btn-sm">
                <x-carbon-add class="w-4 h-4" /> Novo Tipo de Formacao
            </a>
        </div>

        <livewire:master.training-type.show-table />
    </div>
</div>
