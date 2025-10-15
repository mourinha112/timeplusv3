<?php

namespace App\Livewire\User\Plan;

use App\Models\{Plan, Subscribe};
use Illuminate\Support\Facades\{Auth, DB, Log};
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Contratar planos', 'guard' => 'user'])]
class Payment extends Component
{
    public Plan $plan;

    public Subscribe $subscribe;

    #[Rule(['required', 'string', 'in:credit_card,pix'])]
    public ?string $payment_method = null;

    public function mount($plan_id)
    {
        $this->plan = Plan::findOrFail($plan_id);

        // Verificar se já possui assinatura ativa
        $existingSubscribe = Subscribe::where('end_date', '>', now())
            ->where('cancelled_date', null)
            ->where('user_id', Auth::id())
            ->whereHas('payments', function ($query) {
                $query->where('status', 'paid');
            })
            ->first();

        if ($existingSubscribe) {
            session()->flash('error', 'Você já possui uma assinatura ativa.');
            $this->redirect(route('user.subscribe.show'), navigate: true);

            return;
        }

        // Buscar subscribe pendente para este plano (se existir)
        $subscribe = Subscribe::where('user_id', Auth::id())
            ->where('plan_id', $this->plan->id)
            ->whereDoesntHave('payments', function ($query) {
                $query->whereIn('status', ['paid', 'confirmed']);
            })
            ->first();

        // Criar Subscribe no banco se não existir (necessário para exibir dados na view)
        if (!$subscribe) {
            try {
                DB::beginTransaction();

                $startDate = now();
                $endDate   = now()->addDays($this->plan->duration_days);

                $subscribe = Subscribe::create([
                    'user_id'        => Auth::id(),
                    'plan_id'        => $this->plan->id,
                    'start_date'     => $startDate,
                    'end_date'       => $endDate,
                    'cancelled_date' => null,
                ]);

                DB::commit();

                Log::info('Subscribe criado (pendente de pagamento)', [
                    'subscribe_id' => $subscribe->id,
                    'user_id'      => Auth::id(),
                    'plan_id'      => $this->plan->id,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erro ao criar subscribe pendente', [
                    'error'   => $e->getMessage(),
                    'user_id' => Auth::id(),
                    'plan_id' => $this->plan->id,
                ]);

                session()->flash('error', 'Erro ao iniciar assinatura. Tente novamente.');
                $this->redirect(route('user.plan.index'), navigate: true);

                return;
            }
        }

        // Atribuir subscribe
        $this->subscribe = $subscribe;
    }

    public function selectPaymentMethod(string $method)
    {
        $this->payment_method = $method;
    }

    public function render()
    {
        return view('livewire.user.plan.payment');
    }
}
