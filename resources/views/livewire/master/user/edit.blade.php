<div>
    <div class="space-y-6">
        <x-heading>
            <x-title class="flex items-start gap-3">
                <x-carbon-edit class="w-8 text-info" />
                Editar Usuário: {{ $user->name }}
            </x-title>
        </x-heading>

        <x-card class="card-compact">
            <x-card-body>
                <x-form wire:submit="save">
                    <div>
                        <h3 class="text-lg font-semibold text-base-content mb-4">Dados Pessoais</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form-group>
                                <x-label required>Nome</x-label>
                                <x-input type="text" wire:model="name" />
                            </x-form-group>

                            <x-form-group>
                                <x-label required>E-mail</x-label>
                                <x-input type="email" wire:model="email" />
                            </x-form-group>

                            <x-form-group>
                                <x-label>Telefone</x-label>
                                <x-input type="text" wire:model="phone_number" x-mask="(99) 99999-9999" placeholder="(11) 99999-9999" />
                            </x-form-group>

                            <x-form-group>
                                <x-label>CPF</x-label>
                                <x-input type="text" wire:model="cpf" x-mask="999.999.999-99" placeholder="000.000.000-00" />
                            </x-form-group>

                            <x-form-group>
                                <x-label>Data de Nascimento</x-label>
                                <x-input type="date" wire:model="birth_date" />
                            </x-form-group>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-base-content mb-4">Alterar Senha</h3>
                        <p class="text-sm text-base-content/60 mb-4">Deixe em branco para manter a senha atual.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form-group>
                                <x-label>Nova Senha</x-label>
                                <x-input type="password" wire:model="password" placeholder="Minimo 8 caracteres" />
                            </x-form-group>

                            <x-form-group>
                                <x-label>Confirmar Senha</x-label>
                                <x-input type="password" wire:model="password_confirmation" placeholder="Repita a senha" />
                            </x-form-group>
                        </div>
                    </div>

                    <x-form-group>
                        <x-checkbox wire:model="is_active">
                            Usuário ativo
                        </x-checkbox>
                    </x-form-group>

                    <div class="flex justify-end gap-3">
                        <a wire:navigate href="{{ route('master.user.personal-data.show', ['user' => $user->id]) }}" class="btn btn-soft btn-error">
                            <x-carbon-arrow-left class="w-4 h-4" />
                            Cancelar
                        </a>
                        <x-button type="submit" class="btn btn-soft btn-info">
                            <x-carbon-save class="w-4 h-4" />
                            Atualizar Usuário
                        </x-button>
                    </div>
                </x-form>
            </x-card-body>
        </x-card>
    </div>
</div>
