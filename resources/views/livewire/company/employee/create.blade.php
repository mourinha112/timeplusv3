<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-user class="w-8 text-info" />
            Adicionar Funcionário
        </x-title>
        <x-subtitle>
            Cadastre um novo funcionário para sua empresa
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
                        <x-label required>E-mail</x-label>
                        <x-input type="email" wire:model="email" placeholder="funcionario@email.com" />
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
                        <x-label>Plano da empresa</x-label>
                        @if ($companyPlans->isEmpty())
                            <div class="bg-warning/10 px-3 py-2 rounded-lg text-sm">
                                Você ainda não tem planos ativos. O funcionário será cadastrado sem plano e poderá
                                ser vinculado depois.
                            </div>
                        @else
                            <x-select wire:model="company_plan_id">
                                @foreach ($companyPlans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }} ({{ $plan->billing_model === 'credit_pack' ? 'pacote de créditos' : 'por funcionário' }})
                                    </option>
                                @endforeach
                            </x-select>
                            <small class="text-base-content/60 mt-1">
                                O plano ativo da empresa é atribuído automaticamente. Você pode alterá-lo aqui.
                            </small>
                        @endif
                    </x-form-group>
                </div>

                <div class="mt-6 p-3 bg-base-200 rounded-lg text-sm text-base-content/70">
                    Um e-mail com as credenciais de acesso será enviado automaticamente para o endereço informado.
                    O funcionário deverá redefinir a senha no primeiro acesso.
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a wire:navigate href="{{ route('company.employee.index') }}" class="btn btn-soft btn-error">
                        <x-carbon-arrow-left class="w-4 h-4" />
                        Cancelar
                    </a>
                    <x-button type="submit" class="btn-soft btn-info" wire:loading.attr="disabled">
                        <span class="flex items-center gap-1" wire:loading.remove>
                            <x-carbon-add class="w-4 h-4" />
                            Adicionar Funcionário
                        </span>
                        <span wire:loading class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-xs"></span>
                            Adicionando...
                        </span>
                    </x-button>
                </div>
            </x-form>
        </x-card-body>
    </x-card>
</div>
