<?php

namespace App\Livewire\User\Dashboard;

use App\Models\CompanyPlan;
use App\Services\Credit\{CompanyCreditService, UserCreditService};
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dashboard', 'guard' => 'user'])]
class Show extends Component
{
    #[Computed]
    public function nextAppointment()
    {
        return Auth::user()->appointments()
            ->with(['specialist', 'payment', 'room'])
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'completed')
            ->where(function ($query) {
                $query->where('appointment_date', '>', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('appointment_date', now()->toDateString())
                            ->whereRaw("ADDTIME(appointment_time, '01:00:00') >= ?", [now()->format('H:i:s')]);
                    });
            })
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->first();
    }

    #[Computed]
    public function hasActivePlan(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if (method_exists($user, 'hasActiveCompanyPlan') && $user->hasActiveCompanyPlan()) {
            return true;
        }

        return $user->subscribes()
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->whereHas('payments', fn ($q) => $q->where('status', 'paid'))
            ->exists();
    }

    #[Computed]
    public function canAccessPlans(): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $user->companyUsers()
            ->where('is_active', true)
            ->whereNotNull('company_plan_id')
            ->exists();
    }

    /** Resumo de créditos do plano da empresa (pacote de créditos) para o card. */
    #[Computed]
    public function companyCreditInfo(): ?array
    {
        $user        = Auth::user();
        $companyUser = $user?->getActiveCompanyPlan();

        if (!$companyUser || !$companyUser->companyPlan?->isCreditPack()) {
            return null;
        }

        $service = app(CompanyCreditService::class);
        $company = $companyUser->company;
        $plan    = $companyUser->companyPlan;

        $used  = $service->usedByUserInMonth($company, $user);
        $limit = $plan->credits_per_employee;

        return [
            'limit'              => $limit,
            'used'               => $used,
            'employee_remaining' => $limit !== null ? max(0, $limit - $used) : null,
            'company_balance'    => $service->balance($company),
            'unit_price'         => CompanyPlan::CREDIT_UNIT_PRICE,
        ];
    }

    /** Saldo monetário pessoal (cancelamentos/estornos). */
    #[Computed]
    public function personalCreditBalance(): float
    {
        return app(UserCreditService::class)->balance(Auth::user());
    }

    public function isPaid($appointment)
    {
        return $appointment->payment && $appointment->payment->status === 'paid';
    }

    public function hasRoom($appointment)
    {
        return $appointment->room && $appointment->room->status === 'open';
    }

    public function hasScheduledRoom($appointment)
    {
        return $appointment->room && $appointment->room->status === 'closed';
    }

    public function getRoomOpenTime($appointment)
    {
        if (!$this->hasScheduledRoom($appointment)) {
            return null;
        }

        $appointmentDateTime = \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);

        return $appointmentDateTime->subMinutes(10);
    }

    public function render()
    {
        return view('livewire.user.dashboard.show');
    }
}
