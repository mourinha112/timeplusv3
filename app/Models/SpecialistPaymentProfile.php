<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialistPaymentProfile extends Model
{
    protected $fillable = [
        'specialist_id',
        'holder_name',
        'holder_cpf',
        'payment_type',
        'pix_key_type',
        'pix_key',
        'bank_code',
        'bank_name',
        'agency',
        'account_number',
        'account_digit',
        'account_type',
        'gateway_account_id',
        'gateway_wallet_id',
        'is_verified',
        'verified_at',
        'platform_fee_percentage',
    ];

    protected $casts = [
        'is_verified'             => 'boolean',
        'verified_at'             => 'datetime',
        'platform_fee_percentage' => 'decimal:2',
    ];

    /**
     * Relacionamento com Specialist
     */
    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class);
    }

    /**
     * Verifica se está configurado para PIX
     */
    public function isPixPayment(): bool
    {
        return $this->payment_type === 'pix';
    }

    /**
     * Verifica se está configurado para conta bancária
     */
    public function isBankAccountPayment(): bool
    {
        return $this->payment_type === 'bank_account';
    }

    /**
     * Retorna a chave PIX formatada para exibição (parcialmente oculta)
     */
    public function getMaskedPixKeyAttribute(): ?string
    {
        if (!$this->pix_key) {
            return null;
        }

        $key = $this->pix_key;
        $length = strlen($key);

        if ($length <= 6) {
            return $key;
        }

        $visible = 4;
        $hidden = $length - ($visible * 2);

        return substr($key, 0, $visible) . str_repeat('*', $hidden) . substr($key, -$visible);
    }

    /**
     * Retorna a conta bancária formatada para exibição
     */
    public function getFormattedBankAccountAttribute(): ?string
    {
        if (!$this->account_number) {
            return null;
        }

        return "{$this->bank_name} - Ag: {$this->agency} / Cc: {$this->account_number}-{$this->account_digit}";
    }

    /**
     * Calcula o valor do repasse (valor total - taxa da plataforma)
     */
    public function calculatePayout(float $totalAmount): array
    {
        $platformFee = ($totalAmount * $this->platform_fee_percentage) / 100;
        $specialistAmount = $totalAmount - $platformFee;

        return [
            'total_amount'      => round($totalAmount, 2),
            'platform_fee'      => round($platformFee, 2),
            'specialist_amount' => round($specialistAmount, 2),
            'fee_percentage'    => $this->platform_fee_percentage,
        ];
    }
}
