<div class="container mx-auto">
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-user class="w-8 text-info" />
            Editar Funcionário
        </x-title>
        <x-subtitle class="text-base-content/70">
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
                        @error('name')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label required>CPF</x-label>
                        <x-input type="text" wire:model="cpf" placeholder="000.000.000-00" x-mask="999.999.999-99" />
                        @error('cpf')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label>E-mail</x-label>
                        <div class="bg-base-200 px-3 py-2 rounded-lg text-sm text-base-content/70">
                            📧 E-mail atual: <code>{{ $employee->email }}</code>
                        </div>
                        <small class="text-base-content/60">O e-mail não pode ser alterado por questões de
                            segurança</small>
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Telefone</x-label>
                        <x-input type="text" wire:model="phone_number" placeholder="(11) 99999-9999"
                            x-mask="(99) 99999-9999" />
                        @error('phone_number')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Data de Nascimento</x-label>
                        <x-input type="text" wire:model="birth_date" placeholder="01/01/1990" x-mask="99/99/9999" />
                        @error('birth_date')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Plano</x-label>
                        <x-select wire:model="company_plan_id">
                            <option value="">Selecione um plano</option>
                            @foreach ($companyPlans as $plan)
                                <option value="{{ $plan->id }}" @if (!$plan->is_active) disabled @endif>
                                    {{ $plan->name }} ({{ number_format($plan->discount_percentage, 0) }}% desconto)
                                    @if (!$plan->is_active)
                                        - INATIVO
                                    @endif
                                </option>
                            @endforeach
                        </x-select>
                        @error('company_plan_id')
                            <div class="text-error text-sm mt-1">{{ $message }}</div>
                        @enderror
                        <small class="text-base-content/60 mt-1">
                            ⚠️ Apenas planos ativos podem ser selecionados. Planos inativos não concedem descontos.
                        </small>
                    </x-form-group>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('company.employee.index') }}" class="btn btn-soft btn-error">
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
