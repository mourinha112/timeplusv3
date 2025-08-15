<div class="space-y-8">
    @if ($isLoading)
        {{-- <x-spinner>Carregando QR Code PIX...</x-spinner> --}}
    @elseif ($pixQrCode && $pixKey)
        <x-text>Escaneie o QR Code abaixo para realizar o pagamento.</x-text>

        <img src="{{ $pixQrCode }}" alt="QR Code Pix" width="200" />

        {{-- <x-field> --}}
            <x-label required>Chave PIX</x-label>
            <x-input wire:model.defer="pixKey" disabled />
        {{-- </x-field> --}}

        <x-button class="btn-block btn-info" x-data
            @click="$clipboard('{{ $pixKey }}'); $dispatch('notify', 'Chave PIX copiada!')">Copiar chave
            PIX</x-button>
    @else
        <x-alert color="danger">
            Erro ao gerar o QR Code PIX. Tente novamente mais tarde.
        </x-alert>
    @endif
</div>
