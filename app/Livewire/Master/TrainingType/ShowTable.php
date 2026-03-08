<?php

namespace App\Livewire\Master\TrainingType;

use App\Models\TrainingType;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Tipos de Formacao', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.training_type.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return TrainingType::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nome', 'name')->searchable()->sortable(),
            Column::action('Acoes'),
        ];
    }

    #[\Livewire\Attributes\On('master::training-type-edit')]
    public function edit($rowId): void
    {
        $this->redirect(route('master.training-type.edit', ['trainingType' => $rowId]));
    }

    #[\Livewire\Attributes\On('master::training-type-delete')]
    public function deleteTrainingType($rowId): void
    {
        TrainingType::findOrFail($rowId)->delete();
    }

    public function actions(TrainingType $row): array
    {
        return [
            Button::add('edit')->slot('Editar')->id()->class('btn btn-warning btn-sm')->dispatch('master::training-type-edit', ['rowId' => $row->id]),
            Button::add('delete')->slot('Excluir')->id()->class('btn btn-error btn-sm')->dispatch('master::training-type-delete', ['rowId' => $row->id]),
        ];
    }
}
