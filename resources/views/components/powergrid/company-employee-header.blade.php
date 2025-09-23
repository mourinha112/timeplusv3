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
