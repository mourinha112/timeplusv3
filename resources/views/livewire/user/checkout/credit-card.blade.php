<div>
    @error('payment')
        <x-alert class="alert-error">{{ $message }}</x-alert>
    @enderror

    {{-- <x-card>
        <x-card-body>
            <h4 class="font-semibold text-xl">Sessão #{{ $payable->id }}</h4>

            <x-text class="text-xs">
                <strong>Data:</strong>
                {{ \Carbon\Carbon::parse($payable->appointment_date)->format('d/m/Y') }}
            </x-text>

            <x-text class="text-xs">
                <strong>Hora:</strong>
                {{ \Carbon\Carbon::parse($payable->appointment_time)->format('H:i') }}h
            </x-text>

            <x-text class="text-xs">
                <strong>Valor:</strong>
                <x-badge class="badge-info badge-sm badge-ghost">
                    R$ {{ number_format($payable->total_value, 2, ',', '.') }}
                </x-badge>
            </x-text>
        </x-card-body>
    </x-card> --}}

    <x-card>
        <x-card-body>
            <x-card-title>
                <x-carbon-money class="w-5 h-5 text-info" />
                Cartão de crédito
            </x-card-title>
            <x-text>Informe os dados do cartão de crédito para realizar o pagamento.</x-text>

            <x-form wire:submit="pay" class="mt-2">
                <x-field>
                    <x-label required>Nome do titular</x-label>
                    <x-input class="input-lg" type="text" wire:model="card_holder_name" />
                </x-field>

                <x-field>
                    <x-label required>Número do cartão</x-label>
                    <x-input class="input-lg" type="text" wire:model="card_number" x-mask="9999 9999 9999 9999" />
                </x-field>

                <div class="grid grid-cols-3 gap-2">
                    <x-field>
                        <x-label required>Mês de validade</x-label>
                        <x-input class="input-lg" wire:model="card_expiry_month" x-mask="99" placeholder="MM" />
                    </x-field>

                    <x-field>
                        <x-label required>Ano de validade</x-label>
                        <x-input class="input-lg" wire:model="card_expiry_year" x-mask="9999" placeholder="AAAA" />
                    </x-field>

                    <x-field>
                        <x-label required>CVV</x-label>
                        <x-input class="input-lg" wire:model="card_cvv" x-mask="999" />
                    </x-field>
                </div>

                <x-button class="btn-block btn-lg" type="submit">
                    Realizar pagamento
                    <x-carbon-checkmark-outline class="w-5 h-5" />
                </x-button>
            </x-form>
        </x-card-body>
    </x-card>
</div>
