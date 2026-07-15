<?php

namespace App\Console\Commands;

use App\Models\CompanyPlan;
use App\Services\Billing\CompanyBillingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CompanyMonthlyBillingCommand extends Command
{
    protected $signature = 'company:monthly-billing';

    protected $description = 'Gera as faturas mensais das empresas (per_employee e credit_pack) e renova os créditos mensais';

    public function handle(CompanyBillingService $billing): int
    {
        $plans = CompanyPlan::query()
            ->where('is_active', true)
            ->where('billing_status', 'active')
            ->with('company')
            ->get();

        $generated = 0;

        foreach ($plans as $plan) {
            try {
                $payment = $billing->generateMonthlyCharge($plan);

                if ($payment) {
                    $generated++;
                }
            } catch (\Exception $e) {
                Log::error('Erro ao gerar fatura mensal da empresa', [
                    'company_plan_id' => $plan->id,
                    'company_id'      => $plan->company_id,
                    'message'         => $e->getMessage(),
                ]);

                $this->error("Plano #{$plan->id} ({$plan->name}): {$e->getMessage()}");
            }
        }

        $this->info("Faturas geradas/garantidas: {$generated} de {$plans->count()} plano(s) ativo(s).");

        return self::SUCCESS;
    }
}
