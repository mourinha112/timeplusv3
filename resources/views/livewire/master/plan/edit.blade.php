<div class="container mx-auto">
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-plan class="w-8 text-info" />
            Editar Plano
        </x-title>
        <x-subtitle>
            Atualize as informações do plano "{{ $plan->name }}"
        </x-subtitle>
    </x-heading>

    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('master.plan.index') }}" class="btn btn-ghost">
            <x-carbon-arrow-left class="w-4 h-4" />
            Voltar para Planos
        </a>
    </div>

    <x-card class="w-full">
        <x-card-body>
            <x-form wire:submit="save">
                <div class="space-y-6">
                    <x-form-group>
                        <x-label required>Nome do Plano</x-label>
                        <x-input type="text" wire:model="name" placeholder="Ex: Mensal, Semestral, Anual..." />
                        <x-text class="mt-1">
                            Nome que será exibido para os usuários
                        </x-text>
                        @error('name')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Preço (R$)</x-label>
                        <x-input type="number" step="0.01" min="0.01" wire:model="price"
                            placeholder="Ex: 49.99" />
                        <x-text class="mt-1">
                            Valor que será cobrado dos usuários
                        </x-text>
                        @error('price')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Percentual de Desconto (%)</x-label>
                        <x-input type="number" step="0.01" min="0" max="100"
                            wire:model="discount_percentage" placeholder="Ex: 10.00" />
                        <x-text class="mt-1">
                            Desconto padrão aplicado no plano (0% - 100%)
                        </x-text>
                        @error('discount_percentage')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Duração (dias)</x-label>
                        <x-input type="number" min="1" wire:model="duration_days"
                            placeholder="Ex: 30, 180, 365" />
                        <x-text class="mt-1">
                            Duração do plano em dias (30 = Mensal, 180 = Semestral, 365 = Anual)
                        </x-text>
                        @error('duration_days')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <div class="bg-warning/10 p-4 rounded-lg">
                        <div class="flex items-start gap-3">
                            <x-carbon-warning class="w-5 h-5 text-warning flex-shrink-0 mt-0.5" />
                            <div class="text-sm">
                                <div class="font-semibold text-base-content mb-2">Atenção ao editar:</div>
                                <ul class="list-disc list-inside text-base-content/80 space-y-1">
                                    <li>As alterações afetarão todos os usuários com assinaturas ativas deste plano</li>
                                    <li>Mudanças no preço não afetam assinaturas já pagas</li>
                                    <li>Alterações na duração só se aplicam a novas contratações</li>
                                    <li>O desconto é aplicado automaticamente para novos usuários</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-info/10 p-4 rounded-lg">
                        <div class="flex items-start gap-3">
                            <x-carbon-information class="w-5 h-5 text-info flex-shrink-0 mt-0.5" />
                            <div class="text-sm">
                                <div class="font-semibold text-base-content mb-1">Exemplo de cálculo:</div>
                                <p class="text-base-content/80">
                                    Preço: R$ <span x-text="$wire.price || '0,00'"></span> -
                                    Desconto: <span x-text="$wire.discount_percentage || '0'"></span>% =
                                    <strong>R$ <span
                                            x-text="(($wire.price || 0) * (1 - (($wire.discount_percentage || 0) / 100))).toFixed(2)"></span></strong>
                                    (valor final)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <a href="{{ route('master.plan.index') }}" class="btn btn-ghost">
                        <x-carbon-close class="w-4 h-4" />
                        Cancelar
                    </a>
                    <x-button type="submit" color="info">
                        <x-carbon-save class="w-4 h-4" />
                        Atualizar Plano
                    </x-button>
                </div>
            </x-form>
        </x-card-body>
    </x-card>
</div>
