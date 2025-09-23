<?php

namespace App\Livewire\Master\Dashboard\Charts;

use App\Models\Appointment;
use Carbon\{Carbon, CarbonPeriod};
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Livewire\Component;

class SessionInvoiceChart extends Component
{
    public function render()
    {
        $start  = Carbon::parse(Appointment::min("created_at")) ?? Carbon::now()->subYear();
        $end    = Carbon::now();
        $period = CarbonPeriod::create($start, "1 month", $end);

        $appointmentsPerMonth = collect($period)->map(function ($date) {
            $startDate = $date->copy()->startOfMonth();
            $endDate   = $date->copy()->endOfMonth();

            return [
                "count" => Appointment::whereBetween("created_at", [$startDate, $endDate])->count(),
                "month" => $endDate->format("Y-m-d"),
            ];
        });

        $data   = $appointmentsPerMonth->pluck("count")->toArray();
        $labels = $appointmentsPerMonth->pluck("month")->toArray();

        $chart = Chartjs::build()
            ->name("SessionInvoiceChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label"           => "Vendas de Sessões",
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
                        // 'text' => 'Vendas de Sessões por Mês'
                    ],
                ],
            ]);

        return view('livewire.master.dashboard.charts.session-invoice-chart', compact('chart'));
    }
}
