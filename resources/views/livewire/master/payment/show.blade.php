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
</div>
