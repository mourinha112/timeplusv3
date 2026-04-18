<?php

namespace App\Livewire\Master\User\PersonalData;

use App\Models\{Company, User};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\{Filter, PowerGrid};

#[Layout('components.layouts.app', ['title' => 'Usuários', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.user.personal_data.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query()->with('companies');
    }

    public function relationSearch(): array
    {
        return [
            'companies' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('phone_number')
            ->add('cpf')
            ->add('company_name', function (User $model) {
                $company = $model->companies->first();
                return $company ? $company->name : '<span class="text-base-content/40">—</span>';
            })
            ->add('status_indicator', function (User $model) {
                return $model->is_active
                    ? '<span class="badge badge-success badge-sm">Ativo</span>'
                    : '<span class="badge badge-error badge-sm">Inativo</span>';
            })
            ->add('created_at_formatted', fn (User $model) => Carbon::parse($model->created_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nome', 'name')->searchable()->sortable(),
            Column::make('E-mail', 'email')->searchable(),
            Column::make('CPF', 'cpf')->searchable(),
            Column::make('Empresa', 'company_name')->searchable(),
            Column::make('Situação', 'status_indicator', 'is_active'),
            Column::make('Cadastrado em', 'created_at_formatted', 'created_at')->sortable(),
            Column::action('Ações'),
        ];
    }

    public function filters(): array
    {
        $companies = Company::query()
            ->orderBy('name')
            ->get()
            ->map(fn (Company $c) => ['id' => $c->id, 'name' => $c->name])
            ->toArray();

        return [
            Filter::select('company_name')
                ->dataSource($companies)
                ->optionLabel('name')
                ->optionValue('id')
                ->builder(function (Builder $builder, mixed $value) {
                    if ($value === null || $value === '') {
                        return $builder;
                    }

                    return $builder->whereHas('companies', fn (Builder $q) => $q->where('companies.id', $value));
                }),
            Filter::select('status_indicator', 'is_active')->dataSource([
                ['id' => 1, 'name' => 'Ativo'],
                ['id' => 0, 'name' => 'Inativo'],
            ])->optionLabel('name')->optionValue('id'),
        ];
    }

    #[\Livewire\Attributes\On('master::user-show')]
    public function show($rowId): void
    {
        $user = User::findOrFail($rowId);
        $this->redirect(route('master.user.personal-data.show', ['user' => $user->id]));
    }

    #[\Livewire\Attributes\On('master::user-edit')]
    public function edit($rowId): void
    {
        $user = User::findOrFail($rowId);
        $this->redirect(route('master.user.edit', ['user' => $user->id]));
    }

    #[\Livewire\Attributes\On('master::user-toggle')]
    public function toggleActive($rowId): void
    {
        $user = User::findOrFail($rowId);
        $user->update(['is_active' => !$user->is_active]);
    }

    public function actions(User $row): array
    {
        return [
            Button::add('show')
                ->slot('Visualizar')
                ->id()
                ->class('btn btn-info btn-sm')
                ->dispatch('master::user-show', ['rowId' => $row->id]),

            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class('btn btn-warning btn-sm')
                ->dispatch('master::user-edit', ['rowId' => $row->id]),

            Button::add('toggle')
                ->slot($row->is_active ? 'Desativar' : 'Ativar')
                ->id()
                ->class($row->is_active ? 'btn btn-error btn-sm' : 'btn btn-success btn-sm')
                ->dispatch('master::user-toggle', ['rowId' => $row->id]),
        ];
    }
}
