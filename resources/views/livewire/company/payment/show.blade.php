<div class="space-y-3">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a wire:navigate href="{{ route('company.payment.index') }}" class="btn btn-ghost btn-circle">
                <x-carbon-arrow-left class="w-8 text-info" />
            </a>
            <div>
                <h1 class="text-3xl font-bold text-base-content">Detalhes do Pagamento</h1>
                <x-text class="mt-1">Pagamento #{{ $payment->id }}</x-text>
            </div>
        </div>
        <div
            class="badge badge-lg {{ $payment->status === 'paid' ? 'badge-success' : ($payment->status === 'pending' ? 'badge-warning' : 'badge-error') }}">

            @if (ucfirst($payment->status) === 'Paid')
                Pago
            @elseif(ucfirst($payment->status) === 'Pending')
                Pendente
            @else
                Cancelado
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informações do Funcionário -->
        <x-card>
            <div class="card-header border-b border-base-300 p-6">
                <h2 class="text-xl font-semibold text-base-content flex items-center">
                    <x-carbon-user class="w-5.5 mr-2 text-info" />
                    Funcionário
                </h2>
            </div>
            <x-card-body class="space-y-4">
                <div>
                    <x-label class="text-base-content/70">Nome</x-label>
                    <x-text>{{ $payment->payable?->user?->name ?? 'N/A' }}</x-text>
                </div>
                <div>
                    <x-label>E-mail</x-label>
                    <x-text>{{ $payment->payable?->user?->email ?? 'N/A' }}</x-text>
                </div>
                @if ($payment->company_plan_name)
                    <div>
                        <x-label>Plano</x-label>
                        <x-text>{{ $payment->company_plan_name }}</x-text>
                    </div>
                @endif
            </x-card-body>
        </x-card>

        <!-- Informações da Sessão -->
        <x-card>
            <div class="card-header border-b border-base-300 p-6">
                <h2 class="text-xl font-semibold text-base-content flex items-center">
                    <x-carbon-calendar class="w-5.5 mr-2 text-info" />
                    Sessão
                </h2>
            </div>
            <x-card-body class="space-y-4">
                <div>
                    <x-label>Especialista</x-label>
                    <x-text>{{ $payment->payable?->specialist?->name ?? 'N/A' }}</x-text>
                </div>
                @if ($payment->payable?->appointment_date)
                    <div>
                        <x-label>Data</x-label>
                        <x-text>
                            {{ \Carbon\Carbon::parse($payment->payable->appointment_date)->format('d/m/Y') }}</x-text>
                    </div>
                @endif
                @if ($payment->payable?->appointment_time)
                    <div>
                        <x-label>Horário</x-label>
                        <x-text>
                            {{ \Carbon\Carbon::parse($payment->payable->appointment_time)->format('H:i') }}h</x-text>
                    </div>
                @endif
            </x-card-body>
        </x-card>

        <!-- Informações do Pagamento -->
        <x-card>
            <div class="card-header border-b border-base-300 p-6">
                <h2 class="text-xl font-semibold text-base-content flex items-center">
                    <x-carbon-money class="w-5.5 mr-2 text-info" />
                    Valores
                </h2>
            </div>
            <x-card-body class="space-y-4">
                <div class="flex justify-between">
                    <x-label>Valor Original</x-label>
                    <x-text>R$
                        {{ number_format($payment->amount + ($payment->discount_value ?? 0), 2, ',', '.') }}</x-text>
                </div>
                @if ($payment->discount_percentage > 0)
                    <div class="flex justify-between">
                        <x-label>Desconto
                            ({{ $payment->discount_percentage }}%)</x-label>
                        <x-text class="text-error">- R$
                            {{ number_format($payment->discount_value, 2, ',', '.') }}</x-text>
                    </div>
                @endif

                <div class="divider my-2"></div>
                <div class="flex justify-between items-center">
                    <x-label>Total (Cobrado ao funcionário)</x-label>
                    <x-text class="text-xl font-medium text-success">R$
                        {{ number_format($payment->amount, 2, ',', '.') }}</x-text>
                </div>
                <div class="flex justify-between items-center">
                    <x-label>Desconto (Cobrado à {{ $payment->company?->name ?? 'Empresa' }})</x-label>
                    <x-text class="text-xl font-medium text-success">R$
                        {{ number_format($payment->discount_value, 2, ',', '.') }}</x-text>
                </div>
            </x-card-body>
        </x-card>

        <!-- Informações Técnicas -->
        <x-card>
            <div class="card-header border-b border-base-300 p-6">
                <h2 class="text-xl font-semibold text-base-content flex items-center">
                    <x-carbon-information class="w-5.5 mr-2 text-info" />
                    Detalhes do Pagamento
                </h2>
            </div>
            <x-card-body>
                <div>
                    <x-label>Método de Pagamento</x-label>
                    <x-text>
                        @switch($payment->payment_method)
                            @case('credit_card')
                                Cartão de Crédito
                            @break

                            @case('pix')
                                PIX
                            @break

                            @default
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        @endswitch
                    </x-text>
                </div>
                @if ($payment->gateway_order_id)
                    <div>
                        <x-label>ID do Gateway</x-label>
                        <x-text>{{ $payment->gateway_order_id }}</x-text>
                    </div>
                @endif
                @if ($payment->paid_at)
                    <div>
                        <x-label>Data do Pagamento</x-label>
                        <x-text>
                            {{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') }}</x-text>
                    </div>
                @endif
                <div>
                    <x-label>Criado em</x-label>
                    <x-text>{{ $payment->created_at->format('d/m/Y H:i') }}</x-text>
                </div>
            </x-card-body>
        </x-card>
    </div>
</div>
