<?php

namespace App\Livewire\Master\Specialist;

use App\Models\{Specialist, Specialty, Gender, State};
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Especialista', 'guard' => 'master'])]
class Edit extends Component
{
    public Specialist $specialist;

    public $name = '';
    public $email = '';
    public $phone_number = '';
    public $cpf = '';
    public $crp = '';
    public $specialty_id = '';
    public $gender_id = '';
    public $state_id = '';
    public $appointment_value = '';
    public $is_active = true;
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
        $rules = [
            'name'              => 'required|string|max:255',
            'email'             => 'required|email',
            'phone_number'      => 'nullable|string|max:15',
            'cpf'               => 'nullable|string|max:14',
            'crp'               => 'nullable|string|max:20',
            'specialty_id'      => 'nullable|exists:specialties,id',
            'gender_id'         => 'nullable|exists:genders,id',
            'state_id'          => 'nullable|exists:states,id',
            'appointment_value' => 'nullable|numeric|min:0',
            'is_active'         => 'boolean',
        ];

        if (!empty($this->password)) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $this->validate($rules);

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
