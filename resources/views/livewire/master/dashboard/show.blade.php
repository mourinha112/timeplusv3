<div>
    <x-heading>
        <x-title>Painel</x-title>
        <x-subtitle>Consulte relatórios e estatísticas.</x-subtitle>
    </x-heading>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 bg-base-100 border-base-300 border">

        <x-stat title="Usuários" :value="$this->users" :route="route('master.user.index')" />

        <x-stat title="Especialistas" :value="$this->specialists" :route="route('master.specialist.index')" />

        <x-stat title="Empresas" :value="$this->companies" :route="route('master.company.index')" />

        <x-stat title="Agendamentos" :value="$this->appointments" :route="route('master.appointment.index')" />

        <x-stat title="Pagamentos" value="R$ {{ number_format($this->payments['total'], 2, ',', '.') }}" :route="route('master.payment.index')"
            description="{{ $this->payments['count'] }} pagamentos aprovados" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-8">

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
