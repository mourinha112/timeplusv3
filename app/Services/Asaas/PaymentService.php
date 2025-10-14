<?php

namespace App\Services\Asaas;

use App\Exceptions\AsaasException;

class PaymentService extends AsaasBaseService
{
    /**
     * Realiza o pagamento da cobrança com cartão de crédito.
     *
     * @param array $creditCardData
     * @return array
     * @throws AsaasException
     */
    public function payWithCreditCard(array $creditCardData): array
    {
        $data = [
            'creditCard' => [
                'holderName'  => $creditCardData['holder_name'],
                'number'      => $creditCardData['number'],
                'expiryMonth' => $creditCardData['expiry_month'],
                'expiryYear'  => $creditCardData['expiry_year'],
                'ccv'         => $creditCardData['ccv'],
            ],
        ];

        return $this->post("/payments/{$creditCardData['payment_id']}/payWithCreditCard", $data);
    }

    /**
     * Gera o código PIX para uma cobrança específica.
     *
     * @param string $chargeId
     * @return array
     * @throws AsaasException
     */
    public function pixQrCode(string $chargeId): array
    {
        if (empty($chargeId)) {
            throw new AsaasException('ID da cobrança é obrigatório.');
        }

        return $this->get("/payments/{$chargeId}/pixQrCode");
    }
}
