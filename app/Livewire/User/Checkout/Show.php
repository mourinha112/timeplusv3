<?php

namespace App\Livewire\User\Checkout;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Show extends Component
{
    public $payable;

    public string $payment_method;

    public $discountInfo = [];

    public function mount()
    {
        // Calcula desconto apenas para Appointments
        if ($this->payable instanceof \App\Models\Appointment) {
            $user = Auth::user();
            $this->discountInfo = $this->calculateDiscount($user, $this->payable->total_value);

            // Log para debug
            \Log::info('Discount Info Calculated', [
                'user_id' => $user->id,
                'appointment_id' => $this->payable->id,
                'original_value' => $this->payable->total_value,
                'discount_info' => $this->discountInfo,
            ]);
        }
    }

    protected function calculateDiscount($user, $originalAmount)
    {
        // 1. Primeiro, verificar CompanyPlan (funcionários de empresa)
        $activeCompanyPlan = $user->getActiveCompanyPlan();

        if ($activeCompanyPlan && $activeCompanyPlan->companyPlan) {
            $discountPercentage = $activeCompanyPlan->companyPlan->discount_percentage ?? 0;

            if ($discountPercentage > 0) {
                $discountAmount = ($originalAmount * $discountPercentage) / 100;
                $finalAmount = $originalAmount - $discountAmount;

                return [
                    'employee_amount'      => round($finalAmount, 2),
                    'company_amount'       => round($discountAmount, 2),
                    'discount_percentage'  => $discountPercentage,
                    'has_company_discount' => true,
                    'company_name'         => $activeCompanyPlan->company->name,
                    'plan_name'            => $activeCompanyPlan->companyPlan->name,
                ];
            }
        }

        // 2. Se não tem CompanyPlan, verificar Subscribe/Plan (plano individual COM PAGAMENTO CONFIRMADO)
        $activeSubscribe = $user->subscribes()
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->whereNull('cancelled_date')
            ->whereHas('payments', function ($query) {
                $query->where('status', 'paid');
            })
            ->with('plan')
            ->first();

        if ($activeSubscribe && $activeSubscribe->plan) {
            $discountPercentage = $activeSubscribe->plan->discount_percentage ?? 0;

            if ($discountPercentage > 0) {
                $discountAmount = ($originalAmount * $discountPercentage) / 100;
                $finalAmount = $originalAmount - $discountAmount;

                return [
                    'employee_amount'      => round($finalAmount, 2),
                    'company_amount'       => round($discountAmount, 2),
                    'discount_percentage'  => $discountPercentage,
                    'has_company_discount' => true,
                    'company_name'         => $activeSubscribe->plan->name,
                    'plan_name'            => $activeSubscribe->plan->name,
                ];
            }
        }

        // 3. Sem desconto disponível
        return [
            'employee_amount'      => $originalAmount,
            'company_amount'       => 0,
            'discount_percentage'  => 0,
            'has_company_discount' => false,
        ];
    }

    public function selectPaymentMethod(string $method)
    {
        $this->payment_method = $method;
    }

    public function getFinalAmountProperty()
    {
        if ($this->payable instanceof \App\Models\Appointment && !empty($this->discountInfo['has_company_discount'])) {
            return $this->discountInfo['employee_amount'];
        }

        if ($this->payable instanceof \App\Models\Appointment) {
            return $this->payable->total_value;
        }

        if ($this->payable instanceof \App\Models\Subscribe) {
            return $this->payable->plan?->price ?? 0;
        }

        return 0;
    }

    public function render()
    {
        return view('livewire.user.checkout.show');
    }
}
