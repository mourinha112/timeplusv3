<div class="card bg-base-100 shadow-lg border border-gray-200 rounded-xl p-6">
    @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
        <li class="text-danger">{{ $error }}</li>
        @endforeach
    </ul>
    @endif

    <div class="grid grid-cols-5 gap-2 mb-4">
        @foreach($dates as $date)
        <div class="text-center">
            <button class="w-full p-3 rounded-lg border border-gray-200 hover:bg-gray-50 text-base-content dark:hover:bg-base-content/70">
                <div class="text-xs uppercase font-medium text-base-content/70">{{ $date['label'] }}
                </div>
                <div class="text-lg font-bold text-base-content">{{ \Carbon\Carbon::parse($date['full'])->format('d') }}</div>
                <div class="text-xs uppercase text-base-content/70">{{ \Carbon\Carbon::parse($date['full'])->format('M') }}</div>
            </button>
            <div class="mt-2 space-y-1 h-55 overflow-y-scroll">
                @foreach($date['times'] as $time)
                <button class="btn btn-dash btn-info btn-block font-normal bg-base-100 text-sm text-base-content">
                    {{ $time }}
                </button>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    <div class="flex justify-between">
        <div>
            <p class="text-sm text-base-content font-semibold">R$ 100</p>
            <p class="text-xs text-base-content/70">Agendar para 11/07/2025</p>
        </div>
        <button class="btn btn-info">
        <x-carbon-calendar class="w-5 h-5" />
            Agendar
        </button>
    </div>
</div>
