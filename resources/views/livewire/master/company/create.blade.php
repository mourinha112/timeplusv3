<div class="container mx-auto">
    <x-heading>
        <x-title class="flex items-start gap-3">
            <x-carbon-enterprise class="w-8 text-info" />
            Nova Empresa
        </x-title>
    </x-heading>

    <x-card class="card-compact">
        <x-card-body>
            <x-form wire:submit="save">
                <div>
                    <x-subtitle class="text-lg font-semibold text-base-content mb-4">Informações da Empresa</x-subtitle>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-form-group>
                            <x-label required>Nome da Empresa</x-label>
                            <x-input type="text" wire:model="name" />
                        </x-form-group>

                        <x-form-group>
                            <x-label required>CNPJ</x-label>
                            <x-input type="text" wire:model="cnpj" placeholder="00000000000000" maxlength="14" />
                        </x-form-group>

                        <x-form-group>
                            <x-label required>E-mail</x-label>
                            <x-input type="email" wire:model="email" />
                        </x-form-group>

                        <x-form-group>
                            <x-label required>Telefone</x-label>
                            <x-input type="text" wire:model="phone" x-mask="(99) 99999-9999"
                                placeholder="(11) 99999-9999" maxlength="15" />
                        </x-form-group>
                    </div>
                </div>

                <!-- Endereço -->
                <div>
                    <h3 class="text-lg font-semibold text-base-content mb-4">Endereço</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <x-form-group>
                                <x-label required>Endereço</x-label>
                                <x-input type="text" wire:model="address" />
                            </x-form-group>
                        </div>

                        <x-form-group>
                            <x-label required>CEP</x-label>
                            <x-input type="text" wire:model="zip_code" placeholder="00000-000" x-mask="99999-999"
                                maxlength="9" />
                        </x-form-group>

                        <x-form-group>
                            <x-label required>Cidade</x-label>
                            <x-input type="text" wire:model="city" />
                        </x-form-group>

                        <x-form-group>
                            <x-label required>Estado</x-label>
                            <x-select wire:model="state">
                                <option value="">Selecione</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </x-select>
                        </x-form-group>
                    </div>
                </div>

                <!-- Status -->
                <x-form-group>
                    <x-checkbox class="checkbox-info" wire:model="is_active" checked>
                        Empresa ativa
                    </x-checkbox>
                </x-form-group>

                <!-- Botões -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('master.company.index') }}" class="btn btn-soft btn-error">
                        <x-carbon-arrow-left class="w-4 h-4" />
                        Cancelar
                    </a>
                    <x-button type="submit" class="btn btn-soft btn-info">
                        <x-carbon-save class="w-4 h-4" />
                        Criar Empresa
                    </x-button>
                </div>
            </x-form>
        </x-card-body>
    </x-card>

    <!-- Modal de Credenciais -->
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
                        Empresa Criada com Sucesso!
                    </h3>

                    <p class="text-center text-base-content/70 mb-6">
                        Credenciais de acesso geradas automaticamente:
                    </p>

                    <div class="space-y-4 bg-base-200 p-4 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-base-content mb-1">
                                E-mail de Login:
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="text" value="{{ $companyEmail }}" readonly
                                    class="input input-bordered input-sm flex-1 bg-base-100 font-mono">
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $companyEmail }}')"
                                    class="btn btn-ghost btn-sm">
                                    <x-carbon-copy class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-base-content mb-1">
                                Senha de acesso:
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="text" value="{{ $companyPassword }}" readonly
                                    class="input input-bordered input-sm flex-1 bg-base-100 font-mono">
                                <button type="button"
                                    onclick="navigator.clipboard.writeText('{{ $companyPassword }}')"
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
                                    <strong>Importante:</strong> Anote essas credenciais em local seguro.
                                    A empresa deverá alterar a senha no primeiro acesso.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-info/10 border border-info/20 rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <x-carbon-link class="w-4 h-4 text-info" />
                            <span class="text-sm font-medium text-info">Link de Acesso:</span>
                        </div>
                        <a href="{{ route('company.auth.login') }}" target="_blank"
                            class="text-sm text-info hover:text-info/80 underline break-all">
                            {{ route('company.auth.login') }}
                        </a>
                    </div>

                    <div class="flex justify-center mt-6">
                        <button wire:click="closeModal" class="btn btn-info">
                            <x-carbon-checkmark class="w-4 h-4" />
                            Entendi, Continuar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
