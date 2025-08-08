<div class="space-y-4">
    <ul class="menu menu-vertical lg:menu-horizontal bg-base-100 rounded-box w-full mb-6 gap-2">
        <li><a class="{{ !Route::is('specialist.profile.personal-details') ?: 'menu-active'}}" href="{{ route('specialist.profile.personal-details') }}">Dados pessoais</a></li>
        <li><a class="{{ !Route::is('specialist.profile.professional-details') ?: 'menu-active'}}" href="{{ route('specialist.profile.professional-details') }}">Dados profissionais</a></li>
    </ul>

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

    <x-card>
        <x-card-body>
            <x-card-title>Dados pessoais</x-card-title>
            <x-text>Veja os detalhes do perfil pessoal.</x-text>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:mt-2">
                <x-form-group>
                    <x-label>E-mail</x-label>
                    <x-text>{{ $this->specialist->email }}</x-text>
                </x-form-group>

                <x-form-group>
                    <x-label>CPF</x-label>
                    <x-text>{{ $this->specialist->cpf }}</x-text>
                </x-form-group>

                <x-form-group>
                    <x-label>Data de cadastro</x-label>
                    <x-text>{{ \Carbon\Carbon::parse($this->specialist->created_at)->format('d/m/Y') }}</x-text>
                </x-form-group>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:mt-2">
                <x-form-group>
                    <x-label required>Nome</x-label>
                    <x-input type="text" wire:model="name" placeholder="Digite seu nome" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Gênero</x-label>
                    <x-select wire:model="gender_id">
                        <option hidden>Selecione seu gênero</option>
                        @foreach($this->genders as $gender)
                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                        @endforeach
                    </x-select>
                </x-form-group>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:mt-2">
                <x-form-group>
                    <x-label required>Telefone</x-label>
                    <x-input type="text" wire:model="phone_number" x-mask="(99) 99999-9999" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Data de nascimento</x-label>
                    <x-input type="text" wire:model="birth_date" x-mask="99/99/9999" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Dê onde você atende?</x-label>
                    <x-select wire:model="state_id">
                        <option hidden>Selecione seu estado</option>
                        @foreach($this->states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </x-select>
                </x-form-group>
            </div>

            <x-form-group class="mt-2">
                <x-checkbox wire:model="lgbtqia">
                    Me declaro LGBTQIA+.
                    <div class="tooltip" data-tip="Autorizo a exibição do meu perfil para clientes que buscam profissionais LGBTQIA+.">
                        <x-carbon-information class="h-4 w-4" />
                    </div>
                </x-checkbox>
            </x-form-group>

            <div class="mt-2 text-end">
                <x-button class="btn-block sm:btn-wide" wire:click="updateProfile">
                    <x-carbon-save class="w-4 h-4" />
                    Salvar
                </x-button>
            </div>
        </x-card-body>
    </x-card>
</div>
