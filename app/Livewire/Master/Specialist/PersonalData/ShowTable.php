<?php

namespace App\Livewire\Master\Specialist\PersonalData;

use App\Models\Specialist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Especialistas', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'specialist.personal_data.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Specialist::query();
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
            ->add('crp')
            ->add('status_indicator', function (Specialist $model) {
                return $model->is_active
                    ? '<span class="badge badge-success badge-sm">Ativo</span>'
                    : '<span class="badge badge-error badge-sm">Inativo</span>';
            })
            ->add('created_at_formatted', fn (Specialist $model) => Carbon::parse($model->created_at)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nome', 'name')->searchable()->sortable(),
            Column::make('E-mail', 'email')->searchable(),
            Column::make('CRP', 'crp')->searchable(),
            Column::make('Situação', 'status_indicator', 'is_active'),
            Column::make('Cadastrado em', 'created_at_formatted', 'created_at')->sortable(),
            Column::action('Ações'),
        ];
    }

    #[\Livewire\Attributes\On('master::specialist-show')]
    public function show($rowId): void
    {
        $specialist = Specialist::findOrFail($rowId);
        $this->redirect(route('master.specialist.personal-data.show', ['specialist' => $specialist->id]));
    }

    #[\Livewire\Attributes\On('master::specialist-edit')]
    public function edit($rowId): void
    {
        $specialist = Specialist::findOrFail($rowId);
        $this->redirect(route('master.specialist.edit', ['specialist' => $specialist->id]));
    }

    #[\Livewire\Attributes\On('master::specialist-toggle')]
    public function toggleActive($rowId): void
    {
        $specialist = Specialist::findOrFail($rowId);
        $specialist->update(['is_active' => !$specialist->is_active]);
    }

    public function actions(Specialist $row): array
    {
        return [
            Button::add('show')
                ->slot('Visualizar')
                ->id()
                ->class('btn btn-info btn-sm')
                ->dispatch('master::specialist-show', ['rowId' => $row->id]),

            Button::add('edit')
                ->slot('Editar')
                ->id()
                ->class('btn btn-warning btn-sm')
                ->dispatch('master::specialist-edit', ['rowId' => $row->id]),

            Button::add('toggle')
                ->slot($row->is_active ? 'Desativar' : 'Ativar')
                ->id()
                ->class($row->is_active ? 'btn btn-error btn-sm' : 'btn btn-success btn-sm')
                ->dispatch('master::specialist-toggle', ['rowId' => $row->id]),
        ];
    }
}
