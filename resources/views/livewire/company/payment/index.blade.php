<div class="space-y-6">
    <!-- Header -->
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-money class="w-8 text-info" />
            Pagamentos dos Funcionários
        </x-title>
        <x-subtitle>
            Acompanhe os pagamentos de sessões realizadas pelos seus funcionários
        </x-subtitle>
    </x-heading>



    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total de Pagamentos -->
        <x-card>
            <x-card-body>
                <div class="flex items-center justify-between">
                    <div>
                        <x-text>Total de Pagamentos</x-text>
                        <x-title>{{ $totalPayments }}</x-title>
                    </div>
                    <div class="p-3 bg-primary/10 rounded-full">
                        <x-carbon-receipt class="w-6 h-6 text-primary" />
                    </div>
                </div>
            </x-card-body>
        </x-card>


        <!-- Total de Desconto -->
        <x-card>
            <x-card-body>
                <div class="flex items-center justify-between">
                    <div>
                        <x-text class="font-medium">Total de Desconto</x-text>
                        <p class="text-2xl font-bold text-info">R$ {{ number_format($totalDiscount, 2, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-info/10 rounded-full">
                        <x-carbon-tag class="w-6 h-6 text-info" />
                    </div>
                </div>
            </x-card-body>
        </x-card>
    </div>

    <!-- Tabela de Pagamentos -->
    <x-card>
        <div class="card-header border-b border-base-300 p-6">
            <x-title>Histórico de Pagamentos</x-title>
            <x-text>Detalhes de todas as sessões pagas pelos funcionários</x-text>
        </div>
        <x-card-body>
            <livewire:company.payment.show-table />
        </x-card-body>
    </x-card>
</div>
