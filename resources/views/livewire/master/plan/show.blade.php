<div class="container mx-auto">
    <div class="space-y-6">
        @if (session()->has('message'))
            <div role="alert" class="alert alert-success shadow-lg">
                <x-carbon-checkmark class="w-6 h-6" />
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        @endif

        <x-heading>
            <h1 class="text-3xl font-bold text-base-content flex items-center gap-3">
                Detalhes do Plano
            </h1>
        </x-heading>

        <div class="card card-compact bg-base-100 shadow-sm">
            <x-card-body>
                <div class="grid gap-6 md:grid-cols-[1fr_auto] items-start">
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="avatar avatar-placeholder">
                                <div
                                    class="bg-info text-primary-content w-20 h-20 rounded-full flex items-center justify-center">
                                    <x-carbon-plan class="w-8 h-8" />
                                </div>
                            </div>
                            <div>
                                <p class="text-base-content/70">Plano <span
                                        class="text-info font-bold">#{{ $plan->id }}</span></p>
                                <h2 class="text-2xl font-bold text-base-content">{{ $plan->name }}</h2>

                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">


                            <div>
                                <x-text class="text-2xl font-bold text-base-content">{{ $plan->duration_days }}</x-text>
                                <div class="mt-0.5 text-xs text-base-content/60">Dias de duração</div>
                            </div>
                            <div>
                                <div class="text-base font-medium text-base-content">
                                    {{ $plan->created_at?->format('d/m/Y') ?? 'Não informado' }}</div>
                                <div class="mt-0.5 text-xs text-base-content/60">Criado em</div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-base-content flex items-center gap-2 mb-6">
                                <x-carbon-information class="w-5 h-5 text-info" />
                                Informações do Plano
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-tag class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Nome do Plano</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $plan->name }}</p>
                                    </div>

                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-time class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Tipo de Plano</span>
                                        </div>
                                        <p class="text-base-content ml-8">
                                            @switch($plan->duration_days)
                                                @case(30)
                                                    Mensal
                                                @break

                                                @case(180)
                                                    Semestral
                                                @break

                                                @case(365)
                                                    Anual
                                                @break

                                                @default
                                                    {{ $plan->duration_days }} dias
                                            @endswitch
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-money class="w-5 h-5 text-success shrink-0" />
                                            <span class="font-semibold text-base-content">Preço</span>
                                        </div>
                                        <p class="ml-8 text-md font-semibold text-success">
                                            R$ {{ number_format($plan->price, 2, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-percentage class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Desconto</span>
                                        </div>
                                        <p class="ml-8 text-md font-semibold text-info">
                                            {{ $plan->discount_percentage }}%
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 w-full md:w-56">
                        <a href="{{ route('master.plan.edit', $plan) }}" class="btn btn-warning btn-sm">
                            <x-carbon-edit class="w-4 h-4" />
                            Editar Plano
                        </a>

                        <a href="{{ route('master.plan.index') }}" class="btn btn-soft btn-sm btn-info">
                            <x-carbon-arrow-left class="w-5 h-5" />
                            Voltar para a Lista
                        </a>
                    </div>
                </div>
            </x-card-body>
        </div>
    </div>
</div>
