<div>
    <div class="space-y-6">
        @if (session()->has('message'))
            <div role="alert" class="alert alert-success shadow-lg">
                <x-carbon-checkmark class="w-6 h-6" />
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        @endif

        <x-heading>
            <x-title class="flex items-center gap-3">
                <x-carbon-user class="w-8 text-info" />
                Detalhes do Funcionário
            </x-title>
            <x-subtitle>
                Informações completas sobre o funcionário
            </x-subtitle>
        </x-heading>

        <x-card class="card-compact">
            <x-card-body>
                <div class="grid gap-6 md:grid-cols-[1fr_auto] items-start">
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="avatar avatar-placeholder">
                                <div class="bg-neutral text-neutral-content w-12 rounded-full">
                                    <span class="text-lg">
                                        {{ substr($employee->name, 0, 2) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <x-text>Funcionário <span
                                        class="text-info font-bold">#{{ $employee->id }}</span></x-text>
                                <x-title>{{ $employee->name }}</x-title>

                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                            <div>
                                <div class="text-base font-medium text-base-content">
                                    {{ $employee->created_at->format('d/m/Y') }}</div>
                                <div class="mt-0.5 text-xs text-base-content/60">Cadastrado em</div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-base-content flex items-center gap-2 mb-6">
                                <x-carbon-identification class="w-5 h-5 text-info" />
                                Informações Pessoais
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-email class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">E-mail</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $employee->email }}</p>
                                    </div>

                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-phone class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Telefone</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $employee->phone_number }}</p>
                                    </div>

                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-identification class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">CPF</span>
                                        </div>
                                        <p class="text-base-content ml-8 font-mono">{{ $employee->cpf }}</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-calendar class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Data de Nascimento</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $employee->birth_date }}</p>
                                    </div>

                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-user class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Status na Empresa</span>
                                        </div>
                                        <p class="text-base-content ml-8">
                                            <x-badge
                                                class="{{ $companyUser->is_active ? 'badge-success' : 'badge-error' }}">
                                                {{ $companyUser->is_active ? 'Funcionário Ativo' : 'Funcionário Inativo' }}
                                            </x-badge>
                                        </p>
                                    </div>

                                    @if ($companyUser->companyPlan)
                                        @if ($companyUser->companyPlan->is_active)
                                            <div class="p-4 bg-success/10 rounded-lg border border-success/20">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <x-carbon-layers class="w-5 h-5 text-success shrink-0" />
                                                    <span class="font-semibold text-base-content">Plano Atual</span>
                                                    <x-badge class="badge-success ml-auto">ATIVO</x-badge>
                                                </div>
                                                <div class="ml-8">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <x-badge
                                                            class="badge-info">{{ $companyUser->companyPlan->name }}</x-badge>
                                                        <span class="text-sm text-success font-semibold">
                                                            {{ $companyUser->companyPlan->discount_percentage }}%
                                                            desconto
                                                        </span>
                                                    </div>
                                                    <x-text>
                                                        A empresa cobre
                                                        {{ $companyUser->companyPlan->discount_percentage }}% dos
                                                        custos dos serviços
                                                    </x-text>
                                                </div>
                                            </div>
                                        @else
                                            <div class="p-4 bg-warning/10 rounded-lg border border-warning/20">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <x-carbon-layers class="w-5 h-5 text-warning shrink-0" />
                                                    <span class="font-semibold text-base-content">Plano Atual</span>
                                                    <x-badge class="badge-warning ml-auto">INATIVO</x-badge>
                                                </div>
                                                <div class="ml-8">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <x-badge
                                                            class="badge-ghost">{{ $companyUser->companyPlan->name }}</x-badge>
                                                        <span class="text-sm text-warning font-semibold line-through">
                                                            {{ $companyUser->companyPlan->discount_percentage }}%
                                                            desconto
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-warning">
                                                        ⚠️ Plano inativo - desconto não está sendo aplicado
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="p-4 bg-base-200/30 rounded-lg">
                                            <div class="flex items-center gap-3 mb-2">
                                                <x-carbon-layers class="w-5 h-5 text-base-content/50 shrink-0" />
                                                <span class="font-semibold text-base-content">Plano Atual</span>
                                            </div>
                                            <div class="ml-8">
                                                <x-badge class="badge-ghost">Sem plano atribuído</x-badge>
                                                <x-text class="mt-1">
                                                    Este funcionário não possui um plano de desconto ativo
                                                </x-text>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($companyUser->companyPlan)
                                @if ($companyUser->companyPlan->is_active)
                                    <div class="mt-6 p-4 bg-success/10 rounded-lg border border-success/20">
                                        <div class="flex items-start gap-3">
                                            <x-carbon-checkmark class="w-5 h-5 text-success flex-shrink-0 mt-0.5" />
                                            <div class="text-sm">
                                                <div class="font-semibold text-base-content mb-1">Benefício Ativo:</div>
                                                <p class="text-base-content/80">
                                                    Este funcionário possui
                                                    <strong>{{ $companyUser->companyPlan->discount_percentage }}% de
                                                        desconto</strong>
                                                    nos serviços através do plano
                                                    <strong>{{ $companyUser->companyPlan->name }}</strong>.
                                                    A empresa arcará com esta porcentagem dos custos.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-6 p-4 bg-warning/10 rounded-lg border border-warning/20">
                                        <div class="flex items-start gap-3">
                                            <x-carbon-warning class="w-5 h-5 text-warning flex-shrink-0 mt-0.5" />
                                            <div class="text-sm">
                                                <div class="font-semibold text-base-content mb-1">Benefício Suspenso:
                                                </div>
                                                <p class="text-base-content/80">
                                                    O plano <strong>{{ $companyUser->companyPlan->name }}</strong> está
                                                    temporariamente inativo.
                                                    O desconto de
                                                    <strong>{{ $companyUser->companyPlan->discount_percentage }}%</strong>
                                                    não está sendo aplicado nos pagamentos até que o plano seja
                                                    reativado.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 w-full md:w-56">
                        <a wire:navigate href="{{ route('company.employee.index') }}"
                            class="btn btn-soft btn-sm btn-info">
                            <x-carbon-arrow-left class="w-5 h-5" />
                            Voltar para Funcionários
                        </a>
                    </div>
                </div>
            </x-card-body>
        </x-card>
    </div>
</div>
