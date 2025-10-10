<div class="space-y-8">
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

    @if ($isLoading)
    O Pix está sendo carregado...
    @elseif ($pixQrCode && $pixKey)
    <img src="{{ $pixQrCode }}" alt="QR Code Pix" width="200" />

    {{-- <x-field> --}}
    <x-label required>Chave PIX</x-label>
    <x-input wire:model.defer="pixKey" disabled />
    {{-- </x-field> --}}

    <x-button class="btn-block btn-info" x-data @click="$clipboard('{{ $pixKey }}'); $dispatch('notify', 'Chave PIX copiada!')">Copiar chave
        PIX</x-button>
    @else
    <x-button wire:click="generatePix" class="btn-block">Gerar PIX</x-button>
    @endif
</div>
