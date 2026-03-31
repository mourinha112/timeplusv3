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
                            📧 E-mail atual: <code>{{ $employee->email }}</code>
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
                        <x-label required>Plano</x-label>
                        <x-select wire:model="company_plan_id">
                            <option value="">Selecione um plano</option>
                            @foreach ($companyPlans as $plan)
                                <option value="{{ $plan->id }}" @if (!$plan->is_active) disabled @endif>
                                    {{ $plan->name }} ({{ number_format($plan->discount_percentage, 0) }}% de cobertura)
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

                <div class="flex justify-between items-center mt-6">
                    <button type="button" wire:click="openPasswordModal" class="btn btn-soft btn-warning">
                        <x-carbon-password class="w-4 h-4" />
                        Alterar Senha
                    </button>

                    <div class="flex gap-3">
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
                </div>
            </x-form>
        </x-card-body>
    </x-card>

    <!-- Modal de Alteração de Senha -->
    @if ($showPasswordModal)
        <div x-data="{ show: @entangle('showPasswordModal') }" x-show="show" x-transition.opacity.duration.300ms
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
            @click.self="$wire.closePasswordModal()" @keydown.escape.window="$wire.closePasswordModal()"
            style="display: none;">
            <div class="w-full max-w-md mx-4" x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95" @click.stop>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6">
                    <div class="flex items-center justify-center mb-4">
                        <div class="bg-warning/20 p-3 rounded-full">
                            <x-carbon-password class="w-8 text-warning" />
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-center mb-2 text-base-content">
                        Alterar Senha do Funcionário
                    </h3>

                    <p class="text-center text-base-content/70 mb-4">
                        {{ $employee->name }}
                    </p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-base-content mb-1">
                                Nova Senha:
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="text" wire:model="newPassword" readonly
                                    class="input input-bordered input-sm flex-1 bg-base-100 font-mono">
                                <button type="button" wire:click="generateNewPassword" class="btn btn-ghost btn-sm"
                                    title="Gerar nova senha">
                                    <x-carbon-renew class="w-4 h-4" />
                                </button>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $newPassword }}')"
                                    class="btn btn-ghost btn-sm" title="Copiar">
                                    <x-carbon-copy class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" wire:model="sendPasswordEmail" class="checkbox checkbox-info" />
                                <span class="label-text">Enviar nova senha por e-mail para o funcionário</span>
                            </label>
                        </div>
                    </div>

                    <div
                        class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <div class="flex">
                            <x-carbon-warning class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2 mt-0.5" />
                            <div>
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    <strong>Atenção:</strong> Esta ação irá substituir a senha atual do funcionário.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="closePasswordModal" class="btn btn-ghost">
                            Cancelar
                        </button>
                        <button type="button" wire:click="resetPassword" class="btn btn-warning">
                            <x-carbon-checkmark class="w-4 h-4" />
                            Confirmar Alteração
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
