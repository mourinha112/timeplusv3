<?php

namespace App\Livewire\User\Appointment;

use App\Notifications\Specialist\AppointmentCancelledNotification as SpecialistAppointmentCancelledNotification;
use App\Notifications\User\AppointmentCancelledNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Agendamentos', 'guard' => 'user'])]
class Index extends Component
{
    #[Computed]
    public function appointments()
    {
        return Auth::user()->appointments()
            ->with(['specialist', 'payment', 'room'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get();
    }

    public function isPaid($appointment)
    {
        return $appointment->payment && $appointment->payment->status === 'paid';
    }

    public function needsPayment($appointment)
    {
        return !$this->isPaid($appointment);
    }

    public function hasRoom($appointment)
    {
        return $appointment->room && $appointment->room->status === 'open';
    }

    public function hasScheduledRoom($appointment)
    {
        return $appointment->room && $appointment->room->status === 'closed';
    }

    public function getRoomAvailableIn($appointment)
    {
        if (!$this->hasScheduledRoom($appointment)) {
            return null;
        }

        $appointmentDateTime = \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
        $openTime            = $appointmentDateTime->subMinutes(10);
        $now                 = \Carbon\Carbon::now();

        if ($now >= $openTime) {
            return 'Disponível agora';
        }

        $diffInMinutes = $now->diffInMinutes($openTime, false);

        if ($diffInMinutes >= 60) {
            $hours   = intval($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;

            return "Disponível em {$hours}h" . ($minutes > 0 ? " {$minutes}min" : "");
        }

        return "Disponível em {$diffInMinutes}min";
    }

    public function getRoomOpenTime($appointment)
    {
        if (!$this->hasScheduledRoom($appointment)) {
            return null;
        }

        $appointmentDateTime = \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);

        return $appointmentDateTime->subMinutes(10); // 10min antes da consulta
    }

    public function getDisplayValue($appointment)
    {
        $user = Auth::user();

        try {
            // Se o usuário tem plano de empresa, calcular desconto para exibição
            $paymentCalculation = $user->calculatePaymentAmount($appointment->total_value);

            return $paymentCalculation['employee_amount'];
        } catch (\Exception $e) {
            // Se houver erro no cálculo, retornar valor original
            return $appointment->total_value;
        }
    }

    public function hasDiscount($appointment)
    {
        $user = Auth::user();

        try {
            $paymentCalculation = $user->calculatePaymentAmount($appointment->total_value);

            return $paymentCalculation['has_company_discount'];
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getDiscountInfo($appointment)
    {
        $user = Auth::user();

        try {
            return $user->calculatePaymentAmount($appointment->total_value);
        } catch (\Exception $e) {
            return [
                'employee_amount'      => $appointment->total_value,
                'company_amount'       => 0,
                'discount_percentage'  => 0,
                'has_company_discount' => false,
            ];
        }
    }

    /* Verifica se o cliente pode cancelar (mínimo 24h de antecedência) */
    public function canCancel($appointment): bool
    {
        if ($appointment->status !== 'scheduled') {
            return false;
        }

        $appointmentDateTime = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);

        return now()->diffInHours($appointmentDateTime, false) >= 24;
    }

    /* Cancelamento pelo cliente com regra de 24h */
    public function cancelAppointment($appointmentId)
    {
        $appointment = Auth::user()->appointments()->with('specialist')->find($appointmentId);

        if (!$appointment) {
            return;
        }

        if (!$this->canCancel($appointment)) {
            LivewireAlert::title('Não é possível cancelar')
                ->text('O cancelamento deve ser feito com no mínimo 24 horas de antecedência.')
                ->error()
                ->show();

            return;
        }

        try {
            $appointment->update(['status' => 'cancelled']);

            Auth::user()->notify(new AppointmentCancelledNotification($appointment));
            $appointment->specialist->notify(new SpecialistAppointmentCancelledNotification($appointment));

            LivewireAlert::title('Sessão cancelada')
                ->text('Sua sessão foi cancelada com sucesso.')
                ->success()
                ->show();
        } catch (\Exception $e) {
            Log::error('Erro ao cancelar agendamento::' . get_class($this), [
                'message' => $e->getMessage(),
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao cancelar a sessão.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.user.appointment.index');
    }
}
