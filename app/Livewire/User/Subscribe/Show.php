<?php

namespace App\Livewire\User\Subscribe;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public $subscribe;

    public function mount()
    {
        $user = User::find(Auth::id());

        $this->subscribe = $user->subscribes()->where('end_date', '>', now())->first();
    }

    public function cancel()
    {
        if ($this->subscribe->cancelled_date) {
            return session()->flash('error', 'Assinatura já está cancelada.');
        }

        $this->subscribe->update(['cancelled_date' => now()]);

        return session()->flash('success', 'Assinatura cancelada com sucesso!');
    }

    public function render()
    {
        return view('livewire.user.subscribe.show');
    }
}
