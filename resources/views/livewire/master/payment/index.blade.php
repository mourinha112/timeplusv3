<div>
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-xl font-bold text-base-content flex items-center gap-3">
                <x-carbon-money class="w-8 text-info" />
                Pagamentos
            </h1>
            <a wire:navigate href="{{ route('master.payment.payoff-report') }}" class="btn btn-info btn-sm">
                <x-carbon-document class="w-4 h-4" />
                Relatório de baixas
            </a>
        </x-heading>

        <livewire:master.payment.show-table />
    </div>

</div>
