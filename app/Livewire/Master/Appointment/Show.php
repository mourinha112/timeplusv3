<?php

namespace App\Livewire\Master\Appointment;

use App\Models\{Appointment, UserCredit};
use App\Services\Credit\{CompanyCreditService, UserCreditService};
use Illuminate\Support\Facades\{DB, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Agendamento', 'guard' => 'master'])]
class Show extends Component
{
    public Appointment $appointment;

    public function mount(Appointment $appointment): void
    {
        $this->appointment = $appointment->load(['user', 'specialist', 'payment', 'room']);
    }

    public function cancelAppointment(): void
    {
        try {
            $creditGranted          = null;
            $companyCreditsRefunded = 0;

            DB::transaction(function () use (&$creditGranted, &$companyCreditsRefunded) {
                $this->appointment->update(['status' => 'cancelled']);

                if ($this->appointment->room) {
                    $this->appointment->room->update(['status' => 'closed', 'closed_at' => now()]);
                }

                $payment = $this->appointment->payment()->first();

                if ($payment && $payment->status === 'paid' && $this->appointment->user) {
                    // Sessão paga com crédito da empresa → devolve o crédito ao saldo da empresa
                    if (($payment->metadata['company_credit'] ?? false) === true) {
                        $companyCreditsRefunded = app(CompanyCreditService::class)->refundForAppointment($this->appointment);
                    } else {
                        $creditGranted = app(UserCreditService::class)->grant(
                            $this->appointment->user,
                            (float) $payment->amount,
                            UserCredit::SOURCE_CANCELLATION,
                            $this->appointment,
                            null,
                            "Crédito por cancelamento da sessão #{$this->appointment->id} pelo administrador"
                        );
                    }
                }
            });

            $message = match (true) {
                $creditGranted !== null     => 'Agendamento cancelado e R$ ' . number_format((float) $creditGranted->amount, 2, ',', '.') . ' devolvidos como crédito ao paciente.',
                $companyCreditsRefunded > 0 => 'Agendamento cancelado e o crédito devolvido ao saldo da empresa.',
                default                     => 'Agendamento cancelado com sucesso.',
            };

            LivewireAlert::title('Cancelado!')
                ->text($message)
                ->success()
                ->show();

            $this->appointment->refresh();
        } catch (\Exception $e) {
            Log::error('Erro ao cancelar agendamento pelo master', [
                'appointment_id' => $this->appointment->id,
                'error'          => $e->getMessage(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Não foi possível cancelar o agendamento.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.master.appointment.show');
    }
}
