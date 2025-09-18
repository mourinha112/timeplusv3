<div>
    <x-card>
        <x-card-body>
            <x-heading>
                <x-title>Recuperação de Senha</x-title>
                <x-subtitle>Digite seu e-mail para receber o link de redefinição de senha.</x-subtitle>
            </x-heading>

            <x-form wire:submit="submit">
                <x-form-group>
                    <x-label required>E-mail</x-label>
                    <x-input wire:model="email" placeholder="Digite seu endereço de e-mail" />
                </x-form-group>

                <x-button class="btn-block" type="submit">Enviar</x-button>
            </x-form>

            <div class="mt-5">
                <x-text>
                    Lembra da sua senha?
                    <x-link href="{{ route('user.auth.login') }}" wire:navigate>Voltar para o login</x-link>.
                </x-text>
            </div>
        </x-card-body>
    </x-card>
</div>
