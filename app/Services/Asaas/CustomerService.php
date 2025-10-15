<?php

namespace App\Services\Asaas;

class CustomerService extends AsaasBaseService
{
    /**
     * Criação de um cliente no Asaas.
     *
     * @param array $customerData
     * @return array
     */
    public function create(array $customerData): array
    {
        // Mapeamento de campos para compatibilidade com formato anterior (Pagarme)
        $cpfCnpj = $customerData['cpfCnpj']
            ?? $customerData['cpf_cnpj']
            ?? $customerData['document']
            ?? null;

        $phone = $customerData['phone']
            ?? $customerData['mobile_phone']
            ?? null;

        $externalReference = $customerData['externalReference']
            ?? $customerData['external_reference']
            ?? $customerData['code']
            ?? null;

        $data = [
            'name'              => $customerData['name'],
            'email'             => $customerData['email'],
            'cpfCnpj'           => $cpfCnpj,
            'phone'             => $phone,
            'externalReference' => $externalReference,
        ];

        $data = array_filter($data, fn ($value) => !is_null($value));

        return $this->post('/customers', $data);
    }
}
