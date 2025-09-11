<div class="flex justify-between items-center mb-4">
    <div class="flex items-center gap-2">
        <x-subtitle>Gerenciar Empresas</x-subtitle>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('master.company.create') }}" class="btn btn-info btn-sm">
            <x-carbon-add class="w-4 h-4" />
            Nova Empresa
        </a>
        <x-button class="btn-soft btn-sm" onclick="window.location.reload()">
            <x-carbon-renew class="w-4 h-4" />
            Atualizar
        </x-button>
    </div>
</div>
