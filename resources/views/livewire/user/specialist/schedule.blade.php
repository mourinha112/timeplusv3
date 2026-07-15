<x-card class="border border-gray-200 rounded-xl p-6">
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-danger">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @if ($this->companyCreditInfo)
        @php($creditInfo = $this->companyCreditInfo)
        <div class="mb-4 p-4 rounded-lg {{ $creditInfo['can_schedule'] ? 'bg-info/10' : 'bg-warning/10' }}">
            <div class="flex items-start gap-3">
                <x-carbon-wallet class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $creditInfo['can_schedule'] ? 'text-info' : 'text-warning' }}" />
                <div class="text-sm">
                    <div class="font-semibold text-base-content mb-1">
                        Créditos da empresa ({{ $creditInfo['company_name'] }})
                    </div>
                    <p class="text-base-content/80">
                        @if ($creditInfo['limit'] !== null)
                            Você usou {{ $creditInfo['used'] }} de {{ $creditInfo['limit'] }} créditos neste mês
                            — restam <strong>{{ $creditInfo['employee_remaining'] }}</strong>.
                        @else
                            Você usou {{ $creditInfo['used'] }} crédito(s) neste mês (sem limite individual).
                        @endif
                        Cada sessão consome 1 crédito (R$ {{ number_format($creditInfo['unit_price'], 2, ',', '.') }}, pago pela empresa).
                    </p>
                    @if (!$creditInfo['can_schedule'])
                        <p class="text-warning font-medium mt-1">
                            @if ($creditInfo['company_balance'] < 1)
                                A empresa está sem créditos disponíveis no momento.
                            @else
                                Você atingiu seu limite mensal de créditos.
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-start gap-2 mb-4">
        <!-- Botão Anterior -->
        <button
            wire:click="previousDays"
            class="p-3 rounded-lg border transition-all duration-200 {{ $this->canGoPrevious() ? 'border-gray-200 hover:bg-gray-50 text-base-content' : 'border-gray-100 text-gray-300 cursor-not-allowed' }}"
            {{ $this->canGoPrevious() ? '' : 'disabled' }}>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Grid de Dias -->
        <div class="grid grid-cols-5 gap-2 flex-1">
            @foreach ($this->paginatedAvailabilities as $date => $times)
                <div class="text-center">
                    <!-- Botão da Data com estado selecionado -->
                    <button
                        class="w-full p-3 rounded-lg border transition-all duration-200
                    {{ $selectedDate === $date
                        ? 'border-info bg-info/10 text-info'
                        : 'border-gray-200 hover:bg-gray-50 text-base-content dark:hover:bg-base-content/70' }}">
                        <div
                            class="text-xs uppercase font-medium {{ $selectedDate === $date ? 'text-info' : 'text-base-content/70' }}">
                            {{ strtoupper(\Carbon\Carbon::parse($date)->locale('pt_BR')->isoFormat('ddd')) }}
                        </div>
                        <div class="text-lg font-bold">{{ \Carbon\Carbon::parse($date)->format('d') }}</div>
                        <div class="text-xs uppercase {{ $selectedDate === $date ? 'text-info' : 'text-base-content/70' }}">
                            {{ \Carbon\Carbon::parse($date)->format('M') }}
                        </div>
                    </button>

                    <div class="mt-2 space-y-1 max-h-48 overflow-y-auto">
                        @if (count($times) > 0)
                            {{-- Horários disponíveis --}}
                            @foreach ($times as $time)
                                <button
                                    class="btn btn-block font-normal text-sm transition-all duration-200
                            {{ $selectedDate === $date && $selectedTime === $time
                                ? 'btn-info text-white'
                                : 'btn-outline border-gray-200 bg-base-100 text-base-content hover:border-info hover:bg-info/10 hover:text-info' }}"
                                    wire:click="selectSchedule('{{ $date }}', '{{ $time }}')">
                                    {{ \Carbon\Carbon::parse($time)->format('H:i') }}
                                </button>
                            @endforeach
                        @else
                            {{-- Quando não há horários disponíveis --}}
                            <div class="flex flex-col items-center justify-center py-4 text-center">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-500 font-medium">Sem horários</p>
                                <p class="text-xs text-gray-400">disponíveis</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Botão Próximo -->
        <button
            wire:click="nextDays"
            class="p-3 rounded-lg border transition-all duration-200 {{ $this->canGoNext() ? 'border-gray-200 hover:bg-gray-50 text-base-content' : 'border-gray-100 text-gray-300 cursor-not-allowed' }}"
            {{ $this->canGoNext() ? '' : 'disabled' }}>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>

    <div class="flex justify-between">
        <div>
            @if ($selectedTime && $selectedDate)
                <div class="space-y-1">
                    @if ($pricing_info['has_discount'])
                        <div class="space-y-1">
                            <p class="text-xs text-base-content/50 line-through">
                                De: R$ {{ number_format($pricing_info['original_amount'], 2, ',', '.') }}
                            </p>
                            <p class="text-sm text-info font-semibold">
                                Por: R$ {{ number_format($pricing_info['final_amount'], 2, ',', '.') }}
                                <span class="text-xs text-base-content/60">/ {{ $specialist->getSessionDuration() }} min</span>
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="badge badge-success badge-sm">{{ $pricing_info['discount_percentage'] }}%
                                    OFF</span>
                                @if ($pricing_info['company_plan_name'])
                                    <span class="text-xs text-success">Plano:
                                        {{ $pricing_info['company_plan_name'] }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-success">
                                Economia: R$ {{ number_format($pricing_info['company_discount_amount'], 2, ',', '.') }}
                            </p>
                        </div>
                    @else
                        <p class="text-sm text-base-content font-semibold">
                            R$ {{ number_format($specialist->appointment_value, 2, ',', '.') }}
                            <span class="text-xs text-base-content/60">/ {{ $specialist->getSessionDuration() }} min</span>
                        </p>
                    @endif
                    <p class="text-xs text-base-content/70">
                        Agendar para {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }} às
                        {{ \Carbon\Carbon::parse($selectedTime)->format('H:i') }}h.
                    </p>
                </div>
            @endif
        </div>
        <button class="btn btn-info" wire:click="schedule" wire:loading.attr="disabled" wire:target="schedule"
            {{ $selectedTime && $selectedDate ? '' : 'disabled' }}>
            <x-carbon-calendar class="w-5 h-5" />
            Agendar
        </button>
    </div>
</x-card>
