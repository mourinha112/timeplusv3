<?php

namespace App\Livewire\Company\Credit;

use App\Models\{CompanyCreditBalance, CompanyCreditUsage, CompanyPlan, Payment};
use App\Services\Billing\CompanyBillingService;
use App\Services\Credit\CompanyCreditService;
use Illuminate\Support\Facades\{Auth, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Computed, Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Créditos', 'guard' => 'company'])]
class Index extends Component
{
    public $company = null;

    #[Rule('required|integer|min:1|max:1000')]
    public $purchase_credits = 10;

    /** Cobrança PIX gerada na compra de créditos extras (exibe QR Code). */
    public ?int $pixPaymentId = null;

    public function mount()
    {
        $this->company = Auth::guard('company')->user();
    }

    /** Plano ativo por pacote de créditos (se houver). */
    #[Computed]
    public function creditPlan(): ?CompanyPlan
    {
        return $this->company->companyPlans()
            ->where('is_active', true)
            ->where('billing_model', CompanyPlan::BILLING_CREDIT_PACK)
            ->first();
    }

    #[Computed]
    public function creditSummary(): array
    {
        $service = app(CompanyCreditService::class);

        return [
            'total'   => $service->balance($this->company),
            'monthly' => $service->monthlyRemaining($this->company),
            'extra'   => $service->extraRemaining($this->company),
        ];
    }

    /** Buckets extras válidos, com validade (para mostrar vencimentos). */
    #[Computed]
    public function extraBuckets()
    {
        return CompanyCreditBalance::query()
            ->where('company_id', $this->company->id)
            ->where('bucket', CompanyCreditBalance::BUCKET_EXTRA)
            ->usable()
            ->orderBy('valid_until')
            ->get();
    }

    /** Últimos usos de crédito (funcionário + sessão). */
    #[Computed]
    public function usages()
    {
        return CompanyCreditUsage::query()
            ->whereHas('balance', fn ($q) => $q->where('company_id', $this->company->id))
            ->with(['user', 'appointment.specialist', 'balance'])
            ->latest()
            ->limit(30)
            ->get();
    }

    /** Compras de créditos extras aguardando pagamento. */
    #[Computed]
    public function pendingPurchases()
    {
        return Payment::query()
            ->where('company_id', $this->company->id)
            ->where('status', 'pending_payment')
            ->where('metadata->type', 'company_credit_purchase')
            ->latest()
            ->get();
    }

    #[Computed]
    public function pixPayment(): ?Payment
    {
        if (!$this->pixPaymentId) {
            return null;
        }

        return Payment::query()
            ->where('company_id', $this->company->id)
            ->find($this->pixPaymentId);
    }

    public function buyCredits(): void
    {
        $this->validate();

        try {
            $payment = app(CompanyBillingService::class)->createExtraCreditPurchase(
                $this->company,
                (int) $this->purchase_credits,
                $this->creditPlan
            );

            $this->pixPaymentId = $payment->id;

            LivewireAlert::title('Cobrança gerada!')
                ->text('Pague o PIX abaixo para receber os créditos. Eles são liberados automaticamente após a confirmação.')
                ->success()
                ->show();
        } catch (\Exception $e) {
            Log::error('Erro ao gerar compra de créditos extras', [
                'company_id' => $this->company->id,
                'credits'    => $this->purchase_credits,
                'message'    => $e->getMessage(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Não foi possível gerar a cobrança PIX. Verifique os dados da empresa (CNPJ e e-mail) e tente novamente.')
                ->error()
                ->show();
        }
    }

    public function showPix(int $paymentId): void
    {
        $this->pixPaymentId = $paymentId;
    }

    public function render()
    {
        return view('livewire.company.credit.index');
    }
}
