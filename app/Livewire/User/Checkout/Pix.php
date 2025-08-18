<?php

namespace App\Livewire\User\Checkout;

use App\Exceptions\PagarmeException;
use App\Facades\Pagarme;
use App\Models\{Appointment};
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Pix extends Component
{
    public Appointment $appointment;

    public $payable;

    public ?string $pixKey = null;

    public ?string $pixQrCode = null;

    public bool $isLoading = true;

    public function mount(Appointment $appointment): void
    {
        $this->appointment = $appointment;
        $this->loadPixQrCode();
    }

    protected function loadPixQrCode()
    {
        try {
            $payment = $this->payable->payment;

            if (!$payment?->gateway_payment_id) {
                return $this->addError('payment', 'Nenhum pagamento encontrado para esta consulta.');
            }

            $qrCode = $this->getCachedQrCode($payment->gateway_payment_id);

            $this->pixKey    = $qrCode['payload'] ?? null;
            $this->pixQrCode = isset($qrCode['encodedImage']) ? 'data:image/png;base64,' . $qrCode['encodedImage'] : null;

            $this->isLoading = false;
        } catch (PagarmeException $e) {
            return $this->addError('payment', $e->getMessage());
        } catch (\Exception $e) {
            return $this->addError('payment', 'Erro interno do servidor');
        }
    }

    protected function getCachedQrCode(string $paymentId): array
    {
        return Cache::remember("pix_base64_gateway_payment_id_{$paymentId}", now()->addMinutes(10), function () use ($paymentId) {
            return Pagarme::payment()->pixQrCode($paymentId);
        });
    }

    public function render()
    {
        return view('livewire.user.checkout.pix');
    }
}
