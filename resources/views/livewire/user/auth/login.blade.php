<div>
    <x-card>
        <x-card-body>
            <x-heading>
                <x-title>Entrar</x-title>
                <x-subtitle>Inicie a sessão e trilhe sua jornada.</x-subtitle>
            </x-heading>

            {{-- @error('invalidCredentials')
                <x-alert type="error">
                    {{ $message }}
                </x-alert>
            @enderror

            @error('rateLimiter')
                <x-alert type="error">
                    {{ $message }}
                </x-alert>
            @enderror --}}

            <x-form wire:submit="submit">
                <x-form-group>
                    <x-label required>E-mail</x-label>
                    <x-input wire:model="email" placeholder="Digite seu endereço de e-mail" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Senha</x-label>
                    <x-input wire:model="password" type="password" placeholder="Digite sua senha" />
                    <x-link class="text-right" href="{{ route('user.auth.password-recovery') }}" wire:navigate>Esqueceu sua senha?</x-link>
                </x-form-group>

                <x-button class="btn-block" type="submit">Entrar</x-button>
            </x-form>

            <div class="mt-5">
                <x-text>
                    Ainda não é cadastrado?
                    <x-link href="{{ route('user.auth.register') }}" wire:navigate>Registrar</x-link>.
                </x-text>
            </div>
        </x-card-body>
    </x-card>
</div>
