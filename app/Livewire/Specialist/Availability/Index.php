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

    public function mount()
    {
        $this->currentWeekStart = Carbon::now()->startOfWeek();
        $this->loadWeekData();
        $this->loadAvailabilities();
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
        $availability = $this->availabilities[$date][$time . ':00'] ?? null;

        if ($availability) {
            $appointment = Appointment::where('appointment_date', $date)
                ->where('appointment_time', $time . ':00')
                ->where('specialist_id', Auth::guard('specialist')->id())
                ->first();

            /* Caso exista um agendamento para este horário, não permitir a remoção */
            if ($appointment) {
                LivewireAlert::title('Agendamento já existe!')
                    ->text('Não é possível remover a disponibilidade, pois já existe um agendamento para este horário.')
                    ->error()
                    ->show();

                return;
            }

            $availability->delete();
        } else {
            /* Cria uma nova disponibilidade */
            Availability::create([
                'available_date' => $date,
                'available_time' => $time . ':00',
                'specialist_id'  => Auth::guard('specialist')->id(),
            ]);
        }

        $this->loadAvailabilities();
    }

    public function getTimeSlots()
    {
        $slots = [];

        for ($hour = 0; $hour < 24; $hour++) {
            $slots[] = sprintf('%02d:00', $hour);
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
