<?php

namespace App\Livewire\User\Appointment;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Agendamentos', 'guard' => 'user'])]
class Index extends Component
{
    #[Computed]
    public function appointments()
    {
        return Auth::user()->appointments()
            ->with(['specialist', 'payment'])
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

    public function render()
    {
        return view('livewire.user.appointment.index');
    }
}
