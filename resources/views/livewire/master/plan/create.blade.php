<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-plan class="w-8 text-info" />
            Novo Plano
        </x-title>
    </x-heading>

    <div class="flex justify-between items-center mb-6">
        <a wire:navigate href="{{ route('master.plan.index') }}" class="btn btn-ghost">
            <x-carbon-arrow-left class="w-4 h-4" />
            Voltar para Planos
        </a>
    </div>

    <x-card class="w-full">
        <x-card-body>
            <x-form wire:submit="save">
                <div class="space-y-6">
                    <x-form-group>
                        <x-label required>Nome do Plano</x-label>
                        <x-input type="text" wire:model="name" placeholder="Ex: Mensal, Semestral, Anual..." />
                        @error('name') <div class="text-error text-sm mt-1">{{ $message }}</div> @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Preco (R$)</x-label>
                        <x-input type="number" step="0.01" min="0.01" wire:model="price" placeholder="Ex: 49.99" />
                        @error('price') <div class="text-error text-sm mt-1">{{ $message }}</div> @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Percentual de Desconto (%)</x-label>
                        <x-input type="number" step="0.01" min="0" max="100" wire:model="discount_percentage" placeholder="Ex: 10.00" />
                        @error('discount_percentage') <div class="text-error text-sm mt-1">{{ $message }}</div> @enderror
                    </x-form-group>

                    <x-form-group>
                        <x-label required>Duracao (dias)</x-label>
                        <x-input type="number" min="1" wire:model="duration_days" placeholder="Ex: 30, 180, 365" />
                        @error('duration_days') <div class="text-error text-sm mt-1">{{ $message }}</div> @enderror
                    </x-form-group>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <a wire:navigate href="{{ route('master.plan.index') }}" class="btn btn-ghost">
                        <x-carbon-close class="w-4 h-4" /> Cancelar
                    </a>
                    <x-button type="submit" color="info">
                        <x-carbon-save class="w-4 h-4" /> Criar Plano
                    </x-button>
                </div>
            </x-form>
        </x-card-body>
    </x-card>
</div>
