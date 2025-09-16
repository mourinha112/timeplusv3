<div class="space-y-6">
    @error('payment')
        <x-alert class="alert-error">{{ $message }}</x-alert>
    @enderror

    <x-heading>
        <x-title>Realizar pagamento utilizando Cartão de Crédito</x-title>
    </x-heading>

    {{-- <x-divider /> --}}

    <div>
        <x-subtitle>Sessão #{{ $payable->id }}</x-subtitle>

        <x-text class="text-xs"><strong>Data:</strong> {{ $payable->appointment_date }}</x-text>
        <x-text class="text-xs"><strong>Hora:</strong>
            {{ \Carbon\Carbon::parse($payable->appointment_time)->format('H:i') }}</x-text>

        @if ($this->hasDiscount())
            @php $discountInfo = $this->getDiscountInfo(); @endphp
            <div class="space-y-2">
                <x-text>
                    <strong>Valor original:</strong>
                    <span class="line-through">R$ {{ number_format($payable->total_value, 2, ',', '.') }}</span>
                </x-text>
                <x-text>
                    <strong>Valor a pagar:</strong>
                    <x-badge class="badge-success">R$
                        {{ number_format($discountInfo['employee_amount'], 2, ',', '.') }}</x-badge>
                    <span class="badge badge-success badge-sm ml-2">{{ $discountInfo['discount_percentage'] }}%
                        OFF</span>
                </x-text>
                @if (isset($discountInfo['plan_name']))
                    <x-text class="text-xs text-success">
                        <strong>Desconto aplicado via:</strong> {{ $discountInfo['plan_name'] }}
                    </x-text>
                @endif
            </div>
        @else
            <x-text>
                <strong>Valor:</strong>
                <x-badge class="badge-warning">R$ {{ number_format($payable->total_value, 2, ',', '.') }}</x-badge>
            </x-text>
        @endif
    </div>

    {{-- <x-divider /> --}}

    <x-form wire:submit="pay">
        {{-- <x-field> --}}
        <x-label required>Nome do titular</x-label>
        <x-input type="text" wire:model="card_holder_name" />
        {{-- </x-field> --}}

        {{-- <x-field> --}}
        <x-label required>Número do cartão</x-label>
        <x-input type="text" wire:model="card_number" x-mask="9999 9999 9999 9999" />
        {{-- </x-field> --}}

        {{-- <x-field> --}}
        <x-label required>Validade do cartão</x-label>
        <div class="grid grid-cols-2 gap-2">
            <x-input type="text" wire:model="card_expiry_month" x-mask="99" placeholder="MM" />
            <x-input type="text" wire:model="card_expiry_year" x-mask="9999" placeholder="AAAA" />
        </div>
        {{-- </x-field> --}}

        {{-- <x-field> --}}
        <x-label required>CVV</x-label>
        <x-input type="text" wire:model="card_cvv" x-mask="999" />
        {{-- </x-field> --}}

        <x-button class="btn-block btn-warning" type="submit">Realizar pagamento</x-button>
    </x-form>
</div>
