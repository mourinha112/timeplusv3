<div>
    <x-card>
        <x-card-body>
            <x-heading>
                <x-title>Entrar</x-title>
                <x-subtitle>Painel administrativo da aplicação.</x-subtitle>
            </x-heading>

            <x-form wire:submit="submit">
                <x-form-group>
                    <x-label required>E-mail</x-label>
                    <x-input wire:model="email" placeholder="Digite seu endereço de e-mail" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Senha</x-label>
                    <x-input wire:model="password" type="password" placeholder="Digite sua senha" />
                </x-form-group>

                <x-button class="btn-block" type="submit">Entrar</x-button>
            </x-form>
        </x-card-body>
    </x-card>
</div>
