<?php

namespace App\Livewire\Master\Appointment;

use App\Models\{Appointment, Specialist, User};
use App\Services\Payment\PayoutCalculator;
use Illuminate\Support\Carbon;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Agendamento', 'guard' => 'master'])]
class Edit extends Component
{
    public Appointment $appointment;

    #[Rule('required|exists:users,id')]
    public $user_id = null;

    #[Rule('required|exists:specialists,id')]
    public $specialist_id = null;

    #[Rule('required|date_format:d/m/Y')]
    public $appointment_date = '';

    #[Rule('required|string')]
    public $appointment_time = '';

    #[Rule('required|in:timeplus,particular')]
    public string $service_mode = 'particular';

    #[Rule('required|numeric|min:0')]
    public float $total_value = 0.0;

    #[Rule('required|integer|min:15|max:120')]
    public int $duration_minutes = 30;

    #[Rule('required|in:scheduled,completed,cancelled,no_show')]
    public string $status = 'scheduled';

    #[Rule('nullable|string|max:1000')]
    public ?string $notes = null;

    public function mount(Appointment $appointment): void
    {
        $this->appointment      = $appointment;
        $this->user_id          = $appointment->user_id;
        $this->specialist_id    = $appointment->specialist_id;
        $this->appointment_date = Carbon::parse($appointment->appointment_date)->format('d/m/Y');
        $this->appointment_time = substr((string) $appointment->appointment_time, 0, 5);
        $this->service_mode     = $appointment->service_mode ?? 'particular';
        $this->total_value      = (float) $appointment->total_value;
        $this->duration_minutes = (int) ($appointment->duration_minutes ?: 30);
        $this->status           = $appointment->status;
        $this->notes            = $appointment->notes;
    }

    public function save()
    {
        $this->validate();

        $payout = app(PayoutCalculator::class)->calculate($this->service_mode, $this->total_value);

        $this->appointment->update([
            'user_id'           => $this->user_id,
            'specialist_id'     => $this->specialist_id,
            'appointment_date'  => Carbon::createFromFormat('d/m/Y', $this->appointment_date)->format('Y-m-d'),
            'appointment_time'  => $this->appointment_time,
            'service_mode'      => $this->service_mode,
            'total_value'       => $payout['total'],
            'specialist_amount' => $payout['specialist_amount'],
            'platform_amount'   => $payout['platform_amount'],
            'duration_minutes'  => $this->duration_minutes,
            'status'            => $this->status,
            'notes'             => $this->notes,
        ]);

        LivewireAlert::title('Atualizado!')
            ->text('Agendamento atualizado com sucesso.')
            ->success()
            ->show();

        return $this->redirect(route('master.appointment.show', ['appointment' => $this->appointment->id]));
    }

    public function render()
    {
        return view('livewire.master.appointment.edit', [
            'users'       => User::orderBy('name')->get(['id', 'name', 'cpf']),
            'specialists' => Specialist::orderBy('name')->get(['id', 'name', 'crp']),
        ]);
    }
}
