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
                Detalhes do Especialista
            </h1>
        </x-heading>

        <div class="card card-compact bg-base-100 shadow-sm">
            <x-card-body>
                <div class="grid gap-6 md:grid-cols-[1fr_auto] items-start">
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="avatar">
                                <div class="w-20 h-20 rounded-full">
                                    @if ($specialist->avatar)
                                        <img src="{{ asset('storage/' . $specialist->avatar) }}"
                                            alt="Avatar do especialista" class="w-full h-full object-cover" />
                                    @else
                                        <div
                                            class="bg-neutral text-neutral-content rounded-full w-full h-full flex items-center justify-center">
                                            <span
                                                class="text-2xl font-bold">{{ strtoupper(substr($specialist->name ?? 'E', 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <x-text>Especialista <span
                                        class="text-info font-bold">#{{ $specialist->id }}</span></x-text>
                                <h2 class="text-2xl font-bold text-base-content">
                                    {{ $specialist->name ?? 'Não informado' }}</h2>
                                <x-text>{{ $specialist->specialty->name }}</x-text>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <x-text>{{ $specialist->appointments->count() }}</x-text>
                                <div class="mt-0.5 text-xs text-base-content/60">Sessões</div>
                            </div>

                            <div>
                                <x-text
                                    class="font-bold">{{ $specialist->email_verified_at ? 'Verificado' : 'Não verificado' }}</x-text>
                                <div class="mt-0.5 text-xs text-base-content/60">E-mail</div>
                            </div>
                            <div>
                                <div class="text-base font-medium text-base-content">
                                    {{ $specialist->created_at->format('d/m/Y') }}</div>
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
                                        <p class="text-base-content ml-8">{{ $specialist->email }}</p>
                                    </div>

                                    @if ($specialist->phone_number)
                                        <div class="p-4 bg-base-200/30 rounded-lg">
                                            <div class="flex items-center gap-3 mb-2">
                                                <x-carbon-phone class="w-5 h-5 text-info shrink-0" />
                                                <span class="font-semibold text-base-content">Telefone</span>
                                            </div>
                                            <p class="text-base-content ml-8">{{ $specialist->phone_number }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-4">
                                    @if ($specialist->crp)
                                        <div class="p-4 bg-base-200/30 rounded-lg">
                                            <div class="flex items-center gap-3 mb-2">
                                                <x-carbon-identification class="w-5 h-5 text-info shrink-0" />
                                                <span class="font-semibold text-base-content">CRP</span>
                                            </div>
                                            <p class="text-base-content ml-8 font-mono">{{ $specialist->crp }}</p>
                                        </div>
                                    @endif

                                    @if ($specialist->birth_date)
                                        <div class="p-4 bg-base-200/30 rounded-lg">
                                            <div class="flex items-center gap-3 mb-2">
                                                <x-carbon-calendar class="w-5 h-5 text-info shrink-0" />
                                                <span class="font-semibold text-base-content">Data de Nascimento</span>
                                            </div>
                                            <p class="text-base-content ml-8">{{ $specialist->birth_date }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 w-full md:w-56">
                        <a href="{{ route('master.specialist.index') }}" class="btn btn-soft btn-sm btn-info">
                            <x-carbon-arrow-left class="w-5 h-5" />
                            Voltar para a Lista
                        </a>
                    </div>
                </div>
            </x-card-body>
        </div>
    </div>
</div>
