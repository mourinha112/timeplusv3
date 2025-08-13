<div class="card bg-base-100 shadow-lg border border-gray-200 rounded-xl p-6">
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-danger">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="grid grid-cols-5 gap-2 mb-4">
        @foreach ($this->availabilities as $date => $times)
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

                <!-- Horários disponíveis -->
                @php
                    $maxTimes = max(5, max(array_map('count', $this->availabilities))); // Mínimo de 5 linhas
                    $currentTimesCount = count($times);
                @endphp

                <div class="mt-2 space-y-1 {{ $maxTimes > 8 ? 'h-64 overflow-y-auto' : 'min-h-fit' }}">
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

                        {{-- Preencher com campos vazios até atingir o máximo (mínimo 5) --}}
                        @for ($i = $currentTimesCount; $i < $maxTimes; $i++)
                            <div class="btn btn-block font-normal text-sm bg-gray-100 text-gray-400 cursor-not-allowed opacity-50"
                                disabled>

                            </div>
                        @endfor
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

                        {{-- Preencher com campos vazios (mínimo 5 linhas menos 1 da mensagem) --}}
                        {{-- @for ($i = 0; $i < $maxTimes; $i++)
                    <div class="btn btn-block font-normal text-sm bg-gray-100 text-gray-400 cursor-not-allowed opacity-50" disabled>

                    </div>
                    @endfor --}}
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex justify-between">
        <div>
            @if ($selectedTime && $selectedDate)
                <p class="text-sm text-base-content font-semibold">R$ 100</p>
                <p class="text-xs text-base-content/70">
                    Agendar para {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }} às
                    {{ \Carbon\Carbon::parse($selectedTime)->format('H:i') }}h.
                </p>
            @endif
        </div>
        <button class="btn btn-info" wire:click="schedule" wire:loading.attr="disabled" wire:target="schedule"
            {{ $selectedTime && $selectedDate ? '' : 'disabled' }}>
            <x-carbon-calendar class="w-5 h-5" />
            Agendar
        </button>
    </div>
</div>
