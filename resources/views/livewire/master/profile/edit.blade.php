<div>
    <div class="space-y-6">
        <x-heading>
            <x-title class="flex items-start gap-3">
                <x-carbon-user class="w-8 text-info" /> Meu Perfil
            </x-title>
        </x-heading>

        <x-card class="card-compact">
            <x-card-body>
                <x-form wire:submit="save">
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
                            <x-label>Nova Senha (deixe vazio para manter)</x-label>
                            <x-input type="password" wire:model="password" />
                        </x-form-group>

                        <x-form-group>
                            <x-label>Confirmar Nova Senha</x-label>
                            <x-input type="password" wire:model="password_confirmation" />
                        </x-form-group>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <a wire:navigate href="{{ route('master.dashboard.show') }}" class="btn btn-ghost">Cancelar</a>
                        <x-button type="submit" class="btn btn-soft btn-info">
                            <x-carbon-save class="w-4 h-4" /> Atualizar Perfil
                        </x-button>
                    </div>
                </x-form>
            </x-card-body>
        </x-card>
    </div>
</div>
