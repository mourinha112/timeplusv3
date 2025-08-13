<?php

namespace App\Services\Pagarme;

class CustomerService extends PagarmeBaseService
{
    /**
     * Criação de um cliente no Pagar.me.
     *
     * @param array $customerData
     * @return array
     */
    public function create(array $customerData): array
    {
        $data = [
            'name'              => $customerData['name'],
            'email'             => $customerData['email'],
            'cpfCnpj'           => $customerData['cpf_cnpj'],
            'phone'             => $customerData['phone'] ?? null,
            'externalReference' => $customerData['externalReference'] ?? null,
        ];

        $data = array_filter($data, fn ($value) => !is_null($value));

        return $this->post('/customers', $data);
    }
}
