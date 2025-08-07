<div class="space-y-4">
    <x-heading>
        <x-title>Perfil</x-title>
        <x-subtitle>Atualize suas informações pessoais.</x-subtitle>
    </x-heading>

    {{-- Foto de perfil --}}
    <x-card>
        <x-card-body class="items-center justify-center">
            @if ($currentAvatar)
                {{-- Avatar atual --}}
                <img src="{{ Storage::url($currentAvatar) }}" class="w-25 h-25 rounded-full">
            @else
                {{-- Avatar padrão --}}
                <img src="{{ asset('images/avatar.png') }}" class="w-25 h-25 rounded-full">
            @endif

            <x-form-group>
                <x-label>Foto de perfil</x-label>
                <x-input-file wire:model="avatar" accept="image/*" />
                <x-input-description>Imagem deve conter no máximo 2MB.</x-input-description>
            </x-form-group>
        </x-card-body>
    </x-card>

    {{-- Alteração de senha --}}
    <x-card>
        <x-card-body>
            <x-card-title>Alterar senha</x-card-title>
            <x-text>Mantenha sua conta segura alterando sua senha regularmente.</x-text>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <x-form-group>
                    <x-label required>Nova senha</x-label>
                    <x-input type="password" wire:model="password" placeholder="Digite sua nova senha" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Confirmação de senha</x-label>
                    <x-input type="password" wire:model="password_confirmation" placeholder="Digite a confirmação da senha" />
                </x-form-group>
            </div>

            <div class="mt-2 text-end">
                <x-button class="btn-block sm:btn-wide" wire:click="updatePassword">
                    <x-carbon-unlocked class="w-4 h-4" />
                    Alterar senha
                </x-button>
            </div>
        </x-card-body>
    </x-card>

    {{-- Dados pessoais --}}
    <x-card>
        <x-card-body>
            <x-card-title>Dados Pessoais</x-card-title>
            <x-text>Mantenha suas informações pessoais atualizadas.</x-text>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                <x-form-group>
                    <x-label>E-mail</x-label>
                    <x-text>{{ $this->user->email }}</x-text>
                </x-form-group>

                <x-form-group>
                    <x-label>CPF</x-label>
                    <x-text>{{ $this->user->cpf }}</x-text>
                </x-form-group>

                <x-form-group>
                    <x-label>Data de cadastro</x-label>
                    <x-text>{{ \Carbon\Carbon::parse($this->user->created_at)->format('d/m/Y') }}</x-text>
                </x-form-group>

                <x-form-group>
                    <x-label required>Nome</x-label>
                    <x-input type="text" wire:model="name" placeholder="Digite seu nome" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Telefone</x-label>
                    <x-input type="text" wire:model="phone_number" x-mask="(99) 99999-9999" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Data de nascimento</x-label>
                    <x-input type="text" wire:model="birth_date" x-mask="99/99/9999" />
                </x-form-group>
            </div>

            <div class="mt-2 text-end">
                 <x-button class="btn-block sm:btn-wide" wire:click="updateProfile">
                    <x-carbon-save class="w-4 h-4" />
                    Salvar
                </x-button>
            </div>
        </x-card-body>
    </x-card>
</div>
