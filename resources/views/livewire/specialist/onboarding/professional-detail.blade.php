<div>
    <x-title>Dados pessoais</x-title>
    <x-subtitle class="mt-3 mb-8">Informe seus dados pessoais para criar sua conta</x-subtitle>

    <x-form wire:submit="submit">
        {{-- <x-form-group>
            <x-label required>Gênero</x-label>
            <select wire:model="gender_id" class="select w-full p-2 border border-gray-300 rounded">
                <option hidden>Selecione seu gênero</option>
                @foreach($this->genders as $gender)
                    <option value="{{ $gender->id }}">{{ $gender->name }}</option>
        @endforeach
        </select>
        </x-form-group> --}}

        <x-form-group>
            <x-label required>Valor da sessão</x-label>
            <x-input type="number" wire:model="appointment_value" placeholder="Digite o valor da sessão" />
        </x-form-group>

        <x-form-group>
            <x-label required>Resumo</x-label>
            <textarea class="textarea w-full" wire:model="summary" placeholder="Digite um resumo sobre você"></textarea>
            @error('summary')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </x-form-group>

        <x-form-group>
            <x-label required>Descrição pessoal</x-label>
            <textarea class="textarea w-full" wire:model="description" placeholder="Digite uma descrição sobre você"></textarea>
            @error('description')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </x-form-group>

        <x-button type="submit">Finalizar</x-button>
    </x-form>
</div>
