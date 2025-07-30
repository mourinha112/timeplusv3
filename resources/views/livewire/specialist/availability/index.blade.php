<div>
    <div class="space-y-3 mb-8">
        <x-title>Disponibilidades</x-title>
        <x-subtitle>Visualize e gerencie suas disponibilidades.</x-subtitle>
    </div>

    <div class="flex items-center justify-between mb-3">
        <button wire:click="previousWeek" class="btn btn-info btn-outline sm:btn-sm btn-xs">
            <x-carbon-chevron-left class="w-5" /> Anterior
        </button>
        <h2 class="sm:text-lg text-sm text-base-content/70">{{ $firstDayOfWeek }} até {{ $lastDayOfWeek }}</h2>
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
                $availability = $availabilities[$dayInfo['full_date']][$time . ':00'] ?? null;
                $hasAvailability = !is_null($availability);
                @endphp

                @if ($hasAvailability)
                {{-- Slot ocupado --}}
                <button wire:click="toggleTimeAvailability('{{ $dayInfo['full_date'] }}', '{{ $time }}')" class="btn btn-sm lg:btn-md rounded border text-xs font-medium flex items-center justify-center relative cursor-pointer group bg-blue-50 border-blue-200 hover:bg-red-50 hover:border-red-300 text-blue-600" title="OK">
                    <span class="flex items-center gap-1 group-hover:hidden">
                        <x-carbon-checkmark-outline class="w-4 h-4" />
                        <span class="hidden lg:inline">Disponível</span>
                    </span>
                    <span class="hidden group-hover:flex items-center gap-1 text-red-500">
                        <span class="font-bold text-sm">×</span>
                        <span class="hidden lg:inline">Cancelar</span>
                    </span>
                </button>
                @else
                {{-- Slot disponível --}}
                <button wire:click="toggleTimeAvailability('{{ $dayInfo['full_date'] }}', '{{ $time }}')" class="btn btn-sm lg:btn-md rounded border text-xs font-medium flex items-center justify-center relative cursor-pointer group bg-gray-50 border-gray-200 hover:bg-green-50 hover:border-green-300 text-gray-400">
                    <span class="opacity-0 group-hover:opacity-100 text-sm font-bold text-green-500">+</span>
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
                <span>Disponível</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-gray-100 border border-gray-200 rounded"></div>
                <span>Indisponível</span>
            </div>
        </div>
    </div>
</div>
