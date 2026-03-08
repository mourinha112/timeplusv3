<div>
    <div class="space-y-6">
        <x-heading>
            <x-title class="flex items-start gap-3">
                <x-carbon-edit class="w-8 text-info" />
                Editar Especialista: {{ $specialist->name }}
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
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-base-content mb-4">Dados Profissionais</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form-group>
                                <x-label>CRP</x-label>
                                <x-input type="text" wire:model="crp" />
                            </x-form-group>

                            <x-form-group>
                                <x-label>Especialidade</x-label>
                                <x-select wire:model="specialty_id">
                                    <option value="">Selecione</option>
                                    @foreach ($specialties as $specialty)
                                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                    @endforeach
                                </x-select>
                            </x-form-group>

                            <x-form-group>
                                <x-label>Gênero</x-label>
                                <x-select wire:model="gender_id">
                                    <option value="">Selecione</option>
                                    @foreach ($genders as $gender)
                                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                    @endforeach
                                </x-select>
                            </x-form-group>

                            <x-form-group>
                                <x-label>Estado</x-label>
                                <x-select wire:model="state_id">
                                    <option value="">Selecione</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </x-select>
                            </x-form-group>

                            <x-form-group>
                                <x-label>Valor da Consulta (R$)</x-label>
                                <x-input type="number" step="0.01" wire:model="appointment_value" />
                            </x-form-group>
                        </div>
                    </div>

                    <x-form-group>
                        <x-checkbox wire:model="is_active">
                            Especialista ativo
                        </x-checkbox>
                    </x-form-group>

                    <div class="flex justify-end gap-3">
                        <a wire:navigate href="{{ route('master.specialist.personal-data.show', ['specialist' => $specialist->id]) }}" class="btn btn-soft btn-error">
                            <x-carbon-arrow-left class="w-4 h-4" />
                            Cancelar
                        </a>
                        <x-button type="submit" class="btn btn-soft btn-info">
                            <x-carbon-save class="w-4 h-4" />
                            Atualizar Especialista
                        </x-button>
                    </div>
                </x-form>
            </x-card-body>
        </x-card>
    </div>
</div>
