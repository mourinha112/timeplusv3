<div class="container mx-auto">
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-layers class="w-8 text-info" />
            Criar Plano
        </x-title>
        <x-subtitle class="text-base-content/70">
            Crie um novo plano de desconto para seus funcionários
        </x-subtitle>
    </x-heading>

    <x-card class="w-full">
        <x-card-body>
            <x-form wire:submit="save">
                <div class="space-y-6">
                    <x-form-group>
                        <x-label required>Nome do Plano</x-label>
                        <x-input type="text" wire:model="name" placeholder="Ex: Plano Básico, Premium, etc." />
                        <div class="text-sm text-base-content/70 mt-1">
                            Escolha um nome descritivo para identificar este plano
                        </div>
                        @error('name')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Percentual de Desconto (%)</x-label>
                        <x-input type="number" step="0.01" min="0.01" max="100"
                            wire:model="discount_percentage" placeholder="Ex: 50" />
                        <div class="text-sm text-base-content/70 mt-1">
                            Percentual que a empresa irá cobrir dos custos do funcionário (mínimo 0,01% - máximo 100%)
                        </div>
                        @error('discount_percentage')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <div class="bg-info/10 p-4 rounded-lg">
                        <div class="flex items-start gap-3">
                            <x-carbon-information class="w-5 h-5 text-info flex-shrink-0 mt-0.5" />
                            <div class="text-sm">
                                <div class="font-semibold text-base-content mb-1">Como funciona:</div>
                                <p class="text-base-content/80">
                                    O plano define apenas o percentual de desconto. Quando um funcionário usar os
                                    serviços,
                                    a empresa pagará o percentual definido e o funcionário pagará o restante.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('company.plan.index') }}" class="btn btn-soft btn-error">
                        <x-carbon-arrow-left class="w-4 h-4" />
                        Cancelar
                    </a>
                    <x-button type="submit" class="btn-soft btn-info" wire:loading.attr="disabled">
                        <span class="flex items-center gap-1" wire:loading.remove>
                            <x-carbon-add class="w-4 h-4" />
                            Criar Plano
                        </span>
                        <span wire:loading class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-xs"></span>
                            Criando...
                        </span>
                    </x-button>
                </div>
            </x-form>
        </x-card-body>
    </x-card>
</div>
