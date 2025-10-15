<?php

namespace App\Livewire\User\Checkout;

use App\Exceptions\AsaasException;
use App\Facades\Asaas;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Livewire\Component;

class Pix extends Component
{
    public $payable;

    public ?string $pixKey = null;

    public ?string $pixQrCode = null;

    public bool $isLoading = true;

    public bool $shouldRedirect = false;

    public function mount(): void
    {
        // Verificar se já existe pagamento
        $existingPayment = $this->payable->payment;

        if ($existingPayment && $existingPayment->payment_method === 'pix') {
            // Se já existe pagamento PIX, carregar dados
            $this->loadPixQrCode();
        } else {
            // Não existe PIX ou é outro método, gerar novo PIX
            $this->generatePix();
        }
    }

    protected function loadPixQrCode()
    {
        try {
            $payment = $this->payable->payment;

            if (!$payment) {
                $this->isLoading = false;

                return;
            }

            // Se o pagamento foi confirmado, apenas marcar a flag
            // O JavaScript irá detectar e fazer o redirect
            if (in_array($payment->status, ['paid', 'confirmed'])) {
                $this->shouldRedirect = true;
                $this->isLoading      = false;

                return;
            }

            $this->pixKey    = $payment->pix_key ?? null;
            $this->pixQrCode = isset($payment->pix_qr_code) ? 'data:image/png;base64,' . $payment->pix_qr_code : null;

            $this->isLoading = false;
        } catch (AsaasException $e) {
            $this->addError('payment', $e->getMessage());
            $this->isLoading = false;
        } catch (\Exception $e) {
            $this->isLoading = false;
            $this->addError('payment', 'Erro interno do servidor');
        }
    }

    public function generatePix()
    {
        try {
            DB::beginTransaction();

            $this->isLoading = true;

            // Verifica se o usuário tem gateway_customer_id
            $user = Auth::user();

            if (!$user->gateway_customer_id) {
                $this->isLoading = false;
                $this->addError('payment', 'Cliente não configurado no gateway de pagamento.');

                return;
            }

            // Verificar se já existe pagamento PIX pendente para este payable
            $existingPayment = $this->payable->payment()
                ->where('payment_method', 'pix')
                ->whereIn('status', ['pending', 'pending_payment'])
                ->first();

            if ($existingPayment) {
                // Já existe PIX pendente, apenas carregar dados
                DB::commit();
                $this->isLoading = false;
                $this->loadPixQrCode();

                Log::info('PIX já existe, carregando dados existentes', [
                    'payment_id'       => $existingPayment->id,
                    'gateway_order_id' => $existingPayment->gateway_order_id,
                ]);

                return;
            }

            // Determinar o valor base dependendo do tipo de payable
            $baseAmount = $this->payable instanceof \App\Models\Appointment
                ? $this->payable->total_value
                : ($this->payable instanceof \App\Models\Subscribe
                    ? $this->payable->plan->price
                    : 0);

            // Calcular informações de desconto se o usuário tem plano individual ativo
            $paymentCalculation = $this->calculateDiscount($user, $baseAmount);
            $finalAmount        = $paymentCalculation['employee_amount'];
            $discountValue      = $paymentCalculation['company_amount'] ?? 0;
            $discountPercentage = $paymentCalculation['discount_percentage'] ?? 0;
            $companyPlanName    = $paymentCalculation['plan_name'] ?? null;

            // Validar valor final
            if ($finalAmount <= 0) {
                DB::rollBack();
                $this->isLoading = false;
                $this->addError('payment', 'Valor de pagamento inválido.');

                return;
            }

            // Buscar company_id se há desconto
            $companyId = null;

            if ($paymentCalculation['has_company_discount']) {
                $activeCompanyPlan = $user->getActiveCompanyPlan();
                $companyId         = $activeCompanyPlan ? $activeCompanyPlan->company_id : null;
            }

            // Determinar descrição baseado no tipo
            $description = $this->payable instanceof \App\Models\Appointment
                ? 'Pagamento da sessão #' . $this->payable->id
                : 'Assinatura do plano ' . $this->payable->plan->name;

            // Criar pagamento PIX no gateway (somente se não existir pendente)
            $paymentGateway = Asaas::payment()->createWithPix([
                'amount'      => $finalAmount,
                'description' => $description,
                'item_code'   => $this->payable->id ?? 0,
                'customer_id' => $user->gateway_customer_id,
            ]);

            // Preparar metadata
            $metadata = [
                'user_id'      => $user->id,
                'user_name'    => $user->name,
                'payable_type' => get_class($this->payable),
                'payable_id'   => $this->payable->id,
                'has_discount' => $paymentCalculation['has_company_discount'],
                'payment_date' => now()->toISOString(),
            ];

            // Criar/atualizar o pagamento no banco de dados
            $payment = $this->payable->payment()->updateOrCreate(
                ['payable_id' => $this->payable->id, 'payable_type' => get_class($this->payable)],
                [
                    'gateway_order_id'    => $paymentGateway['id'],
                    'gateway_charge_id'   => $paymentGateway['id'],
                    'amount'              => $paymentGateway['amount'] / 100,
                    'payment_method'      => 'pix',
                    'status'              => $paymentGateway['status'],
                    'currency'            => $paymentGateway['currency'] ?? 'BRL',
                    'description'         => $description,
                    'pix_key'             => $paymentGateway['pix_key'],
                    'pix_qr_code'         => $paymentGateway['pix_qr_code'],
                    'metadata'            => $metadata,
                    'company_id'          => $companyId,
                    'original_amount'     => $baseAmount,
                    'discount_value'      => $discountValue,
                    'discount_percentage' => $discountPercentage,
                    'company_plan_name'   => $companyPlanName,
                    'discount'            => $discountValue,
                ]
            );

            DB::commit();

            $this->loadPixQrCode();
        } catch (AsaasException $e) {
            DB::rollBack();
            $this->isLoading = false;
            Log::error('Erro do Asaas no pagamento PIX', [
                'message'    => $e->getMessage(),
                'payable_id' => $this->payable->id,
                'user_id'    => Auth::id(),
            ]);
            $this->addError('payment', 'Ocorreu um erro ao gerar o PIX. Tente novamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->isLoading = false;
            Log::error('Erro interno no pagamento PIX', [
                'message'    => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'payable_id' => $this->payable->id,
                'user_id'    => Auth::id(),
            ]);
            $this->addError('payment', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Verifica o status do pagamento (chamado periodicamente pela view).
     */
    public function checkPaymentStatus(): void
    {
        $payment = $this->payable->payment;

        if (!$payment) {
            return;
        }

        // Se o pagamento foi confirmado, sinalizar para redirecionar
        // O JavaScript irá detectar a mudança e fazer o redirect
        if (in_array($payment->status, ['paid', 'confirmed'])) {
            $this->shouldRedirect = true;
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
                $finalAmount    = $originalAmount - $discountAmount;

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

        // 2. Se não tem CompanyPlan, verificar Subscribe/Plan (plano individual)
        $activeSubscribe = $user->subscribes()
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->whereNull('cancelled_date')
            ->with('plan')
            ->first();

        if ($activeSubscribe && $activeSubscribe->plan) {
            $discountPercentage = $activeSubscribe->plan->discount_percentage ?? 0;

            if ($discountPercentage > 0) {
                $discountAmount = ($originalAmount * $discountPercentage) / 100;
                $finalAmount    = $originalAmount - $discountAmount;

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

    public function render()
    {
        return view('livewire.user.checkout.pix');
    }
}
