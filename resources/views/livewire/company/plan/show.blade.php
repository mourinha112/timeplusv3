<div class="container mx-auto">
    <div class="space-y-6">
        @if (session()->has('message'))
            <div role="alert" class="alert alert-success shadow-lg">
                <x-carbon-checkmark class="w-6 h-6" />
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div role="alert" class="alert alert-error shadow-lg">
                <x-carbon-warning class="w-6 h-6" />
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <x-heading>
            <x-title class="flex items-center gap-3">
                <x-carbon-layers class="w-8 text-info" />
                Detalhes do Plano
            </x-title>
            <x-subtitle class="text-base-content/70">
                Informações completas sobre o plano da empresa
            </x-subtitle>
        </x-heading>

        <x-card class="card-compact">
            <x-card-body>
                <div class="grid gap-6 md:grid-cols-[1fr_auto] items-start">
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="avatar avatar-placeholder">
                                <div class="bg-info text-primary-content w-12 rounded-full">
                                    <x-carbon-layers class="w-6" />
                                </div>
                            </div>
                            <div>
                                <x-text class="text-base-content/70">Plano <span
                                        class="text-info font-bold">#{{ $plan->id }}</span></x-text>
                                <x-title class="text-2xl">{{ $plan->name }}</x-title>

                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div>
                                <div class="text-base font-medium text-base-content">
                                    {{ $plan->created_at->format('d/m/Y') }}</div>
                                <div class="mt-0.5 text-xs text-base-content/60">Criado em</div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-base-content flex items-center gap-2 mb-6">
                                <x-carbon-information class="w-5 h-5 text-info" />
                                Informações do Plano
                            </h3>

                            <div class="grid grid-cols-1 gap-6">
                                <div class="p-4 bg-base-200/30 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <x-carbon-layers class="w-5 h-5 text-info shrink-0" />
                                        <span class="font-semibold text-base-content">Nome do Plano</span>
                                    </div>
                                    <p class="text-base-content ml-8">{{ $plan->name }}</p>
                                </div>

                                <div class="p-4 bg-base-200/30 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <x-carbon-percentage class="w-5 h-5 text-success shrink-0" />
                                        <span class="font-semibold text-base-content">Desconto Oferecido</span>
                                    </div>
                                    <p class="text-base-content ml-8">
                                        <span
                                            class="text-2xl font-bold text-success">{{ $plan->discount_percentage }}%</span>
                                        <span class="text-sm text-base-content/70 ml-2">do valor total será pago pela
                                            empresa</span>
                                    </p>
                                </div>

                                <div class="p-4 bg-base-200/30 rounded-lg">
                                    <div class="flex items-center gap-3 mb-2">
                                        <x-carbon-user class="w-5 h-5 text-info shrink-0" />
                                        <span class="font-semibold text-base-content">Status</span>
                                    </div>
                                    <p class="text-base-content ml-8">
                                        <x-badge class="{{ $plan->is_active ? 'badge-success' : 'badge-error' }}">
                                            {{ $plan->is_active ? 'Plano Ativo' : 'Plano Inativo' }}
                                        </x-badge>
                                    </p>
                                </div>
                            </div>

                            @if ($plan->companyUsers->count() > 0)
                                <div class="mt-6">
                                    <h4 class="font-semibold text-base-content flex items-center gap-2 mb-4">
                                        <x-carbon-user-multiple class="w-5 h-5 text-info" />
                                        Funcionários com este Plano ({{ $plan->companyUsers->count() }})
                                    </h4>

                                    <div class="overflow-x-auto">
                                        <table class="table table-zebra w-full">
                                            <thead>
                                                <tr>
                                                    <th>Funcionário</th>
                                                    <th>E-mail</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($plan->companyUsers as $companyUser)
                                                    <tr>
                                                        <td>
                                                            <div class="flex items-center gap-3">
                                                                <div class="avatar avatar-placeholder">
                                                                    <div
                                                                        class="bg-neutral text-neutral-content w-8 rounded-full">
                                                                        <span class="text-xs">
                                                                            {{ substr($companyUser->user->name, 0, 2) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <div class="font-bold">
                                                                        {{ $companyUser->user->name }}</div>
                                                                    <div class="text-sm opacity-50">
                                                                        {{ $companyUser->user->cpf }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $companyUser->user->email }}</td>
                                                        <td>
                                                            @if ($companyUser->is_active)
                                                                <x-badge class="badge-success">Ativo</x-badge>
                                                            @else
                                                                <x-badge class="badge-error">Inativo</x-badge>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="mt-6 p-4 bg-base-200/20 rounded-lg border border-base-300">
                                    <div class="text-center">
                                        <x-carbon-user-multiple class="w-12 h-12 mx-auto text-base-content/30 mb-2" />
                                        <h4 class="font-semibold text-base-content mb-1">Nenhum funcionário cadastrado
                                        </h4>
                                        <p class="text-sm text-base-content/60">
                                            Este plano ainda não foi aplicado a nenhum funcionário.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-6 p-4 bg-info/10 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <x-carbon-information class="w-5 h-5 text-info flex-shrink-0 mt-0.5" />
                                    <div class="text-sm">
                                        <div class="font-semibold text-base-content mb-1">Como funciona:</div>
                                        <p class="text-base-content/80">
                                            Quando um funcionário com este plano utilizar os serviços,
                                            a empresa pagará <strong>{{ $plan->discount_percentage }}%</strong> do
                                            valor
                                            e o funcionário pagará os
                                            <strong>{{ 100 - $plan->discount_percentage }}%</strong> restantes.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 w-full md:w-56">
                        <a href="{{ route('company.plan.index') }}" class="btn btn-soft btn-sm btn-info">
                            <x-carbon-arrow-left class="w-5 h-5" />
                            Voltar para Planos
                        </a>
                    </div>
                </div>
            </x-card-body>
        </x-card>
    </div>
</div>
