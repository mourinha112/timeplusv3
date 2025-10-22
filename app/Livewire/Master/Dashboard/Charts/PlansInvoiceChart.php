<?php

namespace App\Livewire\Master\Dashboard\Charts;

use App\Models\{Payment, Subscribe};
use Carbon\{Carbon, CarbonPeriod};
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Livewire\Component;

class PlansInvoiceChart extends Component
{
    public function render()
    {
        // Últimos 15 dias
        $start = Carbon::now()->subDays(14)->startOfDay();
        $end   = Carbon::now()->endOfDay();

        // Criar período diário
        $period = CarbonPeriod::create($start, '1 day', $end);

        $planPaymentsPerMonth = collect($period)->map(function ($date) {
            $startOfDay = $date->copy()->startOfDay();
            $endOfDay   = $date->copy()->endOfDay();

            return [
                "count" => Payment::where('payable_type', Subscribe::class)
                    ->where('status', 'paid')
                    ->whereBetween('paid_at', [$startOfDay, $endOfDay])
                    ->count(),
                "day" => $date->format("d/m"),
            ];
        });

        $data   = $planPaymentsPerMonth->pluck("count")->toArray();
        $labels = $planPaymentsPerMonth->pluck("day")->toArray();

        // Verificar se há dados reais
        $hasData = array_sum($data) > 0;

        $chart = Chartjs::build()
            ->name("PlansInvoiceChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label"           => "Vendas de Planos",
                    "backgroundColor" => "#00bafe",
                    "data"            => $data,
                    "fill"            => true,
                    "tension"         => 0.4,
                ],
            ])
            ->options([
                'responsive'          => true,
                'maintainAspectRatio' => false,
                'scales'              => [
                    'x' => [
                        'display' => true,
                        'offset'  => false,
                        'ticks'   => [
                            'autoSkip'    => false,
                            'maxRotation' => 0,
                            'minRotation' => 0,
                        ],
                    ],
                    'y' => [
                        'beginAtZero' => true,
                        'ticks'       => [
                            'stepSize'  => 1,
                            'precision' => 0,
                        ],
                    ],
                ],
                'plugins' => [
                    'title' => [
                        'display' => true,
                        // 'text' => 'Vendas de Planos por Mês'
                    ],
                ],
            ]);

        return view('livewire.master.dashboard.charts.plans-invoice-chart', compact('chart', 'hasData'));
    }
}
