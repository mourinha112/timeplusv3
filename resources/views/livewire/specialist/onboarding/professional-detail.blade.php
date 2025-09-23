<div>
    <x-card>
        <x-card-body>
            <x-heading>
                <x-title>Dados profissionais</x-title>
                <x-subtitle>Informe seus dados profissionais para criar sua conta.</x-subtitle>
            </x-heading>

            <x-form wire:submit="submit">
                <x-form-group>
                    <x-label required>Valor da sessão (R$)</x-label>
                    <x-input type="number" wire:model="appointment_value" placeholder="Digite o valor da sessão" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Resumo</x-label>
                    <x-textarea wire:model="summary" placeholder="Digite um resumo sobre você"></x-textarea>
                </x-form-group>

                <x-form-group>
                    <x-label required>Descrição pessoal</x-label>
                    <x-textarea wire:model="description" placeholder="Digite uma descrição sobre você"></x-textarea>
                </x-form-group>

                <x-button class="btn-block" type="submit">Finalizar</x-button>
            </x-form>

            <div class="mt-5">
                <x-text>
                    Quer entrar com outra conta?
                    <x-link wire:navigate href="{{ route('specialist.auth.logout') }}">Sair</x-link>.
                </x-text>
            </div>
        </x-card-body>
    </x-card>
</div>
