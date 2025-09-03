<div>
    <x-card>
        <x-card-body>
            <x-card-title>
                <x-carbon-money class="w-5 h-5 text-info" />
                Cartão de crédito
            </x-card-title>
            <x-text>Informe os dados do cartão de crédito para realizar o pagamento.</x-text>

            <x-form wire:submit="subscribe" class="mt-2">
                <x-form-group>
                    <x-label required>Nome do titular</x-label>
                    <x-input type="text" wire:model="card_holder" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Número do cartão</x-label>
                    <x-input type="text" wire:model="card_number" x-mask="9999999999999999" />
                </x-form-group>

                <div class="grid grid-cols-3 gap-2">
                    <x-form-group>
                        <x-label required>Mês de validade</x-label>
                        <x-input wire:model="card_expiry_month" x-mask="99" placeholder="MM" />
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Ano de validade</x-label>
                        <x-input wire:model="card_expiry_year" x-mask="9999" placeholder="AAAA" />
                    </x-form-group>

                    <x-form-group>
                        <x-label required>CVV</x-label>
                        <x-input wire:model="card_cvv" x-mask="999" />
                    </x-form-group>
                </div>

                <x-button class="btn-block" type="submit">
                    Realizar pagamento
                    <x-carbon-checkmark-outline class="w-5 h-5" />
                </x-button>
            </x-form>
        </x-card-body>
    </x-card>
</div>
