<div>
    <x-card>
        <x-heading>
            <x-title>Entrar</x-title>
            <x-subtitle>Faça seu login e trilhe sua jornada.</x-subtitle>
        </x-heading>

        <x-form wire:submit="submit">
            <x-form-group>
                <x-label required>E-mail</x-label>
                <x-input wire:model="email" placeholder="Digite seu endereço de e-mail" />
            </x-form-group>

            <x-form-group>
                <x-label required>Senha</x-label>
                <x-input wire:model="password" type="password" placeholder="Digite sua senha" />
                <x-link class="text-right" href="{{ route('user.auth.register') }}">Esqueceu sua senha?</x-link>
            </x-form-group>

            <x-button class="btn-block" type="submit">Entrar</x-button>
        </x-form>

        <div class="mt-5">
            <x-text>
                Ainda não é cadastrado?
                <x-link href="{{ route('user.auth.register') }}">Registrar</x-link>.
            </x-text>
        </div>
    </x-card>
</div>
