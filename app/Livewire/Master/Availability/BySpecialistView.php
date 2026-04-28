<?php

namespace App\Livewire\Master\Availability;

use App\Models\{Availability, Specialist};
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Agenda por Profissional', 'guard' => 'master'])]
class BySpecialistView extends Component
{
    public ?int $specialist_id = null;

    public ?string $from = null;

    public ?string $to = null;

    public function mount(): void
    {
        $this->from = Carbon::today()->format('Y-m-d');
        $this->to   = Carbon::today()->addDays(14)->format('Y-m-d');
    }

    public function render()
    {
        $specialists = Specialist::orderBy('name')->get(['id', 'name', 'crp']);

        $query = Availability::query()
            ->with('specialist:id,name,crp')
            ->whereDate('available_date', '>=', $this->from ?: Carbon::today()->format('Y-m-d'))
            ->whereDate('available_date', '<=', $this->to ?: Carbon::today()->addMonth()->format('Y-m-d'))
            ->orderBy('available_date')
            ->orderBy('available_time');

        if ($this->specialist_id) {
            $query->where('specialist_id', $this->specialist_id);
        }

        $grouped = $query->get()->groupBy('specialist_id');

        return view('livewire.master.availability.by-specialist-view', [
            'specialists' => $specialists,
            'grouped'     => $grouped,
        ]);
    }
}
