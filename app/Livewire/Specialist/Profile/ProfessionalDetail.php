<?php

namespace App\Livewire\Specialist\Profile;

use App\Models\Specialist;
use Illuminate\Support\Facades\{Auth, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dados profissionais', 'guard' => 'specialist'])]
class ProfessionalDetail extends Component
{
    public Specialist $specialist;

    public ?float $particular_session_value = null;
    public ?int $session_duration_minutes = null;
    public bool $accepts_timeplus = true;
    public bool $accepts_particular = true;
    public ?string $summary = null;
    public ?string $description = null;

    public function rules(): array
    {
        return [
            'particular_session_value' => ['required', 'numeric', 'min:30', 'max:1000'],
            'session_duration_minutes' => [
                'required',
                'integer',
                'min:' . Specialist::MIN_SESSION_DURATION,
                'max:' . Specialist::MAX_SESSION_DURATION,
            ],
            'accepts_timeplus'         => ['boolean'],
            'accepts_particular'       => ['boolean'],
            'summary'                  => ['required', 'min:50', 'max:255'],
            'description'              => ['required', 'min:50', 'max:255'],
        ];
    }

    public function mount(): void
    {
        $this->specialist = Auth::guard('specialist')->user();

        $this->particular_session_value = (float) $this->specialist->appointment_value;
        $this->session_duration_minutes = $this->specialist->getSessionDuration();
        $this->accepts_timeplus         = (bool) ($this->specialist->accepts_timeplus ?? true);
        $this->accepts_particular       = (bool) ($this->specialist->accepts_particular ?? true);
        $this->summary                  = $this->specialist->summary;
        $this->description              = $this->specialist->description;
    }

    public function updateProfile(): void
    {
        $this->validate();

        if (!$this->accepts_timeplus && !$this->accepts_particular) {
            LivewireAlert::title('Selecione ao menos uma modalidade')
                ->text('Você precisa aceitar ao menos atendimentos TimePlus ou Particular.')
                ->warning()
                ->show();

            return;
        }

        try {
            $this->specialist->update([
                'particular_session_value' => $this->particular_session_value,
                'session_duration_minutes' => $this->session_duration_minutes,
                'accepts_timeplus'         => $this->accepts_timeplus,
                'accepts_particular'       => $this->accepts_particular,
                'summary'                  => $this->summary,
                'description'              => $this->description,
            ]);

            LivewireAlert::title('Perfil atualizado com sucesso!')
                ->text('Suas informações foram salvas.')
                ->success()
                ->show();
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
        return view('livewire.specialist.profile.professional-detail', [
            'durationOptions'  => [15, 20, 30, 40, 45, 50],
            'timeplusFixedFee' => Specialist::TIMEPLUS_SESSION_VALUE,
        ]);
    }
}
