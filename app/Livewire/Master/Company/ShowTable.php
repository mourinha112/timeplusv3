<?php

namespace App\Livewire\Master\Company;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Empresas', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.company.personal_data.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput()
                ->includeViewOnTop('components.powergrid.company-header'),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Company::query();
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
            ->add('cnpj')
            ->add('email')
            ->add('city')
            ->add('is_active')
            ->add('status_indicator', function (Company $model) {
                return $model->is_active
                    ? '<span class="badge badge-success badge-sm">Ativa</span>'
                    : '<span class="badge badge-error badge-sm">Inativa</span>';
            })
            ->add('created_at_formatted', fn (Company $model) => Carbon::parse($model->created_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nome', 'name')->searchable()->sortable(),
            Column::make('CNPJ', 'cnpj')->searchable(),
            Column::make('E-mail', 'email')->searchable(),
            Column::make('Cidade', 'city')->searchable(),
            Column::make('Situação', 'status_indicator', 'is_active'),
            Column::make('Cadastrada em', 'created_at_formatted', 'created_at')->sortable(),
            Column::action('Ações'),
        ];
    }

    #[\Livewire\Attributes\On('master::company-show')]
    public function show($rowId): void
    {
        $company = Company::findOrFail($rowId);
        $this->redirect(route('master.company.show', ['company' => $company->id]));
    }

    #[\Livewire\Attributes\On('master::company-edit')]
    public function edit($rowId): void
    {
        $company = Company::findOrFail($rowId);
        $this->redirect(route('master.company.edit', ['company' => $company->id]));
    }

    #[\Livewire\Attributes\On('master::company-toggle')]
    public function toggleActive($rowId): void
    {
        $company = Company::findOrFail($rowId);
        $company->update(['is_active' => !$company->is_active]);
    }

    public function actions(Company $row): array
    {
        return [
            Button::add('show')
                ->slot('Visualizar')
                ->id()
                ->class('btn btn-info btn-sm')
                ->dispatch('master::company-show', ['rowId' => $row->id]),

            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class('btn btn-soft btn-info btn-sm')
                ->dispatch('master::company-edit', ['rowId' => $row->id]),

            Button::add('toggle')
                ->slot($row->is_active ? 'Desativar' : 'Ativar')
                ->id()
                ->class($row->is_active ? 'btn btn-error btn-sm' : 'btn btn-success btn-sm')
                ->dispatch('master::company-toggle', ['rowId' => $row->id]),
        ];
    }
}
