<div class="space-y-6">
    @error('payment')
        <x-alert type="error">{{ $message }}</x-alert>
    @enderror

    <x-heading>
        <x-title>Realizar pagamento</x-title>
        <x-text>Realizar pagamento utilizando Cartão de Crédito.</x-text>
    </x-heading>

    {{-- <x-divider /> --}}

    <div>
        <x-subtitle>Sessão #{{ $payable->id }}</x-subtitle>

        <x-text class="text-xs"><strong>Data:</strong> {{ $payable->appointment_date }}</x-text>
        <x-text class="text-xs"><strong>Hora:</strong>
            {{ \Carbon\Carbon::parse($payable->appointment_time)->format('H:i') }}</x-text>

        <x-text>
            <strong>Valor:</strong>
            <x-badge color="success">R$ {{ number_format($payable->total_value, 2, ',', '.') }}</x-badge>
        </x-text>
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
