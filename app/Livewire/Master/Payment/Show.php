<?php

namespace App\Livewire\Master\Payment;

use App\Models\{Appointment, Payment, Room, Subscribe};
use App\Notifications\User\{PaymentApprovedNotification, SubscriptionActiveNotification};
use App\Services\JitsiService;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Pagamento', 'guard' => 'master'])]
class Show extends Component
{
    public Payment $payment;

    public bool $showPayoffModal = false;

    #[Rule('nullable|string|max:500')]
    public ?string $payoffNote = null;

    public function mount(Payment $payment): void
    {
        $this->payment = $payment->load(['payable', 'company']);
    }

    public function openPayoffModal(): void
    {
        $this->payoffNote      = null;
        $this->showPayoffModal = true;
    }

    public function closePayoffModal(): void
    {
        $this->showPayoffModal = false;
    }

    public function payoff(): void
    {
        $this->validate();

        if ($this->payment->manual_paid_at) {
            LivewireAlert::title('Já está dado baixa.')
                ->warning()
                ->show();

            return;
        }

        try {
            $payable = null;

            DB::transaction(function () use (&$payable) {
                $this->payment->update([
                    'status'                   => 'paid',
                    'paid_at'                  => $this->payment->paid_at ?? now(),
                    'manual_paid_at'           => now(),
                    'manual_paid_by_master_id' => Auth::guard('master')->id(),
                    'manual_paid_note'         => $this->payoffNote,
                ]);

                $payable = $this->payment->payable()->first();

                if ($payable instanceof Appointment) {
                    $this->createRoomForAppointment($payable);
                }

                if ($payable instanceof Subscribe) {
                    $payable->update([
                        'billing_status' => Subscribe::STATUS_ACTIVE,
                        'cancelled_date' => null,
                    ]);
                }
            });

            $this->notifyPayoff($payable);

            $this->payment->refresh()->load(['payable', 'company']);
            $this->showPayoffModal = false;

            LivewireAlert::title('Baixa registrada!')
                ->text('Pagamento marcado como recebido manualmente.')
                ->success()
                ->show();
        } catch (\Exception $e) {
            Log::error('Erro ao dar baixa manual em pagamento', [
                'payment_id' => $this->payment->id,
                'error'      => $e->getMessage(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Não foi possível registrar a baixa manual.')
                ->error()
                ->show();
        }
    }

    private function createRoomForAppointment(Appointment $appointment): void
    {
        try {
            if (Room::where('appointment_id', $appointment->id)->exists()) {
                return;
            }

            $roomCode = (new JitsiService())->createRoomCode();

            Room::create([
                'code'           => $roomCode,
                'status'         => 'closed',
                'created_by'     => $appointment->user_id,
                'appointment_id' => $appointment->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar sala após baixa manual', [
                'appointment_id' => $appointment->id,
                'error'          => $e->getMessage(),
            ]);
        }
    }

    private function notifyPayoff($payable): void
    {
        try {
            if ($payable instanceof Appointment && $payable->user) {
                $payable->user->notify(new PaymentApprovedNotification($payable, $this->payment));
            }

            if ($payable instanceof Subscribe && $payable->user) {
                $payable->user->notify(new SubscriptionActiveNotification($payable, $this->payment));
            }
        } catch (\Exception $e) {
            Log::error('Erro ao notificar usuário sobre baixa manual', [
                'payment_id' => $this->payment->id,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.master.payment.show');
    }
}
