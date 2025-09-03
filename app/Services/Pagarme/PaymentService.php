<?php

namespace App\Services\Pagarme;

class PaymentService extends PagarmeBaseService
{
    /**
     * Criação de um pagamento com cartão de crédito.
     *
     * @param array $paymentData
     * @return array
     */
    public function createWithCreditCard(array $paymentData): array
    {
        $data = [
            "payments" => [
                [
                    "credit_card" => [
                        "card" => [
                            "number" => $paymentData['card_number'], /* Número do cartão. Entre 13 e 19 caracteres */
                            "holder_name" => $paymentData['holder_name'], /* Nome do portador como está impresso no cartão. Máximo de 64 caracteres */
                            "exp_month" => $paymentData['exp_month'], /* Mês de validade do cartão. Valor entre 1 e 12 */
                            "exp_year" => $paymentData['exp_year'], /* Ano de validade do cartão. Formatos yy ou yyyy. Ex: 23 ou 2023 */
                            "cvv" => $paymentData['cvv'], /* Código de segurança do cartão. O campo aceita 4 ou 3 caracteres, variando por bandeira */
                            "billing_address" => [
                                "line_1" => "Avenida Das Americas, 3959", /* Linha 1 do endereço. (Número, Rua, e Bairro - Nesta ordem e separados por vírgula) Max: 256 caracteres */
                                "line_2" => "Loja 112", /* Linha 2 do endereço. (Complemento - Andar, Sala, Apto). Max: 128 caracteres */
                                "zip_code" => "22631003", /* CEP. Max: 16 caracteres */
                                "city" => "Rio de Janeiro", /* Cidade. Max: 64 caracteres */
                                "state" => "RJ", /* Código do estado no formato ISO 3166-2. Max: 2 caracteres */
                                "country" => "BR" /* Código do país no formato ISO 3166-1 alpha-2. Max: 2 caracteres */
                            ],
                        ],
                        "installments" => 1, /* Quantidade de parcelas */
                        "statement_descriptor" => "TIMEPLUS" /* Texto exibido na fatura do cartão. Max: 22 caracteres para clientes Gateway; 13 para clientes PSP */
                    ],
                    "payment_method" => "credit_card"
                ]
            ],
            "items" => [
                [
                    "amount" => $paymentData['amount'] * 100, /* Valor unitário. Obrigatoriamente maior que zero */
                    "description" => $paymentData['description'] ?? null, /* Descrição do item */
                    "quantity" => 1, /* Quantidade de itens */
                    "code" => $paymentData['item_code'] ?? null /* Código do item no sistema da loja */
                ]
            ],
            "customer_id" => $paymentData['customer_id'], /* Código do cliente. Obrigatório, caso não seja informado o objeto customer */
            "ip" => request()->ip() /* Endereço IP do dispositivo que solicitou a compra no formato: IPV4 e IPV6 */
        ];

        $data = array_filter($data, fn($value) => !is_null($value));

        return $this->post('/orders', $data);
    }

    /**
     * Criação de um pagamento com PIX.
     *
     * @param array $paymentData
     * @return array
     */
    public function createWithPix(array $paymentData): array
    {
        $data = [
            "payments" => [
                [
                    "pix" => [
                        "expires_in" => 3600 /* Data de expiração do Pix em segundos */
                    ],
                    "payment_method" => "pix"
                ]
            ],
            "items" => [
                [
                    "amount" => $paymentData['amount'] * 100, /* Valor unitário. Obrigatoriamente maior que zero */
                    "description" => $paymentData['description'] ?? null, /* Descrição do item */
                    "quantity" => 1, /* Quantidade de itens */
                    "code" => $paymentData['item_code'] ?? null /* Código do item no sistema da loja */
                ]
            ],
            "customer_id" => $paymentData['customer_id'], /* Código do cliente. Obrigatório, caso não seja informado o objeto customer */
            "ip" => request()->ip() /* Endereço IP do dispositivo que solicitou a compra no formato: IPV4 e IPV6 */
        ];

        $data = array_filter($data, fn($value) => !is_null($value));

        return $this->post('/orders', $data);
    }
}
