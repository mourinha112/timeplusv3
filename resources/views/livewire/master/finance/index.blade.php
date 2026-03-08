<div>
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-xl font-bold text-base-content flex items-center gap-3">
                <x-carbon-wallet class="w-8 text-info" />
                Financeiro - Especialistas
            </h1>
        </x-heading>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 bg-base-100 border-base-300 border">
            <x-stat title="Receita Total (Sessoes)" value="R$ {{ number_format($this->totalRevenue, 2, ',', '.') }}" />
            <x-stat title="Taxa Plataforma" value="R$ {{ number_format($this->totalPlatformFee, 2, ',', '.') }}" />
            <x-stat title="Total Repasses" value="R$ {{ number_format($this->totalSpecialistPayout, 2, ',', '.') }}" />
            <x-stat title="Perfis Cadastrados" :value="$this->profilesCount" />
        </div>

        <livewire:master.finance.show-table />
    </div>
</div>
