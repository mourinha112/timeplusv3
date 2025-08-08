<div>
    <x-card>
        <x-card-body>
            <x-heading>
                <x-title>Dados pessoais</x-title>
                <x-subtitle>Informe seus dados pessoais para criar sua conta</x-subtitle>
            </x-heading>

            <x-form wire:submit="submit">
                <x-form-group>
                    <x-label required>Gênero</x-label>
                    <x-select wire:model="gender_id">
                        <option hidden>Selecione seu gênero</option>
                        @foreach($this->genders as $gender)
                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                        @endforeach
                    </x-select>
                </x-form-group>

                <x-form-group>
                    <x-label required>Especialidade</x-label>
                    <x-select wire:model="specialty_id">
                        <option hidden>Selecione sua especialidade</option>
                        @foreach($this->specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                        @endforeach
                    </x-select>
                </x-form-group>

                <x-form-group>
                    <x-label required>CRP</x-label>
                    <x-input wire:model="crp" placeholder="Digite o seu CRP" />
                </x-form-group>

                <x-form-group>
                    <x-label required>Início de atuação</x-label>
                    <x-input wire:model="year_started_acting" placeholder="Digite o ano de início de atuação" x-mask="9999" />
                </x-form-group>

                <x-button class="btn-block" type="submit">Próximo</x-button>
            </x-form>

            <div class="mt-5">
                <x-text>
                    Quer entrar com outra conta?
                    <x-link href="{{ route('specialist.auth.logout') }}">Sair</x-link>.
                </x-text>
            </div>
        </x-card-body>
    </x-card>
</div>
