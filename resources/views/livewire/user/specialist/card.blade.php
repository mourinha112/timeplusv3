<!-- Coluna 1: Card do Especialista -->
<div class="card bg-base-100 shadow-lg border border-gray-200 rounded-xl relative p-6">

    <!-- Botão de Favorito (Ainda n necessário)-->
    <div class="absolute top-4 right-4">
        <button class="btn btn-circle btn-xs bg-transparent border-none transition-all" wire:click="favorite">
            @if($favorited)
            <x-carbon-favorite-filled class="w-5 h-5 text-error" />
            @else
            <x-carbon-favorite class="w-5 h-5 text-error" />
            @endif
        </button>
    </div>

    <div class="flex items-start space-x-3 mb-4">
        <!-- Avatar -->
        <div class="avatar">
            <div class="w-22 rounded-full">
                <img src="{{ asset('images/avatar.png') }}" alt="{{ $specialist->name }}" />
            </div>
        </div>

        <!-- Conteúdo -->
        <div class="flex flex-col space-y-2">
            <!-- Header Info -->
            <div class="space-y-1">
                <h3 class="text-xl font-extrabold text-base-content">{{ $specialist->name }}</h3>
                <p class="text-xs text-base-content/80">{{ $specialist->specialty->name }}</p>
                <p class="text-xs text-base-content/50">{{ now()->year - $specialist->year_started_acting }} anos de experiência</p>
                <p class="text-xs text-base-content/50">CRM: {{ $specialist->crp }}</p>

                <a href="#" class="btn btn-sm btn-dash mt-2">
                    Mais sobre
                    <x-carbon-add-filled class="w-5" />
                </a>
            </div>
        </div>
    </div>

    <!-- Tags de Especialidades -->
    <div class="flex flex-wrap gap-2 mb-4">
        @if(!empty($specialist->reasons))
        @foreach($specialist->reasons as $reason)
        <div class="badge badge-soft badge-sm badge-info">{{ $reason->name }}</div>
        @endforeach
        @endif
    </div>

    <!-- Descrição -->
    <div class="mb-6">
        <p class="text-xs text-base-content/70">
            {{ $specialist->description }}
        </p>
    </div>

    <!-- Informações de Avaliação e Atendimentos -->
    <div class="flex items-center gap-6 mb-1">
        <div class="flex items-center justify-center gap-1">
            <x-carbon-star-filled class="w-5 text-info" />
            <span class="text-xs font-extrabold text-base-content/70">4.5</span>
            <span class="text-xs text-base-content/70">(10 comentários)</span>
        </div>
        <div class="flex items-center justify-center gap-1">
            <x-carbon-user-feedback class="w-5 text-info" />
            <span class="text-xs font-extrabold text-base-content/70">20</span>
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
        <div class="badge badge-info badge-soft font-bold">R$ {{ number_format($specialist->appointment_value, 2, ',', '.') }}</div>
    </div>
</div>
