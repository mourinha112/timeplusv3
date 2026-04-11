<div class="space-y-4">
    <ul class="menu menu-vertical lg:menu-horizontal bg-base-100 rounded-box w-full mb-6 gap-2">
        <li><a class="{{ !Route::is('specialist.profile.personal-details') ?: 'menu-active' }}" wire:navigate
                href="{{ route('specialist.profile.personal-details') }}">Dados pessoais</a></li>
        <li><a class="{{ !Route::is('specialist.profile.professional-details') ?: 'menu-active' }}" wire:navigate
                href="{{ route('specialist.profile.professional-details') }}">Dados profissionais</a></li>
    </ul>

    <x-heading>
        <x-title>Perfil</x-title>
        <x-subtitle>Atualize suas informações profissionais.</x-subtitle>
    </x-heading>

    <x-card>
        <x-card-body>
            <x-card-title>Dados profissionais</x-card-title>
            <x-text>Configure como você atende e por quais modalidades aceita pacientes.</x-text>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:mt-2">
                <x-form-group>
                    <x-label>CRP</x-label>
                    <x-input value="{{ $specialist->crp }}" disabled readonly />
                </x-form-group>
            </div>

            <div class="divider mt-6">Modalidades de atendimento</div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-start gap-3 p-4 border border-base-300 rounded-lg cursor-pointer">
                    <input type="checkbox" wire:model.live="accepts_particular" class="checkbox checkbox-primary mt-1" />
                    <div>
                        <div class="font-medium">Atender Particular</div>
                        <p class="text-sm text-base-content/70">Você define o valor da sessão. A plataforma retém 20% por repasse.</p>
                    </div>
                </label>

                <label class="flex items-start gap-3 p-4 border border-base-300 rounded-lg cursor-pointer">
                    <input type="checkbox" wire:model.live="accepts_timeplus" class="checkbox checkbox-primary mt-1" />
                    <div>
                        <div class="font-medium">Atender pelo TimePlus</div>
                        <p class="text-sm text-base-content/70">Você recebe R$ {{ number_format($timeplusFixedFee, 2, ',', '.') }} integrais por sessão (valor fixo).</p>
                    </div>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:mt-4">
                <x-form-group>
                    <x-label required>Valor da sessão particular (R$)</x-label>
                    <x-input type="number" step="0.01" wire:model="particular_session_value" placeholder="Ex: 120.00" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Duração da sessão (minutos)</x-label>
                    <x-select wire:model="session_duration_minutes">
                        @foreach ($durationOptions as $minutes)
                            <option value="{{ $minutes }}">{{ $minutes }} minutos</option>
                        @endforeach
                    </x-select>
                </x-form-group>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:mt-2">
                <x-form-group>
                    <x-label required>Resumo</x-label>
                    <x-textarea wire:model="summary" placeholder="Digite um resumo sobre você"></x-textarea>
                </x-form-group>

                <x-form-group>
                    <x-label required>Descrição pessoal</x-label>
                    <x-textarea wire:model="description" placeholder="Digite uma descrição sobre você"></x-textarea>
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
