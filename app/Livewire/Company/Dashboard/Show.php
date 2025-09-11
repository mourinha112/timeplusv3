<?php

namespace App\Livewire\Company\Dashboard;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['title' => 'Dashboard Empresa', 'guard' => 'company'])]
class Show extends Component
{
  public function render()
  {
    $company = Auth::guard('company')->user();

    return view('livewire.company.dashboard.show', [
      'company' => $company
    ]);
  }
}
