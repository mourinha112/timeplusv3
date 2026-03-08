<div>
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-xl font-bold text-base-content flex items-center gap-3">
                <x-carbon-wallet class="w-8 text-info" />
                Financeiro - {{ $specialist->name }}
            </h1>
        </x-heading>

        <div class="flex justify-start">
            <a wire:navigate href="{{ route('master.finance.index') }}" class="btn btn-soft btn-sm btn-info">
                <x-carbon-arrow-left class="w-4 h-4" /> Voltar
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 bg-base-100 border-base-300 border">
            <x-stat title="Sessoes Concluidas" :value="$this->completedSessions" />
            <x-stat title="Total Bruto" value="R$ {{ number_format($this->totalEarned, 2, ',', '.') }}" />
            <x-stat title="Taxa Plataforma ({{ $specialist->paymentProfile?->platform_fee_percentage ?? 20 }}%)" value="R$ {{ number_format($this->platformFee, 2, ',', '.') }}" />
            <x-stat title="Saldo Especialista" value="R$ {{ number_format($this->specialistBalance, 2, ',', '.') }}" />
        </div>

        {{-- Dados de Pagamento --}}
        <div class="card card-compact bg-base-100 shadow-sm">
            <x-card-body>
                <h3 class="text-lg font-semibold mb-4">Dados de Pagamento</h3>

                @if ($specialist->paymentProfile)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-4 bg-base-200/30 rounded-lg">
                            <x-text>Titular</x-text>
                            <div class="text-base font-medium">{{ $specialist->paymentProfile->holder_name }}</div>
                            <div class="text-sm text-base-content/60">CPF: {{ $specialist->paymentProfile->holder_cpf }}</div>
                        </div>

                        <div class="p-4 bg-base-200/30 rounded-lg">
                            <x-text>Tipo</x-text>
                            <div class="text-base font-medium">
                                @if ($specialist->paymentProfile->isPixPayment())
                                    <span class="badge badge-info">PIX</span>
                                    <div class="mt-1 text-sm">
                                        {{ ucfirst($specialist->paymentProfile->pix_key_type) }}: {{ $specialist->paymentProfile->pix_key }}
                                    </div>
                                @else
                                    <span class="badge badge-info">Conta Bancaria</span>
                                    <div class="mt-1 text-sm">{{ $specialist->paymentProfile->formatted_bank_account }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 bg-base-200/30 rounded-lg">
                            <x-text>Verificacao</x-text>
                            <div class="text-base font-medium">
                                @if ($specialist->paymentProfile->is_verified)
                                    <span class="badge badge-success">Verificado</span>
                                    <div class="text-sm text-base-content/60 mt-1">
                                        {{ $specialist->paymentProfile->verified_at?->format('d/m/Y H:i') }}
                                    </div>
                                @else
                                    <span class="badge badge-warning">Pendente</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        Este especialista ainda nao cadastrou seus dados de pagamento.
                    </div>
                @endif
            </x-card-body>
        </div>

        {{-- Historico de Pagamentos --}}
        <div class="card card-compact bg-base-100 shadow-sm">
            <x-card-body>
                <h3 class="text-lg font-semibold mb-4">Ultimos Pagamentos Recebidos</h3>

                @if ($this->recentPayments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data</th>
                                    <th>Metodo</th>
                                    <th>Valor Bruto</th>
                                    <th>Taxa</th>
                                    <th>Repasse</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->recentPayments as $payment)
                                    @php
                                        $fee = $specialist->paymentProfile?->platform_fee_percentage ?? 20;
                                        $platformAmount = round($payment->amount * $fee / 100, 2);
                                        $payout = round($payment->amount - $platformAmount, 2);
                                    @endphp
                                    <tr>
                                        <td>#{{ $payment->id }}</td>
                                        <td>{{ $payment->paid_at?->format('d/m/Y H:i') ?? '---' }}</td>
                                        <td>
                                            @if ($payment->payment_method === 'credit_card')
                                                <span class="badge badge-sm">Cartao</span>
                                            @elseif ($payment->payment_method === 'pix')
                                                <span class="badge badge-sm badge-info">PIX</span>
                                            @else
                                                {{ $payment->payment_method }}
                                            @endif
                                        </td>
                                        <td>R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                                        <td class="text-error">- R$ {{ number_format($platformAmount, 2, ',', '.') }}</td>
                                        <td class="text-success font-medium">R$ {{ number_format($payout, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-base-content/60 py-8">
                        Nenhum pagamento encontrado.
                    </div>
                @endif
            </x-card-body>
        </div>
    </div>
</div>
