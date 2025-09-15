<div class="flex justify-between items-center mb-4">
    <div>
        <h2 class="text-2xl font-bold text-base-content flex items-center gap-3">
            <x-carbon-layers class="w-8 text-info" />
            Planos da Empresa
        </h2>
        <p class="text-base-content/70 mt-1">Gerencie os planos oferecidos pela sua empresa</p>
    </div>

    <div class="flex gap-2">
        <a href="{{ route('company.plan.create') }}" class="btn btn-info">
            <x-carbon-add class="w-4 h-4" />
            Novo Plano
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
