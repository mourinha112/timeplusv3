<?php

namespace App\Services\Asaas;

class ChargeService extends AsaasBaseService
{
    /**
     * Criação de uma cobrança no Asaas.
     *
     * @param array $chargeData
     * @return array
     */
    public function create(array $chargeData): array
    {
        $data = [
            'customer'          => $chargeData['customer_id'],
            'billingType'       => $chargeData['billing_type'],
            'value'             => $chargeData['value'],
            'dueDate'           => $chargeData['due_date'],
            'description'       => $chargeData['description'] ?? null,
            'externalReference' => $chargeData['external_reference'] ?? null,
        ];

        $data = array_filter($data, fn ($value) => !is_null($value));

        return $this->post('/payments', $data);
    }
}
