<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-wallet class="w-8 text-info" />
            Créditos
        </x-title>
        <x-subtitle>
            Acompanhe o saldo de créditos da empresa e compre créditos extras
        </x-subtitle>
    </x-heading>

    {{-- Cards de saldo --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-card>
            <x-card-body>
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-lg bg-info/10">
                        <x-carbon-wallet class="w-6 h-6 text-info" />
                    </div>
                    <div>
                        <p class="text-sm text-base-content/60">Saldo total disponível</p>
                        <p class="text-2xl font-bold">{{ $this->creditSummary['total'] }} crédito(s)</p>
                    </div>
                </div>
            </x-card-body>
        </x-card>

        <x-card>
            <x-card-body>
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-lg bg-success/10">
                        <x-carbon-calendar class="w-6 h-6 text-success" />
                    </div>
                    <div>
                        <p class="text-sm text-base-content/60">Créditos mensais</p>
                        <p class="text-2xl font-bold">{{ $this->creditSummary['monthly'] }}</p>
                        <p class="text-xs text-base-content/60">Expiram em {{ now()->endOfMonth()->format('d/m/Y') }}</p>
                    </div>
                </div>
            </x-card-body>
        </x-card>

        <x-card>
            <x-card-body>
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-lg bg-warning/10">
                        <x-carbon-purchase class="w-6 h-6 text-warning" />
                    </div>
                    <div>
                        <p class="text-sm text-base-content/60">Créditos extras</p>
                        <p class="text-2xl font-bold">{{ $this->creditSummary['extra'] }}</p>
                        @if ($this->extraBuckets->isNotEmpty())
                            <p class="text-xs text-base-content/60">
                                Próximo vencimento: {{ $this->extraBuckets->first()->valid_until->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>
                </div>
            </x-card-body>
        </x-card>
    </div>

    @if ($this->creditPlan)
        <div class="bg-info/10 p-4 rounded-lg mb-6">
            <div class="flex items-start gap-3">
                <x-carbon-information class="w-5 h-5 text-info flex-shrink-0 mt-0.5" />
                <div class="text-sm text-base-content/80">
                    <span class="font-semibold text-base-content">Plano "{{ $this->creditPlan->name }}":</span>
                    {{ $this->creditPlan->monthly_credits }} crédito(s) renovados todo mês
                    (R$ {{ number_format(\App\Models\CompanyPlan::CREDIT_UNIT_PRICE, 2, ',', '.') }} por sessão).
                    Os créditos mensais são consumidos primeiro; créditos extras valem 6 meses e são usados quando o
                    saldo mensal acaba.
                    @if ($this->creditPlan->credits_per_employee)
                        Limite por funcionário: <span class="font-semibold">{{ $this->creditPlan->credits_per_employee }}
                        crédito(s)/mês</span>.
                    @else
                        Sem limite individual por funcionário.
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="bg-warning/10 p-4 rounded-lg mb-6">
            <div class="flex items-start gap-3">
                <x-carbon-warning class="w-5 h-5 text-warning flex-shrink-0 mt-0.5" />
                <div class="text-sm text-base-content/80">
                    Sua empresa ainda não possui um plano por pacote de créditos ativo. Entre em contato com a equipe
                    TimePlus para contratar um plano. Você ainda pode comprar créditos extras (validade de 6 meses).
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Comprar créditos extras --}}
        <x-card>
            <x-card-body>
                <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                    <x-carbon-purchase class="w-5 h-5 text-info" />
                    Comprar créditos extras
                </h3>

                <x-form wire:submit="buyCredits">
                    <x-form-group>
                        <x-label required>Quantidade de créditos</x-label>
                        <x-input type="number" min="1" max="1000" step="1" wire:model.live="purchase_credits" />
                        <x-text class="mt-1">
                            Cada crédito custa R$ {{ number_format(\App\Models\CompanyPlan::CREDIT_UNIT_PRICE, 2, ',', '.') }}
                            e vale por 6 meses.
                        </x-text>
                    </x-form-group>

                    <div class="flex items-center justify-between mt-4">
                        <div class="text-lg">
                            Total:
                            <span class="font-bold text-info">
                                R$ {{ number_format(max(0, (int) $purchase_credits) * \App\Models\CompanyPlan::CREDIT_UNIT_PRICE, 2, ',', '.') }}
                            </span>
                        </div>
                        <x-button type="submit" class="btn-soft btn-info" wire:loading.attr="disabled">
                            <span class="flex items-center gap-1" wire:loading.remove>
                                <x-carbon-qr-code class="w-4 h-4" />
                                Gerar PIX
                            </span>
                            <span wire:loading class="flex items-center gap-2">
                                <span class="loading loading-spinner loading-xs"></span>
                                Gerando...
                            </span>
                        </x-button>
                    </div>
                </x-form>

                @if ($this->pendingPurchases->isNotEmpty())
                    <div class="divider"></div>
                    <h4 class="font-semibold text-sm mb-2">Compras aguardando pagamento</h4>
                    <ul class="space-y-2">
                        @foreach ($this->pendingPurchases as $purchase)
                            <li class="flex items-center justify-between text-sm">
                                <span>
                                    {{ $purchase->metadata['credits'] ?? '-' }} crédito(s) —
                                    R$ {{ number_format((float) $purchase->amount, 2, ',', '.') }}
                                    <span class="text-base-content/50">({{ $purchase->created_at->format('d/m/Y H:i') }})</span>
                                </span>
                                <button type="button" class="btn btn-xs btn-soft btn-info"
                                    wire:click="showPix({{ $purchase->id }})">
                                    Ver PIX
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card-body>
        </x-card>

        {{-- QR Code PIX --}}
        <x-card>
            <x-card-body>
                <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                    <x-carbon-qr-code class="w-5 h-5 text-info" />
                    Pagamento PIX
                </h3>

                @if ($this->pixPayment)
                    <div class="text-center space-y-4">
                        <p class="text-sm text-base-content/70">{{ $this->pixPayment->description }}</p>

                        @if ($this->pixPayment->status === 'paid')
                            <div class="badge badge-success">Pagamento confirmado — créditos liberados!</div>
                        @else
                            @if ($this->pixPayment->pix_qr_code)
                                <img src="data:image/png;base64,{{ $this->pixPayment->pix_qr_code }}"
                                    alt="QR Code PIX" class="mx-auto w-48 h-48 rounded-lg border border-base-300" />
                            @endif

                            @if ($this->pixPayment->pix_key)
                                <div>
                                    <p class="text-xs text-base-content/60 mb-1">PIX copia e cola:</p>
                                    <textarea readonly rows="3" onclick="this.select()"
                                        class="textarea textarea-bordered w-full text-xs">{{ $this->pixPayment->pix_key }}</textarea>
                                </div>
                            @endif

                            <p class="text-xs text-base-content/60">
                                Os créditos são liberados automaticamente assim que o pagamento for confirmado.
                            </p>
                        @endif
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-center text-base-content/50">
                        <x-carbon-qr-code class="w-12 h-12 mb-2" />
                        <p class="text-sm">Gere uma compra de créditos para exibir o QR Code aqui.</p>
                    </div>
                @endif
            </x-card-body>
        </x-card>
    </div>

    {{-- Histórico de uso --}}
    <x-card>
        <x-card-body>
            <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                <x-carbon-time class="w-5 h-5 text-info" />
                Histórico de uso
            </h3>

            @if ($this->usages->isEmpty())
                <p class="text-sm text-base-content/60">Nenhum crédito consumido ainda.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Funcionário</th>
                                <th>Sessão</th>
                                <th>Origem</th>
                                <th class="text-right">Créditos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->usages as $usage)
                                <tr>
                                    <td>{{ $usage->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $usage->user?->name ?? '—' }}</td>
                                    <td>
                                        @if ($usage->appointment)
                                            {{ \Carbon\Carbon::parse($usage->appointment->appointment_date)->format('d/m/Y') }}
                                            {{ \Carbon\Carbon::parse($usage->appointment->appointment_time)->format('H:i') }}
                                            — {{ $usage->appointment->specialist?->name ?? '' }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-sm {{ $usage->balance?->bucket === 'monthly' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $usage->balance?->bucket === 'monthly' ? 'Mensal' : 'Extra' }}
                                        </span>
                                    </td>
                                    <td class="text-right font-semibold">{{ $usage->credits_used }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card-body>
    </x-card>
</div>
