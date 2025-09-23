<div>
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
                                <x-text>Agendamento <span
                                        class="text-info font-bold">#{{ $appointment->id }}</span></x-text>
                                <x-title class="font-bold">
                                    {{ $appointment->status ? ucfirst($appointment->status) : '—' }}</x-title>
                            </div>
                            <div class="text-right">
                                <x-text>Criado em</x-text>
                                <div class="text-base font-medium">{{ $appointment->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Cliente</x-text>
                                <div class="text-base font-medium">{{ $appointment->user?->name ?? '—' }}</div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Especialista</x-text>
                                <div class="text-base font-medium">{{ $appointment->specialist?->name ?? '—' }}</div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Valor</x-text>
                                <div class="text-base font-medium">R$
                                    {{ number_format((float) $appointment->total_value, 2, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Data</x-text>
                                <div class="text-base font-medium">
                                    {{ $appointment->appointment_date ? \Illuminate\Support\Carbon::parse($appointment->appointment_date)->format('d/m/Y') : '—' }}
                                </div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Hora</x-text>
                                <div class="text-base font-medium">{{ $appointment->appointment_time ?? '—' }}</div>
                            </div>
                            <div class="p-4 bg-base-200/30 rounded-lg">
                                <x-text>Situação</x-text>
                                <div class="text-base font-medium">{{ ucfirst($appointment->status ?? '—') }}</div>
                            </div>
                        </div>

                        @if ($appointment->notes)
                            <div class="p-4 bg-base-200/20 rounded-lg border border-base-300">
                                <div class="font-semibold mb-2">Anotações</div>
                                <div class="text-sm">{{ $appointment->notes }}</div>
                            </div>
                        @endif

                        <div class="p-4 bg-base-200/20 rounded-lg border border-base-300">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-text>Pagamento</x-text>
                                    <div class="text-base font-medium">
                                        @if ($appointment->payment)
                                            <a wire:navigate class="link link-info"
                                                href="{{ route('master.payment.show', ['payment' => $appointment->payment->id]) }}">#{{ $appointment->payment->id }}</a>
                                        @else
                                            —
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <x-text>Criado em</x-text>
                                    <div class="text-base">{{ $appointment->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div>
                                    <x-text>Atualizado em</x-text>
                                    <div class="text-base">{{ $appointment->updated_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 w-full md:w-56">
                        <a wire:navigate href="{{ route('master.appointment.index') }}"
                            class="btn btn-soft btn-sm btn-info">
                            <x-carbon-arrow-left class="w-5 h-5" />
                            Voltar para a Lista
                        </a>
                    </div>
                </div>
            </x-card-body>
        </div>
    </div>
</div>
