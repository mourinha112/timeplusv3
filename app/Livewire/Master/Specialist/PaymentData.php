<?php

namespace App\Livewire\Master\Specialist;

use App\Models\{Specialist, SpecialistPaymentProfile};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dados de Pagamento', 'guard' => 'master'])]
class PaymentData extends Component
{
    public Specialist $specialist;

    public ?SpecialistPaymentProfile $profile = null;

    #[Rule('required|string|max:255')]
    public string $holder_name = '';

    #[Rule('required|string|max:14')]
    public string $holder_cpf = '';

    #[Rule('required|in:pix,bank_account')]
    public string $payment_type = 'pix';

    #[Rule('nullable|in:cpf,email,phone,random')]
    public ?string $pix_key_type = null;

    #[Rule('nullable|string|max:255')]
    public ?string $pix_key = null;

    #[Rule('nullable|string|max:10')]
    public ?string $bank_code = null;

    #[Rule('nullable|string|max:255')]
    public ?string $bank_name = null;

    #[Rule('nullable|string|max:10')]
    public ?string $agency = null;

    #[Rule('nullable|string|max:20')]
    public ?string $account_number = null;

    #[Rule('nullable|string|max:2')]
    public ?string $account_digit = null;

    #[Rule('nullable|in:checking,savings')]
    public ?string $account_type = null;

    #[Rule('required|numeric|min:0|max:100')]
    public float $platform_fee_percentage = 20.00;

    #[Rule('boolean')]
    public bool $is_verified = false;

    public function mount(Specialist $specialist): void
    {
        $this->specialist = $specialist;
        $this->profile    = $specialist->paymentProfile;

        if ($this->profile) {
            $this->holder_name             = $this->profile->holder_name;
            $this->holder_cpf              = $this->profile->holder_cpf;
            $this->payment_type            = $this->profile->payment_type;
            $this->pix_key_type            = $this->profile->pix_key_type;
            $this->pix_key                 = $this->profile->pix_key;
            $this->bank_code               = $this->profile->bank_code;
            $this->bank_name               = $this->profile->bank_name;
            $this->agency                  = $this->profile->agency;
            $this->account_number          = $this->profile->account_number;
            $this->account_digit           = $this->profile->account_digit;
            $this->account_type            = $this->profile->account_type;
            $this->platform_fee_percentage = (float) $this->profile->platform_fee_percentage;
            $this->is_verified             = (bool) $this->profile->is_verified;
        } else {
            $this->holder_name = $specialist->name;
            $this->holder_cpf  = $specialist->cpf ?? '';
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->payment_type === 'pix') {
            $this->validate([
                'pix_key_type' => 'required|in:cpf,email,phone,random',
                'pix_key'      => 'required|string|max:255',
            ]);
        } else {
            $this->validate([
                'bank_code'      => 'required|string|max:10',
                'bank_name'      => 'required|string|max:255',
                'agency'         => 'required|string|max:10',
                'account_number' => 'required|string|max:20',
                'account_digit'  => 'required|string|max:2',
                'account_type'   => 'required|in:checking,savings',
            ]);
        }

        $payload = [
            'specialist_id'           => $this->specialist->id,
            'holder_name'             => $this->holder_name,
            'holder_cpf'              => $this->holder_cpf,
            'payment_type'            => $this->payment_type,
            'pix_key_type'            => $this->payment_type === 'pix' ? $this->pix_key_type : null,
            'pix_key'                 => $this->payment_type === 'pix' ? $this->pix_key : null,
            'bank_code'               => $this->payment_type === 'bank_account' ? $this->bank_code : null,
            'bank_name'               => $this->payment_type === 'bank_account' ? $this->bank_name : null,
            'agency'                  => $this->payment_type === 'bank_account' ? $this->agency : null,
            'account_number'          => $this->payment_type === 'bank_account' ? $this->account_number : null,
            'account_digit'           => $this->payment_type === 'bank_account' ? $this->account_digit : null,
            'account_type'            => $this->payment_type === 'bank_account' ? $this->account_type : null,
            'platform_fee_percentage' => $this->platform_fee_percentage,
            'is_verified'             => $this->is_verified,
            'verified_at'             => $this->is_verified ? ($this->profile?->verified_at ?? now()) : null,
        ];

        if ($this->profile) {
            $this->profile->update($payload);
        } else {
            $this->profile = SpecialistPaymentProfile::create($payload);
        }

        LivewireAlert::title('Salvo!')
            ->text('Dados de pagamento atualizados com sucesso.')
            ->success()
            ->show();
    }

    public function render()
    {
        return view('livewire.master.specialist.payment-data');
    }
}
