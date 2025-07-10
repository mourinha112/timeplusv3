<div class="p-6 bg-transparent">
    <div class="grid grid-cols-5 gap-4 w-full">
        @foreach($dates as $date)
        <button wire:click="selectDate('{{ $date['full'] }}')" class="w-full px-3 py-2 rounded-lg text-center border border-gray-300
                {{ $selectedDate === $date['full'] ? 'bg-black text-white' : 'bg-white text-black/70' }}">
            <div class="text-sm font-bold">{{ $date['label'] }}</div>
            <div class="text-xs">{{ \Carbon\Carbon::parse($date['full'])->format('M') }}</div>
        </button>
        @endforeach
    </div>

    <div class="mt-4 grid grid-cols-4 gap-2 h-[250px] overflow-y-auto">
        @if($selectedDate)
        @php
        $selected = collect($dates)->firstWhere('full', $selectedDate);
        @endphp

        @foreach($selected['times'] as $time)
        <button wire:click="selectTime('{{ $time }}')" class="px-3 py-2 rounded-lg text-sm {{ $selectedTime === $time ? 'bg-black text-white font-bold' : 'bg-white text-black/70' }}">
            {{ $time }}
        </button>
        @endforeach
        @else
        <div class="col-span-3 text-center text-gray-400">
            Selecione uma data
        </div>
        @endif
    </div>

    <div class="mt-6 text-right">
        {{-- <button wire:click="schedule" class="h-full font-semibold py-3 px-4 rounded-md transition-colors duration-300 cursor-pointer text-xs uppercase text-yellow-100 bg-yellow-500 hover:bg-yellow-600 disabled:bg-yellow-500/40" @disabled(!($selectedDate && $selectedTime))>
            Agendar
        </button> --}}
        <button wire:click="schedule" class="h-full font-semibold py-3 px-4 rounded-md transition-colors duration-300 cursor-pointer text-xs uppercase text-white disabled:text-gray-100 bg-black disabled:bg-black/20" @disabled(!($selectedDate && $selectedTime))>
            Agendar
        </button>
    </div>

    @if (session()->has('message'))
    <div class="mt-2 text-green-500 text-sm">
        {{ session('message') }}
    </div>
    @endif
</div>
