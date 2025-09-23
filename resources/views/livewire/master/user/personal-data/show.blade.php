<div>
    @if (session()->has('message'))
        <div role="alert" class="alert alert-success shadow-lg">
            <x-carbon-checkmark class="w-6 h-6" />
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <x-heading>
        <x-title>
            Detalhes do Usuário
        </x-title>
    </x-heading>

    <x-card>
        <x-card-body>
            <div class="grid gap-6 md:grid-cols-[1fr_auto] items-start">
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="avatar">
                            <div class="w-20 h-20 rounded-full">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar do usuário"
                                        class="w-full h-full object-cover" />
                                @else
                                    <div
                                        class="bg-neutral text-neutral-content rounded-full w-full h-full flex items-center justify-center">
                                        <span
                                            class="text-2xl font-bold">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            <p class="text-base-content/70">Usuário <span
                                    class="text-info font-bold">#{{ $user->id }}</span></p>
                            <h2 class="text-2xl font-bold text-base-content">{{ $user->name ?? 'Não informado' }}
                            </h2>
                            <p class="text-base-content/60">&nbsp;</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <x-text>{{ $user->appointments->count() }}</x-text>
                            <div class="mt-0.5 text-xs text-base-content/60">Sessões</div>
                        </div>
                        <div>
                            <x-text
                                class="font-bold">{{ $user->email_verified_at ? 'Verificado' : 'Não verificado' }}</x-text>
                            <div class="mt-0.5 text-xs text-base-content/60">E-mail</div>
                        </div>
                        <div>
                            <div class="text-base font-medium text-base-content">
                                {{ $user->created_at->format('d/m/Y') }}</div>
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
                                    <p class="text-base-content ml-8">{{ $user->email }}</p>
                                </div>

                                @if ($user->phone_number)
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-phone class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Telefone</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $user->phone_number }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-4">
                                @if ($user->cpf)
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-identification class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">CPF</span>
                                        </div>
                                        <p class="text-base-content ml-8 font-mono">{{ $user->cpf }}</p>
                                    </div>
                                @endif

                                @if ($user->birth_date)
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-center gap-3 mb-2">
                                            <x-carbon-calendar class="w-5 h-5 text-info shrink-0" />
                                            <span class="font-semibold text-base-content">Data de Nascimento</span>
                                        </div>
                                        <p class="text-base-content ml-8">{{ $user->birth_date }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($user->gateway_customer_id)
                            <div class="mt-6 p-4 bg-base-200/20 rounded-lg border border-base-300">
                                <h4 class="font-semibold text-base-content flex items-center gap-2 mb-3">
                                    <x-carbon-settings class="w-4 h-4 text-warning" />
                                    Informações do Sistema
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-base-content/70">ID Gateway:</span>
                                        <span class="font-mono ml-2">{{ $user->gateway_customer_id }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-base-content/70">Criado em:</span>
                                        <span class="ml-2">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-3 w-full md:w-56">
                    <a href="{{ route('master.user.index') }}" class="btn btn-soft btn-sm btn-info">
                        <x-carbon-arrow-left class="w-5 h-5" />
                        Voltar para a Lista
                    </a>
                </div>
            </div>
        </x-card-body>
    </x-card>
</div>
