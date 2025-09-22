<div>
    <x-heading>
        <x-title>Realizar pagamento</x-title>
        <x-text>Realize o pagamento da sua corrida com a forma de pagamento selecionada.</x-text>
    </x-heading>

    @if (empty($selected_payment_method))
        <x-card>
            <x-card-body>
                <x-card-title>
                    <x-carbon-book class="w-5 h-5 text-info" />
                    Método de pagamento
                </x-card-title>
                <x-text>Selecione o método de pagamento para a corrida.</x-text>

                <x-form wire:submit="submit">
                    <x-form-group>
                        <x-label required>
                            <x-carbon-user-profile class="w-4 h-4 text-info" />
                            Método de pagamento
                        </x-label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">
                            <x-card-radio>
                                <x-radio wire:model="payment_method" value="credit_card">Cartão de Crédito</x-radio>
                            </x-card-radio>
                            <x-card-radio>
                                <x-radio wire:model="payment_method" value="pix">Pix</x-radio>
                            </x-card-radio>
                        </div>
                    </x-form-group>

                    <x-button type="submit" class="btn-wide">
                        Selecionar
                        <x-carbon-checkmark-outline class="w-4 h-4" />
                    </x-button>
                </x-form>
            </x-card-body>
        </x-card>
    @else
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-3">
                {{-- QR Code PIX --}}
                @if ($this->selected_payment_method === 'pix')
                    <livewire:user.plan.payment_methods.pix :plan="$this->plan" />
                @endif

                {{-- Cartão de Crédito --}}
                @if ($this->selected_payment_method === 'credit_card')
                    <livewire:user.plan.payment_methods.credit-card :plan="$this->plan" />
                @endif
            </div>

            {{-- Detalhes do plano --}}
            <x-card class="md:col-span-2">
                <x-card-body class="flex justify-between">
                    <div>
                        <h2 class="text-3xl font-bold">{{ $plan->name }}</h2>

                        <ul class="mt-6 flex flex-col gap-6 text-xs">
                            <li class="flex gap-2">
                                <x-carbon-checkmark class="w-4 h-4 text-info" />
                                Acesso completo à biblioteca de conteúdos Timeplus
                            </li>
                            <li class="flex gap-2">
                                <x-carbon-checkmark class="w-6 h-6 text-info" />
                                Fácil acesso a toda comunidade de psicólogos, psicanalistas, terapeutas e coaches
                            </li>
                            <li class="flex gap-2">
                                <x-carbon-checkmark class="w-4 h-4 text-info" />
                                Atendimento prioritário pela nossa equipe de suporte
                            </li>
                        </ul>
                    </div>
                    <div class="flex justify-between items-center mt-6">
                        <x-text>{{ $plan->hasDiscount() ? 'Valor com desconto' : 'Valor do plano' }}</x-text>
                        <div class="text-right">
                            @if ($plan->hasDiscount())
                                <span class="block text-sm text-base-content/50 line-through">
                                    <x-text>R$</x-text>
                                    {{ number_format($plan->price, 2, ',', '.') }}
                                </span>
                                <div class="flex items-center justify-end gap-2">
                                    <span class="text-3xl font-bold text-success">
                                        <x-text>R$</x-text>
                                        {{ number_format($plan->price_with_discount, 2, ',', '.') }}
                                    </span>
                                    <x-badge class="badge-success badge-sm">
                                        -{{ $plan->discount_percentage_formatted }}%
                                    </x-badge>
                                </div>
                            @else
                                <span class="text-3xl font-bold">
                                    <x-text>R$</x-text>
                                    {{ number_format($plan->price, 2, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </x-card-body>
            </x-card>

    @endif
</div>
</div>
