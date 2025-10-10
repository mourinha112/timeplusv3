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

            $this->pixKey    = $payment['pix_key'] ?? null;
            $this->pixQrCode = isset($payment['pix_qr_code']) ? 'data:image/png;base64,' . $payment['pix_qr_code'] : null;

            $this->isLoading = false;
        } catch (PagarmeException $e) {
            return $this->addError('payment', $e->getMessage());
        } catch (\Exception $e) {
            return $this->addError('payment', 'Erro interno do servidor');
        }
    }

    public function generatePix()
    {
        try {
            $this->isLoading = true;

            $response = Pagarme::payment()->createWithPix([
                'amount' => $this->payable->total_amount,
            ]);

            dd($response);

            $this->loadPixQrCode();
        } catch (PagarmeException $e) {
            $this->isLoading = false;
            return $this->addError('payment', $e->getMessage());
        } catch (\Exception $e) {
            $this->isLoading = false;
            return $this->addError('payment', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.user.checkout.pix');
    }
}
