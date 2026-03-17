<?php

namespace App\Livewire\Master\Specialist;

use App\Models\{Specialist, Specialty, Gender, State};
use Livewire\Attributes\{Layout, Rule};
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Especialista', 'guard' => 'master'])]
class Edit extends Component
{
    public Specialist $specialist;

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|email')]
    public $email = '';

    #[Rule('nullable|string|max:15')]
    public $phone_number = '';

    #[Rule('nullable|string|max:14')]
    public $cpf = '';

    #[Rule('nullable|string|max:20')]
    public $crp = '';

    #[Rule('nullable|exists:specialties,id')]
    public $specialty_id = '';

    #[Rule('nullable|exists:genders,id')]
    public $gender_id = '';

    #[Rule('nullable|exists:states,id')]
    public $state_id = '';

    #[Rule('nullable|numeric|min:0')]
    public $appointment_value = '';

    #[Rule('boolean')]
    public $is_active = true;

    #[Rule('nullable|string|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    public function mount(Specialist $specialist)
    {
        $this->specialist = $specialist;

        $this->name              = $specialist->name;
        $this->email             = $specialist->email;
        $this->phone_number      = $specialist->phone_number;
        $this->cpf               = $specialist->cpf;
        $this->crp               = $specialist->crp;
        $this->specialty_id      = $specialist->specialty_id;
        $this->gender_id         = $specialist->gender_id;
        $this->state_id          = $specialist->state_id;
        $this->appointment_value = $specialist->appointment_value;
        $this->is_active         = $specialist->is_active;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name'              => $this->name,
            'email'             => $this->email,
            'phone_number'      => $this->phone_number,
            'cpf'               => $this->cpf,
            'crp'               => $this->crp,
            'specialty_id'      => $this->specialty_id ?: null,
            'gender_id'         => $this->gender_id ?: null,
            'state_id'          => $this->state_id ?: null,
            'appointment_value' => $this->appointment_value,
            'is_active'         => $this->is_active,
        ];

        if (!empty($this->password)) {
            $data['password'] = $this->password;
        }

        $this->specialist->update($data);

        session()->flash('message', 'Especialista atualizado com sucesso!');

        return $this->redirect(route('master.specialist.personal-data.show', ['specialist' => $this->specialist->id]));
    }

    public function render()
    {
        return view('livewire.master.specialist.edit', [
            'specialties' => Specialty::orderBy('name')->get(),
            'genders'     => Gender::orderBy('name')->get(),
            'states'      => State::orderBy('name')->get(),
        ]);
    }
}
