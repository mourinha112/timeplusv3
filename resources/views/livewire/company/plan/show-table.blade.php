<div>
    @include(data_get($this->setUp(), 'theme.layout.table'))

    <!-- Modal de Criação/Edição -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
            @click.self="$wire.closeModal()" @keydown.escape.window="$wire.closeModal()">
            <div class="w-full max-w-2xl mx-4">
                <x-card class="shadow-2xl">
                    <x-card-body>
                        <x-card-title>
                            {{ $editingPlan ? 'Editar Plano' : 'Novo Plano' }}
                        </x-card-title>
                        <x-form wire:submit="save">
                            <div class="space-y-4">
                                <x-form-group>
                                    <x-label required>Nome do Plano</x-label>
                                    <x-input type="text" wire:model="name"
                                        placeholder="Ex: Plano Básico, Premium, etc." />
                                    <x-text class="mt-1">
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
                                <x-text class="mt-1">
                                    Percentual que a empresa irá cobrir dos custos do funcionário (mínimo 0,01% -
                                    máximo 100%)
                                </x-text>
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
                                            O plano define apenas o percentual de desconto. Quando um funcionário
                                            usar os serviços,
                                            a empresa pagará o percentual definido e o funcionário pagará o
                                            restante.
                                        </p>
                                    </div>
                                </div>
                            </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <x-button type="button" wire:click="closeModal" color="secondary">
                    <x-carbon-close class="w-4 h-4" />
                    Cancelar
                </x-button>
                <x-button type="submit" color="primary">
                    <x-carbon-save class="w-4 h-4" />
                    {{ $editingPlan ? 'Atualizar' : 'Criar' }} Plano
                </x-button>
            </div>
            </x-form>
            </x-card-body>
            </x-card>
        </div>
</div>
@endif
</div>
