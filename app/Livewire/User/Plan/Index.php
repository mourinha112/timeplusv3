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

    #[Computed]
    public function pendingSubscribe()
    {
        $user = User::find(Auth::id());

        return $user->subscribes()
            ->where(function ($query) {
                // Assinaturas com pagamentos pendentes
                $query->whereHas('payments', function ($q) {
                    $q->whereIn('status', ['pending', 'pending_payment']);
                })
                // OU assinaturas criadas mas sem nenhum pagamento confirmado
                ->orWhereDoesntHave('payments', function ($q) {
                    $q->whereIn('status', ['paid', 'confirmed']);
                });
            })
            ->with('plan', 'payments')
            ->orderByDesc('created_at')
            ->first();
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
            $this->companyPlan    = $companyPlan;

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

    public function confirmSubscription($planId)
    {
        // Limpar Subscribes pendentes antigos (mais de 30 minutos sem pagamento)
        Subscribe::where('user_id', Auth::id())
            ->whereDoesntHave('payments', function ($query) {
                $query->whereIn('status', ['paid', 'confirmed']);
            })
            ->where('created_at', '<', now()->subMinutes(30))
            ->delete();

        // Verificar se tem algum pagamento pendente recente (com ou sem payment)
        $pendingSubscribe = Subscribe::where('user_id', Auth::id())
            ->where(function ($query) {
                // Sem pagamentos confirmados
                $query->whereDoesntHave('payments', function ($q) {
                    $q->whereIn('status', ['paid', 'confirmed']);
                })
                // OU com pagamentos pendentes
                ->orWhereHas('payments', function ($q) {
                    $q->whereIn('status', ['pending', 'pending_payment']);
                });
            })
            ->first();

        if ($pendingSubscribe) {
            $this->dispatch('swal:confirm', [
                'icon'              => 'warning',
                'title'             => 'Atenção!',
                'text'              => 'Você já tem uma assinatura aguardando pagamento. Complete o pagamento antes de iniciar outra.',
                'confirmButtonText' => 'Ir para o pagamento',
                'cancelButtonText'  => 'Cancelar',
                'method'            => 'goToPendingPayment',
                'params'            => ['subscribe_id' => $pendingSubscribe->id],
            ]);

            return;
        }

        // Confirmação de assinatura
        $plan = Plan::findOrFail($planId);

        $this->dispatch('swal:confirm', [
            'icon'  => 'warning',
            'title' => 'Confirmar Assinatura',
            'html'  => "
                <div class='text-left space-y-2'>
                    <p><strong>Plano:</strong> {$plan->name}</p>
                    <p><strong>Valor:</strong> R$ " . number_format($plan->price, 2, ',', '.') . "</p>
                    <p><strong>Duração:</strong> {$plan->duration_days} dias</p>
                    <hr class='my-4' />
                    <p class='text-sm text-warning'>⚠️ Ao continuar, você será direcionado para o pagamento e <strong>deverá finalizar a compra</strong>.</p>
                </div>
            ",
            'confirmButtonText' => 'Continuar para o pagamento',
            'cancelButtonText'  => 'Cancelar',
            'method'            => 'proceedToPayment',
            'params'            => ['plan_id' => $planId],
        ]);
    }

    public function goToPendingPayment($data)
    {
        $subscribe = Subscribe::findOrFail($data['subscribe_id']);
        $this->redirect(route('user.plan.payment', ['plan_id' => $subscribe->plan_id]), navigate: true);
    }

    public function proceedToPayment($data)
    {
        $this->redirect(route('user.plan.payment', ['plan_id' => $data['plan_id']]), navigate: true);
    }

    public function render()
    {
        return view('livewire.user.plan.index');
    }
}
