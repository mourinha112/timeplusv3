<div>
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-3xl font-bold text-base-content flex items-center gap-3">
                Detalhes do Pagamento
            </h1>
        </x-heading>

        <div class="card card-compact bg-base-100 shadow-sm">
            <x-card-body>
                <div class="grid gap-6 md:grid-cols-[1fr_auto] items-start">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <x-text>Pagamento <span class="text-info font-bold">#{{ $payment->id }}</span></x-text>
                                <x-title class="text-2xl font-bold">
                                    {{ $payment->status ? ucfirst($payment->status) : '—' }}</x-title>
                            </div>
                            <div class="text-right">
                                <x-text>Criado em</x-text>
                                <div class="text-base font-medium">{{ $payment->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Valor</x-text>
                                <div class="text-base font-medium">R$
                                    {{ number_format((float) $payment->amount, 2, ',', '.') }}</div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Método</x-text>
                                <div class="text-base font-medium">
                                    @switch($payment->payment_method)
                                        @case('credit_card')
                                            Cartão de Crédito
                                        @break

                                        @case('pix')
                                            Pix
                                        @break

                                        @case('credit_balance')
                                            Saldo/Crédito
                                        @break

                                        @default
                                            {{ $payment->payment_method ?? '—' }}
                                    @endswitch
                                </div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Status</x-text>
                                <div class="text-base font-medium">{{ ucfirst($payment->status ?? '—') }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Pago em</x-text>
                                <div class="text-base font-medium">
                                    {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '—' }}</div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Expira em</x-text>
                                <div class="text-base font-medium">
                                    {{ $payment->expires_at ? $payment->expires_at->format('d/m/Y H:i') : '—' }}</div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Moeda</x-text>
                                <div class="text-base font-medium">{{ strtoupper($payment->currency ?? 'BRL') }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-4 bg-base-200/20 rounded-lg border border-base-300">
                                <div class="font-semibold mb-2">Referência</div>
                                <div>
                                    <div><x-text>Tipo:</x-text>
                                        {{ \Illuminate\Support\Str::of($payment->payable_type)->classBasename() }}</div>
                                    <div>
                                        <x-text>ID:</x-text>
                                        @if ($payment->payable_type === \App\Models\Appointment::class)
                                            <a wire:navigate class="link link-info"
                                                href="{{ route('master.appointment.show', ['appointment' => $payment->payable_id]) }}">#{{ $payment->payable_id }}</a>
                                        @else
                                            #{{ $payment->payable_id }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-base-200/20 rounded-lg border border-base-300">
                                <div class="font-semibold mb-2">Informações do Gateway</div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-text>Order ID:</x-text>
                                        <span class="font-mono ml-2">{{ $payment->gateway_order_id ?? '—' }}</span>
                                    </div>
                                    <div>
                                        <x-text>Charge ID:</x-text>
                                        <span class="font-mono ml-2">{{ $payment->gateway_charge_id ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($payment->description)
                            <div class="p-4 bg-base-200/20 rounded-lg border border-base-300">
                                <div class="font-semibold mb-2">Descrição</div>
                                <div class="text-sm">{{ $payment->description }}</div>
                            </div>
                        @endif

                        @if ($payment->refunded_amount)
                            <div class="p-4 bg-base-200/20 rounded-lg border border-info">
                                <div class="font-semibold mb-2">Reembolso</div>
                                <div>
                                    <div><x-text>Valor:</x-text> R$
                                        {{ number_format((float) $payment->refunded_amount, 2, ',', '.') }}</div>
                                    <div><x-text>Em:</x-text>
                                        {{ $payment->refunded_at?->format('d/m/Y H:i') ?? '—' }}</div>
                                    <div><x-text>Motivo:</x-text>
                                        {{ $payment->refund_reason ?? '—' }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3 w-full md:w-56">
                        @if (!$payment->manual_paid_at && $payment->status !== 'paid')
                            <button type="button" wire:click="openPayoffModal"
                                class="btn btn-success btn-sm">
                                <x-carbon-checkmark class="w-5 h-5" />
                                Dar baixa manual
                            </button>
                        @elseif ($payment->manual_paid_at)
                            <div class="alert alert-success p-3 text-sm">
                                Baixa manual em {{ $payment->manual_paid_at->format('d/m/Y H:i') }}
                                @if ($payment->manual_paid_note)
                                    <br><span class="text-xs">{{ $payment->manual_paid_note }}</span>
                                @endif
                            </div>
                        @endif

                        <a wire:navigate href="{{ route('master.payment.index') }}"
                            class="btn btn-soft btn-sm btn-info">
                            <x-carbon-arrow-left class="w-5 h-5" />
                            Voltar para a Lista
                        </a>
                    </div>
                </div>
            </x-card-body>
        </div>
    </div>

    @if ($showPayoffModal)
        <div x-data="{ show: @entangle('showPayoffModal') }" x-show="show"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
            @click.self="$wire.closePayoffModal()" @keydown.escape.window="$wire.closePayoffModal()" style="display: none;">
            <div class="w-full max-w-md mx-4 bg-base-100 rounded-lg shadow-xl p-6" @click.stop>
                <h3 class="text-xl font-bold mb-4">Dar baixa manual</h3>
                <p class="text-sm text-base-content/70 mb-3">
                    Esta ação marcará o pagamento como recebido e ficará registrado no relatório de baixas.
                </p>

                <x-form-group>
                    <x-label>Observação (opcional)</x-label>
                    <textarea wire:model="payoffNote" class="textarea textarea-bordered w-full" rows="3"
                        placeholder="Ex.: Recebido via TED em 28/04 conforme comprovante"></textarea>
                </x-form-group>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" wire:click="closePayoffModal" class="btn btn-ghost">Cancelar</button>
                    <button type="button" wire:click="payoff" class="btn btn-success">
                        Confirmar baixa
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
