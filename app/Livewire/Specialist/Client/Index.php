<?php

namespace App\Livewire\Specialist\Client;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Clientes', 'guard' => 'specialist'])]
class Index extends Component
{
    #[Computed]
    public function clients()
    {
        return User::whereHas('appointments', function ($query) {
            $query->where('specialist_id', auth()->id());
        })->get();
    }

    public function render()
    {
        return view('livewire.specialist.client.index');
    }
}
