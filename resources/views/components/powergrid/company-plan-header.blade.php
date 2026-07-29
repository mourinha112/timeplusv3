<div class="flex justify-between items-center mb-4">
    <div>
        <h2 class="text-2xl font-bold text-base-content flex items-center gap-3">
            <x-carbon-layers class="w-8 text-info" />
            Planos da Empresa
        </h2>
        <x-text class="mt-1">
            Visualize os planos contratados pela sua empresa. Para criar ou alterar um plano, entre em contato com a
            equipe TimePlus.
        </x-text>
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
