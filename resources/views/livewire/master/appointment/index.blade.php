<div>
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-xl font-bold text-base-content flex items-center gap-3">
                <x-carbon-calendar-heat-map class="w-8 text-info" />
                Agendamentos
            </h1>
            <a wire:navigate href="{{ route('master.appointment.create') }}" class="btn btn-info btn-sm">
                <x-carbon-add class="w-4 h-4" />
                Novo agendamento
            </a>
        </x-heading>

        <livewire:master.appointment.show-table />
    </div>
</div>
