<?php

namespace App\Livewire\Master\Specialty;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Layout;
use PowerComponents\LivewirePowerGrid\{Button, Column, PowerGridComponent, PowerGridFields};
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;

#[Layout('components.layouts.app', ['title' => 'Especialidades', 'guard' => 'master'])]
class ShowTable extends PowerGridComponent
{
    public string $tableName = 'master.specialty.table';

    public function setUp(): array
    {
        return [
            PowerGrid::header()->showSearchInput(),
            PowerGrid::footer()->showPerPage()->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Specialty::query()->withCount('specialists');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('specialists_count');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')->sortable(),
            Column::make('Nome', 'name')->searchable()->sortable(),
            Column::make('Especialistas', 'specialists_count')->sortable(),
            Column::action('Acoes'),
        ];
    }

    #[\Livewire\Attributes\On('master::specialty-edit')]
    public function edit($rowId): void
    {
        $this->redirect(route('master.specialty.edit', ['specialty' => $rowId]));
    }

    #[\Livewire\Attributes\On('master::specialty-delete')]
    public function deleteSpecialty($rowId): void
    {
        Specialty::findOrFail($rowId)->delete();
    }

    public function actions(Specialty $row): array
    {
        return [
            Button::add('edit')->slot('Editar')->id()->class('btn btn-warning btn-sm')->dispatch('master::specialty-edit', ['rowId' => $row->id]),
            Button::add('delete')->slot('Excluir')->id()->class('btn btn-error btn-sm')->dispatch('master::specialty-delete', ['rowId' => $row->id]),
        ];
    }
}
