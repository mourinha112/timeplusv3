<div class="space-y-4">
    <x-heading>
        <x-title>Dashboard</x-title>
        <x-subtitle>Consulte relatórios e estatísticas.</x-subtitle>
    </x-heading>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 bg-base-100 border-base-300 border">
        <div class="stat">
            <div class="stat-title flex justify-between">
                Clientes
                <a href="#" class="badge badge-xs badge-ghost">Visualizar</a>
            </div>
            <div class="stat-value">{{ $this->users }}</div>
        </div>

        <div class="stat">
            <div class="stat-title flex justify-between">
                Especialistas
                <a href="#" class="badge badge-xs badge-ghost">Visualizar</a>
            </div>
            <div class="stat-value">{{ $this->specialists }}</div>
        </div>

        <div class="stat">
            <div class="stat-title flex justify-between">
                Agendamentos
                <a href="#" class="badge badge-xs badge-ghost">Visualizar</a>
            </div>
            <div class="stat-value">{{ $this->appointments }}</div>
        </div>

        <div class="stat">
            <div class="stat-title flex justify-between">
                Pagamentos
                <a href="#" class="badge badge-xs badge-ghost">Visualizar</a>
            </div>
            <div class="stat-value">R${{ $this->payments }}</div>
        </div>

    </div>
</div>
