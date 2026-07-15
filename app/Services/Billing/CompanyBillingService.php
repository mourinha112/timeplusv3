<?php

namespace App\Services\Billing;

use App\Facades\Asaas;
use App\Models\{Company, CompanyPlan, Payment};
use App\Services\Credit\CompanyCreditService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Faturamento mensal das empresas (PIX via Asaas).
 *
 * - per_employee: R$30 por funcionário ativo no 1º mês (repasse do corretor),
 *   R$60 por funcionário nos meses seguintes (fica com a plataforma).
 * - credit_pack: monthly_credits × R$30; o bucket mensal de créditos é criado
 *   junto com a fatura e expira no fim do mês.
 */
class CompanyBillingService
{
    public function __construct(protected CompanyCreditService $credits)
    {
    }

    /** Garante que a empresa existe como customer no Asaas. */
    public function ensureGatewayCustomer(Company $company): string
    {
        if ($company->gateway_customer_id) {
            return $company->gateway_customer_id;
        }

        $customer = Asaas::customer()->create([
            'name'     => $company->name,
            'email'    => $company->email,
            'document' => preg_replace('/[^0-9]/', '', (string) $company->cnpj),
            'phone'    => preg_replace('/[^0-9]/', '', (string) $company->phone),
            'code'     => 'company_' . $company->id,
        ]);

        $company->update(['gateway_customer_id' => $customer['id']]);

        return $customer['id'];
    }

    /** Valor da fatura do mês para o plano. */
    public function monthlyAmount(CompanyPlan $plan): float
    {
        if ($plan->isCreditPack()) {
            return round($plan->monthly_credits * (float) CompanyPlan::CREDIT_UNIT_PRICE, 2);
        }

        $activeEmployees = $plan->getActiveUsersCount();

        return round($activeEmployees * $plan->currentPerEmployeePrice(), 2);
    }

    /**
     * Gera a fatura mensal (PIX) do plano para o período informado.
     * Idempotente por período: não gera duas faturas para o mesmo mês.
     * Para credit_pack, garante também o bucket mensal de créditos.
     */
    public function generateMonthlyCharge(CompanyPlan $plan, ?Carbon $month = null): ?Payment
    {
        $month  = ($month ?? now())->copy()->startOfMonth();
        $period = $month->format('Y-m');

        if ($plan->isCreditPack()) {
            $this->credits->grantMonthly($plan, $month);
        }

        $existing = Payment::query()
            ->where('payable_type', Company::class)
            ->where('payable_id', $plan->company_id)
            ->where('metadata->type', 'company_monthly_billing')
            ->where('metadata->company_plan_id', $plan->id)
            ->where('metadata->period', $period)
            ->first();

        if ($existing) {
            return $existing;
        }

        $amount = $this->monthlyAmount($plan);

        if ($amount <= 0) {
            Log::info('Faturamento empresa: valor zero, fatura não gerada', [
                'company_plan_id' => $plan->id,
                'period'          => $period,
            ]);

            return null;
        }

        /** @var Company $company */
        $company      = $plan->company()->firstOrFail();
        $customerId   = $this->ensureGatewayCustomer($company);
        $isFirstMonth = $plan->isPerEmployee() && $plan->isInFirstMonth();

        $description = $plan->isCreditPack()
            ? "TimePlus {$period}: {$plan->monthly_credits} créditos mensais ({$plan->name})"
            : "TimePlus {$period}: " . $plan->getActiveUsersCount() . ' funcionário(s) ativo(s) × R$ '
                . number_format($plan->currentPerEmployeePrice(), 2, ',', '.') . " ({$plan->name})";

        $charge = Asaas::payment()->createWithPix([
            'customer_id' => $customerId,
            'amount'      => $amount,
            'description' => $description,
            'item_code'   => "company_plan_{$plan->id}_{$period}",
        ]);

        /** @var Payment $payment */
        $payment = $company->payments()->create([
            'gateway_order_id' => $charge['id'],
            'amount'           => $amount,
            'payment_method'   => 'pix',
            'status'           => 'pending_payment',
            'description'      => $description,
            'company_id'       => $company->id,
            'pix_key'          => $charge['pix_key'] ?? null,
            'pix_qr_code'      => $charge['pix_qr_code'] ?? null,
            'metadata'         => [
                'type'            => 'company_monthly_billing',
                'company_plan_id' => $plan->id,
                'billing_model'   => $plan->billing_model,
                'period'          => $period,
                'broker_month'    => $isFirstMonth,
            ],
        ]);

        $plan->update(['next_billing_date' => $month->copy()->addMonth()->startOfMonth()]);

        return $payment;
    }

    /**
     * Cria a cobrança PIX de compra de créditos extras. Os créditos só são
     * concedidos quando o pagamento é confirmado (webhook Asaas).
     */
    public function createExtraCreditPurchase(Company $company, int $credits, ?CompanyPlan $plan = null): Payment
    {
        $amount      = round($credits * (float) CompanyPlan::CREDIT_UNIT_PRICE, 2);
        $customerId  = $this->ensureGatewayCustomer($company);
        $description = "TimePlus: compra de {$credits} crédito(s) extra(s) (validade de 6 meses)";

        $charge = Asaas::payment()->createWithPix([
            'customer_id' => $customerId,
            'amount'      => $amount,
            'description' => $description,
            'item_code'   => "company_credits_{$company->id}_" . now()->format('YmdHis'),
        ]);

        /** @var Payment $payment */
        $payment = $company->payments()->create([
            'gateway_order_id' => $charge['id'],
            'amount'           => $amount,
            'payment_method'   => 'pix',
            'status'           => 'pending_payment',
            'description'      => $description,
            'company_id'       => $company->id,
            'pix_key'          => $charge['pix_key'] ?? null,
            'pix_qr_code'      => $charge['pix_qr_code'] ?? null,
            'metadata'         => [
                'type'            => 'company_credit_purchase',
                'credits'         => $credits,
                'company_plan_id' => $plan?->id,
            ],
        ]);

        return $payment;
    }
}
