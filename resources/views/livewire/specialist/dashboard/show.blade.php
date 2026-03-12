<div class="space-y-4">
    <x-heading>
        <x-title>Dashboard</x-title>
        <x-subtitle>Bem-vinda de volta, {{ Auth::guard('specialist')->user()->name }}!</x-subtitle>
    </x-heading>

    {{-- Estatísticas rápidas --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-card>
            <x-card-body class="text-center">
                <div class="text-3xl font-bold text-info">{{ $this->stats['today_total'] }}</div>
                <x-text>Sessões hoje</x-text>
            </x-card-body>
        </x-card>
        <x-card>
            <x-card-body class="text-center">
                <div class="text-3xl font-bold text-success">{{ $this->stats['month_completed'] }}</div>
                <x-text>Concluídas no mês</x-text>
            </x-card-body>
        </x-card>
        <x-card>
            <x-card-body class="text-center">
                <div class="text-3xl font-bold text-base-content">{{ $this->stats['month_total'] }}</div>
                <x-text>Total no mês</x-text>
            </x-card-body>
        </x-card>
    </div>

    {{-- Próxima sessão --}}
    @if ($this->nextAppointment)
        <x-card class="{{ $this->isPaid($this->nextAppointment) ? 'border-info/30 bg-info/5' : 'border-warning/30 bg-warning/5' }}">
            <x-card-body>
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <x-carbon-calendar-heat-map class="w-6 h-6 {{ $this->isPaid($this->nextAppointment) ? 'text-info' : 'text-warning' }}" />
                    <x-card-title>Próxima sessão</x-card-title>
                    @if (!$this->isPaid($this->nextAppointment))
                        <span class="badge badge-warning badge-xs sm:badge-sm">Aguardando pagamento</span>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-1">
                        <div class="text-lg font-semibold">
                            {{ \Carbon\Carbon::parse($this->nextAppointment->appointment_date)->format('d/m/Y') }}
                            - {{ \Carbon\Carbon::parse($this->nextAppointment->appointment_time)->format('H\h') }}
                        </div>
                        <div class="text-base-content/70">
                            <x-carbon-user class="w-4 h-4 inline" />
                            {{ $this->nextAppointment->user->name }}
                        </div>
                    </div>

                    <div>
                        @if ($this->hasRoom($this->nextAppointment))
                            <a href="{{ route('specialist.videocall.show', $this->nextAppointment->room->code) }}"
                                wire:navigate class="btn btn-info btn-sm">
                                <x-carbon-video class="w-4 h-4" />
                                Iniciar atendimento
                            </a>
                        @elseif ($this->hasScheduledRoom($this->nextAppointment))
                            <span class="badge badge-warning badge-sm py-3">
                                <x-carbon-time class="w-3 h-3 mr-1" />
                                <span x-data="countdown('{{ $this->getRoomOpenTime($this->nextAppointment)?->toISOString() }}')" x-text="timeLeft">
                                    Carregando...
                                </span>
                            </span>
                        @elseif ($this->isPaid($this->nextAppointment))
                            <span class="badge badge-info badge-sm py-3">
                                <x-carbon-time class="w-3 h-3 mr-1" />
                                Aguardando sala
                            </span>
                        @else
                            <span class="badge badge-warning badge-sm py-3">
                                <x-carbon-currency-dollar class="w-3 h-3 mr-1" />
                                Paciente não pagou
                            </span>
                        @endif
                    </div>
                </div>
            </x-card-body>
        </x-card>
    @endif

    {{-- Sessões de hoje --}}
    <x-card>
        <x-card-body>
            <x-card-title>Sessões de hoje</x-card-title>
            <x-text>{{ \Carbon\Carbon::now()->translatedFormat('l, d \\d\\e F') }}</x-text>

            @if ($this->todayAppointments->isNotEmpty())
                <div class="mt-3 space-y-2">
                    @foreach ($this->todayAppointments as $appointment)
                        <div class="flex items-center justify-between p-3 rounded-lg border
                            {{ $appointment->status === 'completed' ? 'bg-success/5 border-success/20' : ($this->isPaid($appointment) ? 'bg-info/5 border-info/20' : 'bg-warning/5 border-warning/20') }}">
                            <div class="flex items-center gap-3">
                                <div class="text-lg font-semibold w-14 text-center">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                </div>
                                <div>
                                    <div class="font-medium">{{ $appointment->user->name }}</div>
                                    <div class="text-xs text-base-content/60">
                                        @if ($appointment->status === 'completed')
                                            <span class="text-success">Concluída</span>
                                        @elseif ($this->isPaid($appointment))
                                            <span class="text-info">Confirmada</span>
                                        @else
                                            <span class="text-warning">Aguardando pagamento</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div>
                                @if ($appointment->status === 'completed')
                                    <span class="badge badge-success badge-sm">
                                        <x-carbon-checkmark-filled class="w-3 h-3 mr-1" />
                                        Concluída
                                    </span>
                                @elseif ($this->hasRoom($appointment))
                                    <a href="{{ route('specialist.videocall.show', $appointment->room->code) }}"
                                        wire:navigate class="btn btn-info btn-sm">
                                        <x-carbon-video class="w-4 h-4" />
                                        Atender
                                    </a>
                                @elseif ($this->hasScheduledRoom($appointment))
                                    <span class="badge badge-warning badge-sm py-3">
                                        <x-carbon-time class="w-3 h-3 mr-1" />
                                        <span x-data="countdown('{{ $this->getRoomOpenTime($appointment)?->toISOString() }}')" x-text="timeLeft">
                                            Carregando...
                                        </span>
                                    </span>
                                @elseif ($this->isPaid($appointment))
                                    <span class="badge badge-info badge-sm">
                                        <x-carbon-time class="w-3 h-3 mr-1" />
                                        Em breve
                                    </span>
                                @else
                                    <span class="badge badge-warning badge-sm">Não pago</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-3 text-center py-6 text-base-content/50">
                    <x-carbon-calendar class="w-8 h-8 mx-auto mb-2 opacity-50" />
                    <p>Nenhuma sessão agendada para hoje.</p>
                </div>
            @endif
        </x-card-body>
    </x-card>

    {{-- Histórico recente --}}
    @if ($this->recentAppointments->isNotEmpty())
        <x-card>
            <x-card-body>
                <div class="flex items-center justify-between mb-3">
                    <x-card-title>Sessões anteriores</x-card-title>
                    <a href="{{ route('specialist.appointment.index') }}" wire:navigate class="btn btn-ghost btn-sm">
                        Ver todas
                        <x-carbon-arrow-right class="w-4 h-4" />
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Paciente</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->recentAppointments as $appointment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</td>
                                    <td>{{ $appointment->user->name }}</td>
                                    <td>
                                        @if ($appointment->status === 'completed')
                                            <span class="badge badge-success badge-sm">Concluída</span>
                                        @else
                                            <span class="badge badge-info badge-sm">Agendada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card-body>
        </x-card>
    @endif
</div>

<script>
    function countdown(targetDateTime) {
        return {
            timeLeft: 'Carregando...',

            init() {
                if (!targetDateTime) {
                    this.timeLeft = 'Horário não disponível';
                    return;
                }

                this.updateTime();
                setInterval(() => this.updateTime(), 1000);
            },

            updateTime() {
                const now = new Date();
                const target = new Date(targetDateTime);
                const diff = target - now;

                if (diff <= 0) {
                    this.timeLeft = 'Disponível agora';
                    return;
                }

                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                if (hours > 0) {
                    this.timeLeft = `Disponível em ${hours}h ${minutes}m ${seconds}s`;
                } else if (minutes > 0) {
                    this.timeLeft = `Disponível em ${minutes}m ${seconds}s`;
                } else {
                    this.timeLeft = `Disponível em ${seconds}s`;
                }
            }
        }
    }
</script>
