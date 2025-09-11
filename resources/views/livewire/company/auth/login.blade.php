<div>
    <x-card class="shadow-2xl">
        <x-card-body>


            <x-heading>
                <x-title>Acesso Empresas</x-title>
                <x-subtitle>Entre com suas credenciais de empresa.</x-subtitle>
            </x-heading>

            <x-form wire:submit="submit">
                <x-form-group>
                    <x-label for="email">E-mail</x-label>
                    <x-input type="email" id="email" wire:model="email" placeholder="Digite seu endereço de e-mail"
                        autocomplete="email" />
                </x-form-group>

                <x-form-group>
                    <x-label for="password">Senha</x-label>
                    <x-input type="password" id="password" wire:model="password" placeholder="Digite sua senha"
                        autocomplete="current-password" />
                </x-form-group>



                <x-button class="btn-block" type="submit">Entrar</x-button>
            </x-form>

            <div class="text-center mt-6 pt-6 border-t border-base-300">
                <p class="text-sm text-base-content/70">
                    Problemas de acesso? Entre em contato com o suporte.
                </p>
            </div>
        </x-card-body>
    </x-card>
</div>
