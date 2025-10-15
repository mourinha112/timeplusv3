<div class="space-y-8" x-data="{ shouldRedirect: @entangle('shouldRedirect') }"
    x-effect="if (shouldRedirect) { window.location.href = '{{ route('user.appointment.index') }}' }">
    @error('payment')
        <x-alert class="alert-error">{{ $message }}</x-alert>
    @enderror

    <x-card>
        <x-card-body>
            <x-card-title>
                <x-carbon-qr-code class="w-5 h-5 text-info" />
                PIX
            </x-card-title>
            <x-text>Escaneie o QR Code abaixo para realizar o pagamento.</x-text>

            @if ($isLoading)
                <div class="flex justify-center items-center py-8">
                    <span class="loading loading-spinner loading-lg"></span>
                    <span class="ml-2">O Pix está sendo carregado...</span>
                </div>
            @elseif ($pixQrCode && $pixKey)
                <div class="flex flex-col items-center space-y-4">
                    <img src="{{ $pixQrCode }}" alt="QR Code Pix" width="200" class="border rounded" />

                    <x-field class="w-full">
                        <x-label required>Chave PIX</x-label>
                        <x-input wire:model.defer="pixKey" disabled />
                    </x-field>

                    <x-button class="btn-block" x-data="{
                        copyToClipboard() {
                            const text = '{{ $pixKey }}';
                            // Try modern clipboard API first
                            if (navigator.clipboard && window.isSecureContext) {
                                navigator.clipboard.writeText(text).then(() => {
                                    $dispatch('notify', 'Chave PIX copiada!');
                                }).catch(() => {
                                    this.fallbackCopy(text);
                                });
                            } else {
                                this.fallbackCopy(text);
                            }
                        },
                        fallbackCopy(text) {
                            const textarea = document.createElement('textarea');
                            textarea.value = text;
                            textarea.style.position = 'fixed';
                            textarea.style.opacity = '0';
                            document.body.appendChild(textarea);
                            textarea.select();
                            try {
                                document.execCommand('copy');
                                $dispatch('notify', 'Chave PIX copiada!');
                            } catch (err) {
                                console.error('Erro ao copiar:', err);
                                alert('Não foi possível copiar automaticamente. Copie manualmente: ' + text);
                            }
                            document.body.removeChild(textarea);
                        }
                    }" @click="copyToClipboard()">
                        <x-carbon-copy class="w-5 h-5" />
                        Copiar chave PIX
                    </x-button>
                </div>
            @else
                <x-button wire:click="generatePix" class="btn-block">Gerar PIX</x-button>
            @endif
        </x-card-body>
    </x-card>
</div>
