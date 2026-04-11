<?php

namespace App\Services\Payment;

use App\Models\Specialist;

class PayoutCalculator
{
    public const PARTICULAR_PLATFORM_FEE_PERCENT = 20.0;

    /**
     * Calcula a divisão entre o que o especialista recebe e o que fica
     * com a plataforma para uma sessão, baseado na modalidade de atendimento.
     *
     * Particular: especialista fica com 80%, plataforma fica com 20%.
     * TimePlus:   especialista fica com TIMEPLUS_SESSION_VALUE (R$30 fixo);
     *             plataforma fica com (total - R$30) — geralmente o que a
     *             empresa pagou no plano além do repasse ao profissional.
     *
     * @return array{total:float, specialist_amount:float, platform_amount:float, mode:string}
     */
    public function calculate(string $mode, float $totalAmount): array
    {
        $totalAmount = round($totalAmount, 2);

        if ($mode === 'timeplus') {
            $specialistAmount = Specialist::TIMEPLUS_SESSION_VALUE;
            $platformAmount   = max(0.0, round($totalAmount - $specialistAmount, 2));
        } else {
            $platformAmount   = round($totalAmount * (self::PARTICULAR_PLATFORM_FEE_PERCENT / 100), 2);
            $specialistAmount = round($totalAmount - $platformAmount, 2);
        }

        return [
            'total'             => $totalAmount,
            'specialist_amount' => $specialistAmount,
            'platform_amount'   => $platformAmount,
            'mode'              => $mode,
        ];
    }
}
