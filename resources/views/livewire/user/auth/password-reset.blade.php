<div>
    <x-card>
        <x-card-body>
            <x-heading>
                <x-title>Redefinição de Senha</x-title>
                <x-subtitle>Digite uma nova senha para sua conta.</x-subtitle>
            </x-heading>

            @if($expired)
            <x-alert class="alert-error">
                <x-carbon-warning class="w-4 h-4" />
                O token de redefinição de senha expirou.
            </x-alert>
            @else
            <x-form wire:submit="submit">
                <x-form-group>
                    <x-label required>Nova senha</x-label>
                    <x-input wire:model="password" placeholder="Digite sua nova senha" type="password" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Confirmação da nova senha</x-label>
                    <x-input wire:model="password_confirmation" placeholder="Confirme sua nova senha" type="password" />
                </x-form-group>

                <x-button class="btn-block" type="submit">Salvar</x-button>
            </x-form>
            @endif
            <div class="mt-5">
                <x-text>
                    Lembra da sua senha?
                    <x-link href="{{ route('user.auth.login') }}" wire:navigate>Voltar para o login</x-link>.
                </x-text>
            </div>
        </x-card-body>
    </x-card>
</div>
