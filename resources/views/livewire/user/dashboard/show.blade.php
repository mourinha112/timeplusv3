<div class="space-y-4">
    <x-subtitle>
        Dê o primeiro passo agora! 👋🏻
    </x-subtitle>

    <x-card>
        <x-card-body class="flex justify-between sm:items-center sm:flex-row">

            <div class="space-y-3">
                <x-card-title>Garanta sua sessão hoje</x-card-title>
                <x-text>Busque e encontre os especialistas ideais para você.</x-text>
            </div>

            <x-btn-link href="{{ route('user.specialist.index') }}" wire:navigate class="mt-2 sm:btn sm:mt-0">
                <x-carbon-search-advanced class="w-4 h-4" />
                Encontrar especialistas
            </x-btn-link>

        </x-card-body>
    </x-card>

    <x-card>
        <x-card-body class="flex justify-between sm:items-center sm:flex-row">

            <div class="space-y-3">
                <x-card-title>Eleve sua experiência</x-card-title>
                <x-text>Descubra o melhor plano para aprimorar sua experiência.</x-text>
            </div>

            <x-btn-link href="{{ route('user.plan.index') }}" wire:navigate class="mt-2 sm:btn sm:mt-0">
                <x-carbon-pricing-consumption class="w-4 h-4" />
                Conhecer os planos
            </x-btn-link>

        </x-card-body>
    </x-card>
</div>
