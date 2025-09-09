<div class="container mx-auto">
    <div class="space-y-6">
        <x-heading>
            <h1 class="text-3xl font-bold text-base-content flex items-center gap-3">
                Detalhes do Agendamento
            </h1>
        </x-heading>

        <div class="card card-compact bg-base-100 shadow-sm">
            <x-card-body>
                <div class="grid gap-6 md:grid-cols-[1fr_auto] items-start">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-base-content/70">Agendamento <span class="text-info font-bold">#{{ $appointment->id }}</span></p>
                                <h2 class="text-2xl font-bold text-base-content">{{ $appointment->status ? ucfirst($appointment->status) : '—' }}</h2>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-base-content/70">Criado em</div>
                                <div class="text-base font-medium">{{ $appointment->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <div class="text-sm text-base-content/70">Cliente</div>
                                <div class="text-base font-medium">{{ $appointment->user?->name ?? '—' }}</div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <div class="text-sm text-base-content/70">Especialista</div>
                                <div class="text-base font-medium">{{ $appointment->specialist?->name ?? '—' }}</div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <div class="text-sm text-base-content/70">Valor</div>
                                <div class="text-base font-medium">R$ {{ number_format((float) $appointment->total_value, 2, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <div class="text-sm text-base-content/70">Data</div>
                                <div class="text-base font-medium">{{ $appointment->appointment_date ? \Illuminate\Support\Carbon::parse($appointment->appointment_date)->format('d/m/Y') : '—' }}</div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <div class="text-sm text-base-content/70">Hora</div>
                                <div class="text-base font-medium">{{ $appointment->appointment_time ?? '—' }}</div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <div class="text-sm text-base-content/70">Situação</div>
                                <div class="text-base font-medium">{{ ucfirst($appointment->status ?? '—') }}</div>
                            </div>
                        </div>

                        @if($appointment->notes)
                        <div class="p-4 bg-base-200/20 rounded-lg border border-base-300">
                            <div class="font-semibold mb-2">Anotações</div>
                            <div class="text-sm">{{ $appointment->notes }}</div>
                        </div>
                        @endif

                        <div class="p-4 bg-base-200/20 rounded-lg border border-base-300">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <div class="text-sm text-base-content/70">Pagamento</div>
                                    <div class="text-base font-medium">
                                        @if($appointment->payment)
                                            <a class="link link-info" href="{{ route('master.payment.show', ['payment' => $appointment->payment->id]) }}">#{{ $appointment->payment->id }}</a>
                                        @else
                                            —
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-base-content/70">Criado em</div>
                                    <div class="text-base">{{ $appointment->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div>
                                    <div class="text-sm text-base-content/70">Atualizado em</div>
                                    <div class="text-base">{{ $appointment->updated_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 w-full md:w-56">
                        <a href="{{ route('master.appointment.index') }}" class="btn btn-soft btn-sm btn-info">
                            <x-carbon-arrow-left class="w-5 h-5" />
                            Voltar para a Lista
                        </a>
                    </div>
                </div>
            </x-card-body>
        </div>
    </div>
</div>

