<?php

namespace App\Livewire\User\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('user.auth.login');
    }

    public function render(): string
    {
        return <<<BLADE
            <button class="cursor-pointer" wire:click="logout">Logout</button>
        BLADE;
    }
}
