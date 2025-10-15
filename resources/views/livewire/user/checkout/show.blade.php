<div>
    @php
        $isAppointment = $payable instanceof \App\Models\Appointment;
        $isSubscribe = $payable instanceof \App\Models\Subscribe;
    @endphp

    <x-heading>
        <x-title>Realizar pagamento</x-title>
        <x-subtitle>
            @if ($isAppointment)
                Realize o pagamento da sua sessão com a forma de pagamento selecionada.
            @elseif($isSubscribe)
                Realize o pagamento do plano com a forma de pagamento selecionada.
            @else
                Realize o pagamento com a forma de pagamento selecionada.
            @endif
        </x-subtitle>
    </x-heading>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="md:col-span-3">
            {{-- Seleção de Método de Pagamento --}}
            @if (empty($payment_method) && empty($payable->payment->payment_method))
                <div class="space-y-4">
                    <x-card class="cursor-pointer" wire:click="selectPaymentMethod('pix')">
                        <x-card-body>
                            <div class="space-y-2">
                                <x-card-title>
                                    <x-carbon-qr-code class="w-5 h-5 text-info" />
                                    PIX
                                </x-card-title>
                                <x-text>Realize o pagamento utilizando PIX.</x-text>
                            </div>
                        </x-card-body>
                    </x-card>

                    <x-card class="cursor-pointer" wire:click="selectPaymentMethod('credit_card')">
                        <x-card-body>
                            <div class="space-y-2">
                                <x-card-title>
                                    <x-carbon-purchase class="w-5 h-5 text-info" />
                                    Cartão de crédito
                                </x-card-title>
                                <x-text>Realize o pagamento utilizando cartão de crédito.</x-text>
                            </div>
                        </x-card-body>
                    </x-card>
                </div>
            @endif

            {{-- QR Code PIX --}}
            @if ($payment_method === 'pix' || $payable->payment?->payment_method === 'pix')
                <livewire:user.checkout.pix :payable="$payable" :key="'pix-' . $payable->id" />
            @endif

            {{-- Cartão de Crédito --}}
            @if ($payment_method === 'credit_card' || $payable->payment?->payment_method === 'credit_card')
                <livewire:user.checkout.credit-card :payable="$payable" :key="'credit-card-' . $payable->id" />
            @endif
        </div>

        <div class="md:col-span-2">
            {{-- Summary --}}
            <x-card>
                <x-card-body>
                    <x-card-title>
                        <x-carbon-book class="w-5 h-5 text-info" />
                        @if ($isAppointment)
                            Resumo da sessão
                        @elseif($isSubscribe)
                            Resumo da assinatura
                        @else
                            Resumo
                        @endif
                    </x-card-title>

                    <x-form-group>
                        <x-label>
                            <x-carbon-identification class="w-4 h-4 text-info" />
                            Código identificador
                        </x-label>
                        <x-badge class="badge-sm">#{{ $payable->id }}</x-badge>
                    </x-form-group>

                    @if ($isAppointment)
                        <x-form-group>
                            <x-label>
                                <x-carbon-user class="w-4 h-4 text-info" />
                                Especialista
                            </x-label>
                            <x-text>{{ $payable->specialist?->name ?? '-' }}</x-text>
                        </x-form-group>

                        <x-form-group>
                            <x-label>
                                <x-carbon-calendar class="w-4 h-4 text-info" />
                                Data da sessão
                            </x-label>
                            <x-text>{{ \Carbon\Carbon::parse($payable->appointment_date)->format('d/m/Y') }}</x-text>
                        </x-form-group>

                        <x-form-group>
                            <x-label>
                                <x-carbon-time class="w-4 h-4 text-info" />
                                Horário
                            </x-label>
                            <x-text>{{ \Carbon\Carbon::parse($payable->appointment_time)->format('H:i') }}h</x-text>
                        </x-form-group>

                        @if ($payable->notes)
                            <x-form-group>
                                <x-label>
                                    <x-carbon-book class="w-4 h-4 text-info" />
                                    Notas e observações
                                </x-label>
                                <x-text>{{ $payable->notes }}</x-text>
                            </x-form-group>
                        @endif
                    @endif

                    @if ($isSubscribe)
                        <x-form-group>
                            <x-label>
                                <x-carbon-badge class="w-4 h-4 text-info" />
                                Plano
                            </x-label>
                            <x-text>{{ $payable->plan?->name ?? '-' }}</x-text>
                        </x-form-group>

                        <x-form-group>
                            <x-label>
                                <x-carbon-calendar class="w-4 h-4 text-info" />
                                Data de início
                            </x-label>
                            <x-text>{{ \Carbon\Carbon::parse($payable->start_date)->format('d/m/Y') }}</x-text>
                        </x-form-group>

                        <x-form-group>
                            <x-label>
                                <x-carbon-calendar class="w-4 h-4 text-info" />
                                Data de término
                            </x-label>
                            <x-text>{{ \Carbon\Carbon::parse($payable->end_date)->format('d/m/Y') }}</x-text>
                        </x-form-group>

                        <x-form-group>
                            <x-label>
                                <x-carbon-time class="w-4 h-4 text-info" />
                                Duração
                            </x-label>
                            <x-text>{{ $payable->plan?->duration_days ?? 0 }} dias</x-text>
                        </x-form-group>
                    @endif

                    <div class="divider"></div>

                    <x-form-group>
                        <x-label>
                            <x-carbon-money class="w-4 h-4 text-info" />
                            @if ($isAppointment)
                                Valor da sessão
                            @elseif($isSubscribe)
                                Valor do plano
                            @else
                                Valor
                            @endif
                        </x-label>
                    </x-form-group>


                    @if ($isAppointment && !empty($discountInfo['has_company_discount']))
                        {{-- Mostra desconto da empresa --}}
                        <div class="flex flex-row justify-between gap-2">
                            <x-text class="text-sm">Valor original</x-text>
                            <x-text class="text-sm line-through text-base-content/60">R$
                                {{ number_format($payable->total_value, 2, ',', '.') }}
                            </x-text>
                        </div>

                        <div class="flex flex-row justify-between gap-2">
                            <x-text class="flex flex-row gap-1 text-sm text-success">
                                <x-carbon-tag class="w-4" />
                                Desconto do plano {{ $discountInfo['plan_name'] ?? '' }}
                                ({{ number_format($discountInfo['discount_percentage'], 0) }}%)
                            </x-text>
                            <x-text class="text-sm text-success font-semibold">- R$
                                {{ number_format($discountInfo['company_amount'], 2, ',', '.') }}
                            </x-text>
                        </div>

                        <div class="divider my-1"></div>

                        <div class="flex flex-row justify-between gap-2">
                            <x-text class="text-xl font-semibold">Total a pagar</x-text>
                            <x-text class="text-xl text-success font-semibold">R$
                                {{ number_format($this->finalAmount, 2, ',', '.') }}
                            </x-text>
                        </div>
                    @else
                        {{-- Sem desconto --}}
                        <div class="flex flex-row justify-between gap-2">
                            <x-text class="text-xl font-semibold">Total</x-text>
                            <x-text class="text-xl text-base-content font-semibold">R$
                                @if ($isAppointment)
                                    {{ number_format($payable->total_value, 2, ',', '.') }}
                                @elseif($isSubscribe)
                                    {{ number_format($payable->plan?->price ?? 0, 2, ',', '.') }}
                                @else
                                    0,00
                                @endif
                            </x-text>
                        </div>
                    @endif
                </x-card-body>
            </x-card>
        </div>
    </div>
</div>
