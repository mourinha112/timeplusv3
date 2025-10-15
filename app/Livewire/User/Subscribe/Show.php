<?php

namespace App\Livewire\User\Subscribe;

use App\Models\{CompanyUser, User};
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Assinatura', 'guard' => 'user'])]
class Show extends Component
{
    public $subscribe;

    public $companyPlan;

    public $hasCompanyPlan = false;

    #[Computed]
    public function pendingSubscribe()
    {
        $user = User::find(Auth::id());

        return $user->subscribes()
            ->whereHas('payments', function ($query) {
                $query->whereIn('status', ['pending', 'pending_payment']);
            })
            ->with('plan', 'payments')
            ->first();
    }

    public function mount()
    {
        $user = User::find(Auth::id());

        // Verificar se tem assinatura individual ativa
        $this->subscribe = $user->subscribes()
            ->with([
                'plan',
                'payments' => function ($query) {
                    $query->where('status', 'paid')
                        ->orderByDesc('paid_at')
                        ->orderByDesc('id');
                },
            ])
            ->where('end_date', '>', now())
            ->orderByDesc('end_date')
            ->first();

        // Verificar se está vinculado a uma empresa (ativo ou inativo)
        $companyPlan = CompanyUser::where('user_id', $user->id)
            ->where('is_active', true)
            ->whereNotNull('company_plan_id')
            ->with(['companyPlan.company'])
            ->first();

        if ($companyPlan) {
            $this->companyPlan    = $companyPlan;
            $this->hasCompanyPlan = true;
        }
    }

    public function cancel()
    {
        if ($this->subscribe->cancelled_date) {
            LivewireAlert::title('Assinatura já está cancelada.')
                ->text('Você não pode cancelar uma assinatura que já foi cancelada.')
                ->warning()
                ->show();
        }

        $this->subscribe->update(['cancelled_date' => now()]);

        LivewireAlert::title('Sucesso!')
            ->text('Assinatura cancelada com sucesso!')
            ->success()
            ->show();
    }

    public function render()
    {
        return view('livewire.user.subscribe.show');
    }
}
