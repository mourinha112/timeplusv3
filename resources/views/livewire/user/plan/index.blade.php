<div>
    <x-heading>
        <x-title>Planos</x-title>
        <x-subtitle>Escolha o plano que melhor se adapta às suas necessidades.</x-subtitle>
    </x-heading>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        @foreach ($this->plans as $plan)
        <x-card class="h-fit {{ $loop->iteration === 2 ? 'border-4 border-info/40' : '' }}">
            <x-card-body>

                @if ($loop->iteration === 2)
                <x-badge class="badge-sm badge-success">
                    <span class="font-medium uppercase text-[0.6rem]">Mais Popular</span>
                    <x-carbon-trophy class="w-4 h-4" />
                </x-badge>
                @endif

                <div class="flex justify-between">
                    <h2 class="text-3xl font-bold">{{ $plan->name }}</h2>

                    <span class="text-xl"><span class="text-sm text-base-content/70">R$</span>
                        {{ number_format($plan->price, 2, ',', '.') }}
                    </span>
                </div>

                <ul class="mt-6 flex flex-col gap-6 text-xs">
                    <li class="flex gap-2">
                        <x-carbon-checkmark class="w-4 h-4 text-info" />
                        Acesso completo à biblioteca de conteúdos Timeplus
                    </li>
                    <li class="flex gap-2">
                        <x-carbon-checkmark class="w-6 h-6 text-info" />
                        Fácil acesso a toda comunidade de psicólogos, psicanalistas, terapeutas e coaches
                    </li>
                    <li class="flex gap-2">
                        <x-carbon-checkmark class="w-4 h-4 text-info" />
                        Atendimento prioritário pela nossa equipe de suporte
                    </li>
                </ul>

                <div class="mt-6">
                    <x-button class="btn-block" wire:click="subscribe({{ $plan->id }})">
                        Assinar
                        <x-carbon-checkmark-outline class="w-4 h-4" />
                    </x-button>
                </div>
            </x-card-body>
        </x-card>
        @endforeach
    </div>
</div>
