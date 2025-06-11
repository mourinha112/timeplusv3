<div>
    <h3 class="text-xl">Encontramos <strong>{{ $this->specialists->count() }} especialistas</strong> disponíveis para você.</h3>

    <div class="flex flex-col gap-6 mt-6">
        @foreach($this->specialists as $specialist)
        <x-card>
            <div class="flex flex-col gap-4 md:flex-row md:justify-between md:items-start mb-4">
                <div class="flex gap-4 items-center">
                    <img src="https://avatar.iran.liara.run/public/{{ random_int(1, 30) }}" alt="{{ $specialist->name }}" class="w-20 h-20 rounded-full shrink-0">

                    <div class="space-y-1">
                        <h3 class="text-xl font-bold">{{ $specialist->name }}</h3>
                        <x-text>{{ $specialist->specialty->name }}</x-text>

                        @if($specialist->crp)
                        <x-text>CRP: {{ $specialist->crp }}</x-text>
                        @endif

                        <x-text>{{ now()->year - $specialist->year_started_acting }} anos de experiência</x-text>
                    </div>
                </div>

                <x-button class="hidden md:inline-flex" color="secondary">
                    Agendar consulta
                </x-button>
            </div>

            @if($specialist->reasons->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($specialist->reasons as $reason)
                <x-badge>{{ $reason->name }}</x-badge>
                @endforeach
            </div>
            @endif

            <x-text>
                {{ Str::limit($specialist->description, 90) }}
            </x-text>

            <x-button class="block md:hidden w-full mt-4" color="secondary">
                Agendar consulta
            </x-button>
        </x-card>
        @endforeach
    </div>
</div>
