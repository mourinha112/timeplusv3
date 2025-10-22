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
        // Últimos 15 dias
        $start = Carbon::now()->subDays(14)->startOfDay();
        $end   = Carbon::now()->endOfDay();

        // Criar período diário
        $period = CarbonPeriod::create($start, '1 day', $end);

        $appointmentsPerMonth = collect($period)->map(function ($date) {
            $startOfDay = $date->copy()->startOfDay();
            $endOfDay   = $date->copy()->endOfDay();

            return [
                "count" => Appointment::whereBetween("created_at", [$startOfDay, $endOfDay])->count(),
                "day"   => $date->format("d/m"),
            ];
        });

        $data   = $appointmentsPerMonth->pluck("count")->toArray();
        $labels = $appointmentsPerMonth->pluck("day")->toArray();

        // Verificar se há dados reais
        $hasData = array_sum($data) > 0;

        $chart = Chartjs::build()
            ->name("SessionInvoiceChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($labels)
            ->datasets([
                [
                    "label"           => "Vendas de Sessões",
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
                        // 'text' => 'Vendas de Sessões por Mês'
                    ],
                ],
            ]);

        return view('livewire.master.dashboard.charts.session-invoice-chart', compact('chart', 'hasData'));
    }
}
