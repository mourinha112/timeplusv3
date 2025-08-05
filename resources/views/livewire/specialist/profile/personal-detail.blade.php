<div>
    <div class="min-h-screen p-4">
        <ul class="menu menu-vertical lg:menu-horizontal bg-base-100 rounded-box w-full mb-6 gap-2">
            <li><a class="{{ !Route::is('specialist.profile.personal-details') ?: 'menu-active'}}" href="{{ route('specialist.profile.personal-details') }}">Dados pessoais</a></li>
            <li><a class="{{ !Route::is('specialist.profile.professional-details') ?: 'menu-active'}}" href="{{ route('specialist.profile.professional-details') }}">Dados profissionais</a></li>
        </ul>

        <div class="card">
            <div class="card-body">
                <div class="space-y-3 mb-8">
                    <x-title>Dados pessoais</x-title>
                    <x-subtitle>Veja os detalhes do perfil pessoal.</x-subtitle>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex flex-col items-center md:items-start text-center md:text-start space-y-4 md:col-span-1">
                        <!-- Avatar -->
                        <div class="mb-4 relative">
                            <div class="w-40 h-40 rounded-full bg-transparent border-none flex items-center justify-center">
                                <x-carbon-user-avatar-filled class="text-info h-40 w-40" />
                            </div>

                            <!-- Botão de câmera -->
                            <button onclick="document.getElementById('avatar-input').click()" class="absolute bottom-0 right-0 w-10 h-10 bg-info hover:bg-info rounded-full flex items-center justify-center border-none  shadow-lg transition-colors">
                                <x-carbon-camera class="text-slate-800  h-6 w-6" />
                            </button>

                            <!-- Input de arquivo oculto -->
                            <input type="file" id="avatar-input" accept="image/*" class="hidden" />
                        </div>
                    </div>

                    {{-- Formulário de atualização de perfil --}}
                    <div class="flex flex-col space-y-4 md:col-span-2 text-slate-600">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nome --}}
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Nome: <span class="text-error">*</span></legend>
                                <input type="text" class="input" wire:model="name" />
                                @error('name')
                                <span class="text-error">{{ $message }}</span>
                                @enderror
                            </fieldset>

                            {{-- E-mail --}}
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">E-mail: <span class="text-error">*</span></legend>
                                <input type="text" class="input" wire:model="email" disabled />
                            </fieldset>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Celular --}}
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Celular: <span class="text-error">*</span></legend>
                                <input type="text" class="input" wire:model="phone_number" x-mask="(99) 99999-9999" />
                                {{-- <p class="label">Não aparece para os clientes.</p> --}}
                                @error('phone_number')
                                <span class="text-error">{{ $message }}</span>
                                @enderror
                            </fieldset>

                            {{-- Data de nascimento --}}
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Data de nascimento: <span class="text-error">*</span></legend>
                                <input type="text" class="input" wire:model="birth_date" x-mask="99/99/9999" />
                                <p class="label">Não aparece para os clientes.</p>
                                @error('birth_date')
                                <span class="text-error">{{ $message }}</span>
                                @enderror
                            </fieldset>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Gênero --}}
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">Gênero: <span class="text-error">*</span></legend>
                                <select wire:model="gender_id" class="w-full select">
                                    <option hidden>Selecione seu gênero</option>
                                    @foreach($this->genders as $gender)
                                    <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                    @endforeach

                                </select>
                                @error('gender_id')
                                <span class="text-error">{{ $message }}</span>
                                @enderror
                            </fieldset>

                            {{-- De onde você atende? --}}
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend">De onde você atende? <span class="text-error">*</span></legend>
                                <select wire:model="state_id" class="w-full select">
                                    <option disabled selected>Selecione seu estado</option>
                                    @foreach($this->states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                @error('state_id')
                                <span class="text-error">{{ $message }}</span>
                                @enderror
                            </fieldset>
                        </div>

                        {{-- Me declaro LGBTQIA+ --}}
                        <fieldset class="fieldset">
                            <label class="label">
                                <input type="checkbox" class="checkbox" wire:model="lgbtqia" />
                                Me declaro LGBTQIA+.
                                <div class="tooltip" data-tip="Autorizo a exibição do meu perfil para clientes que buscam profissionais LGBTQIA+.">
                                    <x-carbon-information class="h-4 w-4" />
                                </div>
                            </label>
                        </fieldset>

                        {{-- Botão de salvar --}}
                        <div class="mt-6 sm:text-right">
                            <button class="btn btn-info" wire:click="submit">
                                Salvar
                                <x-carbon-save class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
