<div>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <x-heading>
                <h1 class="text-xl font-bold text-base-content flex items-center gap-3">
                    <x-carbon-calendar-tools class="w-8 text-info" />
                    Disponibilidades dos Especialistas
                </h1>
            </x-heading>

            <a wire:navigate href="{{ route('master.availability.by-specialist') }}"
                class="btn btn-soft btn-info">
                <x-carbon-user class="w-4 h-4" />
                Ver por profissional
            </a>
        </div>

        <livewire:master.availability.show-table />
    </div>
</div>
