<div class="container mx-auto">
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
                        <x-label>E-mail</x-label>
                        <div class="bg-base-200 px-3 py-2 rounded-lg text-sm text-base-content/70">
                            📧 Será gerado automaticamente: <code>xxxxxxxx@timeplus.com.br</code>
                        </div>
                        <small class="text-base-content/60">O funcionário poderá alterar para email pessoal
                            posteriormente</small>
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
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('company.employee.index') }}" class="btn btn-soft btn-error">
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

    <!-- Modal de Resultado -->
    @if ($showCredentialsModal)
        <div x-data="{ show: @entangle('showCredentialsModal') }" x-show="show" x-transition.opacity.duration.300ms
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
            @click.self="$wire.closeModal()" @keydown.escape.window="$wire.closeModal()" style="display: none;">
            <div class="w-full max-w-lg mx-4" x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95" @click.stop>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6">
                    <div class="flex items-center justify-center mb-4">
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                            <x-carbon-checkmark class="w-8 text-green-600 dark:text-green-400" />
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-center mb-2 text-base-content">
                        @if ($userExists)
                            Funcionário Vinculado com Sucesso!
                        @else
                            Funcionário Criado com Sucesso!
                        @endif
                    </h3>

                    <p class="text-center text-base-content/70 mb-6">
                        @if ($userExists)
                            Usuário já existente vinculado à empresa com sucesso.
                        @else
                            Credenciais de acesso geradas automaticamente:
                        @endif
                    </p>

                    @if (!$userExists)
                        <div class="space-y-4 bg-base-200 p-4 rounded-lg">
                            <div>
                                <label class="block text-sm font-medium text-base-content mb-1">
                                    E-mail de Login:
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="text" value="{{ $userEmail }}" readonly
                                        class="input input-bordered input-sm flex-1 bg-base-100 font-mono">
                                    <button type="button"
                                        onclick="navigator.clipboard.writeText('{{ $userEmail }}')"
                                        class="btn btn-ghost btn-sm">
                                        <x-carbon-copy class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-base-content mb-1">
                                    Senha Temporária:
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="text" value="{{ $userPassword }}" readonly
                                        class="input input-bordered input-sm flex-1 bg-base-100 font-mono">
                                    <button type="button"
                                        onclick="navigator.clipboard.writeText('{{ $userPassword }}')"
                                        class="btn btn-ghost btn-sm">
                                        <x-carbon-copy class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="mt-6 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <div class="flex">
                                <x-carbon-warning class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2 mt-0.5" />
                                <div>
                                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                        <strong>Importante:</strong> Forneça essas credenciais ao funcionário.
                                        Ele deverá alterar a senha no primeiro acesso.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-center mt-6">
                        <button wire:click="closeModal" class="btn btn-success">
                            <x-carbon-checkmark class="w-4 h-4" />
                            Entendi, Continuar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
