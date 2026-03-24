<div class="space-y-4">
    <ul class="menu menu-vertical lg:menu-horizontal bg-base-100 rounded-box w-full mb-6 gap-2">
        <li><a class="{{ !Route::is('specialist.profile.personal-details') ?: 'menu-active' }}" wire:navigate
                href="{{ route('specialist.profile.personal-details') }}">Dados pessoais</a></li>
        <li><a class="{{ !Route::is('specialist.profile.professional-details') ?: 'menu-active' }}" wire:navigate
                href="{{ route('specialist.profile.professional-details') }}">Dados profissionais</a></li>
    </ul>

    <x-heading>
        <x-title>Perfil</x-title>
        <x-subtitle>Atualize suas informações pessoais.</x-subtitle>
    </x-heading>

    {{-- Foto de perfil --}}
    <x-card>
        <x-card-body class="items-center justify-center">
            <div class="flex flex-col items-center gap-4">
                {{-- Avatar com overlay clicável --}}
                <label for="avatar-upload" class="relative cursor-pointer group">
                    <div class="w-28 h-28 rounded-full overflow-hidden ring-2 ring-base-300 group-hover:ring-primary transition-all">
                        @if ($currentAvatar)
                            <img src="{{ Storage::url($currentAvatar) }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/avatar.png') }}" class="w-full h-full object-cover">
                        @endif
                    </div>

                    {{-- Overlay de hover --}}
                    <div class="absolute inset-0 rounded-full bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <x-carbon-camera class="w-8 h-8 text-white" />
                    </div>

                    {{-- Loading indicator --}}
                    <div wire:loading wire:target="avatar" class="absolute inset-0 rounded-full bg-black/60 flex items-center justify-center">
                        <span class="loading loading-spinner loading-md text-white"></span>
                    </div>
                </label>

                {{-- Input de arquivo oculto --}}
                <input type="file" id="avatar-upload" wire:model="avatar" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml" class="hidden" />

                <div class="text-center">
                    <label for="avatar-upload" class="btn btn-sm btn-outline btn-primary cursor-pointer">
                        <x-carbon-upload class="w-4 h-4" />
                        Alterar foto
                    </label>
                    <p class="text-xs text-base-content/60 mt-1">JPG, PNG ou GIF. Máximo 2MB.</p>
                </div>

                @error('avatar')
                    <small class="text-red-600 text-xs">{{ $message }}</small>
                @enderror
            </div>
        </x-card-body>
    </x-card>

    {{-- Dados pessoais --}}
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
                        @foreach ($this->genders as $gender)
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
                        @foreach ($this->states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </x-select>
                </x-form-group>
            </div>

            <x-form-group class="mt-2">
                <x-checkbox wire:model="lgbtqia">
                    Me declaro LGBTQIA+.
                    <div class="tooltip"
                        data-tip="Autorizo a exibição do meu perfil para clientes que buscam profissionais LGBTQIA+.">
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
