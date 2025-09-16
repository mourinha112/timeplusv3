<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-base-content">Pagamentos dos Funcionários</h1>
            <p class="text-base-content/70 mt-1">Acompanhe os pagamentos de sessões realizadas pelos seus funcionários
            </p>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total de Pagamentos -->
        <div class="card bg-base-100 shadow-lg border border-base-300">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base-content/70 text-sm font-medium">Total de Pagamentos</p>
                        <p class="text-2xl font-bold text-base-content">{{ $totalPayments }}</p>
                    </div>
                    <div class="p-3 bg-primary/10 rounded-full">
                        <x-carbon-receipt class="w-6 h-6 text-primary" />
                    </div>
                </div>
            </div>
        </div>


        <!-- Total de Desconto -->
        <div class="card bg-base-100 shadow-lg border border-base-300">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base-content/70 text-sm font-medium">Total de Desconto</p>
                        <p class="text-2xl font-bold text-info">R$ {{ number_format($totalDiscount, 2, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-info/10 rounded-full">
                        <x-carbon-tag class="w-6 h-6 text-info" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Pagamentos -->
    <div class="card bg-base-100 shadow-lg border border-base-300">
        <div class="card-header border-b border-base-300 p-6">
            <h2 class="text-xl font-semibold text-base-content">Histórico de Pagamentos</h2>
            <p class="text-base-content/70 text-sm mt-1">Detalhes de todas as sessões pagas pelos funcionários</p>
        </div>
        <div class="card-body p-0">
            <livewire:company.payment.show-table />
        </div>
    </div>
</div>
