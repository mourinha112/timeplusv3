<?php

namespace App\Livewire\User\Subscribe;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Computed, Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Histórico de Assinaturas', 'guard' => 'user'])]
class History extends Component
{
    #[Computed]
    public function subscribes()
    {
        $user = User::find(Auth::id());

        return $user->subscribes()->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.user.subscribe.history');
    }
}
