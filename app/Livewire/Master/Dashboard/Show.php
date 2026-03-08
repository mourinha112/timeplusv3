<?php

namespace App\Livewire\Master\Dashboard;

use App\Models\{Appointment, Company, Payment, Room, Specialist, Subscribe, User};
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dashboard', 'guard' => 'master'])]
class Show extends Component
{
    #[Computed()]
    public function users()
    {
        return User::count();
    }

    #[Computed()]
    public function specialists()
    {
        return Specialist::count();
    }

    #[Computed()]
    public function companies()
    {
        return Company::count();
    }

    #[Computed()]
    public function appointments()
    {
        return Appointment::count();
    }

    #[Computed()]
    public function payments()
    {
        return [
            'total' => Payment::where('status', 'paid')->sum('amount'),
            'count' => Payment::where('status', 'paid')->count(),
        ];
    }

    #[Computed()]
    public function activeSubscriptions()
    {
        return Subscribe::whereNull('cancelled_date')->whereDate('end_date', '>=', now())->count();
    }

    #[Computed()]
    public function openRooms()
    {
        return Room::where('status', 'open')->count();
    }

    #[Computed()]
    public function completionRate()
    {
        $total = Appointment::count();
        if ($total === 0) return 0;
        $completed = Appointment::where('status', 'completed')->count();
        return round(($completed / $total) * 100, 1);
    }

    #[Computed()]
    public function monthlyRevenue()
    {
        return Payment::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');
    }

    public function render()
    {
        return view('livewire.master.dashboard.show');
    }
}
