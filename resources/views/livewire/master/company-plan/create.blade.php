<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-layers class="w-8 text-info" />
            Criar Plano — {{ $company->name }}
        </x-title>
        <x-subtitle>
            Configure uma contratação por funcionário ou por pacote de créditos para esta empresa
        </x-subtitle>
    </x-heading>

    <x-card class="w-full">
        <x-card-body>
            <x-form wire:submit="save">
                <div class="space-y-6">
                    <x-form-group>
                        <x-label required>Nome do Plano</x-label>
                        <x-input type="text" wire:model="name" placeholder="Ex: Plano Básico, Premium, etc." />
                        <x-text class="mt-1">
                            Escolha um nome descritivo para identificar este plano
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

                    <div class="bg-info/10 p-4 rounded-lg">
                        <div class="flex items-start gap-3">
                            <x-carbon-information class="w-5 h-5 text-info flex-shrink-0 mt-0.5" />
                            <div class="text-sm">
                                <div class="font-semibold text-base-content mb-1">Como funciona:</div>
                                @if ($billing_model === 'credit_pack')
                                    <p class="text-base-content/80">
                                        A empresa paga R$ 30,00 por sessão. Os créditos mensais são liberados todo mês e
                                        expiram no fim do mês. Créditos extras comprados valem 6 meses e são consumidos
                                        somente quando os créditos mensais acabam.
                                    </p>
                                @else
                                    <p class="text-base-content/80">
                                        Cobrança mensal por funcionário ativo: R$ 30,00 no primeiro mês e R$ 60,00 nos
                                        meses seguintes. Cada funcionário escolhe um dos planos de consultas (1, 2 ou 4
                                        consultas por mês).
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a wire:navigate href="{{ route('master.company.show', ['company' => $company->id]) }}"
                        class="btn btn-soft btn-error">
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
