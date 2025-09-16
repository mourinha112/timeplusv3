<?php

namespace App\Livewire\User\Checkout;

use App\Exceptions\PagarmeException;
use App\Facades\Pagarme;
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
    public ?string $card_number = '4000000000000010';

    #[Rule(['required', 'integer', 'between:1,12'])]
    public ?int $card_expiry_month = 12;

    #[Rule(['required', 'integer', 'min:2025', 'max:2045'])]
    public ?int $card_expiry_year = 2024;

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
            $paymentCalculation = $user->calculatePaymentAmount($this->payable->total_value);
            return $paymentCalculation['employee_amount'];
        } catch (\Exception $e) {
            return $this->payable->total_value;
        }
    }

    public function hasDiscount()
    {
        $user = Auth::user();

        try {
            $paymentCalculation = $user->calculatePaymentAmount($this->payable->total_value);
            return $paymentCalculation['has_company_discount'];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getDiscountInfo()
    {
        $user = Auth::user();

        try {
            return $user->calculatePaymentAmount($this->payable->total_value);
        } catch (\Exception $e) {
            return [
                'employee_amount'      => $this->payable->total_value,
                'company_amount'       => 0,
                'discount_percentage'  => 0,
                'has_company_discount' => false,
            ];
        }
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
                return $this->addError('payment', 'Cliente não configurado no gateway de pagamento.');
            }

            // Calcular informações de desconto se o usuário tem empresa
            $paymentCalculation = $user->calculatePaymentAmount($this->payable->total_value);
            $finalAmount        = $paymentCalculation['employee_amount'];
            $discountValue      = $paymentCalculation['company_amount'] ?? 0;
            $discountPercentage = $paymentCalculation['discount_percentage'] ?? 0;
            $companyPlanName    = $paymentCalculation['plan_name'] ?? null;

            // Validar valor final
            if ($finalAmount <= 0) {
                DB::rollBack();
                return $this->addError('payment', 'Valor de pagamento inválido.');
            }

            // Buscar company_id se há desconto
            $companyId = null;
            if ($paymentCalculation['has_company_discount']) {
                $activeCompanyPlan = $user->getActiveCompanyPlan();
                $companyId = $activeCompanyPlan ? $activeCompanyPlan->company_id : null;
            }

            // Limpar e formatar os dados do cartão
            $cleanCardNumber = preg_replace('/[^0-9]/', '', $this->card_number);
            $cleanHolderName = strtoupper(trim($this->card_holder_name));

            // Validar se o número do cartão tem tamanho adequado após limpeza
            if (strlen($cleanCardNumber) < 13 || strlen($cleanCardNumber) > 19) {
                DB::rollBack();

                return $this->addError('payment', 'Número do cartão inválido.');
            }

            // Criação do pagamento no gateway
            $paymentGateway = Pagarme::payment()->createWithCreditCard([
                'card_number' => $cleanCardNumber,
                'holder_name' => $cleanHolderName,
                'exp_month'   => $this->card_expiry_month,
                'exp_year'    => $this->card_expiry_year,
                'cvv'         => $this->card_cvv,
                'amount'      => $finalAmount,
                'description' => 'Pagamento da sessão #' . $this->payable->id,
                'item_code'   => $this->payable->id,
                'customer_id' => $user->gateway_customer_id,
            ]);

            // Verifica se o pagamento foi realizado com sucesso
            if ($paymentGateway['status'] !== 'paid') {
                DB::rollBack();

                return $this->addError('payment', 'Não foi possível realizar o pagamento. Verifique os dados do cartão.');
            }

            // Preparar metadata com informações adicionais
            $metadata = [
                'card_last_digits' => substr($cleanCardNumber, -4),
                'card_holder_name' => $cleanHolderName,
                'user_id'          => $user->id,
                'user_name'        => $user->name,
                'payable_type'     => get_class($this->payable),
                'payable_id'       => $this->payable->id,
                'has_discount'     => $paymentCalculation['has_company_discount'],
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
                    'original_amount'     => $this->payable->total_value,
                    'discount_value'      => $discountValue,
                    'discount_percentage' => $discountPercentage,
                    'company_plan_name'   => $companyPlanName,
                ]
            );

            DB::commit();

            // Redireciona para a página de sucesso ou appointments
            session()->flash('success', 'Pagamento realizado com sucesso!');

            return $this->redirect(route('user.appointment.index'));
        } catch (PagarmeException $e) {
            DB::rollBack();
            Log::error('Erro do Pagar.me no pagamento com cartão', [
                'message'          => $e->getMessage(),
                'payable_id'       => $this->payable->id,
                'user_id'          => Auth::id(),
                'card_last_digits' => substr($cleanCardNumber ?? $this->card_number, -4),
                'card_holder'      => $this->card_holder_name,
            ]);

            return $this->addError('payment', 'Erro no pagamento: ' . $e->getMessage());
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

            return $this->addError('payment', 'Erro interno: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.user.checkout.credit-card');
    }
}
