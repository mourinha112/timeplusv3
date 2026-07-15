<?php

namespace App\Services\Credit;

use App\Models\{Appointment, Company, CompanyCreditBalance, CompanyCreditUsage, CompanyPlan, User};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Créditos de sessão da empresa (modelo credit_pack).
 *
 * Regras de negócio:
 * - Crédito mensal (bucket "monthly") vale apenas dentro do mês e esgota no fim dele.
 * - Créditos extras comprados (bucket "extra") valem 6 meses.
 * - Consumo é FIFO com prioridade: mensal primeiro, depois extras (vencendo antes primeiro).
 */
class CompanyCreditService
{
    public const EXTRA_CREDIT_TTL_MONTHS = 6;

    /**
     * Garante o bucket mensal do plano para o mês informado (default: mês atual).
     * Idempotente: se o bucket do mês já existe, ajusta o total pela diferença
     * (ex.: empresa aumentou os créditos mensais do plano).
     */
    public function grantMonthly(CompanyPlan $plan, ?Carbon $month = null): CompanyCreditBalance
    {
        $month    = ($month ?? now())->copy()->startOfMonth();
        $monthEnd = $month->copy()->endOfMonth();
        $credits  = (int) $plan->monthly_credits;

        return DB::transaction(function () use ($plan, $month, $monthEnd, $credits) {
            $balance = CompanyCreditBalance::query()
                ->where('company_id', $plan->company_id)
                ->where('company_plan_id', $plan->id)
                ->where('bucket', CompanyCreditBalance::BUCKET_MONTHLY)
                ->whereDate('valid_from', $month->toDateString())
                ->lockForUpdate()
                ->first();

            if (!$balance) {
                return CompanyCreditBalance::create([
                    'company_id'        => $plan->company_id,
                    'company_plan_id'   => $plan->id,
                    'bucket'            => CompanyCreditBalance::BUCKET_MONTHLY,
                    'credits_total'     => $credits,
                    'credits_remaining' => $credits,
                    'valid_from'        => $month->toDateString(),
                    'valid_until'       => $monthEnd->toDateString(),
                ]);
            }

            $delta = $credits - (int) $balance->credits_total;

            if ($delta !== 0) {
                $balance->credits_total     = $credits;
                $balance->credits_remaining = max(0, (int) $balance->credits_remaining + $delta);
                $balance->save();
            }

            return $balance;
        });
    }

    /**
     * Concede créditos extras (comprados) com validade de 6 meses.
     */
    public function grantExtra(Company $company, int $credits, ?CompanyPlan $plan = null): CompanyCreditBalance
    {
        return CompanyCreditBalance::create([
            'company_id'        => $company->id,
            'company_plan_id'   => $plan?->id,
            'bucket'            => CompanyCreditBalance::BUCKET_EXTRA,
            'credits_total'     => $credits,
            'credits_remaining' => $credits,
            'valid_from'        => now()->toDateString(),
            'valid_until'       => now()->addMonths(self::EXTRA_CREDIT_TTL_MONTHS)->toDateString(),
        ]);
    }

    protected function usableQuery(Company $company): Builder
    {
        return CompanyCreditBalance::query()
            ->where('company_id', $company->id)
            ->usable();
    }

    /** Total de créditos disponíveis (mensal + extras válidos). */
    public function balance(Company $company): int
    {
        return (int) $this->usableQuery($company)->sum('credits_remaining');
    }

    /** Créditos mensais restantes no mês corrente. */
    public function monthlyRemaining(Company $company): int
    {
        return (int) $this->usableQuery($company)
            ->where('bucket', CompanyCreditBalance::BUCKET_MONTHLY)
            ->sum('credits_remaining');
    }

    /** Créditos extras (comprados) restantes e válidos. */
    public function extraRemaining(Company $company): int
    {
        return (int) $this->usableQuery($company)
            ->where('bucket', CompanyCreditBalance::BUCKET_EXTRA)
            ->sum('credits_remaining');
    }

    /**
     * Consome créditos da empresa em FIFO: buckets mensais primeiro, depois
     * extras (os que vencem antes primeiro). Retorna a quantidade efetivamente
     * consumida (menor que $credits se o saldo for insuficiente).
     */
    public function consume(Company $company, int $credits = 1, ?User $user = null, ?Appointment $appointment = null): int
    {
        if ($credits <= 0) {
            return 0;
        }

        return DB::transaction(function () use ($company, $credits, $user, $appointment) {
            $remainingToConsume = $credits;

            $balances = CompanyCreditBalance::query()
                ->where('company_id', $company->id)
                ->where('credits_remaining', '>', 0)
                ->whereDate('valid_from', '<=', now())
                ->whereDate('valid_until', '>=', now())
                ->orderByRaw("CASE WHEN bucket = 'monthly' THEN 0 ELSE 1 END")
                ->orderBy('valid_until')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            foreach ($balances as $balance) {
                if ($remainingToConsume <= 0) {
                    break;
                }

                $use = min((int) $balance->credits_remaining, $remainingToConsume);

                CompanyCreditUsage::create([
                    'company_credit_balance_id' => $balance->id,
                    'appointment_id'            => $appointment?->id,
                    'user_id'                   => $user?->id,
                    'credits_used'              => $use,
                ]);

                $balance->credits_remaining = (int) $balance->credits_remaining - $use;
                $balance->save();

                $remainingToConsume -= $use;
            }

            return $credits - $remainingToConsume;
        });
    }

    /**
     * Devolve à empresa os créditos consumidos por um agendamento (cancelamento).
     * Retorna o total devolvido.
     */
    public function refundForAppointment(Appointment $appointment): int
    {
        return DB::transaction(function () use ($appointment) {
            $usages = CompanyCreditUsage::query()
                ->where('appointment_id', $appointment->id)
                ->lockForUpdate()
                ->get();

            $refunded = 0;

            foreach ($usages as $usage) {
                $balance = CompanyCreditBalance::query()
                    ->whereKey($usage->company_credit_balance_id)
                    ->lockForUpdate()
                    ->first();

                if ($balance) {
                    $balance->credits_remaining = (int) $balance->credits_remaining + (int) $usage->credits_used;
                    $balance->save();

                    $refunded += (int) $usage->credits_used;
                }

                $usage->delete();
            }

            return $refunded;
        });
    }

    /** Créditos consumidos por um funcionário no mês (para o limite universal). */
    public function usedByUserInMonth(Company $company, User $user, ?Carbon $month = null): int
    {
        $month = ($month ?? now())->copy();

        return (int) CompanyCreditUsage::query()
            ->whereHas('balance', fn ($q) => $q->where('company_id', $company->id))
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->sum('credits_used');
    }

    /**
     * Quantos créditos o funcionário ainda pode usar neste mês, considerando o
     * limite universal do plano e o saldo disponível da empresa.
     * Retorna null quando o plano não define limite (limitado só pelo saldo).
     */
    public function employeeRemainingThisMonth(Company $company, CompanyPlan $plan, User $user): ?int
    {
        $limit = $plan->credits_per_employee;

        if ($limit === null) {
            return null;
        }

        return max(0, (int) $limit - $this->usedByUserInMonth($company, $user));
    }
}
