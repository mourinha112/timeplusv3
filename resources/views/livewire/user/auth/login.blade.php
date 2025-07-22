<div>
    <div class="space-y-3 mb-8">
        <x-title>Entrar</x-title>
        <x-subtitle>Faça seu login e trilhe sua jornada.</x-subtitle>
    </div>

    @error('invalidCredentials')
    <span class="text-red-600">{{ $message }}</span>
    @enderror

    @error('rateLimiter')
    <span class="text-red-600">-message:{{ $message }} -seconds: {{ $seconds }}</span>
    @enderror

    <x-form wire:submit="submit">
        <x-form-group>
            <x-label id="email" required>E-mail</x-label>
            <x-input wire:model="email" placeholder="Digite seu endereço de e-mail" />
        </x-form-group>

        <x-form-group>
            <x-label id="password" required>Senha</x-label>
            <x-input wire:model="password" type="password" placeholder="Digite sua senha" />
            <x-link class="text-right" href="{{ route('user.auth.register') }}">Esqueceu sua senha?</x-link>
        </x-form-group>

        <x-button type="submit">Entrar</x-button>
    </x-form>

    <div class="mt-5">
        <x-text>
            Ainda não é cadastrado?
            <x-link href="{{ route('user.auth.register') }}">Registrar</x-link>.
        </x-text>
    </div>
</div>
