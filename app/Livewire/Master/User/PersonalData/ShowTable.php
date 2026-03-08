<?php

namespace App\Livewire\Master\User\PersonalData;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

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
        return User::query();
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
            ->add('phone_number')
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
            Column::make('Situação', 'status_indicator', 'is_active'),
            Column::make('Cadastrado em', 'created_at_formatted', 'created_at')->sortable(),
            Column::action('Ações'),
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
