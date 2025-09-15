<?php

namespace App\Livewire\Company\Employee;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Funcionários', 'guard' => 'company'])]
class Index extends Component
{
  public $company;

  public function mount()
  {
    $this->company = Auth::guard('company')->user();
  }

  public function render()
  {
    return view('livewire.company.employee.index', [
      'company' => $this->company
    ]);
  }
}
