<?php

namespace App\Livewire\Master\User;

use App\Models\{Company, User};
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Editar Usuário', 'guard' => 'master'])]
class Edit extends Component
{
    public User $user;

    public $name = '';
    public $email = '';
    public $phone_number = '';
    public $cpf = '';
    public $birth_date = '';
    public $birth_date_input = '';
    public $company_id = null;
    public $is_active = true;
    public $password = '';
    public $password_confirmation = '';

    public function mount(User $user)
    {
        $this->user = $user;

        $this->name         = $user->name;
        $this->email        = $user->email;
        $this->phone_number = $user->phone_number;
        $this->cpf          = $user->cpf;
        $this->birth_date   = $user->birth_date;
        // Converte DD/MM/YYYY para YYYY-MM-DD para o input type="date"
        $this->birth_date_input = $user->getRawOriginal('birth_date');
        $this->is_active    = (bool) $user->is_active;

        // Carrega a empresa vinculada
        $company = $user->companies()->first();
        $this->company_id = $company?->id;
    }

    public function save()
    {
        $rules = [
            'name'             => 'required|string|max:255',
            'email'            => 'required|email',
            'phone_number'     => 'nullable|string|max:15',
            'cpf'              => 'nullable|string|max:14',
            'birth_date_input' => 'nullable|date',
            'is_active'        => 'boolean',
            'company_id'       => 'nullable|exists:companies,id',
        ];

        if (!empty($this->password)) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $this->validate($rules);

        $data = [
            'name'         => $this->name,
            'email'        => $this->email,
            'phone_number' => $this->phone_number,
            'cpf'          => $this->cpf,
            'is_active'    => $this->is_active,
        ];

        // Salva a data de nascimento diretamente no formato do banco (Y-m-d)
        if ($this->birth_date_input) {
            $data['birth_date'] = $this->birth_date_input;
        }

        if (!empty($this->password)) {
            $data['password'] = $this->password;
        }

        $this->user->update($data);

        // Atualiza a empresa vinculada
        if ($this->company_id) {
            $currentCompany = $this->user->companies()->first();
            if ($currentCompany && $currentCompany->id != $this->company_id) {
                // Remove da empresa atual
                $this->user->companies()->detach($currentCompany->id);
            }
            if (!$currentCompany || $currentCompany->id != $this->company_id) {
                $this->user->companies()->syncWithoutDetaching([$this->company_id => ['is_active' => true]]);
            }
        } else {
            // Se não selecionou empresa, remove todas as vinculações
            $this->user->companies()->detach();
        }

        session()->flash('message', 'Usuário atualizado com sucesso!');

        return $this->redirect(route('master.user.personal-data.show', ['user' => $this->user->id]));
    }

    public function render()
    {
        $companies = Company::where('is_active', true)->orderBy('name')->get();

        return view('livewire.master.user.edit', [
            'companies' => $companies,
        ]);
    }
}
