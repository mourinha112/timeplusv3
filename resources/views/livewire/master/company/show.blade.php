<div>
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
            <h1 class="text-3xl font-bold text-base-content flex items-center gap-3">
                Detalhes da Empresa
            </h1>
        </x-heading>

        <x-card class="card-compact">
            <x-card-body>
                <div class="grid gap-6 md:grid-cols-[1fr_auto] items-start">
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="avatar avatar-placeholder">
                                <div class="bg-neutral text-neutral-content w-12 rounded-full">
                                    <x-carbon-enterprise class="w-5" />
                                </div>
                            </div>
                            <div>
                                <x-text>Empresa <span class="text-info font-bold">#{{ $company->id }}</span></x-text>
                                <x-title>{{ $company->name ?? 'Não informado' }}</x-title>
                                <div class="text-base-content/60">
                                    <x-badge class="{{ $company->is_active ? 'badge-success' : 'badge-error' }}">
                                        {{ $company->is_active ? 'Ativa' : 'Inativa' }}
                                    </x-badge>
                                </div>
                            </div>
                        </div>

                        @php
                            $totalEmployees = $company->employees->count();
                            $activeEmployees = $company->employees->filter(fn($e) => $e->pivot->is_active)->count();
                            $inactiveEmployees = $totalEmployees - $activeEmployees;
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                            <div>
                                <x-text class="font-bold text-success">{{ $activeEmployees }}</x-text>
                                <div class="mt-0.5 text-xs text-base-content/60">Usuários Ativos</div>
                            </div>
                            <div>
                                <x-text class="font-bold text-error">{{ $inactiveEmployees }}</x-text>
                                <div class="mt-0.5 text-xs text-base-content/60">Usuários Inativos</div>
                            </div>
                            <div>
                                <x-text>{{ $company->payments->count() }}</x-text>
                                <div class="mt-0.5 text-xs text-base-content/60">Pagamentos</div>
                            </div>
                            <div>
                                <x-text class="font-bold">{{ $company->companyPlans->count() }}</x-text>
                                <div class="mt-0.5 text-xs text-base-content/60">Planos Configurados</div>
                            </div>
                            <div>
                                <div class="text-base font-medium text-base-content">
                                    {{ $company->created_at->format('d/m/Y') }}</div>
                                <div class="mt-0.5 text-xs text-base-content/60">Cadastrada em</div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-base-content flex items-center gap-2 mb-6">
                                <x-carbon-identification class="w-5 h-5 text-info" />
                                Informações da Empresa
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-email class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">E-mail</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $company->email }}</p>
                                    </div>

                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-phone class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Telefone</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $company->phone }}</p>
                                    </div>

                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-identification class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">CNPJ</span>
                                        </div>
                                        <p class="text-base-content ml-8 font-mono">{{ $company->cnpj }}</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-location class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Endereço</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $company->address }}</p>
                                    </div>

                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-location class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Cidade/Estado</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $company->city }}, {{ $company->state }}
                                        </p>
                                    </div>

                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-location class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">CEP</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $company->zip_code }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h3 class="text-lg font-semibold text-base-content flex items-center gap-2 mb-6">
                                    <x-carbon-user class="w-5 h-5 text-info" />
                                    Responsável / Contato
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-user class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Nome</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $company->contact_name ?? 'Não informado' }}</p>
                                    </div>

                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-identification class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Cargo</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $company->contact_role ?? 'Não informado' }}</p>
                                    </div>

                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-phone class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Telefone</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $company->contact_phone ?? 'Não informado' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-base-200/20 rounded-lg border border-base-300">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold text-base-content flex items-center gap-2">
                                        <x-carbon-plan class="w-4 h-4 text-success" />
                                        Planos Contratados
                                    </h4>
                                    <a wire:navigate
                                        href="{{ route('master.company-plan.create', ['company' => $company->id]) }}"
                                        class="btn btn-info btn-sm">
                                        <x-carbon-add class="w-4 h-4" />
                                        Novo Plano
                                    </a>
                                </div>

                                @if ($company->companyPlans->count() > 0)
                                    <div class="space-y-3">
                                        @foreach ($company->companyPlans as $plan)
                                            <div class="flex items-center justify-between gap-3 p-3 rounded-lg border {{ $plan->is_active ? 'border-success/30 bg-success/5' : 'border-error/30 bg-error/5' }}">
                                                <div>
                                                    <span class="font-semibold">{{ $plan->name }}</span>
                                                    <span class="text-sm text-base-content/60 ml-2">
                                                        {{ $plan->billing_model === 'credit_pack' ? 'Pacote de créditos' : 'Por funcionário' }}
                                                    </span>
                                                    <span class="badge {{ $plan->is_active ? 'badge-success' : 'badge-error' }} badge-sm ml-2">
                                                        {{ $plan->is_active ? 'Ativo' : 'Inativo' }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <a wire:navigate
                                                        href="{{ route('master.company-plan.edit', ['plan' => $plan->id]) }}"
                                                        class="btn btn-info btn-xs">
                                                        Editar
                                                    </a>
                                                    <button type="button"
                                                        wire:click="togglePlanStatus({{ $plan->id }})"
                                                        class="btn btn-xs {{ $plan->is_active ? 'btn-warning' : 'btn-success' }}">
                                                        {{ $plan->is_active ? 'Desativar' : 'Ativar' }}
                                                    </button>
                                                    <button type="button"
                                                        wire:click="deletePlan({{ $plan->id }})"
                                                        wire:confirm="Tem certeza que deseja excluir este plano? Esta ação não pode ser desfeita."
                                                        class="btn btn-error btn-xs">
                                                        Excluir
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-4 bg-warning/10 rounded-lg border border-warning/30">
                                        <div class="flex items-center gap-2 text-warning">
                                            <x-carbon-warning class="w-4 h-4" />
                                            <span class="font-semibold text-sm">Nenhum plano configurado</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 w-full md:w-56">
                        <a wire:navigate href="{{ route('master.company.edit', ['company' => $company->id]) }}"
                            class="btn btn-soft btn-sm btn-warning">
                            <x-carbon-edit class="w-5 h-5" />
                            Editar Empresa
                        </a>
                        <a wire:navigate href="{{ route('master.company.index') }}"
                            class="btn btn-soft btn-sm btn-info">
                            <x-carbon-arrow-left class="w-5 h-5" />
                            Voltar para a Lista
                        </a>
                    </div>
                </div>
            </x-card-body>
        </x-card>
    </div>
</div>
