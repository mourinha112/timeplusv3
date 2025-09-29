<?php

namespace App\Livewire\User\Plan\PaymentMethods;

use App\Facades\Pagarme;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CreditCard extends Component
{
    public $plan;

    #[Rule(['required', 'string', 'min:2', 'max:100', 'regex:/^[\p{L}\s]+$/u'])]
    public ?string $card_holder = null;

    #[Rule(['required', 'size:16'])]
    public ?string $card_number = null;

    #[Rule(['required', 'integer', 'between:1,12'])]
    public ?int $card_expiry_month = null;

    #[Rule(['required', 'integer', 'min:2025', 'max:2045'])]
    public ?int $card_expiry_year = null;

    #[Rule(['required', 'digits_between:3,4'])]
    public ?int $card_cvv = null;

    public function mount($plan)
    {
        $this->plan = $plan;
    }

    public function subscribe()
    {
        $this->validate();

        $amountToCharge = $this->plan->price_with_discount;
        $originalAmount = round((float) $this->plan->price, 2);
        // $discountAmount     = max(round($this->plan->discount_amount, 2), 0.0);
        $discountPercentage = $this->plan->hasDiscount()
            ? round((float) $this->plan->discount_percentage, 2)
            : 0.0;

        try {
            DB::beginTransaction();

            /* Criação do pagamento no gateway */
            $paymentGateway = Pagarme::payment()->createWithCreditCard([
                'card_number' => $this->card_number,
                'holder_name' => $this->card_holder,
                'exp_month'   => $this->card_expiry_month,
                'exp_year'    => $this->card_expiry_year,
                'cvv'         => $this->card_cvv,
                'amount'      => $amountToCharge,
                'description' => 'Assinatura do plano ' . $this->plan->name,
                'item_code'   => $this->plan->id,
                'customer_id' => Auth::user()->gateway_customer_id,
            ]);

            /* Verifica se o pagamento foi realizado com sucesso */
            if ($paymentGateway['status'] !== 'paid') {
                DB::rollBack();
                LivewireAlert::title('Erro!')->text('Não foi possível realizar o pagamento.')->error()->show();

                return;
            }

            /* Criação da assinatura no banco de dados */
            $subscribe = Auth::user()->subscribes()->create([
                'plan_id'    => $this->plan->id,
                'start_date' => now(),
                'end_date'   => now()->addDays($this->plan->duration_days),
            ]);

            /* Criação do pagamento no banco de dados */
            $chargedAmount = round($amountToCharge, 2);

            if (isset($paymentGateway['amount']) && is_numeric($paymentGateway['amount'])) {
                $rawAmount = (float) $paymentGateway['amount'];

                if (abs($rawAmount - (int) $rawAmount) < 0.001 && $rawAmount >= 100) {
                    $chargedAmount = round($rawAmount / 100, 2);
                } else {
                    $chargedAmount = round($rawAmount, 2);
                }
            }

            $subscribe->payments()->create([
                'gateway_order_id'  => $paymentGateway['id'],
                'gateway_charge_id' => $paymentGateway['charges'][0]['id'],
                'amount'            => $chargedAmount,
                'payment_method'    => $paymentGateway['charges'][0]['payment_method'],
                'status'            => $paymentGateway['status'],
                'currency'          => $paymentGateway['currency'],
                'description'       => $paymentGateway['items'][0]['description'] ?? null,
                'paid_at'           => $paymentGateway['charges'][0]['paid_at'] ?? null,
                'original_amount'   => $originalAmount,
                // 'discount_value'      => $discountAmount,
                'discount_percentage' => $discountPercentage,
                // 'discount'            => $discountAmount,
            ]);

            DB::commit();

            LivewireAlert::title('Sucesso!')->text('Assinatura realizada com sucesso!')->success()->show();

            return $this->redirect(route('user.subscribe.show'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')->text('Ocorreu um erro ao tentar realizar a assinatura.')->error()->show();
        }
    }

    public function render()
    {
        return view('livewire.user.plan.payment-methods.credit-card');
    }
}
