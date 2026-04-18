<div>
    <x-heading class="bg-success/15 border-l-4 border-success p-3 rounded-r-lg">
        <x-title class="text-success">
            <x-carbon-time class="w-6 h-6 inline-block mr-1" />
            Disponibilidades
        </x-title>
        <x-subtitle>Visualize e gerencie suas disponibilidades.</x-subtitle>
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

    <div class="flex justify-end mb-3">
        <button type="button"
            wire:click="replicatePreviousWeek"
            wire:confirm="Replicar os horários da semana anterior para esta semana? Slots já existentes serão preservados."
            class="btn btn-success btn-outline btn-xs sm:btn-sm">
            <x-carbon-copy class="w-4" />
            Replicar semana anterior
        </button>
    </div>

    <div class="mb-3 p-3 bg-base-200 rounded-lg">
        <p class="text-xs font-semibold mb-2">Modalidade dos próximos slots que você criar:</p>
        <div class="flex flex-wrap gap-2">
            <button type="button" wire:click="setMode('both')"
                class="btn btn-xs sm:btn-sm {{ $selectedMode === 'both' ? 'btn-primary' : 'btn-outline' }}">
                Ambas
            </button>
            <button type="button" wire:click="setMode('timeplus')"
                class="btn btn-xs sm:btn-sm {{ $selectedMode === 'timeplus' ? 'btn-info' : 'btn-outline' }}">
                Somente TimePlus
            </button>
            <button type="button" wire:click="setMode('particular')"
                class="btn btn-xs sm:btn-sm {{ $selectedMode === 'particular' ? 'btn-success' : 'btn-outline' }}">
                Somente Particular
            </button>
        </div>
    </div>

    <div class="card card-xl bg-base-100 shadow-lg border border-gray-200 rounded-xl p-4">
        <!-- Day Headers (fixos) -->
        <div class="grid grid-cols-[auto_repeat(7,1fr)] gap-1 lg:gap-2 mb-1">
            <div></div>
            @foreach ($weekDays as $dayInfo)
                <div class="text-center">
                    <div class="w-full p-1 lg:p-2 rounded border border-base-content/20 text-base-content">
                        <p class="text-xs uppercase font-medium">{{ $dayInfo['dayOfWeek'] }}</p>
                        <p class="text-sm font-bold">{{ $dayInfo['day'] }}</p>
                        <p class="text-xs uppercase">{{ $dayInfo['month'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="max-h-120 overflow-y-auto pr-2">
            <div class="grid grid-cols-[auto_repeat(7,1fr)] gap-1 lg:gap-2">
                <!-- Time labels and slots, row by row -->
                @foreach ($timeSlots as $time)
                    <!-- Time Label -->
                    <div class="text-center text-xs font-bold text-gray-600 flex items-center justify-center">
                        {{ $time }}
                    </div>

                    <!-- Slots for this time -->
                    @foreach ($weekDays as $dayInfo)
                        @php
                            $availability = $availabilities[$dayInfo['full_date']][$time . ':00'] ?? null;
                            $hasAvailability = !is_null($availability);
                        @endphp

                        @if ($hasAvailability)
                            @php
                                $mode = $availability->service_mode ?? 'both';
                                $modeColor = match ($mode) {
                                    'timeplus' => 'bg-blue-50 border-blue-300 text-blue-700',
                                    'particular' => 'bg-green-50 border-green-300 text-green-700',
                                    default => 'bg-purple-50 border-purple-300 text-purple-700',
                                };
                                $modeLabel = match ($mode) {
                                    'timeplus' => 'TimePlus',
                                    'particular' => 'Particular',
                                    default => 'Ambas',
                                };
                                $modeAbbr = match ($mode) {
                                    'timeplus' => 'TP',
                                    'particular' => 'PA',
                                    default => 'AM',
                                };
                            @endphp
                            {{-- Slot ocupado --}}
                            <button
                                wire:click="toggleTimeAvailability('{{ $dayInfo['full_date'] }}', '{{ $time }}')"
                                class="btn btn-sm lg:btn-md rounded border text-xs font-medium flex items-center justify-center relative cursor-pointer group hover:bg-red-50 hover:border-red-300 {{ $modeColor }}"
                                title="{{ $modeLabel }}">
                                <span class="flex items-center gap-1 group-hover:hidden">
                                    <span class="font-bold">{{ $modeAbbr }}</span>
                                    <span class="hidden lg:inline">{{ $modeLabel }}</span>
                                </span>
                                <span class="hidden group-hover:flex items-center gap-1 text-red-500">
                                    <span class="font-bold text-sm">×</span>
                                    <span class="hidden lg:inline">Remover</span>
                                </span>
                            </button>
                        @else
                            {{-- Slot disponível --}}
                            <button
                                wire:click="toggleTimeAvailability('{{ $dayInfo['full_date'] }}', '{{ $time }}')"
                                class="btn btn-sm lg:btn-md rounded border text-xs font-medium flex items-center justify-center relative cursor-pointer group bg-gray-50 border-gray-200 hover:bg-green-50 hover:border-green-300 text-gray-400">
                                <span
                                    class="opacity-0 group-hover:opacity-100 text-sm font-bold text-green-500">+</span>
                            </button>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>

        <!-- Legenda -->
        <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-600">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-purple-100 border border-purple-300 rounded"></div>
                <span>Ambas (TimePlus + Particular)</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-blue-100 border border-blue-300 rounded"></div>
                <span>Somente TimePlus</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-100 border border-green-300 rounded"></div>
                <span>Somente Particular</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-gray-100 border border-gray-200 rounded"></div>
                <span>Indisponível</span>
            </div>
        </div>
    </div>
</div>
