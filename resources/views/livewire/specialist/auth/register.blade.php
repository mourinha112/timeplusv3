<div>
    <x-card>
        <x-card-body>
            <x-heading>
                <x-title>Cadastre-se agora</x-title>
                <x-subtitle>Inicie a sua jornada de saúde emocional.</x-subtitle>
            </x-heading>

            <x-form wire:submit="submit">
                <x-form-group>
                    <x-label required>Nome</x-label>
                    <x-input wire:model="name" placeholder="Digite seu nome completo" />
                </x-form-group>

                <x-form-group>
                    <x-label required>CPF</x-label>
                    <x-input wire:model="cpf" placeholder="Digite o seu CPF" x-mask="999.999.999-99" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Celular</x-label>
                    <x-input wire:model="phone_number" placeholder="Digite seu celular" x-mask="(99) 99999-9999" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Data de nascimento</x-label>
                    <x-input wire:model="birth_date" placeholder="Digite sua data de nascimento" x-mask="99/99/9999" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Email</x-label>
                    <x-input wire:model="email" placeholder="Digite seu endereço de e-mail" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Senha</x-label>
                    <x-input wire:model="password" type="password" placeholder="Digite sua senha" />
                </x-form-group>

                <x-button class="btn-block" type="submit">Criar minha conta</x-button>
            </x-form>

            <div class="mt-5">
                <x-text>
                    Já é cadastrado?
                    <x-link wire:navigate href="{{ route('specialist.auth.login') }}">Entrar</x-link>.
                </x-text>
            </div>
        </x-card-body>
    </x-card>
</div>
