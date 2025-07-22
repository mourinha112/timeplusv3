<div>
    <div class="space-y-3 mb-8">
        <x-title>Planos</x-title>
        <x-subtitle>Escolha o plano que melhor se adapta às suas necessidades.</x-subtitle>
    </div>

    @if(session()->has('success'))
    <div role="alert" class="alert alert-success">
        <x-carbon-checkmark class="w-6 h-6" />
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session()->has('error'))
    <div role="alert" class="alert alert-error">
        <x-carbon-warning class="w-6 h-6" />
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-4">
        @foreach($this->plans as $plan)
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                {{-- <span class="badge badge-xs badge-success">Most Popular</span> --}}
                <div class="flex justify-between">
                    <h2 class="text-3xl font-bold">{{ $plan->name }}</h2>
                    <span class="text-xl"><span class="text-sm text-base-content/70">R$</span> {{ number_format($plan->price, 2, ',', '.') }}</span>
                </div>
                <ul class="mt-6 flex flex-col gap-6 text-xs">
                    <li class="flex gap-2">
                        <x-carbon-checkmark class="w-4 h-4 text-success" />
                        Acesso completo à biblioteca de conteúdos Timeplus
                    </li>
                    <li class="flex gap-2">
                        <x-carbon-checkmark class="w-4 h-4 text-success" />
                        Fácil acesso a toda comunidade de psicólogos, psicanalistas, terapeutas e coaches
                    </li>
                    <li class="flex gap-2">
                        <x-carbon-checkmark class="w-4 h-4 text-success" />
                        Atendimento prioritário pela nossa equipe de suporte
                    </li>
                </ul>
                <div class="mt-6">
                    <button class="btn btn-info btn-block" wire:click="subscribe({{ $plan->id }})">Assinar</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
