<div>
    @php echo 'Contém ' . $this->availabilities->count() . ' data(s) disponível(eis).' @endphp
    <div class="flex items-center justify-between mb-8">
        <div class="space-y-3">
            <x-title>Disponibilidades</x-title>
            <x-subtitle>Visualize e gerencie suas disponibilidades.</x-subtitle>
        </div>

        <div class="flex items-center gap-2">
            <button class="btn btn-circle btn-sm hover:bg-gray-100">
                <x-carbon-chevron-left class="w-5 text-blue-400" />
            </button>
            <button class="btn btn-circle btn-sm hover:bg-gray-100">
                <x-carbon-chevron-right class="w-5 text-blue-400" />
            </button>
        </div>
    </div>

    <div class="card card-xl bg-base-100 shadow-lg border border-gray-200 rounded-xl p-4">
        <!--TODO: Trazer os horários disponibilizados pelo especialista do banco -->
        @php
        $times = [
        '09:00',
        '10:00',
        '11:00',
        '12:00',
        '13:00',
        '14:00',
        '15:00',
        '16:00',
        '17:00',
        '18:00',
        '19:00',
        ];

        $schedule = [
        [
        'dayOfWeek' => 'QUI',
        'day' => '11',
        'month' => 'JUL',
        'slots' => [
        ['time' => '09:00', 'status' => 'unavailable'],
        ['time' => '10:00', 'status' => 'available'],
        ['time' => '11:00', 'status' => 'available'],
        ['time' => '12:00', 'status' => 'unavailable'],
        ['time' => '13:00', 'status' => 'unavailable'],
        ['time' => '14:00', 'status' => 'available'],
        ['time' => '15:00', 'status' => 'available'],
        ['time' => '16:00', 'status' => 'unavailable'],
        ['time' => '17:00', 'status' => 'available'],
        ['time' => '18:00', 'status' => 'available'],
        ['time' => '19:00', 'status' => 'available'],
        ],
        ],
        [
        'dayOfWeek' => 'SEX',
        'day' => '12',
        'month' => 'JUL',
        'slots' => [
        ['time' => '09:00', 'status' => 'unavailable'],
        ['time' => '10:00', 'status' => 'unavailable'],
        ['time' => '11:00', 'status' => 'available'],
        ['time' => '12:00', 'status' => 'available'],
        ['time' => '13:00', 'status' => 'unavailable'],
        ['time' => '14:00', 'status' => 'available'],
        ['time' => '15:00', 'status' => 'available'],
        ['time' => '16:00', 'status' => 'available'],
        ['time' => '17:00', 'status' => 'unavailable'],
        ['time' => '18:00', 'status' => 'available'],
        ['time' => '19:00', 'status' => 'unavailable'],
        ],
        ],
        [
        'dayOfWeek' => 'SAB',
        'day' => '13',
        'month' => 'JUL',
        'slots' => [
        ['time' => '09:00', 'status' => 'available'],
        ['time' => '10:00', 'status' => 'available'],
        ['time' => '11:00', 'status' => 'unavailable'],
        ['time' => '12:00', 'status' => 'unavailable'],
        ['time' => '13:00', 'status' => 'unavailable'],
        ['time' => '14:00', 'status' => 'unavailable'],
        ['time' => '15:00', 'status' => 'unavailable'],
        ['time' => '16:00', 'status' => 'unavailable'],
        ['time' => '17:00', 'status' => 'unavailable'],
        ['time' => '18:00', 'status' => 'unavailable'],
        ['time' => '19:00', 'status' => 'unavailable'],
        ],
        ],
        [
        'dayOfWeek' => 'DOM',
        'day' => '14',
        'month' => 'JUL',
        'slots' => [
        ['time' => '09:00', 'status' => 'unavailable'],
        ['time' => '10:00', 'status' => 'unavailable'],
        ['time' => '11:00', 'status' => 'unavailable'],
        ['time' => '12:00', 'status' => 'unavailable'],
        ['time' => '13:00', 'status' => 'unavailable'],
        ['time' => '14:00', 'status' => 'unavailable'],
        ['time' => '15:00', 'status' => 'unavailable'],
        ['time' => '16:00', 'status' => 'unavailable'],
        ['time' => '17:00', 'status' => 'unavailable'],
        ['time' => '18:00', 'status' => 'unavailable'],
        ['time' => '19:00', 'status' => 'unavailable'],
        ],
        ],
        [
        'dayOfWeek' => 'SEG',
        'day' => '15',
        'month' => 'JUL',
        'slots' => [
        ['time' => '09:00', 'status' => 'available'],
        ['time' => '10:00', 'status' => 'available'],
        ['time' => '11:00', 'status' => 'available'],
        ['time' => '12:00', 'status' => 'unavailable'],
        ['time' => '13:00', 'status' => 'unavailable'],
        ['time' => '14:00', 'status' => 'available'],
        ['time' => '15:00', 'status' => 'available'],
        ['time' => '16:00', 'status' => 'unavailable'],
        ['time' => '17:00', 'status' => 'available'],
        ['time' => '18:00', 'status' => 'available'],
        ['time' => '19:00', 'status' => 'available'],
        ],
        ],
        ];

        // Create a map for quick lookups: [dayIndex][time] => status
        $scheduleMap = [];
        foreach ($schedule as $dayIndex => $dayInfo) {
        foreach ($dayInfo['slots'] as $slot) {
        $scheduleMap[$dayIndex][$slot['time']] = $slot['status'];
        }
        }
        @endphp

        <div class="h-96 overflow-y-auto pr-2">
            <div class="grid grid-cols-[auto_repeat(5,1fr)] gap-1 lg:gap-2">
                <!-- Top-left empty cell -->
                <div></div>

                <!-- Day Headers -->
                @foreach ($schedule as $dayInfo)
                <div class="text-center">
                    <div class="w-full p-1 lg:p-2 rounded border border-base-content/20 text-base-content">
                        <p class="text-xs uppercase font-medium">{{ $dayInfo['dayOfWeek'] }}</p>
                        <p class="text-sm font-bold">{{ $dayInfo['day'] }}</p>
                        <p class="text-xs uppercase">{{ $dayInfo['month'] }}</p>
                    </div>
                </div>
                @endforeach

                <!-- Time labels and slots, row by row -->
                @foreach ($times as $time)
                <!-- Time Label -->
                <div class="text-center text-xs font-bold text-gray-600 flex items-center justify-center">
                    {{ $time }}
                </div>

                <!-- Slots for this time -->
                @foreach ($schedule as $dayIndex => $dayInfo)
                @php
                $status = $scheduleMap[$dayIndex][$time] ?? 'unavailable';
                $baseClasses =
                'btn btn-sm lg:btn-md rounded border text-xs font-medium flex items-center justify-center relative cursor-pointer group';
                @endphp

                @if ($status === 'available')
                @php
                $classes =
                $baseClasses .
                ' bg-green-50 border-green-200 hover:bg-red-50 hover:border-red-300 text-green-600';
                @endphp
                <button class="{{ $classes }}">
                    <span class="flex items-center gap-1 group-hover:hidden">
                        <x-carbon-checkmark-outline class="w-4 h-4" />
                        <span class="hidden lg:inline">Selecionado</span>
                    </span>
                    <span class="hidden group-hover:flex items-center gap-1 text-red-500">
                        <span class="font-bold text-sm">−</span>
                        <span class="hidden lg:inline">Remover</span>
                    </span>
                </button>
                @else
                @php
                $classes =
                $baseClasses .
                ' bg-gray-50 border-gray-200 hover:bg-green-50 hover:border-green-300 text-gray-400';
                @endphp
                <button class="{{ $classes }}">
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
                <span>(-) Excluir horário</span>
            </div>
            <div class="flex items-center gap-2">
                <span>(+) Adicionar horário</span>
            </div>
        </div>

    </div>
</div>
</div>
