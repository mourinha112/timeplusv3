<?php

namespace App\Livewire\User\Checkout;

use App\Exceptions\AsaasException;
use App\Facades\Asaas;
use App\Models\Room;
use App\Services\JitsiService;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Livewire\Attributes\Rule;
use Livewire\Component;

class CreditCard extends Component
{
    public $payable;

    /* Informações do cartão de crédito */
    #[Rule(['required', 'string', 'min:2', 'max:100', 'regex:/^[\p{L}\s]+$/u'])]
    public ?string $card_holder_name = 'DENIS A C DA SILVA';

    #[Rule(['required', 'regex:/^[0-9\s\-]+$/', 'min:13', 'max:19'])]
    public ?string $card_number = '1234567812345678';

    #[Rule(['required', 'integer', 'between:1,12'])]
    public ?int $card_expiry_month = 12;

    #[Rule(['required', 'integer', 'min:2025', 'max:2045'])]
    public ?int $card_expiry_year = 2025;

    #[Rule(['required', 'digits_between:3,4'])]
    public ?int $card_cvv = 123;

    public function mount($payable)
    {
        $this->payable = $payable;
    }

    public function getDisplayValue()
    {
        $user = Auth::user();

        try {
            $baseAmount         = $this->getBaseAmount();
            $paymentCalculation = $this->calculateDiscount($user, $baseAmount);

            return $paymentCalculation['employee_amount'];
        } catch (\Exception $e) {
            return $this->getBaseAmount();
        }
    }

    public function hasDiscount()
    {
        $user = Auth::user();

        try {
            $baseAmount         = $this->getBaseAmount();
            $paymentCalculation = $this->calculateDiscount($user, $baseAmount);

            return $paymentCalculation['has_company_discount'];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getDiscountInfo()
    {
        $user = Auth::user();

        try {
            $baseAmount = $this->getBaseAmount();

            return $this->calculateDiscount($user, $baseAmount);
        } catch (\Exception $e) {
            $baseAmount = $this->getBaseAmount();

            return [
                'employee_amount'      => $baseAmount,
                'company_amount'       => 0,
                'discount_percentage'  => 0,
                'has_company_discount' => false,
            ];
        }
    }

    protected function getBaseAmount(): float
    {
        if ($this->payable instanceof \App\Models\Appointment) {
            return $this->payable->total_value;
        } elseif ($this->payable instanceof \App\Models\Subscribe) {
            return $this->payable->plan->price;
        }

        return 0;
    }

    public function pay()
    {
        $this->validate();

        $this->payWithCreditCard();
    }

    public function payWithCreditCard()
    {
        $cleanCardNumber = null;

        try {
            DB::beginTransaction();

            // Verifica se o usuário tem gateway_customer_id
            $user = Auth::user();

            if (!$user->gateway_customer_id) {
                $this->addError('payment', 'Cliente não configurado no gateway de pagamento.');

                return;
            }

            // Obter valor base do payable
            $baseAmount = $this->getBaseAmount();

            // Calcular informações de desconto APENAS para Appointments (sessões)
            // Planos (Subscribe) NÃO devem ter desconto aplicado
            $finalAmount        = $baseAmount;
            $discountValue      = 0;
            $discountPercentage = 0;
            $companyPlanName    = null;
            $companyId          = null;

            if ($this->payable instanceof \App\Models\Appointment) {
                $paymentCalculation = $this->calculateDiscount($user, $baseAmount);
                $finalAmount        = $paymentCalculation['employee_amount'];
                $discountValue      = $paymentCalculation['company_amount'] ?? 0;
                $discountPercentage = $paymentCalculation['discount_percentage'] ?? 0;
                $companyPlanName    = $paymentCalculation['plan_name'] ?? null;

                // Buscar company_id se há desconto
                if ($paymentCalculation['has_company_discount']) {
                    $activeCompanyPlan = $user->getActiveCompanyPlan();
                    $companyId         = $activeCompanyPlan ? $activeCompanyPlan->company_id : null;
                }
            }

            // Validar valor final
            if ($finalAmount <= 0) {
                DB::rollBack();

                $this->addError('payment', 'Valor de pagamento inválido.');

                return;
            }

            // Limpar e formatar os dados do cartão
            $cleanCardNumber = preg_replace('/[^0-9]/', '', $this->card_number);
            $cleanHolderName = strtoupper(trim($this->card_holder_name));

            // Validar se o número do cartão tem tamanho adequado após limpeza
            if (strlen($cleanCardNumber) < 13 || strlen($cleanCardNumber) > 19) {
                DB::rollBack();

                $this->addError('payment', 'Número do cartão inválido.');

                return;
            }

            // Determinar descrição baseado no tipo
            $description = $this->payable instanceof \App\Models\Appointment
                ? 'Pagamento da sessão #' . $this->payable->id
                : 'Assinatura do plano ' . $this->payable->plan->name;

            // Criação do pagamento no gateway
            $paymentGateway = Asaas::payment()->createWithCreditCard([
                'card_number' => $cleanCardNumber,
                'holder_name' => $cleanHolderName,
                'exp_month'   => $this->card_expiry_month,
                'exp_year'    => $this->card_expiry_year,
                'cvv'         => $this->card_cvv,
                'amount'      => $finalAmount,
                'description' => $description,
                'item_code'   => $this->payable->id ?? 0,
                'customer_id' => $user->gateway_customer_id,
                'email'       => $user->email,
                'document'    => preg_replace('/[^0-9]/', '', $user->cpf),
                'phone'       => preg_replace('/[^0-9]/', '', $user->phone_number),
            ]);

            // Verifica se o pagamento foi realizado com sucesso
            if ($paymentGateway['status'] !== 'paid') {
                DB::rollBack();

                $this->addError('payment', 'Não foi possível realizar o pagamento. Verifique os dados do cartão.');

                return;
            }

            // Preparar metadata com informações adicionais
            $metadata = [
                'card_last_digits' => substr($cleanCardNumber, -4),
                'card_holder_name' => $cleanHolderName,
                'user_id'          => $user->id,
                'user_name'        => $user->name,
                'payable_type'     => get_class($this->payable),
                'payable_id'       => $this->payable->id,
                'has_discount'     => $discountValue > 0,
                'payment_date'     => now()->toISOString(),
            ];

            // Criação/atualização do pagamento no banco de dados
            $payment = $this->payable->payment()->updateOrCreate(
                ['payable_id' => $this->payable->id, 'payable_type' => get_class($this->payable)],
                [
                    'gateway_order_id'  => $paymentGateway['id'],
                    'gateway_charge_id' => $paymentGateway['charges'][0]['id'] ?? null,
                    'amount'            => $paymentGateway['amount'] / 100,
                    'payment_method'    => 'credit_card',
                    'status'            => $paymentGateway['status'],
                    'currency'          => $paymentGateway['currency'] ?? 'BRL',
                    'description'       => $paymentGateway['items'][0]['description'] ?? null,
                    'paid_at'           => isset($paymentGateway['charges'][0]['paid_at']) ?
                        now()->parse($paymentGateway['charges'][0]['paid_at']) : now(),
                    'metadata'            => $metadata,
                    'company_id'          => $companyId,
                    'discount_value'      => $discountValue,
                    'discount_percentage' => $discountPercentage,
                    'company_plan_name'   => $companyPlanName,
                ]
            );

            // Criar sala de videochamada automaticamente se for um appointment
            $roomCode = null;

            if ($this->payable instanceof \App\Models\Appointment) {
                $roomCode = $this->createRoomForAppointment($this->payable);
            }

            DB::commit();

            // Redireciona para a página de sucesso mostrando a sala criada
            if ($roomCode) {
                session()->flash('success', 'Pagamento realizado com sucesso!');
                session()->flash('room_code', $roomCode);
                session()->flash('appointment_date', $this->payable->appointment_date);
                session()->flash('appointment_time', $this->payable->appointment_time);
            } else {
                session()->flash('success', 'Pagamento realizado com sucesso!');
            }

            $this->redirect(route('user.appointment.index'), navigate: true);
        } catch (AsaasException $e) {
            DB::rollBack();
            Log::error('Erro do Asaas no pagamento com cartão', [
                'message'          => $e->getMessage(),
                'payable_id'       => $this->payable->id,
                'user_id'          => Auth::id(),
                'card_last_digits' => substr($cleanCardNumber ?? $this->card_number ?? '', -4),
            ]);

            $this->addError('payment', 'Ocorreu um erro ao realizar o pagamento. Verifique os dados do cartão e tente novamente.');

            return;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro interno no pagamento com cartão de crédito', [
                'message'          => $e->getMessage(),
                'file'             => $e->getFile(),
                'line'             => $e->getLine(),
                'trace'            => $e->getTraceAsString(),
                'payable_id'       => $this->payable->id,
                'user_id'          => Auth::id(),
                'card_last_digits' => substr($cleanCardNumber ?? $this->card_number, -4),
            ]);

            $this->addError('payment', 'Erro interno: ' . $e->getMessage());

            return;
        }
    }

    protected function calculateDiscount($user, $originalAmount)
    {
        // 1. Primeiro, verificar CompanyPlan (funcionários de empresa)
        $activeCompanyPlan = $user->getActiveCompanyPlan();

        if ($activeCompanyPlan && $activeCompanyPlan->companyPlan) {
            $discountPercentage = $activeCompanyPlan->companyPlan->discount_percentage ?? 0;

            if ($discountPercentage > 0) {
                $discountAmount = ($originalAmount * $discountPercentage) / 100;
                $finalAmount = $originalAmount - $discountAmount;

                return [
                    'employee_amount'      => round($finalAmount, 2),
                    'company_amount'       => round($discountAmount, 2),
                    'discount_percentage'  => $discountPercentage,
                    'has_company_discount' => true,
                    'company_name'         => $activeCompanyPlan->company->name,
                    'plan_name'            => $activeCompanyPlan->companyPlan->name,
                ];
            }
        }

        // 2. Se não tem CompanyPlan, verificar Subscribe/Plan (plano individual COM PAGAMENTO CONFIRMADO)
        $activeSubscribe = $user->subscribes()
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->whereNull('cancelled_date')
            ->whereHas('payments', function ($query) {
                $query->where('status', 'paid');
            })
            ->with('plan')
            ->first();

        if ($activeSubscribe && $activeSubscribe->plan) {
            $discountPercentage = $activeSubscribe->plan->discount_percentage ?? 0;

            if ($discountPercentage > 0) {
                $discountAmount = ($originalAmount * $discountPercentage) / 100;
                $finalAmount = $originalAmount - $discountAmount;

                return [
                    'employee_amount'      => round($finalAmount, 2),
                    'company_amount'       => round($discountAmount, 2),
                    'discount_percentage'  => $discountPercentage,
                    'has_company_discount' => true,
                    'company_name'         => $activeSubscribe->plan->name,
                    'plan_name'            => $activeSubscribe->plan->name,
                ];
            }
        }

        // 3. Sem desconto disponível
        return [
            'employee_amount'      => $originalAmount,
            'company_amount'       => 0,
            'discount_percentage'  => 0,
            'has_company_discount' => false,
        ];
    }

    private function createRoomForAppointment($appointment)
    {
        try {
            $jitsiService = new JitsiService();
            $roomCode     = $jitsiService->createRoomCode();

            // Criar sala fechada - será aberta 10min antes da consulta
            $room = Room::create([
                'code'           => $roomCode,
                'status'         => 'closed',
                'created_by'     => $appointment->user_id, // Usuário que pagou
                'appointment_id' => $appointment->id,
            ]);

            Log::info('Sala criada automaticamente após pagamento', [
                'appointment_id'       => $appointment->id,
                'room_code'            => $roomCode,
                'appointment_datetime' => $appointment->appointment_date . ' ' . $appointment->appointment_time,
                'user_id'              => $appointment->user_id,
                'specialist_id'        => $appointment->specialist_id,
                'status'               => 'closed', // Sala será aberta 10min antes da consulta
            ]);

            // Notificar o especialista sobre a criação da sala
            try {
                $specialist = $appointment->specialist;
                // Aqui você poderia enviar email, SMS, ou notificação push
                // Por ora, apenas logamos a intenção de notificar
                Log::info('Notificação enviada ao especialista sobre sala criada', [
                    'specialist_id'    => $specialist->id,
                    'specialist_email' => $specialist->email,
                    'room_code'        => $roomCode,
                    'appointment_date' => $appointment->appointment_date,
                    'appointment_time' => $appointment->appointment_time,
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao notificar especialista', ['error' => $e->getMessage()]);
            }

            return $roomCode;

        } catch (\Exception $e) {
            Log::error('Erro ao criar sala automaticamente após pagamento', [
                'appointment_id' => $appointment->id,
                'error'          => $e->getMessage(),
                'trace'          => $e->getTraceAsString(),
            ]);

            // Não falhamos o pagamento por causa disso, apenas logamos
            return null;
        }
    }

    public function render()
    {
        return view('livewire.user.checkout.credit-card');
    }
}
