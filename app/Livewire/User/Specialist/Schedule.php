<?php

namespace App\Livewire\User\Specialist;

use Carbon\Carbon;
use Livewire\Component;

class Schedule extends Component
{
    public $dates = [];
    public $selectedDate = null;
    public $selectedTime = null;

    public function mount()
    {
        $this->dates = collect(range(0, 4))->map(function ($i) {
            $date = now()->addDays($i);
            return [
                'label' => strtoupper($date->locale('pt_BR')->isoFormat('ddd')) . ' ' . $date->format('d'),
                'full' => $date->format('Y-m-d'),
                'times' => $this->getAvailableTimes($date),
            ];
        })->toArray();
    }

    public function getAvailableTimes(Carbon $date)
    {
        // Simulação de horários disponíveis
        $times = [
            '08:30', '09:00', '10:00', '10:30', '11:00', '11:30', '12:00', '13:00', '19:00'
        ];

        // Exemplo: Filtro fictício para horários passados no dia de hoje
        if ($date->isToday()) {
            return collect($times)->filter(fn($time) => $date->copy()->setTimeFromTimeString($time)->isAfter(now()))->values();
        }

        return $times;
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->selectedTime = null;
    }

    public function selectTime($time)
    {
        $this->selectedTime = $time;
    }

    public function schedule()
    {
        if ($this->selectedDate && $this->selectedTime) {
            // Lógica para salvar agendamento
            session()->flash('message', 'Agendamento realizado com sucesso!');
        }
    }
    public function render()
    {
        return view('livewire.user.specialist.schedule');
    }
}
