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
            <x-text>Veja os detalhes do perfil profissional.</x-text>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:mt-2">
                <x-form-group>
                    <x-label required>Valor da sessão (R$)</x-label>
                    <x-input type="number" wire:model="appointment_value" placeholder="Digite o valor da sessão" />
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
