<?php

namespace App\Livewire\User\Subscribe;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Assinatura', 'guard' => 'user'])]
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
            LivewireAlert::title('Assinatura já está cancelada.')
                ->text('Você não pode cancelar uma assinatura que já foi cancelada.')
                ->warning()
                ->show();
        }

        $this->subscribe->update(['cancelled_date' => now()]);

        LivewireAlert::title('Sucesso!')
            ->text('Assinatura cancelada com sucesso!')
            ->success()
            ->show();
    }

    public function render()
    {
        return view('livewire.user.subscribe.show');
    }
}
