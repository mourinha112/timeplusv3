<div>
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-xl font-bold text-base-content flex items-center gap-3">
                <x-carbon-certificate class="w-8 text-info" />
                Especialidades
            </h1>
        </x-heading>

        <div class="flex justify-end">
            <a wire:navigate href="{{ route('master.specialty.create') }}" class="btn btn-info btn-sm">
                <x-carbon-add class="w-4 h-4" /> Nova Especialidade
            </a>
        </div>

        <livewire:master.specialty.show-table />
    </div>
</div>
