<div class="space-y-4">
    <x-heading>
        <x-title>Realizar pagamento</x-title>
        <x-subtitle>Realize o pagamento da sua consulta.</x-subtitle>
    </x-heading>

    @if(empty($payment_method) && empty($payable->payment->payment_method))
    <x-card class="cursor-pointer" wire:click="selectPaymentMethod('pix')">
        <x-card-body>
            <div class="space-y-2">
                <x-card-title>
                    <x-carbon-qr-code class="w-5 h-5 text-info" />
                    PIX
                </x-card-title>
                <x-text>Realize o pagamento utilizando PIX.</x-text>
            </div>
        </x-card-body>
    </x-card>

    <x-card class="cursor-pointer" wire:click="selectPaymentMethod('credit_card')">
        <x-card-body>
            <div class="space-y-2">
                <x-card-title>
                    <x-carbon-purchase class="w-5 h-5 text-info" />
                    Cartão de crédito
                </x-card-title>
                <x-text>Realize o pagamento utilizando cartão de crédito.</x-text>
            </div>
        </x-card-body>
    </x-card>
    @endif

    @if($payment_method === 'pix' || $payable->payment?->payment_method === 'pix')
    <livewire:user.checkout.pix :payable="$payable" />
    aaaaa
    @elseif($payment_method === 'credit_card' || $payable->payment?->payment_method === 'credit_card')
    <livewire:user.checkout.credit-card :payable="$payable" />
    aaaaa
    @endif
</div>
