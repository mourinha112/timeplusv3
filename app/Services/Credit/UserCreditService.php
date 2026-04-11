<?php

namespace App\Services\Credit;

use App\Models\{Appointment, User, UserCredit, UserCreditUsage};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserCreditService
{
    /**
     * Validade padrão de créditos avulsos (cancelamentos, refunds): 6 meses.
     */
    public const DEFAULT_CREDIT_TTL_MONTHS = 6;

    /**
     * Concede crédito ao usuário a partir de uma origem (ex: cancelamento de
     * sessão paga). Retorna o crédito criado.
     */
    public function grant(
        User $user,
        float $amount,
        string $source = UserCredit::SOURCE_CANCELLATION,
        ?Model $reference = null,
        ?Carbon $expiresAt = null,
        ?string $description = null
    ): UserCredit {
        $amount = round($amount, 2);

        return UserCredit::create([
            'user_id'               => $user->id,
            'amount'                => $amount,
            'amount_remaining'      => $amount,
            'source'                => $source,
            'source_reference_type' => $reference ? $reference::class : null,
            'source_reference_id'   => $reference?->getKey(),
            'expires_at'            => $expiresAt ?? now()->addMonths(self::DEFAULT_CREDIT_TTL_MONTHS),
            'description'           => $description,
        ]);
    }

    /**
     * Saldo total disponível (somente créditos não vencidos e com saldo).
     */
    public function balance(User $user): float
    {
        return (float) UserCredit::query()
            ->where('user_id', $user->id)
            ->where('amount_remaining', '>', 0)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->sum('amount_remaining');
    }

    /**
     * Consome créditos disponíveis em FIFO (mais antigo primeiro) até o valor
     * desejado, opcionalmente associando o consumo a um agendamento.
     *
     * Retorna o valor efetivamente consumido (pode ser menor que $amount se
     * o saldo for insuficiente).
     */
    public function consume(User $user, float $amount, ?Appointment $appointment = null): float
    {
        $amount = round($amount, 2);

        if ($amount <= 0) {
            return 0.0;
        }

        return DB::transaction(function () use ($user, $amount, $appointment) {
            $remainingToConsume = $amount;

            $credits = UserCredit::query()
                ->where('user_id', $user->id)
                ->where('amount_remaining', '>', 0)
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                })
                ->orderByRaw('expires_at IS NULL, expires_at ASC')
                ->orderBy('created_at')
                ->lockForUpdate()
                ->get();

            foreach ($credits as $credit) {
                if ($remainingToConsume <= 0) {
                    break;
                }

                $use = min((float) $credit->amount_remaining, $remainingToConsume);

                UserCreditUsage::create([
                    'user_credit_id' => $credit->id,
                    'appointment_id' => $appointment?->id,
                    'amount_used'    => $use,
                ]);

                $credit->amount_remaining = round($credit->amount_remaining - $use, 2);

                if ($credit->amount_remaining <= 0) {
                    $credit->consumed_at = now();
                }

                $credit->save();

                $remainingToConsume = round($remainingToConsume - $use, 2);
            }

            return round($amount - $remainingToConsume, 2);
        });
    }
}
