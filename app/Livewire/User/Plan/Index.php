<?php

namespace App\Livewire\User\Plan;

use App\Models\{CompanyUser, Plan, Subscribe, User};
use Illuminate\Support\Facades\{Auth};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Planos', 'guard' => 'user'])]
class Index extends Component
{
    public $hasCompanyPlan = false;
    public $companyPlan = null;

    #[Computed]
    public function plans()
    {
        return Plan::all();
    }

    public function mount()
    {
        $user = User::find(Auth::id());

        // Verificar se tem plano empresarial (ativo ou inativo)
        $companyPlan = CompanyUser::where('user_id', $user->id)
            ->where('is_active', true)
            ->whereNotNull('company_plan_id')
            ->with(['companyPlan.company'])
            ->first();

        if ($companyPlan) {
            $this->hasCompanyPlan = true;
            $this->companyPlan = $companyPlan;

            // Se o plano está ativo, bloqueia completamente
            if ($companyPlan->companyPlan->is_active) {
                return; // Não redireciona, apenas bloqueia a funcionalidade
            }
        }

        // Verificar se já tem assinatura individual ativa
        $subscribe = Subscribe::where('end_date', '>', now())
            ->where('cancelled_date', null)
            ->where('user_id', Auth::id())
            ->whereHas('payments', function ($query) {
                $query->where('status', 'paid');
            })
            ->first();

        if ($subscribe) {
            LivewireAlert::title('Erro!')->text('Você já possui uma assinatura ativa.')->error()->show();
            $this->redirect(route('user.subscribe.show'));
            return;
        }
    }

    public function render()
    {
        return view('livewire.user.plan.index');
    }
}
