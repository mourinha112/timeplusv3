<div class="space-y-4">
    <x-subtitle>
        Dê o primeiro passo agora! 👋🏻
    </x-subtitle>

    @if ($this->nextAppointment)
        <x-card class="{{ $this->isPaid($this->nextAppointment) ? 'border-info/30 bg-info/5' : 'border-warning/30 bg-warning/5' }}">
            <x-card-body>
                <div class="flex items-center gap-3 mb-3">
                    <x-carbon-calendar-heat-map class="w-6 h-6 {{ $this->isPaid($this->nextAppointment) ? 'text-info' : 'text-warning' }}" />
                    <x-card-title>Próxima sessão</x-card-title>
                    @if (!$this->isPaid($this->nextAppointment))
                        <span class="badge badge-warning badge-sm">Aguardando pagamento</span>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-1">
                        <div class="text-lg font-semibold">
                            {{ \Carbon\Carbon::parse($this->nextAppointment->appointment_date)->format('d/m/Y') }}
                            - {{ \Carbon\Carbon::parse($this->nextAppointment->appointment_time)->format('H\h') }}
                        </div>
                        <div class="text-base-content/70">
                            <x-carbon-reminder-medical class="w-4 h-4 inline" />
                            {{ $this->nextAppointment->specialist->name }}
                        </div>
                    </div>

                    <div>
                        @if (!$this->isPaid($this->nextAppointment))
                            <a href="{{ route('user.appointment.payment', ['appointment_id' => $this->nextAppointment->id]) }}"
                                wire:navigate class="btn btn-warning btn-sm">
                                <x-carbon-purchase class="w-4 h-4" />
                                Pagar Agora
                            </a>
                        @elseif ($this->hasRoom($this->nextAppointment))
                            <a href="{{ route('user.videocall.show', $this->nextAppointment->room->code) }}"
                                wire:navigate class="btn btn-info btn-sm">
                                <x-carbon-video class="w-4 h-4" />
                                Entrar na sala {{ $this->nextAppointment->room->code }}
                            </a>
                        @elseif ($this->hasScheduledRoom($this->nextAppointment))
                            <span class="badge badge-warning badge-sm py-3">
                                <x-carbon-time class="w-3 h-3 mr-1" />
                                <span x-data="countdown('{{ $this->getRoomOpenTime($this->nextAppointment)?->toISOString() }}')" x-text="timeLeft">
                                    Carregando...
                                </span>
                            </span>
                        @else
                            <a href="{{ route('user.appointment.index') }}" wire:navigate class="btn btn-info btn-sm">
                                <x-carbon-calendar-heat-map class="w-4 h-4" />
                                Ver sessões
                            </a>
                        @endif
                    </div>
                </div>
            </x-card-body>
        </x-card>
    @endif

    <x-card>
        <x-card-body class="flex justify-between sm:items-center sm:flex-row">

            <div class="space-y-3">
                <x-card-title>Garanta sua sessão hoje</x-card-title>
                <x-text>Busque e encontre os especialistas ideais para você.</x-text>
            </div>

            <x-btn-link href="{{ route('user.specialist.index') }}" wire:navigate class="mt-2 sm:btn sm:mt-0">
                <x-carbon-search-advanced class="w-4 h-4" />
                Encontrar especialistas
            </x-btn-link>

        </x-card-body>
    </x-card>

    <x-card>
        <x-card-body class="flex justify-between sm:items-center sm:flex-row">

            <div class="space-y-3">
                <x-card-title>Eleve sua experiência</x-card-title>
                <x-text>Descubra o melhor plano para aprimorar sua experiência.</x-text>
            </div>

            <x-btn-link href="{{ route('user.plan.index') }}" wire:navigate class="mt-2 sm:btn sm:mt-0">
                <x-carbon-pricing-consumption class="w-4 h-4" />
                Conhecer os planos
            </x-btn-link>

        </x-card-body>
    </x-card>
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
