<?php

namespace App\Livewire\User\Profile;

use App\Models\User;
use App\Rules\FormattedPhoneNumber;
use Illuminate\Support\Facades\{Auth, Log, Storage};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Layout};
use Livewire\{Component, WithFileUploads};

#[Layout('components.layouts.app', ['title' => 'Perfil', 'guard' => 'user'])]
class Update extends Component
{
    use WithFileUploads;

    public User $user;

    public $avatar;

    public $currentAvatar;

    public ?string $name = null;

    public ?string $phone_number = null;

    public ?string $birth_date = null;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function mount()
    {
        $this->user = User::find(Auth::id());

        $this->currentAvatar = $this->user->avatar;

        $this->fill($this->user->only(['name', 'phone_number', 'birth_date']));
    }

    /* Atualiza a foto de perfil do usuário */
    public function updatedAvatar()
    {
        $this->validate([
            'avatar' => 'required|image|max:2048|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try {
            if ($this->user->avatar && Storage::disk('public')->exists($this->user->avatar)) {
                Storage::disk('public')->delete($this->user->avatar);
            }

            $avatarPath = $this->avatar->store("users/{$this->user->id}/avatar", 'public');

            $this->user->update([
                'avatar' => $avatarPath,
            ]);

            $this->currentAvatar = $avatarPath;
            $this->reset(['avatar']);

            LivewireAlert::title('Sucesso!')
                ->text('Foto de perfil atualizado com sucesso!')
                ->success()
                ->show();
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'email'   => $this->user->email,
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar atualizar a foto de perfil.')
                ->error()
                ->show();
        }
    }

    /* Atualiza os dados do perfil do usuário */
    public function updateProfile()
    {
        $this->validate([
            'name'         => ['required', 'max:255'],
            'phone_number' => ['required', 'max:20', new FormattedPhoneNumber()],
            'birth_date'   => ['required', 'date_format:d/m/Y', 'before_or_equal:' . now()->subYears(18)->toDateString()],
        ]);

        try {
            $this->user->update([
                'name'         => $this->name,
                'phone_number' => $this->phone_number,
                'birth_date'   => $this->birth_date,
            ]);

            LivewireAlert::title('Sucesso!')
                ->text('Perfil atualizado com sucesso!')
                ->success()
                ->show();
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'email'   => $this->user->email,
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar atualizar o perfil.')
                ->error()
                ->show();
        }
    }

    /* Atualiza a senha do usuário */
    public function updatePassword()
    {
        $this->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $this->user->update([
                'password' => bcrypt($this->password),
            ]);

            $this->reset(['password', 'password_confirmation']);

            LivewireAlert::title('Sucesso!')
                ->text('Senha atualizada com sucesso!')
                ->success()
                ->show();
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'email'   => $this->user->email,
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar atualizar a senha.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.user.profile.update');
    }
}
