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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-stat title="Sessões" :value="$user->appointments->count()" />
                        <x-stat title="E-mail" :value="$user->email_verified_at ? 'Verificado' : 'Não verificado'" />
                        <x-stat title="Cadastrado em" :value="$user->created_at->format('d/m/Y')" />
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-base-content flex items-center gap-2 mb-6">
                            <x-carbon-identification class="w-5 h-5 text-info" />
                            Informações Pessoais
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="p-4 bg-base-200/30 rounded-lg">
                                    <div class="flex items-start gap-3">
                                        <x-carbon-email class="w-5 h-5 text-info shrink-0" />
                                        <x-info-item label="E-mail" :value="$user->email" />
                                    </div>
                                </div>

                                @if ($user->phone_number)
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-start gap-3">
                                            <x-carbon-phone class="w-5 h-5 text-info shrink-0" />
                                            <x-info-item label="Telefone" :value="$user->phone_number" />
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-4">
                                @if ($user->cpf)
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-start gap-3">
                                            <x-carbon-identification class="w-5 h-5 text-info shrink-0" />
                                            <x-info-item label="CPF" :value="$user->cpf" :mono="true" />
                                        </div>
                                    </div>
                                @endif

                                @if ($user->birth_date)
                                    <div class="p-4 bg-base-200/30 rounded-lg">
                                        <div class="flex items-start gap-3">
                                            <x-carbon-calendar class="w-5 h-5 text-info shrink-0" />
                                            <x-info-item label="Data de Nascimento" :value="$user->birth_date" />
                                        </div>
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-info-item label="ID Gateway" :value="$user->gateway_customer_id" :mono="true" />
                                    <x-info-item label="Criado em" :value="$user->created_at->format('d/m/Y H:i')" />
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-3 w-full md:w-56">
                    <x-btn-link wire:navigate href="{{ route('master.user.index') }}" class="btn-soft btn-sm">
                        <x-carbon-arrow-left class="w-5 h-5" />
                        Voltar para a Lista
                    </x-btn-link>
                </div>
            </div>
        </x-card-body>
    </x-card>
</div>
