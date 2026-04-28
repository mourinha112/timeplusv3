<div class="flex justify-between items-center mb-4">

    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-user-multiple class="w-8 text-info" />
            Funcionários
        </x-title>
        <x-subtitle>
            Gerencie os funcionários da sua empresa
        </x-subtitle>
    </x-heading>

    <div class="flex gap-2">
        {{-- Botão Importar com Tooltip --}}
        <div class="tooltip tooltip-bottom"
            data-tip="Importe vários funcionários de uma vez usando um arquivo CSV. O arquivo deve conter: nome, cpf, email, telefone e data_nascimento.">
            <a wire:navigate href="{{ route('company.employee.import') }}" class="btn btn-soft btn-success">
                <x-carbon-upload class="w-4 h-4" />
                Importar em Massa
            </a>
        </div>

        <a wire:navigate href="{{ route('company.employee.create') }}" class="btn btn-info">
            <x-carbon-add class="w-4 h-4" />
            Novo Funcionário
        </a>
    </div>
</div>

@if (session()->has('message'))
    <div role="alert" class="alert alert-success shadow-lg mb-4">
        <x-carbon-checkmark class="w-6 h-6" />
        <span class="font-medium">{{ session('message') }}</span>
    </div>
@endif

@if (session()->has('error'))
    <div role="alert" class="alert alert-error shadow-lg mb-4">
        <x-carbon-warning class="w-6 h-6" />
        <span class="font-medium">{{ session('error') }}</span>
    </div>
@endif

<div class="flex items-center gap-2 mb-3">
    <span class="text-sm text-base-content/70">Ações em massa:</span>
    <button type="button" wire:click="bulkActivate" class="btn btn-success btn-xs">
        <x-carbon-checkmark class="w-3 h-3" />
        Ativar selecionados
    </button>
    <button type="button" wire:click="bulkDeactivate" class="btn btn-warning btn-xs">
        <x-carbon-close class="w-3 h-3" />
        Desativar selecionados
    </button>
    <span class="text-xs text-base-content/50">
        Marque as caixas de seleção na tabela e clique em uma das ações.
    </span>
</div>
