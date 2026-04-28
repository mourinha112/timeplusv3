<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-edit class="w-8 text-info" />
            Editar Agendamento #{{ $appointment->id }}
        </x-title>
    </x-heading>

    <x-card>
        <x-card-body>
            <x-form wire:submit="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form-group>
                        <x-label required>Cliente</x-label>
                        <x-select wire:model="user_id">
                            <option value="">Selecione</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} — CPF: {{ $user->cpf ?? '—' }}</option>
                            @endforeach
                        </x-select>
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Especialista</x-label>
                        <x-select wire:model="specialist_id">
                            <option value="">Selecione</option>
                            @foreach ($specialists as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} — CRP: {{ $s->crp ?? '—' }}</option>
                            @endforeach
                        </x-select>
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Modalidade</x-label>
                        <x-select wire:model="service_mode">
                            <option value="particular">Particular</option>
                            <option value="timeplus">TimePlus</option>
                        </x-select>
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Valor total (R$)</x-label>
                        <x-input type="number" step="0.01" min="0" wire:model="total_value" />
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Data</x-label>
                        <x-input type="text" wire:model="appointment_date" x-mask="99/99/9999" />
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Horário</x-label>
                        <x-input type="time" wire:model="appointment_time" />
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Duração (min)</x-label>
                        <x-input type="number" min="15" max="120" wire:model="duration_minutes" />
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Status</x-label>
                        <x-select wire:model="status">
                            <option value="scheduled">Agendado</option>
                            <option value="completed">Concluído</option>
                            <option value="cancelled">Cancelado</option>
                            <option value="no_show">Não compareceu</option>
                        </x-select>
                    </x-form-group>
                </div>

                <x-form-group>
                    <x-label>Observações</x-label>
                    <textarea wire:model="notes" class="textarea textarea-bordered w-full"
                        rows="3"></textarea>
                </x-form-group>

                <div class="flex justify-end gap-3 mt-6">
                    <a wire:navigate
                        href="{{ route('master.appointment.show', ['appointment' => $appointment->id]) }}"
                        class="btn btn-soft btn-error">
                        <x-carbon-arrow-left class="w-4 h-4" />
                        Cancelar
                    </a>
                    <x-button type="submit" class="btn btn-info">
                        <x-carbon-save class="w-4 h-4" />
                        Salvar Alterações
                    </x-button>
                </div>
            </x-form>
        </x-card-body>
    </x-card>
</div>
