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

    {{-- ini:Motivos --}}
    @if(!empty($specialist->reasons))
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach($specialist->reasons as $reason)
                <span class="badge badge-info badge-outline">{{ $reason->name }}</span>
            @endforeach
        </div>
    @endif
    {{-- end:Motivos --}}

    {{-- ini:Descrição --}}
    <p class="text-base-content/70">
        {{ $specialist->description }}
    </p>
    {{-- end:Descrição --}}

    {{-- ini:Avaliações & Atendimentos --}}
    <div class="flex gap-6 mt-5 items-end">
        <div class="flex flex-col text-base-content/70 text-xs gap-2 items-center">
            <div class="rating rating-xs">
                <input type="radio" name="rating-1" class="mask mask-star" aria-label="1 star" disabled/>
                <input type="radio" name="rating-1" class="mask mask-star" aria-label="2 star" checked="checked" disabled/>
                <input type="radio" name="rating-1" class="mask mask-star" aria-label="3 star" disabled/>
                <input type="radio" name="rating-1" class="mask mask-star" aria-label="4 star" disabled/>
                <input type="radio" name="rating-1" class="mask mask-star" aria-label="5 star" disabled/>
            </div>
            <span>2,0 (12 comentários)</span>
        </div>
        <span class="text-base-content/70 text-xs">129 atendimentos</span>
    </div>
    {{-- end:Avaliações & Atendimentos --}}

    <hr class="my-6 text-gray-100">

    <div class="flex justify-between items-center">
        <p class="text-base-content/70 text-sm">Sessão 40 min</p>
        <span class="text-base-content text-lg font-bold">R$ 100,00</span>
    </div>
</x-card>
