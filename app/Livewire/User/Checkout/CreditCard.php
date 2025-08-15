<?php

namespace App\Livewire\User\Checkout;

use App\Exceptions\PagarmeException;
use App\Facades\Pagarme;
use App\Models\{Payment, Appointment, Charge};
use Livewire\Attributes\Rule;
use Livewire\Component;

class CreditCard extends Component
{
    public $payable;

    /* Informações do cartão de crédito */
    #[Rule(['required', 'string', 'min:3', 'max:255'])]
    public ?string $card_holder_name = 'DENIS A C SILVA';

    #[Rule(['required', 'string', 'min:16', 'max:19'])]
    public ?string $card_number = '1234567812345678';

    #[Rule(['required', 'string', 'size:2'])]
    public ?string $card_expiry_month = '11';

    #[Rule(['required', 'string', 'size:4'])]
    public ?string $card_expiry_year = '2025';

    #[Rule(['required', 'string', 'min:3', 'max:4'])]
    public ?string $card_cvv = '123';

    public function mount($payable)
    {
        $this->payable = $payable;
    }

    public function pay()
    {
        $this->validate();

        $this->payWithCreditCard();
    }

    // public function payWithCreditCard()
    // {
    //     try {
    //         // $charge = $this->model->charge->where([
    //         //     ''
    //         //     'status' => 'pending'
    //         // ])->first();

    //         if (!$charge) {
    //             return $this->addError('payment', 'Nenhum pagamento encontrado para esta consulta.');
    //         }

    //         $payment = Pagarme::payment()->payWithCreditCard([
    //             'payment_id'   => $payment->gateway_payment_id,
    //             'holder_name'  => $this->card_holder_name,
    //             'number'       => $this->card_number,
    //             'expiry_month' => $this->card_expiry_month,
    //             'expiry_year'  => $this->card_expiry_year,
    //             'ccv'          => $this->card_cvv,
    //         ]);

    //         if ($payment['status'] !== 'CONFIRMED') {
    //             return $this->addError('payment', 'Pagamento não confirmado. Verifique os dados do cartão.');
    //         }

    //         return $this->addError('payment', 'Pagamento realizado com sucesso.');
    //     } catch (PagarmeException $e) {
    //         return $this->addError('payment', $e->getMessage());
    //     } catch (\Exception $e) {
    //         return $this->addError('payment', 'Erro interno do servidor');
    //     }
    // }

    public function render()
    {
        return view('livewire.user.checkout.credit-card');
    }
}
