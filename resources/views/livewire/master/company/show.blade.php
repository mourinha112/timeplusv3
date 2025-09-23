<div>
    <div class="space-y-6">
        @if (session()->has('message'))
            <div role="alert" class="alert alert-success shadow-lg">
                <x-carbon-checkmark class="w-6 h-6" />
                <span class="font-medium">{{ session('message') }}</span>
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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-text>{{ $company->payments->count() }}</x-text>
                                <div class="mt-0.5 text-xs text-base-content/60">Pagamentos</div>
                            </div>
                            <div>
                                <x-text
                                    class="font-bold">{{ $company->companyPlans->count() > 0 ? 'Tem Planos' : 'Sem Planos' }}</x-text>
                                <div class="mt-0.5 text-xs text-base-content/60">Planos da Empresa</div>
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

                            @if ($company->companyPlans->count() > 0)
                                <div class="mt-6 p-4 bg-base-200/20 rounded-lg border border-base-300">
                                    <h4 class="font-semibold text-base-content flex items-center gap-2 mb-3">
                                        <x-carbon-plan class="w-4 h-4 text-success" />
                                        Informações do Plano
                                    </h4>
                                    <div class="text-sm">
                                        <div>
                                            <span class="font-medium text-base-content/70">Status:</span>
                                            <span class="ml-2 text-success">Empresa possui planos configurados</span>
                                        </div>
                                        <div class="mt-2 text-base-content/60">
                                            A empresa gerencia seus próprios planos e preços.
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 w-full md:w-56">
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
