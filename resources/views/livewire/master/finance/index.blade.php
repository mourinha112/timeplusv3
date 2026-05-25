<div>
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-xl font-bold text-base-content flex items-center gap-3">
                <x-carbon-wallet class="w-8 text-info" />
                Financeiro - Especialistas
            </h1>
        </x-heading>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 bg-base-100 border-base-300 border">
            <x-stat title="Receita Total (Sessões)" value="R$ {{ number_format($this->totalRevenue, 2, ',', '.') }}" />
            <x-stat title="Receita da Plataforma" value="R$ {{ number_format($this->totalPlatformFee, 2, ',', '.') }}" />
            <x-stat title="Total Repasses Devidos" value="R$ {{ number_format($this->totalSpecialistPayout, 2, ',', '.') }}" />
            <x-stat title="Repasses Realizados" value="R$ 0,00" />
            <x-stat title="Dados de Pgto Cadastrados" :value="$this->profilesCount" />
        </div>

        <div class="flex flex-wrap gap-2">
            <a wire:navigate href="{{ route('master.finance.index') }}"
                class="btn btn-sm {{ Route::is('master.finance.index') ? 'btn-info' : 'btn-soft' }}">
                Especialistas
            </a>
            <a wire:navigate href="{{ route('master.finance.bank-approvals') }}"
                class="btn btn-sm {{ Route::is('master.finance.bank-approvals') ? 'btn-warning' : 'btn-soft' }}">
                Aprovação de dados de pagamento
                @if ($this->pendingProfilesCount > 0)
                    <span class="badge badge-error badge-sm">{{ $this->pendingProfilesCount }}</span>
                @endif
            </a>
        </div>

        <livewire:master.finance.show-table />
    </div>
</div>
