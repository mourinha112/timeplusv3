<?php

namespace App\Livewire\User\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app', ['title' => 'Perfil', 'guard' => 'user'])]
class Update extends Component
{
    public $user;

    public ?string $password = null;
    public ?string $password_confirmation = null;

    public ?string $name = null;
    public ?string $phone_number = null;
    public ?string $cpf = null;
    public ?string $birth_date = null;

    public function mount()
    {
        $this->user = User::find(Auth::id());

        $this->name = $this->user->name;
        $this->phone_number = $this->user->phone_number;
        $this->cpf = $this->user->cpf;
        $this->birth_date = $this->user->birth_date;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => ['required', 'max:255'],
            'phone_number' => ['required', 'max:20', 'regex:/^\(\d{2}\) \d{5}-\d{4}$/'],
            'birth_date' => ['required', 'date_format:d/m/Y', 'before_or_equal:' . now()->subYears(18)->toDateString()],
        ]);

        $this->user->update([
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'birth_date' => $this->birth_date,
        ]);

        session()->flash('success_update_profile', 'Perfil atualizado com sucesso!');
    }

    public function updatePassword()
    {
        $this->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $this->user->update([
            'password' => bcrypt($this->password),
        ]);

        $this->reset(['password', 'password_confirmation']);

        LivewireAlert::title('Sucesso!')
            ->text('Senha atualizada com sucesso!')
            ->success()
            ->show();
    }

    public function render()
    {
        return view('livewire.user.profile.update');
    }
}
