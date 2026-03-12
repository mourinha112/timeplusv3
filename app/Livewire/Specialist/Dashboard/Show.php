<?php

namespace App\Livewire\Specialist\Dashboard;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dashboard', 'guard' => 'specialist'])]
class Show extends Component
{
    #[Computed]
    public function nextAppointment()
    {
        return Appointment::where('specialist_id', Auth::guard('specialist')->id())
            ->with(['user', 'payment', 'room'])
            ->where('status', 'scheduled')
            ->where(function ($query) {
                $query->where('appointment_date', '>', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('appointment_date', now()->toDateString())
                            ->whereRaw("ADDTIME(appointment_time, '01:00:00') >= ?", [now()->format('H:i:s')]);
                    });
            })
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->first();
    }

    #[Computed]
    public function todayAppointments()
    {
        return Appointment::where('specialist_id', Auth::guard('specialist')->id())
            ->with(['user', 'payment', 'room'])
            ->where('appointment_date', now()->toDateString())
            ->whereIn('status', ['scheduled', 'completed'])
            ->orderBy('appointment_time', 'asc')
            ->get();
    }

    #[Computed]
    public function recentAppointments()
    {
        return Appointment::where('specialist_id', Auth::guard('specialist')->id())
            ->with(['user', 'payment'])
            ->where(function ($query) {
                $query->where('appointment_date', '<', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('appointment_date', now()->toDateString())
                            ->where('appointment_time', '<', now()->format('H:i:s'));
                    });
            })
            ->whereIn('status', ['completed', 'scheduled'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->limit(10)
            ->get();
    }

    #[Computed]
    public function stats()
    {
        $specialistId = Auth::guard('specialist')->id();
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        return [
            'month_total' => Appointment::where('specialist_id', $specialistId)
                ->whereMonth('appointment_date', $currentMonth)
                ->whereYear('appointment_date', $currentYear)
                ->whereIn('status', ['scheduled', 'completed'])
                ->count(),
            'month_completed' => Appointment::where('specialist_id', $specialistId)
                ->whereMonth('appointment_date', $currentMonth)
                ->whereYear('appointment_date', $currentYear)
                ->where('status', 'completed')
                ->count(),
            'today_total' => $this->todayAppointments->count(),
        ];
    }

    public function isPaid($appointment)
    {
        return $appointment->payment && $appointment->payment->status === 'paid';
    }

    public function hasRoom($appointment)
    {
        return $appointment->room && $appointment->room->status === 'open';
    }

    public function hasScheduledRoom($appointment)
    {
        return $appointment->room && $appointment->room->status === 'closed';
    }

    public function getRoomOpenTime($appointment)
    {
        if (!$this->hasScheduledRoom($appointment)) {
            return null;
        }

        $appointmentDateTime = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);

        return $appointmentDateTime->subMinutes(10);
    }

    public function render()
    {
        return view('livewire.specialist.dashboard.show');
    }
}
