<div>
    <x-heading>
        <x-title>Planos da Plataforma</x-title>
        <x-subtitle>Gerencie os planos disponíveis para os usuários.</x-subtitle>
    </x-heading>
    <div class="flex justify-end mb-4">
        <a wire:navigate href="{{ route('master.plan.create') }}" class="btn btn-info btn-sm">
            <x-carbon-add class="w-4 h-4" /> Novo Plano
        </a>
    </div>
    <livewire:master.plan.show-table />
</div>
