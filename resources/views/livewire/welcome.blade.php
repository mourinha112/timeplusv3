<div class="space-y-4">
    <x-card>
        <x-card-body class="flex flex-row justify-between items-center">
            <div class="space-y-2">
                <x-card-title>Cliente</x-card-title>
                <x-text>Acessar plataforma do cliente.</x-text>
            </div>
            <x-btn-link href="{{ route('user.auth.login') }}" wire:navigate>
                Acessar
                <x-carbon-arrow-right class="w-4 h-4" />
            </x-btn-link>
        </x-card-body>
    </x-card>

    <x-card>
        <x-card-body class="flex flex-row justify-between items-center">
            <div class="space-y-2">
                <x-card-title>Especialista</x-card-title>
                <x-text>Acessar plataforma do especialista.</x-text>
            </div>
            <x-btn-link href="{{ route('specialist.auth.login') }}" wire:navigate>
                Acessar
                <x-carbon-arrow-right class="w-4 h-4" />
            </x-btn-link>
        </x-card-body>
    </x-card>

    <x-card>
        <x-card-body class="flex flex-row justify-between items-center">
            <div class="space-y-2">
                <x-card-title>Empresa</x-card-title>
                <x-text>Acessar plataforma da empresa.</x-text>
            </div>
            <x-btn-link href="{{ route('company.auth.login') }}" wire:navigate>
                Acessar
                <x-carbon-arrow-right class="w-4 h-4" />
            </x-btn-link>
        </x-card-body>
    </x-card>
</div>
