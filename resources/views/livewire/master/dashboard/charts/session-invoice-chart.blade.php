@if ($hasData)
    <div wire:ignore class="chart-container">
        <x-chartjs-component :chart="$chart" />
    </div>
@else
    <div class="flex items-center justify-center h-[200px] bg-base-200 rounded-lg">
        <div class="text-center">
            <x-carbon-chart-average class="h-12 w-12 mx-auto mb-2 text-base-content/30" />
            <p class="text-base-content/50 text-sm">Nenhum agendamento nos últimos 15 dias</p>
        </div>
    </div>
@endif
