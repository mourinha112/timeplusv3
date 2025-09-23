<?php

namespace App\Livewire\Master\Dashboard\Charts;

use App\Models\{Payment, Plan};
use Carbon\{Carbon, CarbonPeriod};
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Livewire\Component;

class PlansInvoiceChart extends Component
{
    public function render()
    {
        $start  = Carbon::parse(Payment::min("created_at")) ?? Carbon::now()->subYear();
        $end    = Carbon::now();
        $period = CarbonPeriod::create($start, "1 month", $end);

        $planPaymentsPerMonth = collect($period)->map(function ($date) {
            $startDate = $date->copy()->startOfMonth();
            $endDate   = $date->copy()->endOfMonth();

            return [
                "count" => Payment::where('payable_type', Plan::class)
                    ->whereBetween("created_at", [$startDate, $endDate])
                    ->where('status', 'paid')
                    ->count(),
                "month" => $endDate->format("Y-m-d"),
            ];
        });

        $data   = $planPaymentsPerMonth->pluck("count")->toArray();
        $labels = $planPaymentsPerMonth->pluck("month")->toArray();

        $chart = Chartjs::build()
            ->name("PlansInvoiceChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label"           => "Vendas de Planos",
                    "backgroundColor" => "#00bafe",
                    // "borderColor" => "#ccc",
                    "data" => $data,
                    "fill" => true,

                ],
            ])
            ->options([
                'responsive'          => true,
                'maintainAspectRatio' => false,
                'scales'              => [
                    'x' => [
                        'type' => 'time',
                        'time' => [
                            'unit' => 'month',
                        ],
                        'min' => $start->format("Y-m-d"),
                    ],
                    'y' => [
                        'beginAtZero' => true,
                    ],
                ],
                'plugins' => [
                    'title' => [
                        'display' => true,
                        // 'text' => 'Vendas de Planos por Mês'
                    ],
                ],
            ]);

        return view('livewire.master.dashboard.charts.plans-invoice-chart', compact('chart'));
    }
}
