<?php

namespace App\Livewire\Specialist\Onboarding;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.guest', ['title' => 'Dados Profissionais', 'guard' => 'specialist'])]
class ProfessionalDetail extends Component
{
    #[Rule(['required', 'integer', 'min:10', 'max:1000'])]
    public ?int $appointment_value = null;

    #[Rule(['required', 'min:50', 'max:255'])]
    public ?string $summary = null;

    #[Rule(['required', 'min:50', 'max:255'])]
    public ?string $description = null;

    public function submit()
    {
        $this->validate();

        try {
            $specialist = Auth::guard('specialist')->user();

            $specialist->update([
                'appointment_value' => $this->appointment_value,
                'summary'           => $this->summary,
                'description'       => $this->description,
                'onboarding_step'   => 'completed',
            ]);

            return $this->redirect(route('specialist.appointment.index'), true);
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar salvar os dados.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.specialist.onboarding.professional-detail');
    }
}
