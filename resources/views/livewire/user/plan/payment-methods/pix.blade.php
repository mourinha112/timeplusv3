<div>
    <x-card>
        <x-card-body>
            <x-card-title>
                <x-carbon-qr-code class="w-5 h-5 text-info" />
                PIX
            </x-card-title>
            <x-text>Escaneie o QR Code abaixo para realizar o pagamento.</x-text>
            <x-button class="btn-wide" wire:click="subscribe">Gerar QR Code</x-button>

            {{-- @if ($pixQrCode && $pixKey)
            <img src="{{ $pixQrCode }}" alt="QR Code Pix" width="200" />

            <x-form-group>
                <x-label required>Chave PIX</x-label>
                <x-input wire:model.defer="pixKey" readonly />
            </x-form-group>

            <x-button class="btn-block" x-data @click="$clipboard('{{ $pixKey }}'); toastr.success('Chave PIX copiada!')">
                <x-carbon-copy class="w-5 h-5" />
                Copiar chave PIX
            </x-button>
            @else
            <x-alert class="alert-error alert-soft">
                <x-carbon-close-outline class="w-5 h-5" />
                Erro ao gerar o QR Code PIX. Tente novamente mais tarde.
            </x-alert>
            @endif --}}
        </x-card-body>
    </x-card>
</div>
