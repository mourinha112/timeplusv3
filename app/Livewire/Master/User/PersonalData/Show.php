<?php

namespace App\Livewire\Master\User\PersonalData;

use App\Models\User;
use Livewire\Attributes\{Layout};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Detalhes do Usuário', 'guard' => 'master'])]
class Show extends Component
{
    public User $user;

    public function mount(User $user): void
    {
        $this->user = $user->load(['appointments', 'companies']);
    }

    public function render()
    {
        return view('livewire.master.user.personal-data.show');
    }
}
