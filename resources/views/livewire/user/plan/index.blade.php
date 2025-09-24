<div>
    <x-heading>
        <x-title>Planos</x-title>
        <x-subtitle>Escolha o plano que melhor se adapta às suas necessidades.</x-subtitle>
    </x-heading>

    @if ($hasCompanyPlan)
        @if ($companyPlan->companyPlan->is_active)
            <div class="alert alert-info mb-6">
                <x-carbon-information class="w-6 h-6" />
                <div>
                    <h3 class="font-bold">Plano Empresarial Ativo</h3>
                    <div class="text-sm mt-1">
                        <p>Você está vinculado ao plano <strong>{{ $companyPlan->companyPlan->name }}</strong> da
                            empresa
                            <strong>{{ $companyPlan->companyPlan->company->name }}</strong>.
                        </p>
                        <p class="mt-1">Com
                            <strong>{{ number_format($companyPlan->companyPlan->discount_percentage, 1) }}%
                                de desconto</strong>, você não pode contratar planos individuais.
                        </p>
                        <p class="mt-2">
                            <x-link class="!link-neutral" href="{{ route('user.subscribe.show') }}" wire:navigate>Ver detalhes do plano
                                empresarial →</x-link>
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning mb-6">
                <x-carbon-warning class="w-6 h-6" />
                <div>
                    <h3 class="font-bold">Plano Empresarial Inativo</h3>
                    <div class="text-sm mt-1">
                        <p>Você está vinculado ao plano <strong>{{ $companyPlan->companyPlan->name }}</strong> da
                            empresa
                            <strong>{{ $companyPlan->companyPlan->company->name }}</strong>, mas este plano está
                            <strong>temporariamente inativo</strong>.
                        </p>
                        <p class="mt-1">⚠️ <strong>Sem desconto disponível</strong> - O desconto de
                            {{ number_format($companyPlan->companyPlan->discount_percentage, 1) }}% não está sendo
                            aplicado até que o plano seja reativado.</p>
                        <p class="mt-1">Entre em contato com sua empresa para mais informações sobre a reativação do
                            plano.</p>
                        <p class="mt-2">
                            <x-link class="!link-neutral" href="{{ route('user.subscribe.show') }}" wire:navigate>Ver detalhes
                                →</x-link>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <div
        class="grid grid-cols-1 md:grid-cols-3 gap-3 {{ $hasCompanyPlan && $companyPlan->companyPlan->is_active ? 'opacity-50 pointer-events-none' : '' }}">
        @foreach ($this->plans as $plan)
            <x-card class="h-fit {{ $loop->iteration === 2 ? 'border-4 border-info/40' : '' }}">
                <x-card-body>

                    @if ($loop->iteration === 2)
                        <x-badge class="badge-sm badge-success">
                            <span class="font-medium uppercase text-[0.6rem]">Mais Popular</span>
                            <x-carbon-trophy class="w-4 h-4" />
                        </x-badge>
                    @endif

                    <div class="flex justify-between items-start">
                        <h2 class="text-3xl font-bold">{{ $plan->name }}</h2>

                        <div class="text-right">
                            @if ($plan->hasDiscount())
                                <x-badge class="badge-success badge-sm">
                                    -{{ $plan->discount_percentage_formatted }}%
                                </x-badge>
                                <span class="block text-xs text-base-content/50 line-through">
                                    <x-text>R$</x-text>
                                    {{ number_format($plan->price, 2, ',', '.') }}
                                </span>
                                <div class="flex items-center justify-end gap-2">
                                    <span class="text-xl font-bold text-success">
                                        <x-text>R$</x-text>
                                        {{ number_format($plan->price_with_discount, 2, ',', '.') }}
                                    </span>

                                </div>
                            @else
                                <span class="text-xl"><x-text>R$</x-text>
                                    {{ number_format($plan->price, 2, ',', '.') }}
                                </span>
                            @endif
                        </div>
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
                        @if ($hasCompanyPlan)
                            @if ($companyPlan->companyPlan->is_active)
                                <button class="btn btn-block btn-disabled" disabled>
                                    <x-carbon-locked class="w-4 h-4" />
                                    Plano Empresarial Ativo
                                </button>
                            @else
                                <button class="btn btn-block btn-disabled" disabled>
                                    <x-carbon-warning class="w-4 h-4" />
                                    Plano Empresarial Inativo
                                </button>
                            @endif
                        @else
                            <x-btn-link class="btn-block"
                                href="{{ route('user.plan.payment', ['plan_id' => $plan->id]) }}" wire:navigate>
                                Assinar
                                <x-carbon-checkmark-outline class="w-4 h-4" />
                            </x-btn-link>
                        @endif
                    </div>
                </x-card-body>
            </x-card>
        @endforeach
    </div>
</div>
