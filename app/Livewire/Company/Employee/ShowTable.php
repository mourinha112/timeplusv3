<?php

namespace App\Livewire\Company\Employee;

use App\Models\User;
use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

#[Layout('components.layouts.app', ['title' => 'Funcionários', 'guard' => 'company'])]
class ShowTable extends PowerGridComponent
{
  public string $tableName = 'company.employee.table';

  public function setUp(): array
  {
    return [
      PowerGrid::header()
        ->showSearchInput()
        ->includeViewOnTop('components.powergrid.company-employee-header'),
      PowerGrid::footer()->showPerPage()->showRecordCount(),
    ];
  }

  public function datasource(): Builder
  {
    $company = Auth::guard('company')->user();

    return User::query()
      ->join('company_user', 'users.id', '=', 'company_user.user_id')
      ->leftJoin('company_plans', 'company_user.company_plan_id', '=', 'company_plans.id')
      ->where('company_user.company_id', $company->id)
      ->select([
        'users.*',
        'company_user.is_active as employee_is_active',
        'company_user.id as company_user_id',
        'company_plans.name as plan_name',
        'company_plans.discount_percentage',
        'company_plans.is_active as plan_is_active'
      ]);
  }

  public function relationSearch(): array
  {
    return [];
  }

  public function fields(): PowerGridFields
  {
    return PowerGrid::fields()
      ->add('id')
      ->add('name')
      ->add('email')
      ->add('cpf')
      ->add('phone_number')
      ->add('employee_is_active')
      ->add('plan_name')
      ->add('discount_percentage')
      ->add('status_indicator', function ($model) {
        return $model->employee_is_active
          ? '<span class="badge badge-success">Ativo</span>'
          : '<span class="badge badge-error">Inativo</span>';
      })
      ->add('plan_badge', function ($model) {
        if ($model->plan_name) {
          return '<span class="badge badge-info">' . $model->plan_name . '</span>';
        }
        return '<span class="badge badge-ghost">Sem plano</span>';
      });
  }

  public function columns(): array
  {
    return [
      Column::make('ID', 'id')->sortable(),
      Column::make('Nome', 'name')->searchable()->sortable(),
      Column::make('E-mail', 'email')->searchable(),
      Column::make('Telefone', 'phone_number')->searchable(),
      Column::make('Plano', 'plan_badge', 'plan_name')
        ->bodyAttribute('class', 'text-center'),
      Column::make('Situação', 'status_indicator', 'employee_is_active')
        ->bodyAttribute('class', 'text-center'),

      Column::action('Ações'),
    ];
  }

  #[\Livewire\Attributes\On('company::employee-show')]
  public function show($rowId): void
  {
    $employee = User::findOrFail($rowId);
    $this->redirect(route('company.employee.show', ['employee' => $employee->id]));
  }

  #[\Livewire\Attributes\On('company::employee-toggle-status')]
  public function toggleStatus($rowId): void
  {
    $company = Auth::guard('company')->user();
    $companyUser = CompanyUser::where('company_id', $company->id)
      ->where('user_id', $rowId)
      ->firstOrFail();

    $companyUser->update(['is_active' => !$companyUser->is_active]);

    $status = $companyUser->is_active ? 'ativado' : 'desativado';
    session()->flash('message', "Funcionário {$status} com sucesso!");
  }

  #[\Livewire\Attributes\On('company::employee-remove')]
  public function remove($rowId): void
  {
    $company = Auth::guard('company')->user();
    $companyUser = CompanyUser::where('company_id', $company->id)
      ->where('user_id', $rowId)
      ->firstOrFail();

    $companyUser->delete();
    session()->flash('message', 'Funcionário removido da empresa com sucesso!');
  }

  public function actions($row): array
  {
    return [
      Button::add('show')
        ->slot('Visualizar')
        ->id()
        ->class('btn btn-info btn-sm')
        ->dispatch('company::employee-show', ['rowId' => $row->id]),

      Button::add('edit')
        ->slot('Editar')
        ->id()
        ->class('btn btn-info btn-sm')
        ->route('company.employee.edit', ['employee' => $row->id]),

      Button::add('toggle-status')
        ->slot($row->employee_is_active ? 'Desativar' : 'Ativar')
        ->id()
        ->class($row->employee_is_active ? 'btn btn-warning btn-sm' : 'btn btn-success btn-sm')
        ->dispatch('company::employee-toggle-status', ['rowId' => $row->id]),

      Button::add('remove')
        ->slot('Remover')
        ->id()
        ->class('btn btn-error btn-sm')
        ->dispatch('company::employee-remove', ['rowId' => $row->id])
        ->confirm('Tem certeza que deseja remover este funcionário da empresa?')
    ];
  }
}
