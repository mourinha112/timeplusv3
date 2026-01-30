<?php

namespace App\Livewire\Specialist\Finance;

use App\Models\{Specialist, SpecialistPaymentProfile};
use Illuminate\Support\Facades\{Auth, DB, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Computed, Layout, Locked, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dados de Pagamento', 'guard' => 'specialist'])]
class PaymentData extends Component
{
    #[Locked]
    public int $specialistId;

    public bool $hasProfile = false;

    // Dados do titular
    #[Rule('required|string|max:255')]
    public string $holder_name = '';

    #[Rule('required|string|size:14')]
    public string $holder_cpf = '';

    // Tipo de pagamento
    #[Rule('required|in:pix,bank_account')]
    public string $payment_type = 'pix';

    // Dados PIX
    #[Rule('required_if:payment_type,pix|nullable|in:cpf,email,phone,random')]
    public ?string $pix_key_type = 'cpf';

    #[Rule('required_if:payment_type,pix|nullable|string|max:255')]
    public ?string $pix_key = '';

    // Dados bancários
    #[Rule('required_if:payment_type,bank_account|nullable|string|max:10')]
    public ?string $bank_code = '';

    #[Rule('required_if:payment_type,bank_account|nullable|string|max:255')]
    public ?string $bank_name = '';

    #[Rule('required_if:payment_type,bank_account|nullable|string|max:10')]
    public ?string $agency = '';

    #[Rule('required_if:payment_type,bank_account|nullable|string|max:20')]
    public ?string $account_number = '';

    #[Rule('required_if:payment_type,bank_account|nullable|string|max:2')]
    public ?string $account_digit = '';

    #[Rule('required_if:payment_type,bank_account|nullable|in:checking,savings')]
    public ?string $account_type = 'checking';

    // Status
    public bool $is_verified = false;

    public function mount(): void
    {
        $specialist = Auth::guard('specialist')->user();
        $this->specialistId = $specialist->id;

        // Preencher dados do titular com os dados do especialista por padrão
        $this->holder_name = $specialist->name ?? '';
        $this->holder_cpf = $specialist->cpf ?? '';

        // Carregar perfil existente se houver
        $profile = SpecialistPaymentProfile::where('specialist_id', $this->specialistId)->first();

        if ($profile) {
            $this->hasProfile = true;
            $this->holder_name = $profile->holder_name;
            $this->holder_cpf = $profile->holder_cpf;
            $this->payment_type = $profile->payment_type;
            $this->pix_key_type = $profile->pix_key_type;
            $this->pix_key = $profile->pix_key;
            $this->bank_code = $profile->bank_code;
            $this->bank_name = $profile->bank_name;
            $this->agency = $profile->agency;
            $this->account_number = $profile->account_number;
            $this->account_digit = $profile->account_digit;
            $this->account_type = $profile->account_type;
            $this->is_verified = $profile->is_verified;
        }
    }

    #[Computed]
    public function banks(): array
    {
        return [
            '001' => 'Banco do Brasil',
            '033' => 'Santander',
            '104' => 'Caixa Econômica Federal',
            '237' => 'Bradesco',
            '341' => 'Itaú',
            '077' => 'Banco Inter',
            '260' => 'Nubank',
            '290' => 'PagBank',
            '336' => 'C6 Bank',
            '212' => 'Banco Original',
            '756' => 'Sicoob',
            '748' => 'Sicredi',
            '422' => 'Safra',
            '070' => 'BRB',
            '085' => 'Ailos',
            '136' => 'Unicred',
            '323' => 'Mercado Pago',
            '380' => 'PicPay',
            '403' => 'Cora',
        ];
    }

    public function updatedBankCode($value): void
    {
        $banks = $this->banks();
        $this->bank_name = $banks[$value] ?? '';
    }

    public function save(): void
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $data = [
                'specialist_id' => $this->specialistId,
                'holder_name'   => $this->holder_name,
                'holder_cpf'    => $this->holder_cpf,
                'payment_type'  => $this->payment_type,
            ];

            if ($this->payment_type === 'pix') {
                $data['pix_key_type'] = $this->pix_key_type;
                $data['pix_key'] = $this->pix_key;
                // Limpar dados bancários
                $data['bank_code'] = null;
                $data['bank_name'] = null;
                $data['agency'] = null;
                $data['account_number'] = null;
                $data['account_digit'] = null;
                $data['account_type'] = null;
            } else {
                // Conta bancária
                $data['bank_code'] = $this->bank_code;
                $data['bank_name'] = $this->bank_name;
                $data['agency'] = $this->agency;
                $data['account_number'] = $this->account_number;
                $data['account_digit'] = $this->account_digit;
                $data['account_type'] = $this->account_type;
                // Limpar dados PIX
                $data['pix_key_type'] = null;
                $data['pix_key'] = null;
            }

            SpecialistPaymentProfile::updateOrCreate(
                ['specialist_id' => $this->specialistId],
                $data
            );

            DB::commit();

            $this->hasProfile = true;

            LivewireAlert::title('Sucesso!')
                ->text('Dados de pagamento salvos com sucesso!')
                ->success()
                ->show();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao salvar dados de pagamento', [
                'specialist_id' => $this->specialistId,
                'error'         => $e->getMessage(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao salvar os dados de pagamento.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.specialist.finance.payment-data');
    }
}
