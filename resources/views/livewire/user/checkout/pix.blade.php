<div class="space-y-8">
    @if ($isLoading)
        {{-- <x-spinner>Carregando QR Code PIX...</x-spinner> --}}
    @elseif ($pixQrCode && $pixKey)
        <div class="space-y-4">
            <x-text>Escaneie o QR Code abaixo para realizar o pagamento.</x-text>

            @if ($this->hasDiscount())
                @php $discountInfo = $this->getDiscountInfo(); @endphp
                <div class="card bg-base-100 border border-base-300 p-4">
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
                </div>
            @else
                <div class="card bg-base-100 border border-base-300 p-4">
                    <x-text>
                        <strong>Valor:</strong>
                        <x-badge class="badge-warning">R$
                            {{ number_format($payable->total_value, 2, ',', '.') }}</x-badge>
                    </x-text>
                </div>
            @endif
        </div>

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
