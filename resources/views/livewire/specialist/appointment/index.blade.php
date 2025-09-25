<div>
    <x-heading>
        <x-title>Agendamentos</x-title>
        <x-subtitle>Visualize e gerencie seus agendamentos.</x-subtitle>
    </x-heading>

    <div class="flex items-center justify-between mb-3">
        <button wire:click="previousWeek" class="btn btn-info btn-outline sm:btn-sm btn-xs">
            <x-carbon-chevron-left class="w-5" /> Anterior
        </button>
        <x-text class="sm:text-lg">{{ $firstDayOfWeek }} até {{ $lastDayOfWeek }}</x-text>
        <button wire:click="nextWeek" class="btn btn-info btn-outline sm:btn-sm btn-xs">
            <x-carbon-chevron-right class="w-5" /> Próxima
        </button>
    </div>

    <div class="card card-xl bg-base-100 shadow-lg border border-gray-200 rounded-xl p-4">
        <div class="max-h-120 overflow-y-auto pr-2">
            <div class="grid grid-cols-[auto_repeat(7,1fr)] gap-1 lg:gap-2">
                <!-- Top-left empty cell -->
                <div></div>

                <!-- Day Headers -->
                @foreach ($weekDays as $dayInfo)
                    <div class="text-center">
                        <div class="w-full p-1 lg:p-2 rounded border border-base-content/20 text-base-content">
                            <p class="text-xs uppercase font-medium">{{ $dayInfo['dayOfWeek'] }}</p>
                            <p class="text-sm font-bold">{{ $dayInfo['day'] }}</p>
                            <p class="text-xs uppercase">{{ $dayInfo['month'] }}</p>
                        </div>
                    </div>
                @endforeach

                <!-- Time labels and slots, row by row -->
                @foreach ($timeSlots as $time)
                    <!-- Time Label -->
                    <div class="text-center text-xs font-bold text-gray-600 flex items-center justify-center">
                        {{ $time }}
                    </div>

                    <!-- Slots for this time -->
                    @foreach ($weekDays as $dayInfo)
                        @php
                            $appointment = $appointments[$dayInfo['full_date']][$time . ':00'] ?? null;
                            $hasAppointment = !is_null($appointment);
                            $userName = $hasAppointment ? $appointment->user->name ?? 'Usuário' : '';
                        @endphp

                        @if ($hasAppointment)
                            {{-- Slot ocupado --}}
                            {{-- <button wire:click="cancelAppointment('{{ $dayInfo['full_date'] }}', '{{ $time }}')" class="btn btn-sm lg:btn-md rounded border text-xs font-medium flex items-center justify-center relative cursor-pointer group bg-blue-50 border-blue-200 hover:bg-red-50 hover:border-red-300 text-blue-600" title="Agendado para: {{ $userName }}">
                <span class="flex items-center gap-1 group-hover:hidden">
                    <x-carbon-user class="w-3 h-3" />
                    <span class="hidden lg:inline text-xs truncate">{{ Str::limit($userName, 8) }}</span>
                </span>
                <span class="hidden group-hover:flex items-center gap-1 text-red-500">
                    <span class="font-bold text-sm">×</span>
                    <span class="hidden lg:inline">Cancelar</span>
                </span>
                </button> --}}
                            <div class="dropdown">
                                <div tabindex="0" role="button"
                                    class="btn btn-sm lg:btn-md rounded border text-xs font-medium flex items-center justify-center relative cursor-pointer group bg-blue-50 border-blue-200 text-blue-600">
                                    <x-carbon-user class="w-3 h-3" />
                                    <span
                                        class="hidden lg:inline text-xs truncate">{{ Str::limit($userName, 10) }}</span>
                                </div>
                                <ul tabindex="0"
                                    class="dropdown-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">

                                    @if ($appointment->status === 'completed')
                                        <li><a class="text-success cursor-default">
                                                <x-carbon-checkmark-filled class="w-4 h-4" />
                                                Sessão concluída
                                            </a></li>
                                    @elseif ($this->hasRoom($appointment))
                                        <li><a href="{{ route('specialist.videocall.show', $appointment->room->code) }}" wire:navigate>
                                                <x-carbon-video-chat class="w-4 h-4" />
                                                Iniciar atendimento
                                            </a></li>
                                    @elseif ($this->hasScheduledRoom($appointment))
                                        <li><a class="cursor-default">
                                                <x-carbon-time class="w-4 h-4" />
                                                <div x-data="countdown('{{ $this->getRoomOpenTime($appointment)?->toISOString() }}')"
                                                     x-text="timeLeft"
                                                     class="inline">
                                                    Carregando...
                                                </div>
                                            </a></li>
                                    @elseif ($this->isPaid($appointment))
                                        <li><a class="text-info cursor-default">
                                                <x-carbon-time class="w-4 h-4" />
                                                Aguardando sala
                                            </a></li>
                                    @else
                                        <li><a class="text-warning cursor-default">
                                                <x-carbon-currency-dollar class="w-4 h-4" />
                                                Aguardando pagamento
                                            </a></li>
                                    @endif

                                    @if ($appointment->status !== 'completed')
                                        <li><a class="text-error"
                                                wire:click="cancelAppointment('{{ $dayInfo['full_date'] }}', '{{ $time }}')">
                                                <x-carbon-close class="w-4 h-4" />
                                                Cancelar
                                            </a></li>
                                    @endif
                                </ul>
                            </div>
                        @else
                            {{-- Slot disponível --}}
                            <button
                                class="btn btn-sm lg:btn-md rounded border text-xs font-medium flex items-center justify-center cursor-not-allowed relative group bg-gray-50 border-gray-200 text-gray-400">
                            </button>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>

        <!-- Legenda -->
        <div class="flex gap-6 mt-3 text-xs text-gray-600">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-blue-100 border border-blue-200 rounded"></div>
                <span>Agendado</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-gray-100 border border-gray-200 rounded"></div>
                <span>Não agendado</span>
            </div>
        </div>
    </div>
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
                setInterval(() => this.updateTime(), 1000); // Atualiza a cada segundo
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
