<div class="container mx-auto">
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-3xl font-bold text-base-content flex items-center gap-3">
                Currículo do Especialista
            </h1>
        </x-heading>

        <div class="card card-compact bg-base-100 shadow-sm">
            <x-card-body>
                <div class="grid gap-6 md:grid-cols-[1fr_auto] items-start">
                    <div class="space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="avatar">
                                <div class="w-24 h-24 rounded-full">
                                    @if ($specialist->avatar)
                                        <img src="{{ asset('storage/' . $specialist->avatar) }}"
                                            alt="Foto do especialista" class="w-full h-full object-cover" />
                                    @else
                                        <div
                                            class="bg-info text-primary-content rounded-full w-full h-full flex items-center justify-center">
                                            <span
                                                class="text-3xl font-bold">{{ strtoupper(substr($specialist->name ?? 'E', 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-2">
                                <h2 class="text-2xl font-bold text-base-content">
                                    {{ $specialist->name ?? 'Nome não informado' }}</h2>
                                <p class="text-base-content/70">
                                    {{ $specialist->specialty?->name ?? 'Especialidade não informada' }}</p>
                                <div class="flex flex-wrap items-center gap-2 text-sm text-base-content/60">
                                    @if (!empty($specialist->year_started_acting))
                                        <span>Atua desde {{ $specialist->year_started_acting }}</span>
                                    @endif

                                    @if ($experienceYears)
                                        <span class="badge badge-soft badge-info">{{ $experienceYears }} anos de
                                            experiência</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($specialist->summary)
                            <div class="p-4 bg-base-200/30 rounded-lg space-y-2">
                                <h3 class="font-semibold text-base-content flex items-center gap-2">
                                    <x-carbon-information class="w-5 h-5 text-info" />
                                    Resumo Profissional
                                </h3>
                                <p class="text-sm text-base-content/80 whitespace-pre-line break-words break-all"
                                    style="word-break: break-word; overflow-wrap: anywhere;">{{ $specialist->summary }}
                                </p>
                            </div>
                        @endif

                        @if ($specialist->description)
                            <div class="p-4 bg-base-200/30 rounded-lg space-y-2">
                                <h3 class="font-semibold text-base-content flex items-center gap-2">
                                    <x-carbon-document class="w-5 h-5 text-info" />
                                    Sobre o Especialista
                                </h3>
                                <p class="text-sm text-base-content/80 whitespace-pre-line break-words break-all"
                                    style="word-break: break-word; overflow-wrap: anywhere;">
                                    {{ $specialist->description }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 bg-base-200/20 rounded-lg">
                                <div class="text-xs uppercase text-base-content/50">CRM/CRP</div>
                                <div class="text-base font-medium text-base-content">
                                    {{ $specialist->crp ?? 'Não informado' }}</div>
                            </div>
                            <div class="p-4 bg-base-200/20 rounded-lg">
                                <div class="text-xs uppercase text-base-content/50">Valor por sessão</div>
                                <div class="text-base font-medium text-base-content">
                                    @if ($specialist->appointment_value !== null && $specialist->appointment_value !== '')
                                        R$ {{ number_format($specialist->appointment_value, 2, ',', '.') }}
                                    @else
                                        Não informado
                                    @endif
                                </div>
                            </div>
                            <div class="p-4 bg-base-200/20 rounded-lg">
                                <div class="text-xs uppercase text-base-content/50">E-mail</div>
                                <div class="text-base font-medium text-base-content">
                                    {{ $specialist->email ?? 'Não informado' }}</div>
                            </div>
                            <div class="p-4 bg-base-200/20 rounded-lg">
                                <div class="text-xs uppercase text-base-content/50">Telefone</div>
                                <div class="text-base font-medium text-base-content">
                                    {{ $specialist->phone_number ?? 'Não informado' }}</div>
                            </div>
                        </div>

                        @if ($specialist->reasons->isNotEmpty())
                            <div class="space-y-2">
                                <h3 class="font-semibold text-base-content flex items-center gap-2">
                                    <x-carbon-tag class="w-5 h-5 text-info" />
                                    Áreas de Atuação
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($specialist->reasons as $reason)
                                        <span class="badge badge-soft badge-info badge-sm">{{ $reason->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3 w-full md:w-64">
                        <a href="{{ route('user.specialist.index') }}" wire:navigate
                            class="btn btn-soft btn-sm btn-info">
                            <x-carbon-arrow-left class="w-5 h-5" />
                            Voltar para Especialistas
                        </a>
                        <div class="p-4 bg-base-200/30 rounded-lg space-y-3">
                            <div>
                                <div class="text-xs uppercase text-base-content/50">Sessão</div>
                                <div class="text-lg font-semibold text-base-content">50 minutos</div>
                            </div>
                            <div>
                                <div class="text-xs uppercase text-base-content/50">Valor</div>
                                <div class="text-xl font-bold text-info">
                                    @if ($specialist->appointment_value !== null && $specialist->appointment_value !== '')
                                        R$ {{ number_format($specialist->appointment_value, 2, ',', '.') }}
                                    @else
                                        A combinar
                                    @endif
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </x-card-body>
        </div>
    </div>
</div>
