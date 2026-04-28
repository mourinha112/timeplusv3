<div>
    <x-heading>
        <x-title class="flex items-center gap-3">
            <x-carbon-calendar class="w-8 text-info" />
            Agenda por Profissional
        </x-title>
        <x-subtitle>
            Veja as disponibilidades agrupadas por especialista no período selecionado.
        </x-subtitle>
    </x-heading>

    <x-card class="mb-4">
        <x-card-body>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <x-form-group>
                    <x-label>Especialista</x-label>
                    <x-select wire:model.live="specialist_id">
                        <option value="">Todos</option>
                        @foreach ($specialists as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->crp ?? '—' }})</option>
                        @endforeach
                    </x-select>
                </x-form-group>

                <x-form-group>
                    <x-label>De</x-label>
                    <x-input type="date" wire:model.live="from" />
                </x-form-group>

                <x-form-group>
                    <x-label>Até</x-label>
                    <x-input type="date" wire:model.live="to" />
                </x-form-group>

                <div class="flex items-end">
                    <a wire:navigate href="{{ route('master.availability.index') }}"
                        class="btn btn-soft btn-info w-full">
                        Voltar à lista plana
                    </a>
                </div>
            </div>
        </x-card-body>
    </x-card>

    @if ($grouped->isEmpty())
        <div class="text-center text-base-content/60 py-10">
            Nenhuma disponibilidade no período.
        </div>
    @else
        <div class="space-y-4">
            @foreach ($grouped as $specialistId => $items)
                @php
                    $specialist = $items->first()->specialist;
                    $byDate = $items->groupBy(fn ($a) => \Carbon\Carbon::parse($a->available_date)->format('Y-m-d'));
                @endphp

                <details class="collapse collapse-arrow bg-base-100 shadow-sm" open>
                    <summary class="collapse-title text-base font-semibold flex items-center justify-between">
                        <div>
                            {{ $specialist?->name ?? 'Especialista #' . $specialistId }}
                            <span class="text-sm text-base-content/60 ml-2">
                                CRP: {{ $specialist?->crp ?? '—' }}
                            </span>
                        </div>
                        <span class="badge badge-info">{{ $items->count() }} slots</span>
                    </summary>

                    <div class="collapse-content">
                        @foreach ($byDate as $date => $slots)
                            <div class="mt-3">
                                <div class="text-sm font-medium text-base-content/80 mb-1">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('D, d/m/Y') }}
                                </div>
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($slots as $slot)
                                        <span class="badge badge-success">
                                            {{ \Carbon\Carbon::parse($slot->available_time)->format('H:i') }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </details>
            @endforeach
        </div>
    @endif
</div>
