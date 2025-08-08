<?php

namespace App\Livewire\Specialist\Profile;

use App\Models\{Gender, Specialist, State};
use App\Rules\FormattedPhoneNumber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\{Computed, Layout, Rule};
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

#[Layout('components.layouts.app', ['title' => 'Dados pessoais', 'guard' => 'specialist'])]
class PersonalDetail extends Component
{
    use WithFileUploads;

    public Specialist $specialist;

    public $avatar;

    public $currentAvatar;

    #[Rule(['required', 'max:255'])]
    public ?string $name;

    public ?string $email;

    #[Rule(['required', 'max:20', new FormattedPhoneNumber()])]
    public ?string $phone_number;

    public ?string $birth_date;

    #[Rule(['required', 'exists:genders,id'])]
    public ?int $gender_id;

    #[Rule(['required', 'exists:states,id'])]
    public ?int $state_id;

    public ?bool $lgbtqia = false;

    public function rules(): array
    {
        return [
            'birth_date' => ['required', 'date_format:d/m/Y', 'before_or_equal:' . now()->subYears(18)->toDateString()],
        ];
    }

    #[Computed]
    public function genders()
    {
        return Gender::all();
    }

    #[Computed]
    public function states()
    {
        return State::all();
    }

    public function mount()
    {
        $this->specialist = Auth::guard('specialist')->user();

        $this->currentAvatar = $this->specialist->avatar;

        $this->name         = $this->specialist->name;
        $this->email        = $this->specialist->email;
        $this->phone_number = $this->specialist->phone_number;
        $this->birth_date   = $this->specialist->birth_date;
        $this->gender_id    = $this->specialist->gender_id;
        $this->state_id     = $this->specialist->state_id;
        $this->lgbtqia      = $this->specialist->lgbtqia;
    }

    /* Atualiza a foto de perfil do usuário */
    public function updatedAvatar()
    {
        $this->validate([
            'avatar' => 'required|image|max:2048|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try {
            if ($this->specialist->avatar && Storage::disk('public')->exists($this->specialist->avatar)) {
                Storage::disk('public')->delete($this->specialist->avatar);
            }

            $avatarPath = $this->avatar->store("specialists/{$this->specialist->id}/avatar", 'public');

            $this->specialist->update([
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
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar atualizar a foto de perfil.')
                ->error()
                ->show();
        }
    }

    /* Atualiza os dados pessoais do especialista */
    public function updateProfile()
    {
        $this->validate();

        try {
            $this->specialist->update([
                'name'         => $this->name,
                'phone_number' => $this->phone_number,
                'birth_date'   => $this->birth_date,
                'gender_id'    => $this->gender_id,
                'state_id'     => $this->state_id,
                'lgbtqia'      => $this->lgbtqia,
            ]);

            LivewireAlert::title('Perfil atualizado com sucesso!')
                ->text('Suas informações foram salvas com sucesso.')
                ->success()
                ->show();
        } catch (\Exception $e) {
            Log::error('Erro interno::' . get_class($this), [
                'message' => $e->getMessage(),
                'ip'      => request()->ip(),
            ]);

            LivewireAlert::title('Erro!')
                ->text('Ocorreu um erro ao tentar atualizar seu perfil.')
                ->error()
                ->show();
        }
    }

    public function render()
    {
        return view('livewire.specialist.profile.personal-detail');
    }
}
