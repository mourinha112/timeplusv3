<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-gender-female class="w-8 text-info" />
            Editar Genero
        </x-title>
    </x-heading>

    <div class="flex justify-between items-center mb-6">
        <a wire:navigate href="{{ route('master.gender.index') }}" class="btn btn-ghost">
            <x-carbon-arrow-left class="w-4 h-4" />
            Voltar para Generos
        </a>
    </div>

    <x-card class="w-full">
        <x-card-body>
            <x-form wire:submit="save">
                <div class="space-y-6">
                    <x-form-group>
                        <x-label required>Nome do Genero</x-label>
                        <x-input type="text" wire:model="name" placeholder="Ex: Masculino, Feminino..." />
                        @error('name') <div class="text-error text-sm mt-1">{{ $message }}</div> @enderror
                    </x-form-group>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <a wire:navigate href="{{ route('master.gender.index') }}" class="btn btn-ghost">
                        <x-carbon-close class="w-4 h-4" /> Cancelar
                    </a>
                    <x-button type="submit" color="info">
                        <x-carbon-save class="w-4 h-4" /> Atualizar Genero
                    </x-button>
                </div>
            </x-form>
        </x-card-body>
    </x-card>
</div>
