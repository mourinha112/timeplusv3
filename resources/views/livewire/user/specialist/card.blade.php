<!-- Coluna 1: Card do Especialista -->
<x-card class="shadow-lg border border-gray-200 rounded-xl relative p-6">
    <!-- Botão de Favorito (Ainda n necessário)-->
    <div class="absolute top-4 right-4">
        <button class="btn btn-circle btn-xs bg-transparent border-none transition-all" wire:click="favorite">
            @if ($favorited)
                <x-carbon-favorite-filled class="w-5 h-5 text-error" />
            @else
                <x-carbon-favorite class="w-5 h-5 text-error" />
            @endif
        </button>
    </div>

    <div class="flex items-start space-x-3 mb-4">
        <!-- Avatar -->
        @if ($specialist->avatar)
            <img src="{{ asset('storage/' . $specialist->avatar) }}" class="w-10 h-10 rounded-full" />
        @else
            <img src="{{ asset('images/avatar.png') }}" class="w-10 h-10 rounded-full" />
        @endif

        <!-- Conteúdo -->
        <div class="flex flex-col space-y-2">
            <!-- Header Info -->
            <div class="space-y-1">
                <h3 class="text-xl font-extrabold text-base-content">{{ $specialist->name }}</h3>
                <p class="text-xs text-base-content/80">
                    {{ $specialist->specialty?->name ?? 'Especialidade não informada' }}</p>
                <p class="text-xs text-base-content/50">
                    @if ($specialist->year_started_acting)
                        {{ now()->year - $specialist->year_started_acting }} anos de experiência
                    @else
                        Experiência não informada
                    @endif
                </p>
                <p class="text-xs text-base-content/50">CRM/CRP: {{ $specialist->crp ?? 'Não informado' }}</p>

                <a href="{{ route('user.specialist.show', $specialist) }}" class="btn btn-sm btn-dash mt-2">
                    Mais sobre
                    <x-carbon-add-filled class="w-5" />
                </a>
            </div>
        </div>
    </div>

    <!-- Tags de Especialidades -->
    <div class="flex flex-wrap gap-2 mb-4">
        @if (!empty($specialist->reasons))
            @foreach ($specialist->reasons as $reason)
                <div class="badge badge-soft badge-sm badge-info">{{ $reason->name }}</div>
            @endforeach
        @endif
    </div>

    <!-- Descrição -->
    <div class="mb-6">
        <p class="text-xs text-base-content/70 break-words break-all"
            style="word-break: break-word; overflow-wrap: anywhere;">
            {{ $specialist->description }}
        </p>
    </div>

    <!-- Informações de Avaliação e Atendimentos -->
    <div class="flex items-center gap-6 mb-1">
        <div class="flex items-center justify-center gap-1">
            <x-carbon-star-filled class="w-5 text-info" />
            <span class="text-xs font-extrabold text-base-content/70">5.0</span>
            <span class="text-xs text-base-content/70">({{ $this->reviews }} avaliações)</span>
        </div>
        <div class="flex items-center justify-center gap-1">
            <x-carbon-user-feedback class="w-5 text-info" />
            <span class="text-xs font-extrabold text-base-content/70">{{ $this->appointments }}</span>
            <span class="text-xs font-base text-base-content/70">atendimentos</span>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Footer com Sessão e Preço -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <x-carbon-time class="w-5 text-base-content/70" />
            <span class="text-xs text-base-content/70">Sessão 50 min</span>
        </div>
        <div class="badge badge-info badge-soft font-bold">R$
            {{ number_format($specialist->appointment_value, 2, ',', '.') }}</div>
    </div>
</x-card>
