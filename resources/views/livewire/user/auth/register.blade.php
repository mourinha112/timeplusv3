<div>
    <x-title>Cadastre-se agora</x-title>
    <x-subtitle class="mt-3 mb-8">Inicie a sua jornada de saúde emocional</x-subtitle>

    <x-form wire:submit="submit">
        <x-form-group>
            <x-label required>Nome</x-label>
            <x-input wire:model="name" placeholder="Digite seu nome completo" />
        </x-form-group>

        <x-form-group>
            <x-label required>CPF</x-label>
            <x-input wire:model="cpf" placeholder="Digite o seu CPF" x-mask="999.999.999-99"/>
        </x-form-group>

        <x-form-group>
            <x-label required>Celular</x-label>
            <x-input wire:model="phone_number" placeholder="Digite seu celular" x-mask="(99) 99999-9999" />
        </x-form-group>

        <x-form-group>
            <x-label required>Email</x-label>
            <x-input wire:model="email" placeholder="Digite seu endereço de e-mail" />
        </x-form-group>

        <x-form-group>
            <x-label required>Senha</x-label>
            <x-input wire:model="password" type="password" placeholder="Digite sua senha" />
        </x-form-group>

        <x-button type="submit">Criar minha conta</x-button>
    </x-form>

    <div class="mt-5">
        <x-text>
            Já é cadastrado?
            <x-link href="{{ route('user.auth.login') }}">Entrar</x-link>.
        </x-text>
    </div>
</div>
