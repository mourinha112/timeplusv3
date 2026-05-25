<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-user class="w-8 text-info" />
            Editar Funcionário
        </x-title>
        <x-subtitle>
            Edite as informações pessoais do funcionário {{ $employee->name }}
        </x-subtitle>
    </x-heading>

    <x-card class="w-full">
        <x-card-body>
            <x-form wire:submit="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form-group>
                        <x-label required>Nome Completo</x-label>
                        <x-input type="text" wire:model="name" placeholder="João Silva" />
                    </x-form-group>

                    <x-form-group>
                        <x-label required>CPF</x-label>
                        <x-input type="text" wire:model="cpf" placeholder="000.000.000-00" x-mask="999.999.999-99" />
                    </x-form-group>

                    <x-form-group>
                        <x-label>E-mail</x-label>
                        <div class="bg-base-200 px-3 py-2 rounded-lg text-sm text-base-content/70">
                            E-mail atual: <code>{{ $employee->email }}</code>
                        </div>
                        <small class="text-base-content/60">O e-mail não pode ser alterado por questões de
                            segurança</small>
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Telefone</x-label>
                        <x-input type="text" wire:model="phone_number" placeholder="(11) 99999-9999"
                            x-mask="(99) 99999-9999" />
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Data de Nascimento</x-label>
                        <x-input type="text" wire:model="birth_date" placeholder="01/01/1990" x-mask="99/99/9999" />
                    </x-form-group>

                    <x-form-group>
                        <x-label>Área</x-label>
                        <x-input type="text" wire:model="department" placeholder="Ex.: RH, Comercial, Operações" />
                    </x-form-group>

                    <x-form-group>
                        <x-label>Plano</x-label>
                        <x-select wire:model="company_plan_id">
                            <option value="">Selecione um plano</option>
                            @foreach ($companyPlans as $plan)
                                <option value="{{ $plan->id }}" @if (!$plan->is_active) disabled @endif>
                                    {{ $plan->name }} ({{ $plan->billing_model === 'credit_pack' ? 'pacote de créditos' : 'por funcionário' }})
                                    @if (!$plan->is_active)
                                        - INATIVO
                                    @endif
                                </option>
                            @endforeach
                        </x-select>
                        <small class="text-base-content/60 mt-1">
                            Apenas planos ativos podem ser selecionados.
                        </small>
                    </x-form-group>
                </div>

                <div class="mt-6 p-3 bg-base-200 rounded-lg text-sm text-base-content/70">
                    A senha do funcionário é gerenciada por ele próprio. Caso ele tenha esquecido a senha, oriente-o
                    a usar a opção <strong>"Esqueci minha senha"</strong> na tela de login.
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a wire:navigate href="{{ route('company.employee.index') }}" class="btn btn-soft btn-error">
                        <x-carbon-arrow-left class="w-4 h-4" />
                        Cancelar
                    </a>
                    <x-button type="submit" class="btn-soft btn-info" wire:loading.attr="disabled">
                        <span class="flex items-center gap-1" wire:loading.remove>
                            <x-carbon-save class="w-4 h-4" />
                            Salvar Alterações
                        </span>
                        <span wire:loading class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-xs"></span>
                            Salvando...
                        </span>
                    </x-button>
                </div>
            </x-form>
        </x-card-body>
    </x-card>
</div>
