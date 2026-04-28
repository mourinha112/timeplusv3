<?php

namespace App\Livewire\Master\Finance;

use App\Models\SpecialistPaymentProfile;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app', ['title' => 'Aprovação de Dados Bancários', 'guard' => 'master'])]
class BankApprovals extends Component
{
    use WithPagination;

    public string $filter = 'pending';

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function approve(int $profileId): void
    {
        $profile = SpecialistPaymentProfile::findOrFail($profileId);

        $profile->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        LivewireAlert::title('Aprovado!')
            ->text("Dados bancários de {$profile->specialist?->name} aprovados.")
            ->success()
            ->show();
    }

    public function revoke(int $profileId): void
    {
        $profile = SpecialistPaymentProfile::findOrFail($profileId);

        $profile->update([
            'is_verified' => false,
            'verified_at' => null,
        ]);

        LivewireAlert::title('Aprovação removida')
            ->text("Os dados bancários de {$profile->specialist?->name} voltaram para pendente.")
            ->warning()
            ->show();
    }

    public function render()
    {
        $query = SpecialistPaymentProfile::with('specialist');

        if ($this->filter === 'pending') {
            $query->where('is_verified', false);
        } elseif ($this->filter === 'approved') {
            $query->where('is_verified', true);
        }

        return view('livewire.master.finance.bank-approvals', [
            'profiles' => $query->orderByDesc('updated_at')->paginate(15),
        ]);
    }
}
