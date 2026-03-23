<?php

namespace App\Livewire\User\Appointment;

use App\Models\Appointment;
use Illuminate\Support\Facades\{Auth, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Realizar pagamento', 'guard' => 'user'])]
class Payment extends Component
{
    public $appointment;

    public function mount($appointment_id)
    {
        try {
            $this->appointment = Appointment::where(['id' => $appointment_id, 'user_id' => Auth::id()])->first();

            if (!$this->appointment) {
                $this->redirect(route('user.appointment.index'), navigate: true);

                return;
            }

            // Bloquear pagamento de sessão que já passou
            $appointmentDateTime = \Carbon\Carbon::parse($this->appointment->appointment_date . ' ' . $this->appointment->appointment_time);
            if ($appointmentDateTime->isPast()) {
                LivewireAlert::title('Sessão expirada')
                    ->text('Não é possível pagar uma sessão que já passou. Agende uma nova sessão.')
                    ->warning()
                    ->show();

                $this->redirect(route('user.appointment.index'), navigate: true);

                return;
            }
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar acessar a página de pagamentos.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.user.appointment.payment');
    }
}
