<?php

namespace App\Livewire\Company\Plan;

use App\Models\CompanyPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout};
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Planos da Empresa', 'guard' => 'company'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'company.plan.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
              ->showSearchInput()
              ->includeViewOnTop('components.powergrid.company-plan-header'),
            PowerGrid::footer()
              ->showPerPage()
              ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        $company = Auth::guard('company')->user();

        return CompanyPlan::query()->where('company_id', $company->id);
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
          ->add('billing_model')
          ->add('monthly_credits')
          ->add('price_per_unit')
          ->add('is_active')
          ->add('status_indicator', function (CompanyPlan $model) {
              return $model->is_active
                ? '<span class="badge badge-success">Ativo</span>'
                : '<span class="badge badge-error">Inativo</span>';
          })
          ->add('billing_model_badge', function (CompanyPlan $model) {
              return $model->billing_model === 'credit_pack'
                ? '<span class="badge badge-info">Pacote de créditos</span>'
                : '<span class="badge badge-success">Por funcionário</span>';
          })
          ->add('monthly_credits_formatted', function (CompanyPlan $model) {
              return $model->billing_model === 'credit_pack' ? (string) $model->monthly_credits : '—';
          })
          ->add('price_per_unit_formatted', function (CompanyPlan $model) {
              return 'R$ ' . number_format((float) ($model->price_per_unit ?: 0), 2, ',', '.');
          })
          ->add('employees_count', function (CompanyPlan $model) {
              return $model->getActiveUsersCount();
          })
          ->add('created_at_formatted', fn (CompanyPlan $model) => Carbon::parse($model->created_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nome do Plano', 'name')->searchable()->sortable(),
            Column::make('Modelo', 'billing_model_badge', 'billing_model')
              ->bodyAttribute('class', 'text-center'),
            Column::make('Créditos/mês', 'monthly_credits_formatted', 'monthly_credits')
              ->bodyAttribute('class', 'text-center'),
            Column::make('Valor base', 'price_per_unit_formatted', 'price_per_unit')
              ->bodyAttribute('class', 'text-center'),
            Column::make('Funcionários', 'employees_count')
              ->bodyAttribute('class', 'text-center'),
            Column::make('Situação', 'status_indicator', 'is_active')
              ->bodyAttribute('class', 'text-center'),
            Column::make('Criado em', 'created_at_formatted', 'created_at')->sortable(),
            Column::action('Ações'),
        ];
    }

    #[\Livewire\Attributes\On('company::plan-show')]
    public function show($rowId): void
    {
        $plan = CompanyPlan::findOrFail($rowId);
        $this->redirect(route('company.plan.show', ['plan' => $plan->id]));
    }

    #[\Livewire\Attributes\On('company::plan-toggle-status')]
    public function toggleStatus($rowId): void
    {
        $plan = CompanyPlan::findOrFail($rowId);
        $plan->update(['is_active' => !$plan->is_active]);

        $status = $plan->is_active ? 'ativado' : 'desativado';
        session()->flash('message', "Plano {$status} com sucesso!");
    }

    #[\Livewire\Attributes\On('company::plan-delete')]
    public function delete($rowId): void
    {
        $plan = CompanyPlan::findOrFail($rowId);

        // Verificar se há funcionários usando este plano
        if ($plan->getActiveUsersCount() > 0) {
            session()->flash('error', 'Não é possível excluir um plano que possui funcionários ativos!');

            return;
        }

        $plan->delete();
        session()->flash('message', 'Plano excluído com sucesso!');
    }

    public function actions(CompanyPlan $row): array
    {
        return [
            Button::add('show')
              ->slot('Visualizar')
              ->id()
              ->class('btn btn-info btn-sm')
              ->dispatch('company::plan-show', ['rowId' => $row->id]),

            Button::add('edit')
              ->slot('Editar')
              ->id()
              ->class('btn btn-info btn-sm')
              ->route('company.plan.edit', ['plan' => $row->id]),

            Button::add('toggle-status')
              ->slot($row->is_active ? 'Desativar' : 'Ativar')
              ->id()
              ->class($row->is_active ? 'btn btn-warning btn-sm' : 'btn btn-success btn-sm')
              ->dispatch('company::plan-toggle-status', ['rowId' => $row->id]),

            Button::add('delete')
              ->slot('Excluir')
              ->id()
              ->class('btn btn-error btn-sm')
              ->dispatch('company::plan-delete', ['rowId' => $row->id])
              ->confirm('Tem certeza que deseja excluir este plano? Esta ação não pode ser desfeita.'),
        ];
    }
}
