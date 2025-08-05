<div>
    <x-title>Dados pessoais</x-title>
    <x-subtitle class="mt-3 mb-8">Informe seus dados pessoais para criar sua conta</x-subtitle>

    <x-form wire:submit="submit">
        <x-form-group>
            <x-label required>Gênero</x-label>
            <select wire:model="gender_id" class="select w-full p-2 border border-gray-300 rounded">
                <option hidden>Selecione seu gênero</option>
                @foreach($this->genders as $gender)
                    <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                @endforeach
            </select>
        </x-form-group>

        <x-form-group>
            <x-label required>Especialidade</x-label>
            <select wire:model="specialty_id" class="select w-full p-2 border border-gray-300 rounded">
                <option hidden>Selecione sua especialidade</option>
                @foreach($this->specialties as $specialty)
                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                @endforeach
            </select>
        </x-form-group>

        <x-form-group>
            <x-label required>CRP</x-label>
            <x-input wire:model="crp" placeholder="Digite o seu CRP" />
        </x-form-group>

        <x-form-group>
            <x-label required>Início de atuação</x-label>
            <x-input wire:model="year_started_acting" placeholder="Digite o ano de início de atuação" x-mask="9999" />
        </x-form-group>

        <x-button type="submit">Próximo</x-button>
    </x-form>
</div>
