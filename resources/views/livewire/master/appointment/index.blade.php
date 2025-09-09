<div class="container mx-auto">
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-xl font-bold text-base-content flex items-center gap-1">
                <x-carbon-calendar-heat-map class="w-8 text-info" />
                Agendamentos
            </h1>
        </x-heading>

        <livewire:master.appointment.show-table />
    </div>
</div>
