<?php

namespace App\Services\Asaas;

use App\Exceptions\AsaasException;

class SubscriptionService extends AsaasBaseService
{
    private const CYCLE_MAP = [
        'monthly'   => 'MONTHLY',
        'quarterly' => 'QUARTERLY',
        'yearly'    => 'YEARLY',
    ];

    /**
     * Cria uma assinatura recorrente no Asaas (cobrança automática mensal/anual).
     *
     * Campos esperados em $data:
     *  - customer_id        (string, obrigatório) gateway_customer_id
     *  - amount             (float, obrigatório)  valor por ciclo
     *  - cycle              (string, obrigatório) monthly|quarterly|yearly
     *  - billing_type       (string, opcional)    CREDIT_CARD|PIX|BOLETO  (default: CREDIT_CARD)
     *  - description        (string, opcional)
     *  - next_due_date      (string Y-m-d, opcional, default: hoje)
     *  - external_reference (string, opcional)
     *  - end_date           (string Y-m-d, opcional)
     *  - credit_card        (array,  opcional)    quando billing_type=CREDIT_CARD
     *  - credit_card_holder_info (array, opcional)
     *
     * @throws AsaasException
     */
    public function create(array $data): array
    {
        if (empty($data['customer_id'])) {
            throw new AsaasException('customer_id é obrigatório para criar assinatura.');
        }

        if (empty($data['amount'])) {
            throw new AsaasException('amount é obrigatório para criar assinatura.');
        }

        $cycle = self::CYCLE_MAP[$data['cycle'] ?? 'monthly'] ?? 'MONTHLY';

        $payload = [
            'customer'    => $data['customer_id'],
            'billingType' => $data['billing_type'] ?? 'CREDIT_CARD',
            'value'       => round((float) $data['amount'], 2),
            'cycle'       => $cycle,
            'description' => $data['description'] ?? 'Assinatura TimePlus',
            'nextDueDate' => $data['next_due_date'] ?? now()->format('Y-m-d'),
        ];

        if (!empty($data['external_reference'])) {
            $payload['externalReference'] = $data['external_reference'];
        }

        if (!empty($data['end_date'])) {
            $payload['endDate'] = $data['end_date'];
        }

        if (($payload['billingType'] === 'CREDIT_CARD') && !empty($data['credit_card'])) {
            $payload['creditCard']           = $data['credit_card'];
            $payload['creditCardHolderInfo'] = $data['credit_card_holder_info'] ?? [];
        }

        $response = $this->post('/subscriptions', $payload);

        return [
            'id'             => $response['id'] ?? null,
            'status'         => $response['status'] ?? null,
            'next_due_date'  => $response['nextDueDate'] ?? null,
            'cycle'          => $response['cycle'] ?? null,
            'value'          => $response['value'] ?? null,
            'raw'            => $response,
        ];
    }

    /**
     * Cancela uma assinatura recorrente no Asaas.
     *
     * @throws AsaasException
     */
    public function cancel(string $subscriptionId): array
    {
        if (empty($subscriptionId)) {
            throw new AsaasException('subscription_id é obrigatório.');
        }

        return $this->delete("/subscriptions/{$subscriptionId}");
    }

    /**
     * Atualiza a assinatura no Asaas (valor, ciclo, billingType etc).
     *
     * @throws AsaasException
     */
    public function update(string $subscriptionId, array $data): array
    {
        if (empty($subscriptionId)) {
            throw new AsaasException('subscription_id é obrigatório.');
        }

        return $this->put("/subscriptions/{$subscriptionId}", $data);
    }

    /**
     * Lista as cobranças geradas por uma assinatura.
     *
     * @throws AsaasException
     */
    public function payments(string $subscriptionId): array
    {
        return $this->get("/subscriptions/{$subscriptionId}/payments");
    }
}
