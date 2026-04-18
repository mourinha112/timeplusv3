<?php

namespace App\Livewire\Specialist\Availability;

use App\Models\{Appointment, Availability};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Disponibilidades', 'guard' => 'specialist'])]
class Index extends Component
{
    public $currentWeekStart;

    public $availabilities;

    public $weekDays = [];

    public string $selectedMode = Availability::MODE_BOTH;

    public function mount()
    {
        $this->currentWeekStart = Carbon::now()->startOfWeek();
        $this->loadWeekData();
        $this->loadAvailabilities();
    }

    public function setMode(string $mode): void
    {
        if (in_array($mode, [Availability::MODE_BOTH, Availability::MODE_TIMEPLUS, Availability::MODE_PARTICULAR], true)) {
            $this->selectedMode = $mode;
        }
    }

    public function getFirstDayOfWeek()
    {
        return $this->currentWeekStart->format('d/m/Y');
    }

    public function getLastDayOfWeek()
    {
        return $this->currentWeekStart->copy()->addDays(6)->format('d/m/Y');
    }

    public function loadWeekData()
    {
        $this->weekDays = [];

        for ($i = 0; $i < 7; $i++) {
            $date             = $this->currentWeekStart->copy()->addDays($i);
            $this->weekDays[] = [
                'date'      => $date,
                'dayOfWeek' => $this->getDayOfWeekAbbr($date->dayOfWeek),
                'day'       => $date->format('d'),
                'month'     => $this->getMonthAbbr($date->format('m')),
                'full_date' => $date->format('Y-m-d'),
            ];
        }
    }

    public function loadAvailabilities()
    {
        $startDate = $this->currentWeekStart->format('Y-m-d');
        $endDate   = $this->currentWeekStart->copy()->addDays(6)->format('Y-m-d');

        $this->availabilities = Availability::whereBetween('available_date', [$startDate, $endDate])
            ->where('specialist_id', Auth::guard('specialist')->id()) // Filtra por especialista autenticado
            ->get()
            ->groupBy('available_date')
            ->map(function ($dayAvailabilities) {
                return $dayAvailabilities->keyBy('available_time');
            });
    }

    public function previousWeek()
    {
        $this->currentWeekStart = $this->currentWeekStart->subWeek();
        $this->loadWeekData();
        $this->loadAvailabilities();
    }

    public function nextWeek()
    {
        $this->currentWeekStart = $this->currentWeekStart->addWeek();
        $this->loadWeekData();
        $this->loadAvailabilities();
    }

    public function toggleTimeAvailability($date, $time)
    {
        $specialist = Auth::guard('specialist')->user();

        if ($date < now()->toDateString()) {
            LivewireAlert::title('Data inválida')
                ->text('Não é possível criar disponibilidade em datas passadas.')
                ->warning()
                ->show();

            return;
        }

        $availability = $this->availabilities[$date][$time . ':00'] ?? null;

        if ($availability) {
            $appointment = Appointment::where('appointment_date', $date)
                ->where('appointment_time', $time . ':00')
                ->where('specialist_id', $specialist->id)
                ->first();

            if ($appointment) {
                LivewireAlert::title('Agendamento já existe!')
                    ->text('Não é possível remover a disponibilidade, pois já existe um agendamento para este horário.')
                    ->error()
                    ->show();

                return;
            }

            $availability->delete();
        } else {
            $mode = $this->selectedMode;

            if ($mode === Availability::MODE_TIMEPLUS && !$specialist->accepts_timeplus) {
                LivewireAlert::title('Modalidade desativada')
                    ->text('Você não está aceitando atendimentos TimePlus. Ative em Perfil > Dados profissionais.')
                    ->warning()
                    ->show();

                return;
            }

            if ($mode === Availability::MODE_PARTICULAR && !$specialist->accepts_particular) {
                LivewireAlert::title('Modalidade desativada')
                    ->text('Você não está aceitando atendimentos particulares. Ative em Perfil > Dados profissionais.')
                    ->warning()
                    ->show();

                return;
            }

            Availability::create([
                'available_date' => $date,
                'available_time' => $time . ':00',
                'specialist_id'  => $specialist->id,
                'service_mode'   => $mode,
            ]);
        }

        $this->loadAvailabilities();
    }

    public function replicatePreviousWeek(): void
    {
        $specialist   = Auth::guard('specialist')->user();
        $previousStart = $this->currentWeekStart->copy()->subWeek();
        $previousEnd   = $previousStart->copy()->addDays(6);

        $previousAvailabilities = Availability::where('specialist_id', $specialist->id)
            ->whereBetween('available_date', [$previousStart->format('Y-m-d'), $previousEnd->format('Y-m-d')])
            ->get();

        if ($previousAvailabilities->isEmpty()) {
            LivewireAlert::title('Semana anterior vazia')
                ->text('Não há disponibilidades cadastradas na semana anterior para replicar.')
                ->warning()
                ->show();

            return;
        }

        $created = 0;
        $skipped = 0;
        $today   = now()->toDateString();

        foreach ($previousAvailabilities as $previous) {
            $newDate = Carbon::parse($previous->available_date)->addWeek()->format('Y-m-d');

            if ($newDate < $today) {
                $skipped++;

                continue;
            }

            $exists = Availability::where('specialist_id', $specialist->id)
                ->where('available_date', $newDate)
                ->where('available_time', $previous->available_time)
                ->exists();

            if ($exists) {
                $skipped++;

                continue;
            }

            Availability::create([
                'specialist_id'  => $specialist->id,
                'available_date' => $newDate,
                'available_time' => $previous->available_time,
                'service_mode'   => $previous->service_mode,
            ]);

            $created++;
        }

        $this->loadAvailabilities();

        $message = "{$created} horário(s) replicado(s).";

        if ($skipped > 0) {
            $message .= " {$skipped} pulado(s) (já existiam ou já passaram).";
        }

        LivewireAlert::title('Semana replicada')
            ->text($message)
            ->success()
            ->show();
    }

    public function getTimeSlots()
    {
        $specialist = Auth::guard('specialist')->user();
        $duration   = $specialist?->getSessionDuration() ?? \App\Models\Specialist::DEFAULT_SESSION_DURATION;

        $slots   = [];
        $current = Carbon::createFromTime(0, 0, 0);
        $end     = Carbon::createFromTime(0, 0, 0)->addDay();

        while ($current->lt($end)) {
            $slots[] = $current->format('H:i');
            $current->addMinutes($duration);
        }

        return $slots;
    }

    private function getDayOfWeekAbbr($dayOfWeek)
    {
        $days = [
            0 => 'DOM', // Sunday
            1 => 'SEG', // Monday
            2 => 'TER', // Tuesday
            3 => 'QUA', // Wednesday
            4 => 'QUI', // Thursday
            5 => 'SEX', // Friday
            6 => 'SAB',  // Saturday
        ];

        return $days[$dayOfWeek];
    }

    private function getMonthAbbr($month)
    {
        $months = [
            '01' => 'JAN',
            '02' => 'FEV',
            '03' => 'MAR',
            '04' => 'ABR',
            '05' => 'MAI',
            '06' => 'JUN',
            '07' => 'JUL',
            '08' => 'AGO',
            '09' => 'SET',
            '10' => 'OUT',
            '11' => 'NOV',
            '12' => 'DEZ',
        ];

        return $months[$month];
    }

    public function render()
    {
        return view('livewire.specialist.availability.index', [
            'timeSlots'           => $this->getTimeSlots(),
            'totalAvailabilities' => $this->availabilities->flatten()->count(),
            'firstDayOfWeek'      => $this->getFirstDayOfWeek(),
            'lastDayOfWeek'       => $this->getLastDayOfWeek(),
        ]);
    }
}
