<?php

namespace App\Livewire\User\Plan\PaymentMethods;

use App\Facades\Pagarme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CreditCard extends Component
{
    public $plan;

    #[Rule(['required', 'string', 'min:2', 'max:100', 'regex:/^[\p{L}\s]+$/u'])]
    public ?string $card_holder = 'DENIS A C DA SILVA';

    #[Rule(['required', 'size:16'])]
    public ?string $card_number = '4000000000000010';

    #[Rule(['required', 'integer', 'between:1,12'])]
    public ?int $card_expiry_month = 12;

    #[Rule(['required', 'integer', 'min:2025', 'max:2045'])]
    public ?int $card_expiry_year = 2024;

    #[Rule(['required', 'digits_between:3,4'])]
    public ?int $card_cvv = 123;

    public function mount($plan){
        $this->plan = $plan;
    }

    public function subscribe()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            /* Criação do pagamento no gateway */
            $paymentGateway = Pagarme::payment()->createWithCreditCard([
                'card_number' => $this->card_number,
                'holder_name' => $this->card_holder,
                'exp_month' => $this->card_expiry_month,
                'exp_year' => $this->card_expiry_year,
                'cvv' => $this->card_cvv,
                'amount' => $this->plan->price,
                'description' => 'Assinatura do plano ' . $this->plan->name,
                'item_code' => $this->plan->id,
                'customer_id' => Auth::user()->gateway_customer_id,
            ]);

            /* Verifica se o pagamento foi realizado com sucesso */
            if ($paymentGateway['status'] !== 'paid') {
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
            $subscribe->payments()->create([
                'gateway_order_id' => $paymentGateway['id'],
                'gateway_charge_id' => $paymentGateway['charges'][0]['id'],
                'amount'            => $paymentGateway['amount'] / 100,
                'payment_method'    => $paymentGateway['charges'][0]['payment_method'],
                'status'            => $paymentGateway['status'],
                'currency'          => $paymentGateway['currency'],
                'description'       => $paymentGateway['items'][0]['description'] ?? null,
                'paid_at'           => $paymentGateway['charges'][0]['paid_at'] ?? null,
            ]);

            // DB::commit();

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
