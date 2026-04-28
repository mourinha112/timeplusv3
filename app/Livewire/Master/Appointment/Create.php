<?php

namespace App\Livewire\Master\Appointment;

use App\Models\{Appointment, Specialist, User};
use App\Services\Payment\PayoutCalculator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\{DB, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Novo Agendamento', 'guard' => 'master'])]
class Create extends Component
{
    #[Rule('required|exists:users,id')]
    public $user_id = null;

    #[Rule('required|exists:specialists,id')]
    public $specialist_id = null;

    #[Rule('required|date_format:d/m/Y|after_or_equal:today')]
    public $appointment_date = '';

    #[Rule('required|string')]
    public $appointment_time = '';

    #[Rule('required|in:timeplus,particular')]
    public string $service_mode = 'particular';

    #[Rule('required|numeric|min:0')]
    public float $total_value = 0.0;

    #[Rule('required|integer|min:15|max:120')]
    public int $duration_minutes = 30;

    #[Rule('nullable|string|max:1000')]
    public ?string $notes = null;

    public function updatedSpecialistId($value): void
    {
        if (!$value) {
            return;
        }

        $specialist = Specialist::find($value);

        if (!$specialist) {
            return;
        }

        $this->duration_minutes = $specialist->getSessionDuration();
        $this->total_value      = $this->service_mode === 'timeplus'
            ? Specialist::TIMEPLUS_SESSION_VALUE
            : (float) $specialist->appointment_value;
    }

    public function updatedServiceMode($value): void
    {
        if (!$this->specialist_id) {
            return;
        }

        $specialist        = Specialist::find($this->specialist_id);
        $this->total_value = $value === 'timeplus'
            ? Specialist::TIMEPLUS_SESSION_VALUE
            : (float) $specialist?->appointment_value;
    }

    public function save()
    {
        $this->validate();

        try {
            $payout = app(PayoutCalculator::class)->calculate($this->service_mode, $this->total_value);

            $appointment = DB::transaction(function () use ($payout) {
                return Appointment::create([
                    'user_id'           => $this->user_id,
                    'specialist_id'     => $this->specialist_id,
                    'appointment_date'  => Carbon::createFromFormat('d/m/Y', $this->appointment_date)->format('Y-m-d'),
                    'appointment_time'  => $this->appointment_time,
                    'service_mode'      => $this->service_mode,
                    'total_value'       => $payout['total'],
                    'specialist_amount' => $payout['specialist_amount'],
                    'platform_amount'   => $payout['platform_amount'],
                    'duration_minutes'  => $this->duration_minutes,
                    'status'            => 'scheduled',
                    'notes'             => $this->notes,
                ]);
            });

            session()->flash('message', 'Agendamento criado com sucesso!');

            return $this->redirect(route('master.appointment.show', ['appointment' => $appointment->id]));
        } catch (\Exception $e) {
            Log::error('Erro ao criar agendamento pelo master', [
                'error' => $e->getMessage(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao criar o agendamento.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.master.appointment.create', [
            'users'       => User::orderBy('name')->get(['id', 'name', 'cpf']),
            'specialists' => Specialist::orderBy('name')->get(['id', 'name', 'crp', 'appointment_value', 'session_duration_minutes']),
        ]);
    }
}
