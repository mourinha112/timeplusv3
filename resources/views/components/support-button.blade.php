@php
    $whatsapp = config('support.whatsapp');
    $email    = config('support.email');
    $hasAny   = !empty($whatsapp) || !empty($email);
@endphp

@if ($hasAny)
    <div x-data="{ open: false }" class="fixed bottom-5 right-5 z-50">
        <div x-show="open" x-transition
            class="mb-3 bg-base-100 shadow-2xl rounded-xl p-4 w-72 border border-base-300"
            x-cloak style="display: none;">
            <div class="font-semibold text-base-content mb-2">Precisa de ajuda?</div>
            <p class="text-sm text-base-content/70 mb-3">
                Fale com a equipe TimePlus pelos canais abaixo.
            </p>
            <div class="space-y-2">
                @if ($whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $whatsapp) }}" target="_blank"
                        rel="noopener" class="btn btn-success btn-sm w-full">
                        WhatsApp
                    </a>
                @endif
                @if ($email)
                    <a href="mailto:{{ $email }}" class="btn btn-info btn-sm w-full">
                        E-mail: {{ $email }}
                    </a>
                @endif
            </div>
        </div>

        <button type="button" @click="open = !open"
            class="btn btn-circle btn-primary shadow-xl"
            :title="open ? 'Fechar' : 'Suporte'">
            <span x-show="!open">
                <x-carbon-help class="w-6 h-6" />
            </span>
            <span x-show="open" x-cloak>
                <x-carbon-close class="w-6 h-6" />
            </span>
        </button>
    </div>
@endif
