<?php

namespace App\Livewire\User\Specialist;

use App\Models\{Appointment, Availability};
use App\Notifications\Specialist\AppointmentScheduledNotification as SpecialistAppointmentScheduledNotification;
use App\Notifications\User\AppointmentScheduledNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, DB, Log};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Computed, Rule};
use Livewire\Component;

class Schedule extends Component
{
    public $specialist;

    public $dates = [];

    #[Rule('required|string|date_format:Y-m-d')]
    public $selectedDate = null;

    #[Rule('required|string|date_format:H:i:s')]
    public $selectedTime = null;

    public array $pricing_info = [
        'original_amount'         => 0,
        'final_amount'            => 0,
        'has_discount'            => false,
        'discount_percentage'     => 0,
        'company_discount_amount' => 0,
        'company_plan_name'       => null,
    ];

    public function mount()
    {
        $this->calculatePricingForDisplay();
    }

    public function calculatePricingForDisplay()
    {
        $user           = Auth::user();
        $originalAmount = $this->specialist->appointment_value;

        try {
            $calculatedPayment = $user->calculatePaymentAmount($originalAmount);

            $this->pricing_info = [
                'original_amount'         => $originalAmount,
                'final_amount'            => $calculatedPayment['employee_amount'],
                'has_discount'            => $calculatedPayment['has_company_discount'],
                'discount_percentage'     => $calculatedPayment['discount_percentage'],
                'company_discount_amount' => $calculatedPayment['company_amount'],
                'company_plan_name'       => $calculatedPayment['plan_name'] ?? null,
            ];
        } catch (\Exception $e) {
            // Se houver erro no cálculo, usar valores padrão
            $this->pricing_info = [
                'original_amount'         => $originalAmount,
                'final_amount'            => $originalAmount,
                'has_discount'            => false,
                'discount_percentage'     => 0,
                'company_discount_amount' => 0,
                'company_plan_name'       => null,
            ];
        }
    }

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
                $scheduled      = $scheduledTimes[$date] ?? [];

                // Filtrar horários já agendados
                $filteredTimes = array_values(array_diff($availableTimes, $scheduled));

                // Se for hoje, filtrar horários que já passaram
                if ($date === now()->toDateString()) {
                    $currentTime   = now()->format('H:i');
                    $filteredTimes = array_filter($filteredTimes, function ($time) use ($currentTime) {
                        return $time > $currentTime;
                    });
                    $filteredTimes = array_values($filteredTimes); // Reindexar array
                }

                return $filteredTimes;
            })
            ->toArray();

        /* Garantir os próximos 5 dias (hoje + 4) */
        $result = [];

        for ($i = 0; $i < 5; $i++) {
            $date          = Carbon::today()->addDays($i)->toDateString();
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
                // ->where('status', '!=', 'cancelled')
                ->first();

            if ($appointment) {
                LivewireAlert::title('Horário Indisponível')
                    ->text('O horário selecionado já está ocupado. Por favor, escolha outro horário.')
                    ->error()
                    ->show();

                $this->clearSelection();

                return;
            }

            $appointment = Appointment::create([
                'user_id'          => Auth::user()->id,
                'specialist_id'    => $this->specialist->id,
                'total_value'      => $this->specialist->appointment_value,
                'appointment_date' => $this->selectedDate,
                'appointment_time' => $this->selectedTime,
                'status'           => 'scheduled',
            ]);

            // Enviar notificações de agendamento
            Auth::user()->notify(new AppointmentScheduledNotification($appointment));
            $this->specialist->notify(new SpecialistAppointmentScheduledNotification($appointment));

            LivewireAlert::title('Agendamento Confirmado')
                ->text('Seu agendamento foi realizado com sucesso!')
                ->success()
                ->show();

            $this->clearSelection();

            DB::commit();

            $this->redirect(route('user.appointment.payment', ['appointment_id' => $appointment->id]), true);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'ip'      => request()->ip(),
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
