<?php

namespace App\Livewire\User\Plan\PaymentMethods;

use App\Facades\Pagarme;
use App\Models\{Payment, Subscribe};
use Illuminate\Support\Facades\{Auth, Cache, DB};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class Pix extends Component
{
    public $plan;

    public ?string $pixKey = null;

    public ?string $pixQrCode = null;

    public bool $isLoading = true;

    public function mount($plan): void
    {
        $this->plan = $plan;
        // $this->loadPixQrCode();
    }

    public function subscribe()
    {
        try {
            DB::beginTransaction();

            /* Criação do pagamento no gateway */
            $paymentGateway = Pagarme::payment()->createWithPix([
                'amount'      => $this->plan->price_with_discount,
                'description' => 'Assinatura do plano ' . $this->plan->name,
                'item_code'   => $this->plan->id,
                'customer_id' => Auth::user()->gateway_customer_id,
            ]);
            dd($paymentGateway);
        } catch (\Exception $e) {
            DB::rollBack();
            LivewireAlert::title('Erro!')->text('Não foi possível realizar o pagamento.')->error()->show();
        }
    }

    // protected function loadPixQrCode()
    // {
    //     try {
    //         $subscribe = Subscribe::where('end_date', '>', now())
    //             ->where('cancelled_date', null)
    //             ->where('user_id', Auth::id())
    //             ->whereHas('payments', function ($query) {
    //                 $query->where('status', '!=', 'paid');
    //             })
    //             ->first();

    //         dd($subscribe);

    //         if (!$subscribe) {
    //             LivewireAlert::title('Erro!')->text('Não foi possível realizar o pagamento.')->error()->show();
    //         }

    //         $qrCode = $this->getCachedQrCode($subscribe->payment->gateway_payment_id);

    //         $this->pixKey    = $qrCode['payload'] ?? null;
    //         $this->pixQrCode = isset($qrCode['encodedImage']) ? 'data:image/png;base64,' . $qrCode['encodedImage'] : null;

    //         $this->isLoading = false;
    //     } catch (\Exception $e) {
    //         return $this->addError('payment', 'Erro interno do servidor.');
    //     }
    // }

    // protected function getCachedQrCode(string $paymentId): array
    // {
    //     return Cache::remember("pix_base64_gateway_payment_id_{$paymentId}", now()->addMinutes(10), function () use ($paymentId) {
    //         // return Asaas::payment()->pixQrCode($paymentId);
    //     });
    // }

    public function render()
    {
        return view('livewire.user.plan.payment-methods.pix');
    }
}
