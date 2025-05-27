<div>
    <x-title>Entrar</x-title>
    <x-subtitle class="mt-3 mb-8">Faça seu login e trilhe sua jornada.</x-subtitle>

    @error('invalidCredentials')
        <span class="text-red-600">{{ $message }}</span>
    @enderror

    <x-form wire:submit="submit">
        <x-form-group>
            <x-label required>E-mail</x-label>
            <x-input wire:model="email" placeholder="Digite seu endereço de e-mail" />
        </x-form-group>

        <x-form-group>
            <x-label required>Senha</x-label>
            <x-input wire:model="password" type="password" placeholder="Digite sua senha" />
        </x-form-group>

        <x-link href="{{ route('user.auth.register') }}">Esqueceu sua senha?</x-link>

        <x-button type="submit">Entrar</x-button>
    </x-form>

    <div class="mt-5">
        <x-text>
            Ainda não é cadastrado?
            <x-link href="{{ route('user.auth.register') }}">Registrar</x-link>.
        </x-text>
    </div>
</div>
