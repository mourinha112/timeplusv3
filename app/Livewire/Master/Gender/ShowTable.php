<?php

namespace App\Livewire\Master\Gender;

use App\Models\Gender;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Generos', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.gender.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Gender::query();
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

    #[\Livewire\Attributes\On('master::gender-edit')]
    public function edit($rowId): void
    {
        $this->redirect(route('master.gender.edit', ['gender' => $rowId]));
    }

    #[\Livewire\Attributes\On('master::gender-delete')]
    public function deleteGender($rowId): void
    {
        Gender::findOrFail($rowId)->delete();
    }

    public function actions(Gender $row): array
    {
        return [
            Button::add('edit')->slot('Editar')->id()->class('btn btn-warning btn-sm')->dispatch('master::gender-edit', ['rowId' => $row->id]),
            Button::add('delete')->slot('Excluir')->id()->class('btn btn-error btn-sm')->dispatch('master::gender-delete', ['rowId' => $row->id]),
        ];
    }
}
