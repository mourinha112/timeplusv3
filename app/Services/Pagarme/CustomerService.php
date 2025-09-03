<?php

namespace App\Services\Pagarme;

use InvalidArgumentException;

class CustomerService extends PagarmeBaseService
{
    /**
     * Criação de um cliente.
     *
     * @param array $customerData
     * @return array
     */
    public function create(array $customerData): array
    {
        /* Validação dos campos obrigatórios */
        $requiredFields = ['name', 'email', 'code', 'document', 'mobile_phone'];

        foreach ($requiredFields as $field) {
            if (empty($customerData[$field])) {
                throw new InvalidArgumentException("O campo {$field} é obrigatório.");
            }
        }

        /* Retira os caracteres não numéricos */
        $customerData['document'] = preg_replace('/\D/', '', $customerData['document']);
        $customerData['mobile_phone'] = preg_replace('/\D/', '', $customerData['mobile_phone']);

        $data = [
            'name'          => $customerData['name'],
            'email'         => $customerData['email'],
            'code'          => $customerData['code'], /* Código de referência do cliente no sistema da loja. Max: 52 caracteres */
            'document'      => $customerData['document'], /* CPF, CNPJ ou PASSPORT do cliente. Max: 16 caracteres para CPF e CNPJ e Max: 50 caracteres para PASSPORT */
            'type'          => $customerData['type'] ?? 'individual', /* Tipo de cliente. Valores possíveis: individual (pessoa física) ou company (pessoa jurídica) */
            'document_type' => $customerData['document_type'] ?? 'CPF', /* Tipo de documento. Valores possíveis: CPF, CNPJ ou PASSPORT */
            "phones" => [
                "mobile_phone" => [
                    "country_code" => "55",
                    "area_code" => substr($customerData['mobile_phone'], 0, 2),
                    "number" => substr($customerData['mobile_phone'], 2)
                ]
            ],
        ];

        $data = array_filter($data, fn($value) => !is_null($value));

        return $this->post('/customers', $data);
    }
}
