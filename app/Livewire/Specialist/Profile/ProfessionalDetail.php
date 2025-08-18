<?php

namespace App\Livewire\Specialist\Profile;

use App\Models\Specialist;
use Illuminate\Support\Facades\{Auth, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dados profissionais', 'guard' => 'specialist'])]
class ProfessionalDetail extends Component
{
    public Specialist $specialist;

    #[Rule(['required', 'integer', 'min:10', 'max:1000'])]
    public ?int $appointment_value = null;

    #[Rule(['required', 'min:50', 'max:255'])]
    public ?string $summary = null;

    #[Rule(['required', 'min:50', 'max:255'])]
    public ?string $description = null;

    public function mount()
    {
        $this->specialist = Auth::guard('specialist')->user();

        $this->appointment_value = $this->specialist->appointment_value;
        $this->summary           = $this->specialist->summary;
        $this->description       = $this->specialist->description;
    }

    public function updateProfile()
    {
        $this->validate();

        try {
            $this->specialist->update([
                'appointment_value' => $this->appointment_value,
                'summary'           => $this->summary,
                'description'       => $this->description,
            ]);

            LivewireAlert::title('Perfil atualizado com sucesso!')
                ->text('Suas informações foram salvas com sucesso.')
                ->success()
                ->show();
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'ip'      => request()->ip(),
            ]);

            dd($e->getMessage());

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar salvar os dados.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.specialist.profile.professional-detail');
    }
}
