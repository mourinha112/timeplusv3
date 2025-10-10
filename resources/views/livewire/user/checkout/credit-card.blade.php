<div>
    @error('payment')
    <x-alert class="alert-error">{{ $message }}</x-alert>
    @enderror

    <x-card>
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
    </x-card>

    <x-form wire:submit="pay">
        <x-form-group>
            <x-label required>Nome do titular</x-label>
            <x-input type="text" wire:model="card_holder_name" />
        </x-form-group>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-form-group>
                <x-label required>Número do cartão</x-label>
                <x-input type="text" wire:model="card_number" x-mask="9999 9999 9999 9999" />
            </x-form-group>

            <x-form-group>
                <x-label required>Validade do cartão</x-label>
                <div class="grid grid-cols-2 gap-2">
                    <x-input type="text" wire:model="card_expiry_month" x-mask="99" placeholder="MM" />
                    <x-input type="text" wire:model="card_expiry_year" x-mask="9999" placeholder="AAAA" />
                </div>
            </x-form-group>

            <x-form-group>
                <x-label required>CVV</x-label>
                <x-input type="text" wire:model="card_cvv" x-mask="999" />
            </x-form-group>
        </div>

        <x-button class="btn-block btn-info" type="submit">Realizar pagamento</x-button>
    </x-form>
</div>
