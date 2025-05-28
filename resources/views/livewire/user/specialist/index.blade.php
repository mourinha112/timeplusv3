<div>
    <x-text>Encontramos {{ $this->specialists->count() }} especialistas.</x-text>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
        @foreach($this->specialists as $specialist)

        <x-card>
            <h3 class="font-bold text-xl">{{ $specialist->name }}</h3>

            <div class="flex flex-col mb-4">
                <x-text>{{ $specialist->specialty->name }}</x-text>

                @if($specialist->crp)
                <x-text>CRP: {{ $specialist->crp }}</x-text>
                @endif

                <x-text>{{ (now()->year - $specialist->year_started_acting) }} anos de experiência</x-text>
            </div>

            <div class="mb-3">
                @foreach($specialist->reasons as $reason)
                <x-badge>{{ $reason->name }}</x-badge>
                @endforeach
            </div>

            <x-text>
                {{ Str::limit($specialist->description, 90) }}
            </x-text>
        </x-card>

        @endforeach
    </div>
</div>
