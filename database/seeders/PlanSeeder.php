<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Planos oficiais dos funcionários (assinatura mensal recorrente).
     * Idempotente: pode rodar em produção sem duplicar registros.
     */
    public function run(): void
    {
        $now = now();

        $plans = [
            [
                'name'         => '1 Consulta Mensal',
                'description'  => '1 consulta por mês para funcionários ativos.',
                'price'        => 30.00,
                'max_sessions' => 1,
            ],
            [
                'name'         => '2 Consultas Mensais',
                'description'  => '2 consultas por mês para funcionários ativos.',
                'price'        => 60.00,
                'max_sessions' => 2,
            ],
            [
                'name'         => '4 Consultas Mensais',
                'description'  => '4 consultas por mês para funcionários ativos.',
                'price'        => 120.00,
                'max_sessions' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            DB::table('plans')->updateOrInsert(
                ['name' => $plan['name']],
                $plan + [
                    'discount_percentage' => 0,
                    'duration_days'       => 30,
                    'billing_cycle'       => 'monthly',
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ]
            );
        }
    }
}
