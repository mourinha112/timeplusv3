<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-layers class="w-8 text-info" />
            Editar Plano — {{ $plan->company->name }}
        </x-title>
        <x-subtitle>
            Atualize as informações do plano "{{ $plan->name }}"
        </x-subtitle>
    </x-heading>

    <div class="flex justify-between items-center mb-6">
        <a wire:navigate href="{{ route('master.company.show', ['company' => $plan->company_id]) }}" class="btn btn-ghost">
            <x-carbon-arrow-left class="w-4 h-4" />
            Voltar para a Empresa
        </a>
    </div>

    <x-card class="w-full">
        <x-card-body>
            <x-form wire:submit="save">
                <div class="space-y-6">
                    <x-form-group>
                        <x-label required>Nome do Plano</x-label>
                        <x-input type="text" wire:model="name" placeholder="Ex: Plano Básico, Premium, VIP..." />
                        <x-text class="mt-1">
                            Escolha um nome descritivo e fácil de identificar
                        </x-text>
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Modelo de contratação</x-label>
                        <x-select wire:model.live="billing_model">
                            <option value="per_employee">Por funcionário ativo</option>
                            <option value="credit_pack">Pacote de créditos</option>
                        </x-select>
                    </x-form-group>

                    @if ($billing_model === 'credit_pack')
                        <x-form-group>
                            <x-label required>Créditos mensais</x-label>
                            <x-input type="number" min="1" step="1" wire:model="monthly_credits" />
                            <x-text class="mt-1">
                                Quantidade de sessões cobertas por mês. Créditos mensais não usados expiram no fim do
                                mês.
                            </x-text>
                        </x-form-group>

                        <x-form-group>
                            <x-label>Limite de créditos por funcionário/mês</x-label>
                            <x-input type="number" min="1" step="1" wire:model="credits_per_employee"
                                placeholder="Deixe vazio para não limitar" />
                            <x-text class="mt-1">
                                Limite universal: vale igualmente para todos os funcionários. Deixe vazio para permitir
                                que usem enquanto houver saldo da empresa.
                            </x-text>
                        </x-form-group>
                    @endif

                    <x-form-group>
                        <x-label required>Valor base</x-label>
                        <x-input type="number" step="0.01" min="30" wire:model="price_per_unit" readonly />
                        <x-text class="mt-1">
                            @if ($billing_model === 'credit_pack')
                                R$ 30,00 por sessão/crédito consumido pelos funcionários.
                            @else
                                R$ 30,00 por funcionário ativo no 1º mês; R$ 60,00 por funcionário nos meses seguintes.
                            @endif
                        </x-text>
                    </x-form-group>

                    <div class="bg-warning/10 p-4 rounded-lg">
                        <div class="flex items-start gap-3">
                            <x-carbon-warning class="w-5 h-5 text-warning flex-shrink-0 mt-0.5" />
                            <div class="text-sm">
                                <div class="font-semibold text-base-content mb-2">Atenção ao editar:</div>
                                <ul class="list-disc list-inside text-base-content/80 space-y-1">
                                    <li>As alterações afetarão todos os funcionários que possuem este plano</li>
                                    <li>O novo modelo/valor será aplicado imediatamente aos funcionários vinculados</li>
                                    <li>Recomendamos comunicar a empresa sobre mudanças significativas</li>
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
                    <a wire:navigate href="{{ route('master.company.show', ['company' => $plan->company_id]) }}"
                        class="btn btn-ghost">
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
