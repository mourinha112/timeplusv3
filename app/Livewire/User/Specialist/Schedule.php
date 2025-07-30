<?php

namespace App\Livewire\User\Specialist;

use App\Models\Appointment;
use App\Models\Availability;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Schedule extends Component
{
    public $specialist;
    public $dates = [];

    #[Rule('required|string|date_format:Y-m-d')]
    public $selectedDate = null;

    #[Rule('required|string|date_format:H:i:s')]
    public $selectedTime = null;

    #[Computed]
    public function availabilities()
    {
        $scheduledTimes = Appointment::where('specialist_id', $this->specialist->id)
            ->where('appointment_date', '>=', now()->toDateString())
            // ->where('status', '!=', 'cancelled')
            ->get()
            ->groupBy('appointment_date')
            ->map(function ($appointments) {
                return $appointments->pluck('appointment_time')->toArray();
            })
            ->toArray();

        $availabilities = Availability::where('specialist_id', $this->specialist->id)
            ->where('available_date', '>=', now()->toDateString())
            ->orderBy('available_date')
            ->orderBy('available_time')
            ->get()
            ->groupBy('available_date')
            ->map(function ($items, $date) use ($scheduledTimes) {
                $availableTimes = $items->pluck('available_time')->toArray();
                $scheduled = $scheduledTimes[$date] ?? [];

                // Filtrar horários já agendados
                return array_values(array_diff($availableTimes, $scheduled));
            })
            ->toArray();


        /* Garantir os próximos 5 dias (hoje + 4) */
        $result = [];
        for ($i = 0; $i < 5; $i++) {
            $date = Carbon::today()->addDays($i)->toDateString();
            $result[$date] = $availabilities[$date] ?? [];
        }

        return $result;
    }

    public function selectDate($date)
    {
        // Se clicou na mesma data, deseleciona
        if ($this->selectedDate === $date) {
            $this->selectedDate = null;
            $this->selectedTime = null;
        } else {
            $this->selectedDate = $date;
            $this->selectedTime = null; // Reset do horário ao mudar data
        }
    }

    public function selectTime($date, $time)
    {
        // Se clicou no mesmo horário, deseleciona
        if ($this->selectedDate === $date && $this->selectedTime === $time) {
            $this->selectedTime = null;
        } else {
            $this->selectedDate = $date;
            $this->selectedTime = $time;
        }
    }

    public function selectSchedule($date, $time)
    {
        $this->selectedDate = $date;
        $this->selectedTime = $time;
    }

    public function clearSelection()
    {
        $this->selectedDate = null;
        $this->selectedTime = null;
    }

    public function schedule()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $appointment = Appointment::where('specialist_id', $this->specialist->id)
                ->where('appointment_date', $this->selectedDate)
                ->where('appointment_time', $this->selectedTime)
                ->first();

            if ($appointment) {
                LivewireAlert::title('Horário Indisponível')
                    ->text('O horário selecionado já está ocupado. Por favor, escolha outro horário.')
                    ->error()
                    ->show();

                $this->clearSelection();
                return;
            }

            Appointment::create([
                'user_id' => Auth::user()->id,
                'specialist_id' => $this->specialist->id,
                'appointment_date' => $this->selectedDate,
                'appointment_time' => $this->selectedTime,
                'status' => 'pending',
            ]);

            LivewireAlert::title('Agendamento Confirmado')
                ->text('Seu agendamento foi realizado com sucesso!')
                ->success()
                ->show();

            $this->clearSelection();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro ao agendar consulta', [
                'user_id' => Auth::id(),
                'specialist_id' => $this->specialist->id,
                'date' => $this->selectedDate,
                'time' => $this->selectedTime,
                'error' => $e->getMessage(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao agendar sua consulta. Por favor, tente novamente.')
                ->error()
                ->show();

            $this->clearSelection();
        }
    }

    public function render()
    {
        return view('livewire.user.specialist.schedule');
    }
}
