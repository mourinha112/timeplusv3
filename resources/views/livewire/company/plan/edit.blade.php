<div class="container mx-auto">
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-layers class="w-8 text-info" />
            Editar Plano
        </x-title>
        <x-subtitle class="text-base-content/70">
            Atualize as informações do plano "{{ $plan->name }}"
        </x-subtitle>
    </x-heading>

    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('company.plan.index') }}" class="btn btn-ghost">
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
                        <x-input type="text" wire:model="name" placeholder="Ex: Plano Básico, Premium, VIP..." />
                        <div class="text-sm text-base-content/70 mt-1">
                            Escolha um nome descritivo e fácil de identificar
                        </div>
                        @error('name')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Percentual de Desconto (%)</x-label>
                        <x-input type="number" step="0.01" min="0.01" max="100"
                            wire:model="discount_percentage" placeholder="Ex: 50.00" />
                        <div class="text-sm text-base-content/70 mt-1">
                            Percentual que a empresa irá cobrir dos custos (mínimo 0,01% - máximo 100%)
                        </div>
                        @error('discount_percentage')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <div class="bg-warning/10 p-4 rounded-lg">
                        <div class="flex items-start gap-3">
                            <x-carbon-warning class="w-5 h-5 text-warning flex-shrink-0 mt-0.5" />
                            <div class="text-sm">
                                <div class="font-semibold text-base-content mb-2">Atenção ao editar:</div>
                                <ul class="list-disc list-inside text-base-content/80 space-y-1">
                                    <li>As alterações afetarão todos os funcionários que possuem este plano</li>
                                    <li>O novo percentual será aplicado imediatamente</li>
                                    <li>Recomendamos comunicar os funcionários sobre mudanças significativas</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if ($plan->getActiveUsersCount() > 0)
                        <div class="bg-info/10 p-4 rounded-lg">
                            <div class="flex items-start gap-3">
                                <x-carbon-information class="w-5 h-5 text-info flex-shrink-0 mt-0.5" />
                                <div class="text-sm">
                                    <div class="font-semibold text-base-content mb-1">Funcionários ativos:</div>
                                    <p class="text-base-content/80">
                                        Este plano está sendo usado por <strong>{{ $plan->getActiveUsersCount() }}
                                            funcionários</strong> atualmente.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <a href="{{ route('company.plan.index') }}" class="btn btn-ghost">
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
