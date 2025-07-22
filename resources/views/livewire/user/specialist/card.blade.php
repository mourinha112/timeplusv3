<x-card class="!p-6">
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
    </div>

    @if(!empty($specialist->reasons))
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach($specialist->reasons as $reason)
        <span class="badge badge-info badge-outline">{{ $reason->name }}</span>
        @endforeach
    </div>
    @endif

    {{-- ini:Descrição --}}
    <p class="text-base-content/70">
        {{ $specialist->description }}
        jifsijfjiajifjisa
    </p>
    {{-- end:Descrição --}}

    <div class="flex gap-6 mt-5">
        <span class="text-gray-500 text-xs"><strong>5</strong> (12 comentários)</span>
        <span class="text-gray-500 text-xs"><strong>129</strong> atendimentos</span>
    </div>
    <hr class="my-6 text-gray-100">
    <div class="flex justify-between items-center">
        <x-text>Sessão 40 min</x-text>
        {{-- <x-badge>R$ 100,00</x-badge> --}}
    </div>
</x-card>
