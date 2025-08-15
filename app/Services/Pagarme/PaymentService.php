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
            "customer_id" => $paymentData['customer_id'],
            // "items" => [
            //     [
            //         "amount" => $paymentData['amount'] * 100,
            //         "description" => $paymentData['description'] ?? null,
            //         "quantity" => 1,
            //     ],
            // ],
            'currency' => 'BRL',
            "payments" => [
                [
                    'amount' => $paymentData['amount'] * 100,
                    "payment_method" => "credit_card",
                    "credit_card" => [
                        "recurrence" => false,
                        "installments" => 1,
                        "card" => [
                            "number" => $paymentData['card_number'],
                            "holder_name" => $paymentData['card_holder'],
                            "exp_month" => $paymentData['card_exp_month'],
                            "exp_year" => $paymentData['card_exp_year'],
                            "cvv" => $paymentData['card_cvv'],
                            // "billing_address" => [
                            //     "line_1" => $paymentData['billing_address'] ?? null,
                            //     "zip_code" => $paymentData['billing_zip_code'] ?? null,
                            //     "city" => $paymentData['billing_city'] ?? null,
                            //     "state" => $paymentData['billing_state'] ?? null,
                            //     "country" => $paymentData['billing_country'] ?? null,
                            // ],
                        ],
                    ],
                ],
            ],
            "ip" => $paymentData['ip'] ?? null,
            "device" => [
                "platform" => $paymentData['device_platform'] ?? null,
            ],
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
            "customer_id" => $paymentData['customer_id'],
            // "items" => [
            //     [
            //         "amount" => $paymentData['amount'] * 100,
            //         "description" => $paymentData['description'] ?? null,
            //         "quantity" => 1,
            //     ],
            // ],
            'amount' => $paymentData['amount'] * 100,
            'currency' => 'BRL',
            'payment' => [
                'payment_method' => 'pix',
                'pix' => [
                    'expires_in' => $paymentData['pix_expires_in'] ?? 600,
                ]
            ],
            "ip" => $paymentData['ip'] ?? null,
            "device" => [
                "platform" => $paymentData['device_platform'] ?? null,
            ],
        ];

        $data = array_filter($data, fn($value) => !is_null($value));

        return $this->post('/orders', $data);
    }
}
