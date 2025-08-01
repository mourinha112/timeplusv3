<?php

namespace App\Livewire\Specialist\Appointment;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Agendamentos', 'guard' => 'specialist'])]
class Index extends Component
{
    public $currentWeekStart;

    public $appointments;

    public $weekDays = [];

    public function mount()
    {
        $this->currentWeekStart = Carbon::now()->startOfWeek();
        $this->loadWeekData();
        $this->loadAppointments();
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

    public function loadAppointments()
    {
        $startDate = $this->currentWeekStart->format('Y-m-d');
        $endDate   = $this->currentWeekStart->copy()->addDays(6)->format('Y-m-d');

        $this->appointments = Appointment::whereBetween('appointment_date', [$startDate, $endDate])
            ->where('specialist_id', Auth::guard('specialist')->id())
            ->where('status', 'scheduled')
            ->with('user') // Assumindo que existe relacionamento com User
            ->get()
            ->groupBy('appointment_date')
            ->map(function ($dayAppointments) {
                return $dayAppointments->keyBy('appointment_time');
            });
    }

    public function previousWeek()
    {
        $this->currentWeekStart = $this->currentWeekStart->subWeek();
        $this->loadWeekData();
        $this->loadAppointments();
    }

    public function nextWeek()
    {
        $this->currentWeekStart = $this->currentWeekStart->addWeek();
        $this->loadWeekData();
        $this->loadAppointments();
    }

    public function cancelAppointment($date, $time)
    {
        $appointment = $this->appointments[$date][$time . ':00'] ?? null;

        if ($appointment) {
            // Se existe agendamento, remover
            $appointment->update(['status' => 'cancelled']);
        }

        LivewireAlert::title('Agendamento cancelado!')
            ->text('O agendamento foi cancelado com sucesso.')
            ->success()
            ->show();

        $this->loadAppointments();
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
        return view('livewire.specialist.appointment.index', [
            'timeSlots'         => $this->getTimeSlots(),
            'totalAppointments' => $this->appointments->flatten()->count(),
            'firstDayOfWeek'    => $this->getFirstDayOfWeek(),
            'lastDayOfWeek'     => $this->getLastDayOfWeek(),
        ]);
    }
}
