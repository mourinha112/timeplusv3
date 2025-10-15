<?php

namespace App\Services\Asaas;

use App\Exceptions\AsaasException;

class PaymentService extends AsaasBaseService
{
    /**
     * Cria uma cobrança e processa pagamento com cartão de crédito em uma única chamada.
     * Método de compatibilidade com formato anterior (Pagarme).
     *
     * @param array $paymentData
     * @return array
     * @throws AsaasException
     */
    public function createWithCreditCard(array $paymentData): array
    {
        // Mapear campos para compatibilidade
        $expiryMonth = str_pad($paymentData['exp_month'] ?? $paymentData['expiry_month'], 2, '0', STR_PAD_LEFT);
        $expiryYear = $paymentData['exp_year'] ?? $paymentData['expiry_year'];

        // Garantir que o ano tenha 4 dígitos
        if (strlen($expiryYear) == 2) {
            $expiryYear = '20' . $expiryYear;
        }

        // Validar campos obrigatórios do creditCardHolderInfo
        if (empty($paymentData['email'])) {
            throw new AsaasException('Email do titular do cartão é obrigatório.');
        }

        if (empty($paymentData['document'])) {
            throw new AsaasException('CPF/CNPJ do titular do cartão é obrigatório.');
        }

        if (empty($paymentData['phone'])) {
            throw new AsaasException('Telefone do titular do cartão é obrigatório.');
        }

        $data = [
            'customer'      => $paymentData['customer_id'],
            'billingType'   => 'CREDIT_CARD',
            'value'         => $paymentData['amount'],
            'dueDate'       => now()->format('Y-m-d'),
            'description'   => $paymentData['description'] ?? 'Pagamento',
            'creditCard'    => [
                'holderName'  => $paymentData['holder_name'],
                'number'      => $paymentData['card_number'],
                'expiryMonth' => $expiryMonth,
                'expiryYear'  => $expiryYear,
                'ccv'         => $paymentData['cvv'],
            ],
            'creditCardHolderInfo' => [
                'name'           => $paymentData['holder_name'],
                'email'          => $paymentData['email'],
                'cpfCnpj'        => $paymentData['document'],
                'postalCode'     => '01310100', // CEP válido (Av. Paulista, SP) para testes
                'addressNumber'  => '1000',
                'phone'          => $paymentData['phone'],
            ],
        ];

        if (isset($paymentData['item_code'])) {
            $data['externalReference'] = $paymentData['item_code'];
        }

        $response = $this->post('/payments', $data);

        // Normalizar resposta para compatibilidade com formato esperado pelo código
        return [
            'id'       => $response['id'],
            'status'   => $this->mapAsaasStatusToPagarme($response['status']),
            'amount'   => $response['value'] * 100, // Converter para centavos
            'currency' => 'BRL',
            'charges'  => [
                [
                    'id'      => $response['id'],
                    'paid_at' => $response['confirmedDate'] ?? $response['paymentDate'] ?? now()->toISOString(),
                ],
            ],
            'items' => [
                [
                    'description' => $response['description'],
                ],
            ],
        ];
    }

    /**
     * Cria uma cobrança PIX.
     * Método de compatibilidade com formato anterior (Pagarme).
     *
     * @param array $paymentData
     * @return array
     * @throws AsaasException
     */
    public function createWithPix(array $paymentData): array
    {
        $data = [
            'customer'    => $paymentData['customer_id'],
            'billingType' => 'PIX',
            'value'       => $paymentData['amount'],
            'dueDate'     => now()->addHours(24)->format('Y-m-d'),
            'description' => $paymentData['description'] ?? 'Pagamento via PIX',
        ];

        if (isset($paymentData['item_code'])) {
            $data['externalReference'] = $paymentData['item_code'];
        }

        $response = $this->post('/payments', $data);

        // Buscar QR Code do PIX
        $pixData = $this->get("/payments/{$response['id']}/pixQrCode");

        return [
            'id'          => $response['id'],
            'status'      => $this->mapAsaasStatusToPagarme($response['status']),
            'amount'      => $response['value'] * 100, // Converter para centavos
            'currency'    => 'BRL',
            'pix_key'     => $pixData['payload'] ?? null,
            'pix_qr_code' => $pixData['encodedImage'] ?? null,
        ];
    }

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

    /**
     * Mapeia os status do Asaas para os status do Pagarme (compatibilidade).
     *
     * @param string $asaasStatus
     * @return string
     */
    private function mapAsaasStatusToPagarme(string $asaasStatus): string
    {
        $statusMap = [
            'PENDING'            => 'pending_payment',
            'RECEIVED'           => 'paid',
            'CONFIRMED'          => 'paid',
            'OVERDUE'            => 'pending_payment',
            'REFUNDED'           => 'refunded',
            'RECEIVED_IN_CASH'   => 'paid',
            'REFUND_REQUESTED'   => 'refunded',
            'CHARGEBACK_REQUESTED' => 'failed',
            'CHARGEBACK_DISPUTE' => 'failed',
            'AWAITING_CHARGEBACK_REVERSAL' => 'failed',
        ];

        return $statusMap[$asaasStatus] ?? 'pending';
    }
}
