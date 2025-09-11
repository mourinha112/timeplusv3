<div class="space-y-4">
    <x-heading>
        <x-title>Dashboard</x-title>
        <x-subtitle>Consulte relatórios e estatísticas.</x-subtitle>
    </x-heading>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 bg-base-100 border-base-300 border">
        <div class="stat">
            <div class="stat-title flex justify-between">
                Clientes
                <a href="{{ route('master.user.index') }}" class="badge badge-xs badge-ghost">Visualizar</a>
            </div>
            <div class="stat-value">{{ $this->users }}</div>
        </div>

        <div class="stat">
            <div class="stat-title flex justify-between">
                Especialistas
                <a href="{{ route('master.specialist.index') }}" class="badge badge-xs badge-ghost">Visualizar</a>
            </div>
            <div class="stat-value">{{ $this->specialists }}</div>
        </div>

        <div class="stat">
            <div class="stat-title flex justify-between">
                Empresas
                <a href="{{ route('master.company.index') }}" class="badge badge-xs badge-ghost">Visualizar</a>
            </div>
            <div class="stat-value">{{ $this->companies }}</div>
        </div>

        <div class="stat">
            <div class="stat-title flex justify-between">
                Agendamentos
                <a href="{{ route('master.appointment.index') }}" class="badge badge-xs badge-ghost">Visualizar</a>
            </div>
            <div class="stat-value">{{ $this->appointments }}</div>
        </div>

        <div class="stat">
            <div class="stat-title flex justify-between">
                Pagamentos
                <a href="{{ route('master.payment.index') }}" class="badge badge-xs badge-ghost">Visualizar</a>
            </div>
            <div class="stat-value">R$ {{ number_format($this->payments['total'], 2, ',', '.') }}</div>
            <div class="stat-desc">{{ $this->payments['count'] }} pagamentos aprovados</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <div class="bg-base-100 border-base-300 border p-6 rounded-lg">
            <div class="mb-4">
                <h3 class="text-lg font-semibold">Vendas de Sessões</h3>
                <p class="text-sm text-gray-600">Agendamentos de consultas ao longo do tempo</p>
            </div>
            <livewire:master.dashboard.charts.session-invoice-chart />
        </div>

        <div class="bg-base-100 border-base-300 border p-6 rounded-lg">
            <div class="mb-4">
                <h3 class="text-lg font-semibold">Vendas de Planos</h3>
                <p class="text-sm text-gray-600">Pagamentos de planos aprovados por mês</p>
            </div>
            <livewire:master.dashboard.charts.plans-invoice-chart />
        </div>

    </div>
</div>
